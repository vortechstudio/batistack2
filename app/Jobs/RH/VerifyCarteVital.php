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

class VerifyCarteVital implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Employe $salarie,
        private string $cartePath
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $ocr = new TesseractService();
            $text = $ocr->analyze(Storage::disk('public')->path($this->cartePath));

            // Vérification numéro sécurité sociale
            preg_match('/('.$this->formatNumSecuSalarie().')/ius', $text, $matches);

            if (!$matches) {
                throw new \Exception('Numéro sécurité sociale invalide');
            }

            $normalize = fn ($str) => preg_replace('/\s+/', '', $str);
            $numeroSS = $normalize(implode('', array_slice($matches, 1, 6)));

            if ($numeroSS !== $normalize($this->salarie->info->num_secu)) {
                throw new \Exception('Numéro sécurité sociale non concordant');
            }

            $this->salarie->info->update([
                'vital_verified_at' => now()
            ]);

        } catch (\Exception $e) {
            Log::error("Erreur vérification Carte Vitale : {$e->getMessage()}");
            throw $e;
        }
    }

    private function formatNumSecuSalarie(): string
    {
        return preg_replace('/(\d{1})(\d{2})(\d{2})(\d{2})(\d{3})(\d{3})(\d{2})/i', '$1 $2 $3 $4 $5 $6 $7', $this->salarie->info->num_secu);
    }
}
