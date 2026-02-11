<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
    ];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}
