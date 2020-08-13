<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends BaseModel
{

    protected $fillable = [
        'company_id',
        'code',
        'name',
        'address',
        'total_quantity',
        'size_dc',
        'layout_of_whip',
        'distance_between_poles',
        'remarks',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
