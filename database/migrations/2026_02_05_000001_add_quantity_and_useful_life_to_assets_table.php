<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->integer('quantity')->default(1)->after('asset_code')->comment('Quantity of the asset');
            $table->integer('useful_life')->default(5)->after('purchase_date')->change()->comment('Useful life in years');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
};
