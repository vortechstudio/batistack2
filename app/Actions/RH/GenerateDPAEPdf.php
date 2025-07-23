<?php

namespace App\Actions\RH;

use App\Models\RH\Employe;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;

class GenerateDPAEPdf
{
    public function handle(
        Employe $employe,
        string $directory,
    ): Employe
{
        $employe->load('info', 'contrat');
        $pdf = Pdf::view('pdf.rh.dpae_employe', compact('employe'))
            ->format(Format::A4)
            ->margins(1,2,2,2, Unit::Centimeter)
            ->name('dpae_'.$employe->matricule.'.pdf')
            ->disk('public');

        $pdf->save($path = FacadesStorage::path($directory).DIRECTORY_SEPARATOR.'dpae_'.$employe->matricule.'.pdf');

        $employe
            ->addMedia($path)
            ->toMediaCollection();

        return $employe->refresh();
    }
}
