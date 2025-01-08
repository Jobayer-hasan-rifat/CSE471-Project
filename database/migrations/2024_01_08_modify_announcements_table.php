<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('announcements', function (Blueprint $table) {
            // Drop the existing foreign key if it exists
            $table->dropForeign(['club_id']);
            
            // Modify the club_id column to allow null values
            $table->foreignId('club_id')->nullable()->change();
            
            // Re-add the foreign key constraint
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('announcements', function (Blueprint $table) {
            // Drop the existing foreign key
            $table->dropForeign(['club_id']);
            
            // Make club_id not nullable
            $table->foreignId('club_id')->nullable(false)->change();
            
            // Re-add the foreign key constraint
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
        });
    }
};
