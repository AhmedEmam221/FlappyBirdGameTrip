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
        Schema::table('score_submissions', function (Blueprint $table) {
            if (! Schema::hasColumn('score_submissions', 'bus_number')) {
                $table->string('bus_number')->nullable()->after('submitted_score');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('score_submissions', function (Blueprint $table) {
            if (Schema::hasColumn('score_submissions', 'bus_number')) {
                $table->dropColumn('bus_number');
            }
        });
    }
};
