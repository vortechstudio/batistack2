<?php

namespace App\Models\RH;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function info()
    {
        return $this->hasOne(EmployeInfo::class);
    }

    protected function casts(): array
    {
        return [
            'date_embauche' => 'date',
            'date_sortie' => 'date',
        ];
    }
}
