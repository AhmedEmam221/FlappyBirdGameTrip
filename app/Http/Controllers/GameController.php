<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameSession;
use App\Models\Player;
use App\Models\ScoreSubmission;
use App\Models\Setting;
use Illuminate\Support\Str;

class GameController extends Controller
{
    /**
     * Show the application landing page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show the play page with game canvas.
     *
     * @return \Illuminate\Http\Response
     */
    public function play()
    {
        return view('play', [
            'gameAvailable' => (bool) setting('game_available', true),
        ]);
    }

    /**
     * Create a new game session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createSession(Request $request)
    {
        // Block new sessions when the event/game is closed
        if (! (bool) setting('game_available', true)) {
            return response()->json([
                'success' => false,
                'error' => 'The game is currently closed. Please check back later.',
                'game_available' => false,
            ], 403);
        }

        // Generate a secure, unpredictable token
        $token = Str::random(40);

        $session = GameSession::create([
            'session_uuid' => (string) Str::uuid(),
            'secure_token' => hash('sha256', $token),
            'score' => 0,
            'valid' => true,
            'metadata' => [
                'started_at' => now()->toDateTimeString(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
        ]);

        return response()->json([
            'success' => true,
            'session_uuid' => $session->session_uuid,
            // Return the RAW token to the client. The DB stores only its hash;
            // on submission we hash the received token and compare with the stored hash.
            'secure_token' => $token,
        ]);
    }

    /**
     * Record an Instagram link click and redirect to the configured page.
     * Validates the destination against the configured URL to prevent open redirects.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function instagramVisit(Request $request)
    {
        $instagramUrl = setting('instagram_page_url', 'https://www.instagram.com/YOUR_PAGE_USERNAME/');

        // Validate the destination: only allow https Instagram URLs
        if (! preg_match('/^https:\/\/(www\.)?instagram\.com\/[a-zA-Z0-9._]+\/?$/', $instagramUrl)) {
            $instagramUrl = 'https://www.instagram.com/';
        }

        // Record the click if a valid session reference is supplied
        $sessionUuid = $request->query('session');
        if ($sessionUuid) {
            $session = GameSession::where('session_uuid', $sessionUuid)->first();
            if ($session) {
                \App\Models\InstagramClickEvent::create([
                    'game_session_id' => $session->id,
                    'player_id' => null,
                    'submission_id' => null,
                    'click_timestamp' => now(),
                    'destination_url' => $instagramUrl,
                ]);
            }
        }

        // Redirect to the configured Instagram page (opens in new tab via target=_blank on the link)
        return redirect()->away($instagramUrl);
    }

    /**
     * Display the leaderboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function leaderboard()
    {
        // Get approved submissions with player info, sorted by score then by submission time (tie-breaker)
        $perPage = (int) setting('num_leaderboard_entries', 10);
        if ($perPage < 1) $perPage = 10;

        $submissions = ScoreSubmission::where('verification_status', 'approved')
            ->with('player')
            ->orderByDesc('submitted_score')
            ->orderBy('submitted_at', 'asc')
            ->paginate($perPage);

        return view('leaderboard', [
            'submissions' => $submissions,
        ]);
    }

    /**
     * Display the public dashboard (no authentication required).
     * Shows ALL scores with Instagram usernames and verification status.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $submissions = ScoreSubmission::with('player')
            ->orderByDesc('submitted_score')
            ->orderBy('submitted_at', 'asc')
            ->get();

        return view('dashboard', [
            'submissions' => $submissions,
            'gameAvailable' => (bool) setting('game_available', true),
        ]);
    }

    /**
     * Toggle whether the game is available to play.
     * Public endpoint for the event operator's dashboard.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleGame()
    {
        $current = (bool) setting('game_available', true);
        Setting::setValue('game_available', ! $current);

        return redirect()->route('dashboard')
            ->with('status', $current ? 'Game closed. No one can play or submit now.' : 'Game is now open for everyone.');
    }

    /**
     * Show the competition rules.
     *
     * @return \Illuminate\Http\Response
     */
    public function rules()
    {
        return view('rules');
    }

    /**
     * Show the privacy policy.
     *
     * @return \Illuminate\Http\Response
     */
    public function privacy()
    {
        return view('privacy');
    }
}