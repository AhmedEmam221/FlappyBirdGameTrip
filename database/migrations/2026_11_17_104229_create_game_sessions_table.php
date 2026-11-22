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
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();
            $table->uuid('session_uuid');
            $table->string('secure_token')->index();
            $table->integer('score');
            $table->boolean('valid')->default(true);
            $table->string('validation_reason')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_sessions');
    }
};
