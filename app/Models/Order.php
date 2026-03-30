<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'data'];

    protected $casts = [
        'data' => 'array',
    ];
}
