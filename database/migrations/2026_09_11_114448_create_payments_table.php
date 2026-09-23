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
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                // We'll add booking_id later if needed or payment belongs to booking.
                // Let's actually link payment to booking:
                $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
                $table->string('payment_method'); // VNPay, MoMo, Credit Card
                $table->decimal('amount', 10, 2);
                $table->string('transaction_id')->nullable();
                $table->string('status')->default('pending'); // pending, success, failed
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
