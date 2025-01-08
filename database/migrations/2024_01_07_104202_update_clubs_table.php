<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            // Drop the status column if it exists
            if (Schema::hasColumn('clubs', 'status')) {
                $table->dropColumn('status');
            }
            
            // Add is_active column if it doesn't exist
            if (!Schema::hasColumn('clubs', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            if (Schema::hasColumn('clubs', 'is_active')) {
                $table->dropColumn('is_active');
            }
            
            if (!Schema::hasColumn('clubs', 'status')) {
                $table->string('status')->default('active');
            }
        });
    }
};
