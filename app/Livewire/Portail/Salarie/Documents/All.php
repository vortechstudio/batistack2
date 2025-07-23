<?php

namespace App\Livewire\Portail\Salarie\Documents;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class All extends Component
{
    public function render()
    {
        $matricule = Auth::user()->employe->matricule;
        $documentsPath = $matricule . '/documents';

        // Récupérer tous les fichiers du dossier documents de l'employé
        $documents = [];
        if (Storage::disk('ged')->exists($documentsPath)) {
            $allFiles = Storage::disk('ged')->allFiles($documentsPath);

            foreach ($allFiles as $file) {
                $documents[] = [
                    'name' => basename($file),
                    'path' => $file,
                    'url' => Storage::disk('ged')->url($file),
                    'size' => Storage::disk('ged')->size($file),
                    'lastModified' => Storage::disk('ged')->lastModified($file),
                    'category' => $this->getCategoryFromPath($file),
                ];
            }
        }

        return view('livewire.portail.salarie.documents.all', [
            'documents' => $documents,
            'matricule' => $matricule
        ]);
    }

    private function getCategoryFromPath($filePath)
    {
        if (str_contains($filePath, '/rh/')) {
            return 'RH';
        } elseif (str_contains($filePath, '/paie/')) {
            return 'Paie';
        } elseif (str_contains($filePath, '/contrat')) {
            return 'Contrat';
        }

        return 'Autre';
    }

    public function downloadDocument($filePath)
    {
        if (Storage::disk('ged')->exists($filePath)) {
            return Storage::disk('ged')->download($filePath);
        }

        session()->flash('error', 'Le document demandé n\'existe pas.');
    }

    public function formatFileSize($bytes)
    {
        if ($bytes == 0) return '0 Bytes';

        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($bytes) / log($k));

        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }
}
