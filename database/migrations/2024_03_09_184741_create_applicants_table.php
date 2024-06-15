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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->json('speciality')->default('{}');
            $table->json('education')->default('[]');
            $table->json('languages')->default('[]');
            $table->json('skills')->default('[]');
            $table->json('tools')->default('[]');
            $table->json('details')->default('[]');
            $table->text('summary')->default('');
            $table->json('courses')->default('[]');
            $table->json('contact');
            $table->json('employment')->default('[]');
            $table->json('activities')->default('[]');
            $table->boolean('published')->default(false);

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
