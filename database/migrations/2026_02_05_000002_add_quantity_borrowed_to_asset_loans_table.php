<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('asset_loans', function (Blueprint $table) {
            $table->integer('quantity_borrowed')->default(1)->after('asset_id')->comment('Quantity of asset borrowed');
        });
    }

    public function down(): void
    {
        Schema::table('asset_loans', function (Blueprint $table) {
            $table->dropColumn('quantity_borrowed');
        });
    }
};
