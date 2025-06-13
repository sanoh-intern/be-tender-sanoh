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
        Schema::table('company_profile', function (Blueprint $table) {
            $table->renameColumn('skpp_file', 'sppkp_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_profile', function (Blueprint $table) {
            $table->renameColumn('sppkp_file', 'skpp_file');
        });
    }
};
