<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('clubs', function (Blueprint $table) {
            // Drop the foreign key first
            $table->dropForeign(['user_id']);
            
            // Make user_id nullable
            $table->foreignId('user_id')->nullable()->change();
            
            // Add status and email fields if they don't exist
            if (!Schema::hasColumn('clubs', 'status')) {
                $table->string('status')->default('active');
            }
            if (!Schema::hasColumn('clubs', 'email')) {
                $table->string('email')->nullable();
            }
            
            // Add foreign key back
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        // Update existing clubs to have proper status
        DB::table('clubs')->update(['status' => 'active']);
    }

    public function down()
    {
        Schema::table('clubs', function (Blueprint $table) {
            $table->dropColumn(['status', 'email']);
            
            // Reset user_id to be required
            $table->foreignId('user_id')->change();
        });
    }
};
