<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetLoanReturn extends Model
{
    protected $table = 'asset_loan_returns';

    protected $fillable = [
        'asset_loan_id',
        'asset_unit_id',
        'returned_at',
        'notes',
        'return_batch',
    ];

    protected $casts = [
        'returned_at' => 'datetime',
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(AssetLoan::class, 'asset_loan_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(AssetUnit::class, 'asset_unit_id');
    }
}
