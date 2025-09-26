<?php

namespace App\Imports;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class OrdersImport implements ToModel, WithHeadingRow, SkipsEmptyRows, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    private $processedRows = 0;
    private $customErrors = [];
    
    public function model(array $row)
    {
        ++$this->processedRows;
        
        try {      
            if (empty($row['email'])) {
                $this->customErrors[] = "Row {$this->processedRows}: Missing required fields (email or product_name)";
                return null;
            }
            
            $email = strtolower(trim($row['email']));
            $pass = bcrypt(Str::random(10));
            
            $customer = Customer::firstOrCreate(
                ['email' => $email],
                [
                    'first_name' => $row['first_name'] ?? NULL,
                    'last_name' => $row['last_name'] ?? NULL,
                    'phone' => $row['phone'] ?? null,
                    'password' => $pass,
                    'address' => $row['address'] ?? NULL,
                    'address_2' => $row['address_2'] ?? null,
                    'zip_code' => $row['zip_code'] ?? null,
                    'city' => $row['city'] ?? null,
                    'state' => $row['state'] ?? null,
                ]
            );
            
            $product = Product::where('name', $row['product_name'])->first();
            if (!$product) {
                $this->customErrors[] = "Row {$this->processedRows}: Product '{$row['product_name']}' not found";
                //return null;
            }
            
            if (empty($row['ordeid'])) {
                $this->customErrors[] = "Row {$this->processedRows}: Missing order ID";
                //return null;
            }
            

            if (!empty($product))
            {

            $order = Order::updateOrCreate(
                ['order_number' => $row['ordeid']],
                [
                    'user_id' => $customer->id,
                    'email' => $email,
                    'subtotal' => $row['subtotal'] ?? 0,
                    'discount_amount' => $row['discount_amount'] ?? 0,
                    'tax_amount' => $row['tax_amount'] ?? 0,
                    'shipping_amount' => $row['shipping_amount'] ?? 0,
                    'total_amount' => $row['total_amount'] ?? 0,
                    'payment_method' => $row['payment_method'] ?? 'unknown',
                    'status' => $row['payment_status'] ?? 'pending',
                    'shipping_method' => $row['shipping_method'] ?? 'standard',
                    'address' => $row['address'] ?? $customer->address,
                    'address_2' => $row['address_2'] ?? $customer->address_2,
                    'customer_phone' => $row['customer_phone'] ?? $customer->phone,
                    'coupon_code' => $row['coupon_code'] ?? null,
                    'order_number' => $row['ordeid'],
                ]
            );
            
            OrderItem::updateOrCreate(
                [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                ],
                [
                    'product_name' => $row['product_name'],
                    'quantity' => $row['quantity'] ?? 1,
                    'price' => $product->price,
                    'total' => ($row['quantity'] ?? 1) * $product->price,
                ]
            );
            
            return $order;
            }
        } catch (\Exception $e) {
            $errorMsg = "Row {$this->processedRows}: " . $e->getMessage();
            $this->customErrors[] = $errorMsg;
            Log::error($errorMsg);
            return null;
        }
    }
    
    public function getProcessedRowCount(): int
    {
        return $this->processedRows;
    }
    
    public function getCustomErrors(): array
    {
        return $this->customErrors;
    }
}