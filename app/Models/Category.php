<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'slug', 
        'parent_id', 
        'is_visible', 
        'description',
        'meta_tag_title',
        'meta_tag_description',
        'meta_tag_keywords',
        'vendor_id'
        
    ];  

    protected static function boot()
    {
            parent::boot();

            static::saving(function ($model) {
            // $postfix = $model->parent_id 
            // ? '-' . \App\Models\Category::find($model->parent_id)?->slug 
            // : '-books';

            // // Ensure slug ends with the suffix
            // if (!str_ends_with($model->slug, $postfix)) {
            //     $model->slug .= $postfix;
            // }
        });
    }

    public function parent(): BelongsTo
    {
            return $this->belongsTo(related:Category::class, foreignKey: 'parent_id');
    } 

    public function child(): HasMany
    {   
        //return $this->hasMany(related:Category::class, foreignKey: 'parent_id');
        return $this->hasMany(Category::class, 'parent_id')->with('child');
    }
    
  

    public function products(): BelongsToMany
    {   
        return $this->belongsToMany(related:Product::class);

    }

    

}
