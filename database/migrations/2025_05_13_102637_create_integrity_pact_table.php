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
        Schema::create('integrity_pact', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
            $table->string('integrity_pact_file', 255)->nullable();
            $table->string('integrity_pact_desc', 255)->nullable();
            $table->unsignedBigInteger('integrity_pact_verified_by')->nullable();
            $table->foreign('integrity_pact_verified_by')->references('id')->on('user')->onDelete('cascade');
            $table->timestamp('integrity_pact_verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integrity_pact');
    }
};
