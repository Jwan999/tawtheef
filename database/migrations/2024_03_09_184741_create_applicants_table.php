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
            $table->increments('id');
            $table->string('image')->nullable();
            $table->string('speciality_title')->nullable();
            $table->json('speciality_children')->nullable();
            $table->json('education')->nullable();
            $table->json('languages')->nullable();
            $table->json('skills')->nullable();
            $table->json('tools')->nullable();
            $table->boolean('work_availability')->nullable();
            $table->string('full_name')->nullable();
            $table->text('summary')->nullable();
            $table->json('courses')->nullable();
            $table->string('phone')->nullable();
            $table->string('gender')->nullable();
            $table->string('email')->nullable();
            $table->json('links')->nullable();
            $table->string('birthdate')->nullable();
            $table->string('city')->nullable();
            $table->string('zone')->nullable();
            $table->json('employment')->nullable();
            $table->json('activities')->nullable();
            $table->integer('profileable_id')->unsigned()->nullable();
            $table->string('profileable_type')->nullable();
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
