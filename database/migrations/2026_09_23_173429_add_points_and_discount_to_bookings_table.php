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
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('original_price', 10, 2)->nullable()->after('showtime_id');
            $table->integer('points_used')->default(0)->after('total_price');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('points_used');
            $table->integer('points_earned')->default(0)->after('discount_amount');
            $table->boolean('points_processed')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['original_price', 'points_used', 'discount_amount', 'points_earned', 'points_processed']);
        });
    }
};
