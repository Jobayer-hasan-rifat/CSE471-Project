<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First, let's make sure we have a backup of any existing status
        if (Schema::hasColumn('clubs', 'is_active')) {
            Schema::table('clubs', function (Blueprint $table) {
                $table->string('temp_status')->nullable();
            });

            // Convert boolean is_active to string status
            DB::statement("UPDATE clubs SET temp_status = CASE WHEN is_active = 1 THEN 'active' ELSE 'inactive' END");
            
            // Drop the old column
            Schema::table('clubs', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }

        // Now add our new status column if it doesn't exist
        if (!Schema::hasColumn('clubs', 'status')) {
            Schema::table('clubs', function (Blueprint $table) {
                $table->string('status')->default('active');
            });
        }

        // If we had a temp_status, migrate it to the new status column
        if (Schema::hasColumn('clubs', 'temp_status')) {
            DB::statement("UPDATE clubs SET status = temp_status WHERE temp_status IS NOT NULL");
            
            Schema::table('clubs', function (Blueprint $table) {
                $table->dropColumn('temp_status');
            });
        }

        // Make sure all clubs have a status
        DB::statement("UPDATE clubs SET status = 'active' WHERE status IS NULL");
    }

    public function down()
    {
        Schema::table('clubs', function (Blueprint $table) {
            if (Schema::hasColumn('clubs', 'status')) {
                $table->boolean('is_active')->default(true);
                DB::statement("UPDATE clubs SET is_active = CASE WHEN status = 'active' THEN 1 ELSE 0 END");
                $table->dropColumn('status');
            }
        });
    }
};
