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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('image')->nullable();
            $table->string('phone');
            $table->string('email')->unique();
            $table->json('links')->nullable();
            $table->json('education')->nullable();
            $table->boolean('job_status')->nullable();

            $table->string('jobTitle')->nullable();
            $table->longText('summary')->nullable();

            $table->json('employment')->nullable();

            $table->json('languages')->nullable();

            $table->json('courses')->nullable();

            $table->json('skills')->nullable();

            $table->json('tools')->nullable();

            $table->json('activities')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
