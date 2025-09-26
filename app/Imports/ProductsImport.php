<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Category;
use App\Models\Production;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Skip the row if essential fields are empty
        if (empty($row['name']) && empty($row['production_id']) && empty($row['category'])) {
            return null;
        }

        // Generate unique slug
        $uniqueSlug = $row['slug'] ?? Str::slug($row['name'] ?? 'product-'.time().rand(1000,9999));

        // Handle production - skip if production_id is empty
        $production = null;
        if (!empty($row['production'])) {
            $production = Production::firstOrCreate(
                ['name' => $row['production']],
                ['name' => $row['production']]
            );
        }

        // Handle category - skip if category is empty
        $category = null;
        if (!empty($row['category']) || !empty($row['category_name'])) {
            $categoryName = $row['category'] ?? $row['category_name'];
            $category = Category::firstOrCreate(
                ['name' => $categoryName],
                [
                    'name' => $categoryName,
                    'slug' => Str::slug($categoryName),
                ]
            );
            $categoryId = $category->id;
        }
        $subcategory = null;
        if (!empty($row['sub_category_id']) || !empty($row['sub_category_id'])) {
            $sub_category_name = $row['sub_category_id'] ?? $row['sub_category_id'];
            $subcategory = Category::firstOrCreate(
                ['name' => $sub_category_name],
                [
                    'name' => $sub_category_name,
                    'parent_id'=>  $categoryId,
                    'slug' => Str::slug($sub_category_name),
                ]
            );
            $subcategoryId = $subcategory->id;
        }

        if (!empty($row['medium'])) {

            if($row['medium']=="2")
            {
                $medium = "Hindi";
            }
            else if($row['medium']=="1")
            {
                $medium = "English";
            }
             else if($row['medium']=="3")
            {
                $medium = "Other";
            }
            else
            {
                $medium = NULL;
            }
        }
        


        // Skip if name is empty as it's required
        if (empty($row['name'])) {
            return null;
        }

        return Product::updateOrCreate(
            ['slug' => $uniqueSlug],
            [
                'name' => $row['name'],
                'production_id' => $production->id ?? null,
                'category_id' => $categoryId ?? null,
                'sub_category_id' => $subcategoryId ?? null,
                'child_category_id' => $row['child_category_id'] ?? null,
                'slug' => $uniqueSlug,
                'sku' => $row['sku'] ?? null,
                'image' => $row['img'] ?? null,
                'gallery' => $row['gallery'] ?? null,
                'description' => $row['description'] ?? null,
                'meta_tag_title' => $row['meta_title'] ?? null,
                'meta_tag_description' => $row['meta_description'] ?? null,
                'meta_tag_keywords' => $row['meta_keyword'] ?? null,
                'model' => $row['model'] ?? null,
                'author' => $row['author'] ?? null,
                'year' => $row['year'] ?? null,
                'quantity' => $row['quantity'] ?? null,
                'mrp' => $row['mrp'] ?? null,
                'price' => $row['stock_price'] ?? null,
                'number_of_pages' => $row['number_of_pages'] ?? null,
                'book_language' => $row['book_language'] ?? null,
                'weight' => $row['weight'] ?? null,
                'isbn' => $row['isbn'] ?? null,
                'isbn10' => $row['isbn10'] ?? null,
                'isbn13' => $row['isbn13'] ?? null,
                'is_visible' => $row['status'] ?? null,
                'type' => $medium ?? null,
                'published_at' => now(),
            ]
        );
    }
}