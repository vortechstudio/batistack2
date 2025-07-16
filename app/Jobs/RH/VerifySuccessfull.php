<?php

namespace App\Jobs\RH;

use App\Enums\RH\ProcessEmploye;
use App\Models\RH\Employe;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;

class VerifySuccessfull implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Employe $salarie,
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if(isset($this->salarie->info->cni_verified_at) && isset($this->salarie->info->vital_card_verified_at)) {
            $this->salarie->info->update([
                'process' => ProcessEmploye::DPAE
            ]);
        }
    }
}
