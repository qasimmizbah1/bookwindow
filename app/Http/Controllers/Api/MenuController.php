<?php
// app/Http/Controllers/Api/MenuController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Get menu items by menu name
     *
     * @param string $menuName
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMenuItems($menuName)
    {
        // Find the menu by name
        $menu = Menu::where('name', $menuName)->first();
        
        if (!$menu) {
            return response()->json([
                'success' => false,
                'message' => 'Menu not found'
            ], 404);
        }

        // Get menu items with parent-child hierarchy
        $menuItems = MenuItem::where('menu_id', $menu->id)
            ->with('children')
            ->whereNull('parent_id')
            ->orderBy('_lft')
            ->get();

        return response()->json([
            'data' => $menuItems
        ]);
    }
}