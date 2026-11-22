<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreSubmission extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'player_id',
        'game_session_id',
        'submitted_score',
        'bus_number',
        'verification_status',
        'self_reported_follow',
        'reviewed_by_admin_id',
        'reviewed_at',
        'review_notes',
        'rejection_reason',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_score' => 'integer',
        'verification_status' => 'string',
        'self_reported_follow' => 'boolean',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    public function gameSession()
    {
        return $this->belongsTo(GameSession::class, 'game_session_id');
    }
}