<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScoreSubmission;
use App\Models\GameSession;
use App\Models\Player;
use App\Models\InstagramClickEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class SubmissionController extends Controller
{
    /**
     * Store a new score submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        // Block submissions when the event/game is closed
        if (! (bool) setting('game_available', true)) {
            return response()->json([
                'success' => false,
                'error' => 'The game is currently closed. Submissions are disabled.',
                'game_available' => false,
            ], 403);
        }

        // Validate the request (allow optional leading @ and periods in usernames)
        $validated = $request->validate([
            'game_session_id' => 'required|string',
            'score' => 'required|integer|min:0',
            'instagram_username' => 'required|string|regex:/^@?[a-zA-Z0-9._]+$/|max:30',
            'bus_number' => 'required|string|max:20|regex:/^[a-zA-Z0-9\-\s]+$/',
        ]);

        // Normalize the Instagram username
        $instagramUsername = trim($validated['instagram_username']);
        if (str_starts_with($instagramUsername, '@')) {
            $instagramUsername = substr($instagramUsername, 1);
        }
        $instagramUsername = trim($instagramUsername, " \t\n\r\0\x0B.@");

        // Validate the game session FIRST (avoid creating orphan players)
        $session = GameSession::where('session_uuid', $validated['game_session_id'])
            ->where('secure_token', hash('sha256', $request->header('X-Session-Token', '')))
            ->first();

        if (! $session) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid or expired game session.',
            ], 400);
        }

        // Find or create the player (only after the session is validated)
        $player = Player::firstOrCreate(
            ['instagram_username' => strtolower($instagramUsername)],
            ['display_name' => $instagramUsername]
        );

        // Check if this session has already been submitted
        $existingSubmission = ScoreSubmission::where('game_session_id', $session->id)
            ->where('player_id', $player->id)
            ->first();

        if ($existingSubmission) {
            return response()->json([
                'success' => false,
                'error' => 'This game session has already been submitted.',
            ], 400);
        }

        // Validate the score against elapsed time and game constraints
        $maxScore = setting('max_accepted_score', 999999);
        if ($validated['score'] > $maxScore) {
            return response()->json([
                'success' => false,
                'error' => "Score exceeds the maximum allowed score of {$maxScore}.",
            ], 400);
        }

        // Check if the competition is still active
        $isActive = setting('competition_status', 'active') === 'active';
        if (! $isActive) {
            return response()->json([
                'success' => false,
                'error' => 'The competition is no longer accepting submissions.',
            ], 400);
        }

        // Check if new submissions are enabled
        $allowNew = setting('allow_new_submissions', true) !== false;
        if (! $allowNew) {
            return response()->json([
                'success' => false,
                'error' => 'New submissions are currently disabled.',
            ], 400);
        }

        // Check for rate limiting - prevent duplicate submissions from same session
        $recentSubmissions = ScoreSubmission::where('game_session_id', $session->id)
            ->where('submitted_at', '>=', now()->subMinutes(30))
            ->count();

        if ($recentSubmissions >= setting('max_submissions_per_session', 5)) {
            return response()->json([
                'success' => false,
                'error' => 'Too many submissions from this session. Please try again later.',
            ], 400);
        }

        // Create the submission
        $submission = ScoreSubmission::create([
            'player_id' => $player->id,
            'game_session_id' => $session->id,
            'submitted_score' => $validated['score'],
            'bus_number' => strtoupper(trim($validated['bus_number'])),
            'verification_status' => 'pending_verification',
            'self_reported_follow' => false,
        ]);

        // Record Instagram click event if applicable
        // (would be called when player clicks the Instagram button)

        return response()->json([
            'success' => true,
            'submission_id' => $submission->id,
            'verification_status' => 'pending_verification',
            'message' => 'Score submitted successfully. Awaiting administrator verification.',
            'instagram_url' => setting('instagram_page_url', 'https://www.instagram.com/YOUR_PAGE_USERNAME/'),
        ]);
    }
}