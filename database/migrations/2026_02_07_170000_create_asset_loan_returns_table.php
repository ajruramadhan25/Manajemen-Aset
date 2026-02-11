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
        Schema::create('asset_loan_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_loan_id')->constrained('asset_loans')->onDelete('cascade');
            $table->foreignId('asset_unit_id')->constrained('asset_units')->onDelete('cascade');
            $table->timestamp('returned_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_loan_returns');
    }
};
