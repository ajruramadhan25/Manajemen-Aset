<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asset_loan_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_loan_id')->constrained('asset_loans')->cascadeOnDelete();
            $table->foreignId('asset_unit_id')->constrained('asset_units')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['asset_loan_id', 'asset_unit_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_loan_units');
    }
};
