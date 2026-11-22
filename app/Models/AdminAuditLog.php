<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminAuditLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'admin_user_id',
        'action',
        'target_record_id',
        'target_table',
        'before_state',
        'after_state',
    ];

    protected $casts = [
        'before_state' => 'json',
        'after_state' => 'json',
        'created_at' => 'datetime',
    ];

    public function adminUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'admin_user_id');
    }
}