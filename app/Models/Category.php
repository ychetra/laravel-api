<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'parent_id'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
    // Parent relationship
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    
    // Children relationship
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
} 