<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update events table
        if (Schema::hasColumn('events', 'expected_attendees')) {
            DB::statement('ALTER TABLE events CHANGE expected_attendees expected_attendance INT');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert events table changes
        if (Schema::hasColumn('events', 'expected_attendance')) {
            DB::statement('ALTER TABLE events CHANGE expected_attendance expected_attendees INT');
        }
    }
};
