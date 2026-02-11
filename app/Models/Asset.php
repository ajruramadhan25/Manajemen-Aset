<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'asset_code',
        'quantity',
        'category_id',
        'brand_id',
        'status',
        'purchase_date',
        'price',
        'image',
        'useful_life',
        'residual_value',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'price' => 'decimal:2',
        'residual_value' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function loans()
    {
        return $this->hasMany(AssetLoan::class);
    }

    public function units()
    {
        return $this->hasMany(AssetUnit::class);
    }

    public function activeLoan()
    {
        return $this->hasOne(AssetLoan::class)->where('status', 'borrowed')->latest();
    }
}
