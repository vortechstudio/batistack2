<?php

namespace App\Helpers\RH;

use Illuminate\Database\Eloquent\Model;

class UpdateEmploye
{
    public function update(array $data, Model $record)
    {
        $record->update($data);
        $record->info->update($data);
        $record->contrat->update($data);
    }
}
