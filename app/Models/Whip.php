<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Whip extends BaseModel
{
    protected $fillable = [
        'have_s',
        'quotation_id',
        'component_comb_id',
        'multiple',
        'rowhead_to',
        'remarks',
        'digital_a',
        'digital_b'
    ];

    public function componentComb()
    {
        return $this->belongsTo(ComponentComb::class);
    }
}
