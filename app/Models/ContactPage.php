<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactPage extends Model
{
    protected $fillable = [
        'con_title',
        'con_address',
        'con_phone',
        'con_email',
        'con_map',
        
    ];
}
