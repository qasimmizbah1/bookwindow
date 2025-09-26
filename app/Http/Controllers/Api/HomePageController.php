<?php
// app/Http/Controllers/Api/HomePageController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HomePage;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    public function index()
    {
        $homePage = HomePage::first();
        
        if (!$homePage) {
            return response()->json(['message' => 'Home page not configured'], 404);
        }
        
            
        return response()->json([
            'banner' => [
                'banner_description' => $homePage->banner_description,
                'banner_button_title' => $homePage->banner_button_title,
                'banner_button_url' => $homePage->banner_button_url,
                'images' => $homePage->banner_images,
                'logo_img' => $homePage->banner_logo,
            ],
            'category_section' => [
                'cat_sec_title' => $homePage->cat_sec_title,
                'cat_sec_description' => $homePage->cat_sec_description,
                'category_sections' => $homePage->category_sections,
            ],
            'category_tabs' => [
                'cat_tab_subtitle' => $homePage->cat_tab_subtitle,
                'cat_tab_title' => $homePage->cat_tab_title,
                'cat_tab_description' => $homePage->cat_tab_description,
                'cat_tabs' => Category::whereIn('id', $homePage->cat_tabs)
            ->get(['id', 'name', 'slug'])
            ->toArray(),
            ],
            'testimonial_sections' => $homePage->testimonial_sections,
            'feature_sections' => [
                'feature_title' => $homePage->feature_title,
                'feature_description' => $homePage->feature_description,
                'feature_data' => $homePage->custom_sections,
            ],

            
        ]);
    }
}