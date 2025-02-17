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
        Schema::table('project_header', function (Blueprint $table) {
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_at');
            $table->foreign('updated_by')->references('id')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_header', function (Blueprint $table) {
            $table->dropColumn('updated_by');
        });
    }
};
