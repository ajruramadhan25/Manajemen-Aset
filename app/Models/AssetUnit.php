<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetUnit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_id',
        'unique_identifier',
        'status',
        'notes',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function loans()
    {
        return $this->belongsToMany(AssetLoan::class, 'asset_loan_units');
    }
}