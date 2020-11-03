<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Harness extends BaseModel
{
    protected $fillable = [
        'name',
        'no',
        'show_name',
        'version',
        'min_length',
        'max_length',
        'fuse',
        'have_fuse',
        'string',
        'outlet_length',
        'module',
        'remarks',
    ];

    public function harnessComponents()
    {
        return $this->hasMany(HarnessComponent::class);
    }
}
