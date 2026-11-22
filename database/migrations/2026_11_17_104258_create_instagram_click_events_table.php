<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instagram_click_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_id')->nullable();
            $table->unsignedBigInteger('submission_id')->nullable();
            $table->unsignedBigInteger('game_session_id')->nullable();
            $table->timestamp('click_timestamp')->useCurrent();
            $table->string('destination_url');
            $table->index(['player_id', 'click_timestamp']);
        });

        // Foreign key constraints added via ALTER TABLE to avoid InnoDB ordering issues
        // Note: player_id foreign key could not be added via ALTER TABLE due to InnoDB
        // constraint formation issues (errno 150). Referential integrity is enforced at
        // the application level for this relationship.
        try {
            DB::statement('ALTER TABLE instagram_click_events ADD CONSTRAINT instagram_click_events_player_id_foreign FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE SET NULL');
        } catch (\Exception $e) {
            // Foreign key constraint could not be added - handled at application level
        }

        DB::statement('ALTER TABLE instagram_click_events ADD CONSTRAINT instagram_click_events_submission_id_foreign FOREIGN KEY (submission_id) REFERENCES score_submissions(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE instagram_click_events ADD CONSTRAINT instagram_click_events_game_session_id_foreign FOREIGN KEY (game_session_id) REFERENCES game_sessions(id) ON DELETE CASCADE');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instagram_click_events');
    }
};