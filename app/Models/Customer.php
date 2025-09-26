<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;


class Customer extends Model implements CanResetPassword
{
    use HasFactory , HasApiTokens, Notifiable, CanResetPasswordTrait;

    protected $fillable = [
        'first_name',
        'last_name',
        'email', 
        'phone',
        'date_of_birth',
        'address',
        'zip_code',
        'city',
        'state',
        'address_2',
        'password',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

}
