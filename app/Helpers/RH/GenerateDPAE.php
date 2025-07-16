<?php

namespace App\Helpers\RH;

use App\Models\Core\Company;
use App\Models\RH\Employe;
use Spatie\ArrayToXml\ArrayToXml;
use Storage;

class GenerateDPAE
{
    public function generate(Employe $salarie, string $nameDpae)
    {
        $array = [
            'Envoie' => [
                'IdentifiantEmetteur' => Company::first()->siret,
                'TypeEnvoie' => 'EMISSION',
                'DateEnvoie' => now()->format('Y-m-d')
            ],
            'Declaration' => [
                'Employeur' => [
                    'SIRET' => Company::first()->siret,
                    'RaisonSociale' => Company::first()->name,
                    'Adresse' => [
                        'Numero' => '',
                        'Voie' => Company::first()->address,
                        'CodePostal' => Company::first()->code_postal,
                        'Ville' => Company::first()->ville,
                    ],
                ],
                'Salarie' => [
                    'Nom' => $salarie->nom,
                    'Prenom' => $salarie->prenom,
                    'DateNaissance' => $salarie->info->date_naissance,
                    'NIR' => $salarie->info->num_secu,
                    'Nationalite' => 'FR',
                    'LieuNaissance' => $salarie->info->lieu_naissance,
                    'PaysNaissance' => $salarie->info->pays_naissance
                ],
                'Contrat' => [
                    'DateDebut' => $salarie->contrat->date_debut->format('Y-m-d'),
                    'HeureDebut' => $salarie->contrat->date_debut->format('H:i'),
                    'NatureContrat' => $salarie->contrat->type->value,
                    'LieuTravail' => [
                        'Numero' => '',
                        'Voie' => Company::first()->address,
                        'CodePostal' => Company::first()->code_postal,
                        'Ville' => Company::first()->ville,
                    ]
                ]
            ]
        ];

        $xml = ArrayToXml::convert($array, [
            'rootElementName' => 'DPAE',
            '_attributes' => ['version' => '1.0'],
        ]);

        Storage::disk('public')->put('rh/salarie/'.$salarie->id.'/documents/'.$nameDpae, $xml);
    }
}
