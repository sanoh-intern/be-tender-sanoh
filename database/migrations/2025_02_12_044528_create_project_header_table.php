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
        Schema::connection('mysql')->create('project_header', function (Blueprint $table) {
            $table->id();
            $table->string('project_name', 255)->nullable();
            $table->string('project_status', 25)->nullable();
            $table->string('project_type', 25)->nullable();
            $table->text('project_description')->nullable();
            $table->string('project_attach', 255)->nullable();
            $table->string('project_winner', 255)->nullable();
            $table->string('registration_status', 25)->nullable();
            $table->dateTime('registration_due_at')->nullable();
            $table->unsignedBigInteger('final_review_by')->nullable();
            $table->foreign('final_review_by')->references('id')->on('user')->onDelete('cascade');
            $table->timestamp('final_review_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('user')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_header');
    }
};
