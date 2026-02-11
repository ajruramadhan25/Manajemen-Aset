<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'parent_category_id'];

    // Parent category (if this is a subcategory)
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

    // Child categories (subcategories)
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_category_id');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    // Check if this is a parent category (has no parent)
    public function isParent()
    {
        return is_null($this->parent_category_id);
    }

    // Check if this is a subcategory (has parent)
    public function isSubcategory()
    {
        return !is_null($this->parent_category_id);
    }

    /**
     * Get total asset count including assets from all child categories
     */
    public function getTotalAssetCount()
    {
        $count = $this->assets()->count();
        
        // Add assets from all children
        foreach ($this->children as $child) {
            $count += $child->getTotalAssetCount();
        }
        
        return $count;
    }
}