<?php

namespace App\Helpers\RH;

use Illuminate\Database\Eloquent\Model;

class UpdateEmploye
{
    public function update(array $data, Model $record)
    {
        $record->update([
            'civility' => $data['civility'],
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'adresse' => $data['adresse'],
            'ville' => $data['ville'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'portable' => $data['portable'],
            'poste' => $data['poste'],
            'date_embauche' => $data['date_debut'],
            'date_sortie' => $data['date_fin'],
            'type_contrat' => $data['type'],
            'salaire_base' => ($data['salaire_horaire'] * $data['heure_travail']) * 4,
        ]);

        $record->info->update([
            'num_cni' => $data['num_cni'],
            'num_secu' => $data['num_secu'],
            'num_passport' => $data['num_passport'],
            'date_naissance' => $data['date_naissance'],
            'lieu_naissance' => $data['lieu_naissance'],
            'pays_naissance' => $data['pays_naissance'],
            'num_permis_btp' => $data['num_permis_btp'],
            'exp_permis_btp' => $data['exp_permis_btp'],
        ]);

        $record->contrat->update([
            'type' => $data['type'],
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin'],
            'salaire_horaire' => $data['salaire_horaire'],
            'heure_travail' => $data['heure_travail'],
        ]);
    }
}
