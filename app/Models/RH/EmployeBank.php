<?php

namespace App\Models\RH;

use App\Models\Core\Bank;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeBank extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }

    public function getBankNameAttribute()
    {
        return $this->bank->name;
    }
}
