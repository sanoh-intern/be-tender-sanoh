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
        Schema::create('business_license', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
            $table->string('business_license_number',255)->nullable();
            $table->string('business_license_file', 255)->nullable();
            $table->string('business_type', 255)->nullable();
            $table->string('qualification', 255)->nullable();
            $table->string('sub_classification', 255)->nullable();
            $table->string('issuing_agency', 255)->nullable();
            $table->date('issuing_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->unsignedBigInteger('business_license_verified_by')->nullable();
            $table->foreign('business_license_verified_by')->references('id')->on('user')->onDelete('cascade');
            $table->timestamp('business_license_verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_license');
    }
};
