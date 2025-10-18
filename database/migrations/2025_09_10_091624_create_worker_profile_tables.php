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
         // Worker profiles table
        Schema::create('worker_profiles', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $t->string('job_title');                  // Job title
            $t->string('avatar')->nullable();         // Personal image
            $t->text('bio_title')->nullable();        // About me title
            $t->text('bio');                          // About me description
            $t->timestamps();
        });

        // Skills table
        Schema::create('skills', function (Blueprint $t) {
            $t->id();
            $t->foreignId('worker_profile_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->text('description')->nullable();
            $t->timestamps();
        });

        // Services table
        Schema::create('services', function (Blueprint $t) {
            $t->id();
            $t->foreignId('worker_profile_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->text('description')->nullable();
            $t->timestamps();
        });

        // Portfolio table
        Schema::create('portfolio_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('worker_profile_id')->constrained()->cascadeOnDelete();
            $t->string('title');
            $t->string('subtitle')->nullable();
            $t->text('description')->nullable();
            $t->string('image')->nullable();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio_items');
        Schema::dropIfExists('services');
        Schema::dropIfExists('skills');
        Schema::dropIfExists('worker_profiles');
    }
};
