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
        Schema::table('asset_loans', function (Blueprint $table) {
            $table->integer('original_quantity')->nullable()->after('quantity_borrowed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_loans', function (Blueprint $table) {
            $table->dropColumn('original_quantity');
        });
    }
};
