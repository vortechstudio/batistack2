<?php

declare(strict_types=1);

namespace App\Actions\RH;

use App\Models\RH\Employe;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;

final class GenerateDPAEPdf
{
    public function handle(
        Employe $employe,
    ): Employe {
        $employe->load('info', 'contrat');
        $pdf = Pdf::view('pdf.rh.dpae_employe', compact('employe'))
            ->format(Format::A4)
            ->margins(1, 2, 2, 2, Unit::Centimeter)
            ->name('dpae_'.$employe->matricule.'.pdf')
            ->disk('ged')
            ->save($employe->matricule.'/documents/rh/dpae_'.$employe->matricule.'.pdf');

        return $employe->refresh();
    }
}
