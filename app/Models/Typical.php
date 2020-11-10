<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Typical extends BaseModel
{

    protected $fillable = [
        'name',
        'show_name',
        'version',
        'harnesses_selected',
        'fuse',
        'nofuse',
        'motors',
        'margin',
        'res_fuses',
    ];

    protected $casts = [
        'harnesses_selected' => 'array',
        'fuse'               => 'array',
        'nofuse'             => 'array',
        'motors'             => 'array',
        'margin'             => 'array',
        'res_fuses'             => 'array',
    ];
}
