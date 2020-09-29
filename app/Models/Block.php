<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

//    protected static function boot()
//    {
//        parent::boot();
//
//        static::addGlobalScope('ignore_da_liu', function (Builder $builder) {
//            return $builder->where('name','not like', '%2%');
//        });
//    }
}
