<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('score_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade');
            $table->foreignId('game_session_id')->constrained('game_sessions')->onDelete('cascade');
            $table->integer('submitted_score');
            $table->enum('verification_status', ['pending_verification', 'approved', 'rejected'])->default('pending_verification');
            $table->boolean('self_reported_follow')->default(false);
            $table->timestamp('submitted_at')->useCurrent();
            $table->foreignId('reviewed_by_admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->unique(['game_session_id', 'player_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('score_submissions');
    }
};
