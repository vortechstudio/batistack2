<?php

namespace App\Jobs\RH;

use App\Models\RH\Employe;
use App\Services\TesseractService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Log;
use Storage;
use Str;

class VerifyBTPCard implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Employe $salarie,
        private string $cniPath
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $ocr = new TesseractService();
            if (Storage::disk('public')->exists($this->cniPath)) {
                $text = $ocr->analyze(Storage::disk('public')->path($this->cniPath));
                $normalize = fn ($str) => mb_strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $str));
                $cleanText = preg_replace('/[^\p{L}0-9\s]/u', ' ', $text);
                $cleanText = preg_replace('/\s+/', ' ', $cleanText);

                // Extraction nom/prénom/date de naissance
                preg_match('/('.Str::upper($this->salarie->nom).').*?('.Str::upper($this->salarie->prenom).')/ius', $cleanText, $matches);

                if (count($matches) < 3) {
                    throw new \Exception('Informations manquantes sur la carte BTP');
                }

                $nomCNI = $normalize($matches[1]);
                $prenomCNI = $normalize($matches[2]);

                $nomEmploye = $normalize($this->salarie->nom);
                $prenomEmploye = $normalize($this->salarie->prenom);

                if ($nomCNI !== $nomEmploye || $prenomCNI !== $prenomEmploye) {
                    throw new \Exception('Données CNI non concordantes');
                }

                $this->salarie->info->update([
                    'btp_card_verified_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Erreur vérification CNI : {$e->getMessage()}");
            throw $e;
        }
    }
}
