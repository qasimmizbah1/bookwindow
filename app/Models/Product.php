<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_id',
        'name',
        'slug',
        'sku',
        'category_id',
        'sub_category_id',
        'child_category_id',
        'description',
        'meta_tag_title',
        'meta_tag_description',
        'meta_tag_keywords',
        'image',
        'gallery',
        'model',
        'author',
        'year',
        'mrp',
        'number_of_pages',
        'book_language',
        'weight',
        'isbn',
        'isbn10',
        'isbn13',
        'quantity',
        'price',
        'is_visible',
        'type',
        'published_at',
        'vendor_id'
    ];

    protected $casts = [
        'gallery' => 'array'
    ];

    protected static function boot()
    {
    parent::boot();

    // static::saving(function ($model) {
    // $postfix = $model->category_id 
    // ? '-' . '-bookwindow' 
    // : '-bookwindow';

    // // Ensure slug ends with the suffix
    // if (!str_ends_with($model->slug, $postfix)) {
    // $model->slug .= $postfix;
    // }
    // });
    }

    
    public function production(): BelongsTo
    {
        
        return $this->belongsTo(related: Production::class);

    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(related: Category::class);

    }

        // public function category() {
        // return $this->belongsTo(Category::class, 'category_id');
        // }
        // public function subCategory() {
        // return $this->belongsTo(Category::class, 'sub_category_id');
        // }
        // public function childCategory() {
        // return $this->belongsTo(Category::class, 'child_category_id');
        // }


    public function categoires(): BelongsToMany
    {
        return $this->belongsToMany(related: Category::class)->withTimestamps();

    }

        public function orderItems()
        {
        return $this->hasMany(OrderItem::class);
        }

        public function vendor()
        {
        return $this->belongsTo(Vendor::class);
        }

        



}
