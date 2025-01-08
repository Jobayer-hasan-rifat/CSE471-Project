<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('club_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->onDelete('cascade');
            $table->string('position_name');
            $table->string('member_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('image_path')->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->default(0); // For ordering positions
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('club_positions');
    }
};
