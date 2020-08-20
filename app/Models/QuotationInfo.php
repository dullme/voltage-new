<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationInfo extends BaseModel
{

    protected $fillable = [
        'quotation_id',
        'of_module_per_string',
        'style',
        'connector',
        'fuse',
        'junction_box_to_rack1',
        'junction_box_to_rack1_remark',
        'junction_box_to_rack2',
        'junction_box_to_rack2_remark',
        'number_of_string',
        'typical',
        'solar_panels',
        'bracket_id',
        'end_of_extender',
        'module_to_module_extender',
        'remarks',
        'string',
    ];

    protected $casts = [
        'number_of_string' => 'array',
        'typical'          => 'array',
        'solar_panels'     => 'array'
    ];

    public function solarPanel()
    {
        return $this->belongsTo(SolarPanel::class);
    }

    public function bracket()
    {
        return $this->belongsTo(Bracket::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}
