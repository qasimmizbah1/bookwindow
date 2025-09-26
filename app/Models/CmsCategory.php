<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CmsCategory extends Model
{
    use HasFactory;

     protected $fillable = ['name', 'slug', 'content'];
    

    public function posts()
    {
        return $this->hasMany(CmsPost::class);
    }
}
