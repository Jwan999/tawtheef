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
            $table->string('image')->nullable();
            $table->json('speciality'); // Make sure this column exists
            $table->json('education');
            $table->json('languages');
            $table->json('skills');
            $table->json('tools');
            $table->json('details');
            $table->text('summary');
            $table->json('courses');
            $table->json('contact');
            $table->json('employment');
            $table->json('activities');
            $table->boolean('published');
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
