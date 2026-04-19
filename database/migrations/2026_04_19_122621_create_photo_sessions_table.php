<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photo_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->string('template')->nullable();
            $table->integer('total_shots')->default(4);
            $table->string('status')->default('pending'); // pending, active, completed, expired
            $table->string('customer_email')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('qr_code_url')->nullable();
            $table->string('gallery_url')->nullable();
            $table->boolean('email_sent')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photo_sessions');
    }
};
