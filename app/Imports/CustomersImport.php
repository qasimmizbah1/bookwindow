<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomersImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row)
    {   

        if (empty($row['email'])) {
            return null;
        }

        $password = bcrypt(Str::random(10));
        
        

        return Customer::updateOrCreate(
             ['email' => $row['email']],
             [
            'first_name'     => $row['firstname'],
            'last_name'     => $row['lastname'],
            'email'    => $row['email'], 
            'phone'    => $row['phone'],
            'address' => $row['address'],
            'address_2' => $row['address_2'],
            'city' => $row['city'],
            'state' => $row['state'],
            'zip_code' => $row['zipcode'],
            'country' => "India",
            'password'=>$password,
            
        ]
    );
    }
}