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
        Schema::table('User', function (Blueprint $table) {
            $table->dropColumn('company_photo');
        });

        Schema::table('company_profile', function (Blueprint $table) {
            $table->string('company_photo', 255)->nullable()->after('company_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_profile', function (Blueprint $table) {
            $table->dropColumn('company_photo');
        });

        Schema::table('User', function (Blueprint $table) {
            $table->string('company_photo', 255)->after('role_id');
        });

    }
};
