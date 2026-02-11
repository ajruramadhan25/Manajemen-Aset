<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asset_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->string('unique_identifier')->nullable()->comment('Unique ID per unit, e.g., plate number or serial');
            $table->string('serial_number')->nullable();
            $table->string('status')->default('available')->comment('available, borrowed, maintenance, retired');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['asset_id', 'unique_identifier']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_units');
    }
};
