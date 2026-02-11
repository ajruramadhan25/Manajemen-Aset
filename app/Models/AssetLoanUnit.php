<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetLoanUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_loan_id',
        'asset_unit_id',
    ];

    public function loan()
    {
        return $this->belongsTo(AssetLoan::class, 'asset_loan_id');
    }

    public function unit()
    {
        return $this->belongsTo(AssetUnit::class, 'asset_unit_id');
    }
}
