<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image_path', 'price', 'createdate', 'category_id'];
    
    protected $casts = [
        'price' => 'decimal:2',
        'createdate' => 'date'
    ];

    /**
     * Get the product's image URL.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        
        return asset('storage/products/default.jpg');
    }
    
    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
      