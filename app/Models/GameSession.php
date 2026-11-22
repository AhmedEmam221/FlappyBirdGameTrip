<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_uuid',
        'secure_token',
        'score',
        'valid',
        'validation_reason',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function scoreSubmissions()
    {
        return $this->hasMany(ScoreSubmission::class, 'game_session_id');
    }

    public function instagramClickEvents()
    {
        return $this->hasMany(InstagramClickEvent::class, 'game_session_id');
    }
}