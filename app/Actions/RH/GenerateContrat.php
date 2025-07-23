<?php

namespace App\Actions\RH;

use App\Models\RH\Employe;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;

class GenerateContrat
{
    public function handle(
        Employe $salarie,
    )
    {
        $salarie->load('info', 'contrat');
        $pdf = Pdf::view('pdf.rh.contrat', compact('salarie'))
            ->format(Format::A4)
            ->margins(1,2,2,2, Unit::Centimeter)
            ->name('contrat.pdf')
            ->disk('public')
            ->save('rh/salarie/'.$salarie->id.'/documents/contrat.pdf');
    }
}
