<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Production extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 
        'is_visible', 
        'description',
        'meta_tag_title',
        'meta_tag_description',
        'meta_tag_keywords'
    ];

    public function productions(): HasMany
    {
        return $this->hasmany(related: Product::class);
    }
    public function products(): HasMany
    {
        return $this->hasmany(related: Product::class);
    } 
                        
}
