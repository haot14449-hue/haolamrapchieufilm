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
        Schema::table('rooms', function (Blueprint $table) {
            $table->integer('total_rows')->default(10)->after('capacity');
            $table->integer('total_columns')->default(16)->after('total_rows');
            $table->longText('layout_data')->nullable()->after('total_columns');
        });

        Schema::table('seats', function (Blueprint $table) {
            $table->string('type', 30)->default('standard')->after('number');
            $table->integer('col_index')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['total_rows', 'total_columns', 'layout_data']);
        });

        Schema::table('seats', function (Blueprint $table) {
            $table->dropColumn(['type', 'col_index']);
        });
    }
};
