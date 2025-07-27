<?php

declare(strict_types=1);

namespace App\Models\RH;

use App\Models\Core\ModeReglement;
use Illuminate\Database\Eloquent\Model;

final class NoteFraisPaiement extends Model
{
    protected $guarded = [];

    protected $casts = [
        'montant' => 'float',
        'date_paiement' => 'date',
    ];

    public function noteFrais()
    {
        return $this->belongsTo(NoteFrais::class);
    }

    public function modeReglement()
    {
        return $this->belongsTo(ModeReglement::class);
    }

    public function getTypePaiementAttribute()
    {
        return $this->modeReglement->name;
    }

    public function getCompteBancaireAttribute()
    {
        return $this->noteFrais->employe->bank->iban;
    }

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($noteFraisPaiement) {
            if (empty($noteFraisPaiement->numero_paiement)) {
                // Générer un numéro unique avec retry en cas de conflit
                $year = date('Y');
                $attempts = 0;
                $maxAttempts = 10;

                do {
                    $attempts++;

                    // Utiliser un timestamp microseconde + random pour plus d'unicité
                    $microtime = (int) (microtime(true) * 1000000);
                    $random = mt_rand(1000, 9999);
                    $sequence = mb_substr((string) ($microtime + $random), -4);

                    $numero = 'PNF-'.$year.'-'.$sequence;

                    // Vérifier si le numéro existe déjà
                    $exists = static::where('numero_paiement', $numero)->exists();

                    if (! $exists) {
                        $noteFraisPaiement->numero_paiement = $numero;
                        break;
                    }

                    // Si on arrive ici, il y a eu un conflit, on réessaie
                    usleep(1000); // Attendre 1ms avant de réessayer

                } while ($attempts < $maxAttempts);

                // Si on n'a pas réussi après plusieurs tentatives, utiliser un UUID tronqué
                if (empty($noteFraisPaiement->numero_paiement)) {
                    $uuid = str_replace('-', '', (string) \Illuminate\Support\Str::uuid());
                    $noteFraisPaiement->numero_paiement = 'PNF-'.$year.'-'.mb_substr($uuid, 0, 4);
                }
            }
        });
    }
}
