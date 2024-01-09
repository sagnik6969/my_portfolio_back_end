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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('github_link');
            $table->string('live_link');
            $table->string('image_link')->default('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQsk-4Y13Pf9giRlQoIj43QrGPNEVgDW0roWa9KfHuKFA&s');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
