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
            // Add new columns if they don't exist
            if (!Schema::hasColumn('clubs', 'president_name')) {
                $table->string('president_name')->nullable();
            }
            if (!Schema::hasColumn('clubs', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('clubs', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            if (!Schema::hasColumn('clubs', 'status')) {
                $table->string('status')->default('active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            $table->dropColumn(['president_name', 'phone', 'is_active', 'status']);
        });
    }
};
