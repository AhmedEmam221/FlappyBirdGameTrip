<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'instagram_username',
        'display_name',
    ];

    protected $casts = [
        'first_seen_at' => 'datetime',
    ];

    public function scoreSubmissions()
    {
        return $this->hasMany(ScoreSubmission::class, 'player_id');
    }

    public function instagramClickEvents()
    {
        return $this->hasMany(InstagramClickEvent::class, 'player_id');
    }
}