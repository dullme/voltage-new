<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectInfo extends BaseModel
{
    protected $fillable = [
        'project_id',
        'solar_panel_id',
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
        'bracket_id',
        'end_of_extender',
        'module_to_module_extender',
        'remarks',
        'string',
    ];

    protected $casts = [
        'number_of_string' => 'array',
        'typical' => 'array'
    ];

    public function solarPanel()
    {
        return $this->belongsTo(SolarPanel::class);
    }

    public function bracket()
    {
        return $this->belongsTo(Bracket::class);
    }
}
