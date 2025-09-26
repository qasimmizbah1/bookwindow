<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CmsPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'cms_category_id', 'title', 'slug', 'content', 'is_active', 'image', 'meta_title', 'meta_description', 'meta_keywords'
    ];

    public function category()
    {
        return $this->belongsTo(CmsCategory::class, 'cms_category_id');
    }
}
