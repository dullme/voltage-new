<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhipBlock extends BaseModel
{
    protected $fillable = [
        'quotation_id',
        'block_id',
        'name',
        'whip',
    ];

    protected $casts = [
        'whip' => 'array'
    ];
}
