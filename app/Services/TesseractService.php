<?php

namespace App\Services;

use Exception;
use Intervention\Image\Laravel\Facades\Image;
use thiagoalessio\TesseractOCR\TesseractOCR;

class TesseractService
{
    public function analyze(string $imagePath)
    {
        if(!$this->preprocessOCR($imagePath)) {
            return null;
        }

        $ocr = new TesseractOCR($imagePath);
        $ocr->executable(config('services.tesseract.bin_path'));

        return $ocr->lang(config('services.tesseract.lang'))
            ->run();
    }

    private function preprocessOCR(string $imagePath)
    {
        try {
            $image = Image::make($imagePath)
                ->greyscale()
                ->resize(2480, 3508, function($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->contrast(10)
                ->brightness(10)               // Légère augmentation de luminosité
                ->sharpen(5);

            $image->encode('png')->filter(function ($filterImage) {
                $filterImage->each(function ($pixel) {
                    $gray = ($pixel->r + $pixel->g + $pixel->b) / 3;
                    $value = $gray > 128 ? 255 : 0;
                    $pixel->r = $pixel->g = $pixel->b = $value;
                });
            });

            $image->save($imagePath);
        } catch(Exception $ex) {
            \Log::error('OCR preprocessing failed: ' . $ex->getMessage());
            return false;
        }
    }
}
