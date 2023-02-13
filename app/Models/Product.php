<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Image;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'enable'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'products_categories','product_id','category_id');
    }

    public function images()
    {
        return $this->belongsToMany(Image::class, 'products_images','product_id','image_id');
    }
}
