<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('photo_session_id')->constrained()->onDelete('cascade');
            $table->string('reference_number')->unique()->nullable();
            $table->decimal('amount', 8, 2)->default(0);
            $table->string('method')->default('cash'); // cash, gcash, paypal
            $table->string('status')->default('pending'); // pending, paid, failed, refunded
            $table->string('payment_type')->default('pay_now'); // pay_now, pay_later
            $table->json('metadata')->nullable(); // extra payment gateway data
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
