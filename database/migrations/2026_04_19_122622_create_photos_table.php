<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('photo_session_id')->constrained()->onDelete('cascade');
            $table->string('filename');
            $table->string('path');
            $table->string('url');
            $table->string('storage_driver')->default('local'); // local, cloudinary, firebase
            $table->string('public_id')->nullable(); // for cloudinary
            $table->integer('shot_number')->default(1);
            $table->boolean('is_collage')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
