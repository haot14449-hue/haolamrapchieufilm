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
        if (!Schema::hasColumn('users', 'birthday')) {
            Schema::table('users', function (Blueprint $table) {
                $table->date('birthday')->nullable()->after('phone');
            });
        }

        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'cashier_id')) {
                $table->foreignId('cashier_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('bookings', 'cash_given')) {
                $table->decimal('cash_given', 12, 2)->nullable()->after('total_price');
            }
            if (!Schema::hasColumn('bookings', 'cash_change')) {
                $table->decimal('cash_change', 12, 2)->nullable()->after('cash_given');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['cashier_id']);
            $table->dropColumn(['cashier_id', 'cash_given', 'cash_change']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('birthday');
        });
    }
};
