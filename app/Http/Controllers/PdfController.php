<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Enums\Orientation;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;

final class PdfController extends Controller
{
    public function __invoke(string $type, $records)
    {
        return match ($type) {
            'liste_produit' => $this->listeProduits($records),
        };
    }

    private function listeProduits($records)
    {
        return Pdf::view('pdf.produit.liste_produit', ['produits' => $records])
            ->format(Format::A4)
            ->orientation(Orientation::Landscape)
            ->margins(1, 2, 2, 2, Unit::Centimeter)
            ->name('liste_produit.pdf')
            ->download();
    }
}
