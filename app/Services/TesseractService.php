<?php

namespace App\Services;

use thiagoalessio\TesseractOCR\TesseractOCR;

class TesseractService
{
    public function analyze(string $imagePath)
    {
        $ocr = new TesseractOCR($imagePath);
        $ocr->executable(config('services.tesseract.bin_path'));

        return $ocr->lang(config('services.tesseract.lang'))
            ->run();
    }
}
