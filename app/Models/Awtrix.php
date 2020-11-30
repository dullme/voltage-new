<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Awtrix extends Model
{
    protected $fillable = [
        'type',
        'value',
        'date',
        'updated_at',
    ];
}
