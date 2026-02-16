<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = [
        'produce_name',
        'nepali_name',
        'unit',
        'min_price',
        'max_price',
        'avg_price',
        'price_date',
    ];

    protected $casts = [
        'min_price' => 'decimal:2',
        'max_price' => 'decimal:2',
        'avg_price' => 'decimal:2',
        'price_date' => 'date',
    ];
}

