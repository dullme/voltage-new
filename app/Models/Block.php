<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends BaseModel
{
    protected $fillable = [
        'quotation_id',
        'name',
        'block',
        'reference',
        'order',
        'total_typical',
    ];

    protected $casts = [
        'block' => 'array',
        'total_typical' => 'array',
    ];
}
