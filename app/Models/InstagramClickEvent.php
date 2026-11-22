<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstagramClickEvent extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        'click_timestamp' => 'datetime',
    ];

    protected $fillable = [
        'player_id',
        'submission_id',
        'game_session_id',
        'click_timestamp',
        'destination_url',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    public function submission()
    {
        return $this->belongsTo(ScoreSubmission::class, 'submission_id');
    }

    public function gameSession()
    {
        return $this->belongsTo(GameSession::class, 'game_session_id');
    }
}