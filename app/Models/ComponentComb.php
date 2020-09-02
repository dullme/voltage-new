<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComponentComb extends BaseModel
{

    public function lineId()
    {
        return $this->belongsTo(Component::class, 'line_id');
    }

    public function maleId()
    {
        return $this->belongsTo(Component::class, 'male_id');
    }

    public function femaleId()
    {
        return $this->belongsTo(Component::class, 'female_id');
    }
}
