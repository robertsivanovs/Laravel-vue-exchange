<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = [
        'base_currency', 
        'quote_currency', 
        'exchange_rate'
    ];
    
    use HasFactory;
}
