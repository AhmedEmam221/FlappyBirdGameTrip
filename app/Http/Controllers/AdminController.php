<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScoreSubmission;
use App\Models\Player;
use App\Models\GameSession;
use App\Models\InstagramClickEvent;
use App\Models\Setting;
use App\Models\AdminAuditLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Summary cards
        $totalSessions = GameSession::count();
        $totalSubmissions = ScoreSubmission::count();
        $pendingSubmissions = ScoreSubmission::where('verification_status', 'pending_verification')->count();
        $approvedSubmissions = ScoreSubmission::where('verification_status', 'approved')->count();
        $rejectedSubmissions = ScoreSubmission::where('verification_status', 'rejected')->count();
        $uniqueUsernames = Player::distinct()->count('instagram_username');
        $instagramClicks = InstagramClickEvent::count();
        
        // Top 3 players
        $topThree = ScoreSubmission::where('verification_status', 'approved')
            ->with('player')
            ->orderByDesc('submitted_score')
            ->take(3)
            ->get();

        // Recent submissions
        $recentSubmissions = ScoreSubmission::with(['player', 'gameSession'])
            ->orderByDesc('submitted_at')
            ->take(10)
            ->get();

        return view('admin.dashboard', [
            'totalSessions' => $totalSessions,
            'totalSubmissions' => $totalSubmissions,
            'pendingSubmissions' => $pendingSubmissions,
            'approvedSubmissions' => $approvedSubmissions,
            'rejectedSubmissions' => $rejectedSubmissions,
            'uniqueUsernames' => $uniqueUsernames,
            'instagramClicks' => $instagramClicks,
            'topThree' => $topThree,
            'recentSubmissions' => $recentSubmissions,
            'competitionStatus' => setting('competition_status', 'active'),
        ]);
    }

    /**
     * Display participant (players) management.
     *
     * @return \Illuminate\Http\Response
     */
    public function players()
    {
        $search = request('search');
        $query = Player::with('scoreSubmissions');

        if ($search) {
            $query->where('instagram_username', 'like', "%{$search}%");
        }

        $players = $query->orderBy('first_seen_at', 'desc')->paginate(20);

        return view('admin.players', [
            'players' => $players,
            'search' => $search,
        ]);
    }

    /**
     * Display competition analytics.
     *
     * @return \Illuminate\Http\Response
     */
    public function analytics()
    {
        $totalSessions = GameSession::count();
        $totalSubmissions = ScoreSubmission::count();
        $approvedSubmissions = ScoreSubmission::where('verification_status', 'approved')->count();
        $pendingSubmissions = ScoreSubmission::where('verification_status', 'pending_verification')->count();
        $rejectedSubmissions = ScoreSubmission::where('verification_status', 'rejected')->count();
        $instagramClicks = InstagramClickEvent::count();

        $topTen = ScoreSubmission::where('verification_status', 'approved')
            ->with('player')
            ->orderByDesc('submitted_score')
            ->orderBy('submitted_at', 'asc')
            ->take(10)
            ->get();

        return view('admin.analytics', [
            'totalSessions' => $totalSessions,
            'totalSubmissions' => $totalSubmissions,
            'approvedSubmissions' => $approvedSubmissions,
            'pendingSubmissions' => $pendingSubmissions,
            'rejectedSubmissions' => $rejectedSubmissions,
            'instagramClicks' => $instagramClicks,
            'topTen' => $topTen,
        ]);
    }

    /**
     * Display the submissions management table.
     *
     * @return \Illuminate\Http\Response
     */
    public function entries()
    {
        $query = ScoreSubmission::with(['player', 'gameSession'])
            ->orderByDesc('submitted_at');

        // Filter by status
        $status = request('status');
        if ($status && $status !== 'all') {
            $query->where('verification_status', $status);
        }

        // Filter by score
        $minScore = request('min_score');
        if ($minScore) {
            $query->where('submitted_score', '>=', $minScore);
        }

        $maxScore = request('max_score');
        if ($maxScore) {
            $query->where('submitted_score', '<=', $maxScore);
        }

        // Filter by date
        $startDate = request('start_date');
        if ($startDate) {
            $query->whereDate('submitted_at', '>=', $startDate);
        }

        $endDate = request('end_date');
        if ($endDate) {
            $query->whereDate('submitted_at', '<=', $endDate);
        }

        // Search by Instagram username
        $search = request('search');
        if ($search) {
            $query->whereHas('player', function ($q) use ($search) {
                $q->where('instagram_username', 'like', "%{$search}%");
            });
        }

        $submissions = $query->paginate(20);

        return view('admin.entries', [
            'submissions' => $submissions,
            'statusFilters' => ['all', 'pending_verification', 'approved', 'rejected'],
            'currentFilters' => [
                'status' => $status,
                'min_score' => $minScore,
                'max_score' => $maxScore,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'search' => $search,
            ],
        ]);
    }

    /**
     * Approve a submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ScoreSubmission  $submission
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, ScoreSubmission $submission)
    {
        $submission->verification_status = 'approved';
        $submission->reviewed_by_admin_id = auth()->id();
        $submission->reviewed_at = now();
        $submission->review_notes = $request->filled('review_notes') ? $request->input('review_notes') : null;
        $submission->save();

        // Log the action
        auth()->user()->adminAuditLogs()->create([
            'action' => 'approve',
            'target_record_id' => $submission->id,
            'target_table' => 'score_submissions',
            'before_state' => null,
            'after_state' => json_encode([
                'verification_status' => 'approved',
            ]),
        ]);

        return back()->with('success', 'Submission approved successfully.');
    }

    /**
     * Reject a submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ScoreSubmission  $submission
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, ScoreSubmission $submission)
    {
        $submission->verification_status = 'rejected';
        $submission->rejection_reason = $request->filled('rejection_reason') ? $request->input('rejection_reason') : null;
        $submission->review_notes = $request->filled('review_notes') ? $request->input('review_notes') : null;
        $submission->reviewed_by_admin_id = auth()->id();
        $submission->reviewed_at = now();
        $submission->save();

        // Log the action
        auth()->user()->adminAuditLogs()->create([
            'action' => 'reject',
            'target_record_id' => $submission->id,
            'target_table' => 'score_submissions',
            'before_state' => null,
            'after_state' => json_encode([
                'verification_status' => 'rejected',
                'rejection_reason' => $submission->rejection_reason,
            ]),
        ]);

        return back()->with('success', 'Submission rejected.');
    }

    /**
     * Flag a suspicious submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ScoreSubmission  $submission
     * @return \Illuminate\Http\Response
     */
    public function flag(Request $request, ScoreSubmission $submission)
    {
        $submission->rejection_reason = $request->filled('flag_reason') ? $request->input('flag_reason') : 'Flagged for review';
        $submission->review_notes = $request->filled('flag_notes') ? $request->input('flag_notes') : null;
        $submission->reviewed_by_admin_id = auth()->id();
        $submission->reviewed_at = now();
        $submission->save();

        // Log the action
        auth()->user()->adminAuditLogs()->create([
            'action' => 'flag',
            'target_record_id' => $submission->id,
            'target_table' => 'score_submissions',
            'before_state' => null,
            'after_state' => json_encode([
                'flag_reason' => $submission->rejection_reason,
            ]),
        ]);

        return back()->with('success', 'Submission flagged for review.');
    }

    /**
     * Finalize the competition and determine winners.
     *
     * @return \Illuminate\Http\Response
     */
    public function finalize()
    {
        // Get all approved submissions, sorted by score then by submission time
        $approved = ScoreSubmission::where('verification_status', 'approved')
            ->with('player')
            ->orderByDesc('submitted_score')
            ->orderBy('submitted_at', 'asc')
            ->get();

        // Determine top 3
        $winners = $approved->take(3);

        // Log the finalization action
        auth()->user()->adminAuditLogs()->create([
            'action' => 'finalize_competition',
            'target_record_id' => 0,
            'target_table' => 'settings',
            'before_state' => null,
            'after_state' => json_encode([
                'winners_count' => $winners->count(),
                'final_standings' => $approved->take(3)->toArray(),
            ]),
        ]);

        return view('admin.finalize', [
            'winners' => $winners,
            'allApproved' => $approved,
        ]);
    }

    /**
     * Export submissions to CSV.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportCsv()
    {
        $submissions = ScoreSubmission::with(['player', 'gameSession'])
            ->where('verification_status', 'approved')
            ->orderByDesc('submitted_score')
            ->get();

        $headers = [
            'Content-Type: text/csv; charset=utf-8',
            'Content-Disposition: attachment; filename=entries.csv',
            'Pragma: no-cache',
            'Expires: 0',
        ];

        $columns = ['Instagram Username', 'Bus Number', 'Best Score', 'Submitted Score', 'Submission Date', 'Verification Status', 'Review Notes', 'Rejection Reason'];

        $callback = function () use ($submissions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($submissions as $submission) {
                $row = [
                    $submission->player->instagram_username,
                    $submission->bus_number ?? '',
                    $submission->submitted_score,
                    $submission->submitted_score,
                    $submission->submitted_at->format('Y-m-d H:i:s'),
                    $submission->verification_status,
                    $submission->review_notes ?? '',
                    $submission->rejection_reason ?? '',
                ];
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 240, $headers);
    }
}