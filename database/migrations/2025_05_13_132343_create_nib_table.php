<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nib', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
            $table->string('nib_number', 25)->nullable();
            $table->string('nib_file', 255)->nullable();
            $table->string('issuing_agency', 255)->nullable();
            $table->date('issuing_date')->nullable();
            $table->string('investment_status', 255)->nullable();
            $table->string('kbli', 255)->nullable();
            $table->unsignedBigInteger('nib_verified_by')->nullable();
            $table->foreign('nib_verified_by')->references('id')->on('user')->onDelete('cascade');
            $table->timestamp('nib_verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nib');
    }
};
