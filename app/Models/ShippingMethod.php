<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $fillable = ['name', 'code', 'price', 'is_active'];
}

// app/Models/Coupon.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'min_cart_amount', 
        'valid_from', 'valid_to', 'is_active'
    ];

    protected $dates = ['valid_from', 'valid_to'];

    public function scopeValid(Builder $query)
    {
        return $query->where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_to', '>=', now());
    }

    public function calculateDiscount($amount)
    {
        if ($this->type === 'fixed') {
            return min($this->value, $amount);
        }
        
        return $amount * ($this->value / 100);
    }
}


// // app/Models/OrderItem.php
// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class OrderItem extends Model
// {
//     protected $fillable = [
//         'order_id', 'product_id', 'product_name',
//         'price', 'quantity', 'total'
//     ];
// }
