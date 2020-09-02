<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends BaseModel
{
    protected $fillable = [
        'project_id',
        'status',
        'name',
        'total_quantity',
        'layout_of_whip',
        'distance_between_poles',
        'buffer',
        'remarks',
        'typical'
    ];

    protected $casts = [
        'typical' => 'array'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function quotationInfos()
    {
        return $this->hasMany(QuotationInfo::class);
    }

    public function blocks()
    {
        return $this->hasMany(Block::class);
    }

    public function whips()
    {
        return $this->hasMany(Whip::class);
    }
}
