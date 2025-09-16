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
        Schema::create('job_interests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('health_job_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Prevent duplicate interests from same user for same job
            $table->unique(['user_id', 'health_job_id']);

            // Add indexes for better query performance
            $table->index('user_id');
            $table->index('health_job_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_interests');
    }
};
