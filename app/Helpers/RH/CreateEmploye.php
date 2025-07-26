<?php

declare(strict_types=1);

namespace App\Helpers\RH;

use App\Enums\Core\UserRole;
use App\Models\RH\Employe;
use App\Models\RH\EmployeContrat;
use App\Models\RH\EmployeInfo;
use App\Models\User;
use App\Services\Bridge;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Str;

final class CreateEmploye
{
    public function create(array $data)
    {
        $bridge = new Bridge();
        // Création de l'utilisateur
        $user = User::create([
            'name' => $data['nom'].' '.$data['prenom'],
            'email' => $data['email'],
            'password' => Hash::make('password'),
            'blocked' => true,
            'role' => UserRole::SALARIE,
            'phone_number' => $data['portable'],
            'notif_phone' => false,
        ]);

        // Création de la fiche employé
        Employe::create([
            'civility' => $data['civility'],
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'adresse' => $data['address'],
            'code_postal' => $data['code_postal'],
            'ville' => $data['ville'],
            'telephone' => $data['telephone'],
            'portable' => $data['portable'],
            'email' => $data['email'],
            'poste' => $data['poste'],
            'date_embauche' => $data['date_debut'],
            'date_sortie' => $data['date_fin'],
            'type_contrat' => $data['type'],
            'salaire_base' => ($data['salaire_horaire'] * $data['heure_travail']) * 4,
            'status' => 'inactif',
            'user_id' => $user->id,
            'uuid' => Str::uuid(),
        ]);

        $salarie = Employe::latest()->first();

        EmployeInfo::create([
            'employe_id' => $salarie->id,
            'num_cni' => $data['num_cni'],
            'num_secu' => $data['num_secu'],
            'num_passport' => $data['num_passport'],
            'date_naissance' => $data['date_naissance'],
            'lieu_naissance' => $data['lieu_naissance'],
            'pays_naissance' => $data['pays_naissance'],
            'num_permis_btp' => $data['num_permis_btp'],
            'exp_permis_btp' => $data['exp_permis_btp'],
        ]);

        EmployeContrat::create([
            'employe_id' => $salarie->id,
            'type' => $data['type'],
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin'],
            'salaire_horaire' => $data['salaire_horaire'],
            'heure_travail' => $data['heure_travail'],
            'status' => 'draft',
        ]);

        $userBridge = $bridge->post('/aggregation/users', [
            'external_user_id' => $salarie->matricule,
        ]);

        $salarie->update([
            'bridge_user_id' => $userBridge['uuid'],
        ]);

        try {
            if (! Storage::disk('ged')->exists($salarie->matricule)) {
                Storage::disk('ged')->makeDirectory($salarie->matricule);
                Storage::disk('ged')->makeDirectory($salarie->matricule.'/documents');
                Storage::disk('ged')->makeDirectory($salarie->matricule.'/documents/rh');
            }
        } catch (Exception $ex) {
            Log::emergency($ex->getMessage());
        }

        $salarie->user->notify(
            Notification::make()
                ->success()
                ->title('Création de compte')
                ->body('Votre compte a été créé avec succès.')
                ->toDatabase(),
        );

        $salarie->user->notify(
            Notification::make()
                ->info()
                ->title('Signature de votre contrat')
                ->body('Veuillez signer votre contrat en cliquant sur le bouton ci-dessous.')
                ->toDatabase(),
        );

        $salarie->user->notify(
            Notification::make()
                ->info()
                ->title('Compte Bancaire')
                ->body('Veuillez configurer votre compte bancaire en cliquant sur le bouton ci-dessous.')
                ->toDatabase(),
        );
    }
}
