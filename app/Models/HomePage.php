<?php
// app/Models/HomePage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class HomePage extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'banner_description',
        'banner_button_title',
        'banner_button_url',
        'banner_images' => 'array',
        'category_sections' => 'array',
        'cat_tabs'=>'array',
        'testimonial_sections'=>'array',
        'featured_products' => 'array',
        'best_sellers' => 'array',
        'categories' => 'array',
        'custom_sections' => 'array',

    ];
    
    public function featuredProducts()
    {
        return $this->belongsToMany(Product::class, 'home_page_featured_products');
    }
    
    public function bestSellers()
    {
        return $this->belongsToMany(Product::class, 'home_page_best_sellers');
    }
    
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'home_page_categories');
    }
    
    // Accessor for slider images with full URLs
    protected function sliderImages(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true) ? array_map(function($image) {
                return asset('storage/'.$image);
            }, json_decode($value, true)) : [],
        );
    }
}