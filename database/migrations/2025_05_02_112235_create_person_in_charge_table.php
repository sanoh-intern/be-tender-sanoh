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
        Schema::create('person_in_charge', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
            $table->string('job_position', 25)->nullable();
            $table->string('departement', 25)->nullable();
            $table->string('pic_name', 255)->nullable();
            $table->string('pic_telp_number_1', 25)->nullable();
            $table->string('pic_telp_number_2', 25)->nullable();
            $table->string('pic_email_1', 255)->nullable();
            $table->string('pic_email_2', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_in_charge');
    }
};
