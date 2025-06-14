<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class PlanComptable extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'lettrage' => 'boolean',
        ];
    }
}
