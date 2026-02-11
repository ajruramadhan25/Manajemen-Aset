<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetLoan extends Model
{
    protected $fillable = [
        'asset_id',
        'quantity_borrowed',
        'original_quantity',
        'user_id',
        'loan_date',
        'return_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'return_date' => 'date',
        'original_quantity' => 'integer',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function units()
    {
        return $this->belongsToMany(AssetUnit::class, 'asset_loan_units', 'asset_loan_id', 'asset_unit_id')->withTimestamps();
    }
}
