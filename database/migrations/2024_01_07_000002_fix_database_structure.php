<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop existing tables if they exist
        Schema::dropIfExists('club_chats');
        Schema::dropIfExists('announcements');

        // Create club_chats table
        Schema::create('club_chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users');
            $table->foreignId('receiver_id')->constrained('users');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // Create announcements table
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained('clubs');
            $table->string('title');
            $table->text('content');
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('club_chats');
        Schema::dropIfExists('announcements');
    }
};
