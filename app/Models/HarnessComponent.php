<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HarnessComponent extends BaseModel
{
    protected $fillable = [
        'harness_id',
        'component_id',
        'part_type',
        'length',
        'quantity',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function component()
    {
        return $this->belongsTo(Component::class);
    }
}
