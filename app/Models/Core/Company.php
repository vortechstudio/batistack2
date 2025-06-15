<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function banks(): HasMany
    {
        return $this->hasMany(Bank::class);
    }
}
