<?php

namespace App\Livewire\Portail\Salarie\Documents;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class RH extends Component
{
    public function render()
    {
        $matricule = Auth::user()->employe->matricule;
        $rhDocumentsPath = $matricule . '/documents/rh';

        // Récupérer tous les fichiers du dossier RH de l'employé
        $documents = [];
        if (Storage::disk('ged')->exists($rhDocumentsPath)) {
            $allFiles = Storage::disk('ged')->allFiles($rhDocumentsPath);

            foreach ($allFiles as $file) {
                $documents[] = [
                    'name' => basename($file),
                    'path' => $file,
                    'url' => Storage::disk('ged')->url($file),
                    'size' => Storage::disk('ged')->size($file),
                    'lastModified' => Storage::disk('ged')->lastModified($file),
                    'type' => $this->getDocumentType($file),
                ];
            }
        }

        return view('livewire.portail.salarie.documents.r-h', [
            'documents' => $documents,
            'matricule' => $matricule
        ]);
    }

    private function getDocumentType($filePath)
    {
        $fileName = basename($filePath);

        if (str_contains(strtolower($fileName), 'contrat')) {
            return 'Contrat de travail';
        } elseif (str_contains(strtolower($fileName), 'cni') || str_contains(strtolower($fileName), 'carte') && str_contains(strtolower($fileName), 'identite')) {
            return 'Carte d\'identité';
        } elseif (str_contains(strtolower($fileName), 'vital') || str_contains(strtolower($fileName), 'vitale')) {
            return 'Carte vitale';
        } elseif (str_contains(strtolower($fileName), 'rib')) {
            return 'RIB';
        } elseif (str_contains(strtolower($fileName), 'dpae')) {
            return 'DPAE';
        } elseif (str_contains(strtolower($fileName), 'cv') || str_contains(strtolower($fileName), 'curriculum')) {
            return 'CV';
        } elseif (str_contains(strtolower($fileName), 'diplome') || str_contains(strtolower($fileName), 'certificat')) {
            return 'Diplôme/Certificat';
        }

        return 'Document RH';
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
