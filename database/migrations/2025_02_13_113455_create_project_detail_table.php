e<?php

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
        Schema::connection('mysql')->create('project_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_header_id')->nullable();
            $table->foreign('project_header_id')->references('id')->on('project_header')->onDelete('cascade');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('user')->onDelete('cascade');
            $table->string('proposal_attach', 255)->nullable();
            $table->bigInteger('proposal_total_amount')->nullable();
            $table->string('proposal_status', 25)->nullable();
            $table->text('proposal_comment')->nullable();
            $table->string('proposal_revision_no', 25)->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->foreign('reviewed_by')->references('id')->on('user')->onDelete('cascade');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_detail');
    }
};
