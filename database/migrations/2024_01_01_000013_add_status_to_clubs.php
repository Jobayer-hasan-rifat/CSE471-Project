<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('clubs', function (Blueprint $table) {
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('clubs', 'status')) {
                $table->string('status')->default('active');
            }
        });

        // Update all existing clubs to active status
        DB::table('clubs')->update(['status' => 'active']);
    }

    public function down()
    {
        Schema::table('clubs', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
