<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('clubs', 'status')) {
            Schema::table('clubs', function (Blueprint $table) {
                $table->string('status')->default('active');
            });
        }

        if (!Schema::hasColumn('clubs', 'logo_path')) {
            Schema::table('clubs', function (Blueprint $table) {
                $table->string('logo_path')->nullable();
            });
        }

        if (Schema::hasColumn('clubs', 'logo_url')) {
            Schema::table('clubs', function (Blueprint $table) {
                $table->dropColumn('logo_url');
            });
        }
    }

    public function down()
    {
        Schema::table('clubs', function (Blueprint $table) {
            if (Schema::hasColumn('clubs', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('clubs', 'logo_path')) {
                $table->dropColumn('logo_path');
            }
            $table->string('logo_url')->nullable();
        });
    }
};
