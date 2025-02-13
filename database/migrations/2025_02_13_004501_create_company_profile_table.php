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
        Schema::connection('mysql')->create('company_profile', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
            $table->string('bp_code', 25)->nullable();
            $table->string('tax_id', 25)->nullable();
            $table->string('company_name', 255)->nullable();
            $table->string('company_status', 25)->nullable();
            $table->text('company_description')->nullable();
            $table->string('company_url', 255)->nullable();
            $table->string('business_field', 255)->nullable();
            $table->string('sub_business_field', 255)->nullable();
            $table->string('product', 255)->nullable();
            $table->string('adr_line_1', 255)->nullable();
            $table->string('adr_line_2', 255)->nullable();
            $table->string('adr_line_3', 255)->nullable();
            $table->string('adr_line_4', 255)->nullable();
            $table->string('province', 25)->nullable();
            $table->string('city', 25)->nullable();
            $table->string('postal_code', 25)->nullable();
            $table->string('company_phone_1', 25)->nullable();
            $table->string('company_phone_2', 25)->nullable();
            $table->string('company_fax_1', 25)->nullable();
            $table->string('company_fax_2', 25)->nullable();
            $table->unsignedBigInteger('profile_verified_by')->nullable();
            $table->foreign('profile_verified_by')->references('id')->on('user')->onDelete('cascade');
            $table->timestamp('profile_verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_profile');
    }
};
