<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'tblProductData';
    protected $primaryKey = 'intProductDataId';
    public $timestamps = false;

    protected $fillable = [
        'strProductName',
        'strProductDesc',
        'strProductCode',
        'dtmAdded',
        'dtmDiscontinued',
        'price',
        'stock',
    ];

    protected array $dates = [
        'dtmAdded',
        'dtmDiscontinued',
    ];
}

