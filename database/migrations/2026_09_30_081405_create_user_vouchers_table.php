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
        Schema::create('user_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('promotion_id')->constrained('promotions')->onDelete('cascade');
            $table->boolean('is_used')->default(false);
            $table->timestamp('used_at')->nullable();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('set null');
            $table->timestamp('saved_at')->useCurrent();
            $table->timestamps();

            // A user can save each promotion once
            $table->unique(['user_id', 'promotion_id']);
        });

        // Add promotion_id to bookings if not already present
        if (!Schema::hasColumn('bookings', 'promotion_id')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->foreignId('promotion_id')->nullable()->after('showtime_id')->constrained('promotions')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_vouchers');

        if (Schema::hasColumn('bookings', 'promotion_id')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropForeign(['promotion_id']);
                $table->dropColumn('promotion_id');
            });
        }
    }
};
