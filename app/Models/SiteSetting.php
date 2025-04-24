<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type'
    ];

    protected $casts = [];

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = is_array($value) ? json_encode($value) : $value;
    }

    public function getValueAttribute($value)
    {
        if ($this->type === 'array') {
            return json_decode($value, true);
        }
        return $value;
    }
} 