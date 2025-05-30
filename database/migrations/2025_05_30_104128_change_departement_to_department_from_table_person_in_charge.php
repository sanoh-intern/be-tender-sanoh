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
        Schema::table('person_in_charge', function (Blueprint $table) {
            $table->renameColumn('departement', 'department');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('person_in_charge', function (Blueprint $table) {
            $table->renameColumn('department', 'departement');
        });
    }
};
