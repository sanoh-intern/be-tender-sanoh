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
            $table->string('tax_id_file', 255)->nullable()->after('tax_id');
            $table->string('skpp_file', 255)->nullable()->after('company_fax_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_profile', function (Blueprint $table) {
            $table->dropColumn('tax_id_file');
            $table->dropColumn('skpp_file');
        });
    }
};
