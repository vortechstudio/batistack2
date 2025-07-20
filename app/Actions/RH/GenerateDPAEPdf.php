<?php

namespace App\Actions\RH;

use App\Models\RH\Employe;
use Barryvdh\DomPDF\Facade\Pdf;
use Storage;

class GenerateDPAEPdf
{
    public function handle(
        Employe $employe,
        string $directory,
    ): Employe
{
        $employe->load('info', 'contrat');
        $pdf = Pdf::loadView('pdf.rh.dpae_employe', compact('employe'));
        if (Storage::directoryMissing($directory)) {
            Storage::makeDirectory($directory);
        }
        $pdf->save($path = Storage::path($directory).DIRECTORY_SEPARATOR.'dpae_'.$employe->matricule.'.pdf');
        $employe
            ->addMedia($path)
            ->toMediaCollection();

        return $employe->refresh();
    }
}
