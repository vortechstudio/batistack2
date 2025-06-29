<?php

declare(strict_types=1);

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Company extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function banks(): HasMany
    {
        return $this->hasMany(Bank::class);
    }
}
