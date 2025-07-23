<?php

namespace App\Livewire\Portail\Salarie\Documents;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Carbon\Carbon;

class Paie extends Component
{
    public function render()
    {
        $matricule = Auth::user()->employe->matricule;
        $paieDocumentsPath = $matricule . '/documents/paie';

        // Récupérer tous les fichiers du dossier paie de l'employé
        $documents = [];
        if (Storage::disk('ged')->exists($paieDocumentsPath)) {
            $allFiles = Storage::disk('ged')->allFiles($paieDocumentsPath);

            foreach ($allFiles as $file) {
                $documents[] = [
                    'name' => basename($file),
                    'path' => $file,
                    'url' => Storage::disk('ged')->url($file),
                    'size' => Storage::disk('ged')->size($file),
                    'lastModified' => Storage::disk('ged')->lastModified($file),
                    'period' => $this->extractPeriodFromFileName($file),
                    'type' => $this->getDocumentType($file),
                ];
            }

            // Trier par période (plus récent en premier)
            usort($documents, function($a, $b) {
                return $b['lastModified'] <=> $a['lastModified'];
            });
        }

        return view('livewire.portail.salarie.documents.paie', [
            'documents' => $documents,
            'matricule' => $matricule
        ]);
    }

    private function extractPeriodFromFileName($filePath)
    {
        $fileName = basename($filePath);

        // Rechercher des patterns de date dans le nom du fichier
        if (preg_match('/(\d{2})[-_](\d{4})/', $fileName, $matches)) {
            // Format MM-YYYY ou MM_YYYY
            return $matches[1] . '/' . $matches[2];
        } elseif (preg_match('/(\d{4})[-_](\d{2})/', $fileName, $matches)) {
            // Format YYYY-MM ou YYYY_MM
            return $matches[2] . '/' . $matches[1];
        } elseif (preg_match('/(\d{1,2})[-_](\d{1,2})[-_](\d{4})/', $fileName, $matches)) {
            // Format DD-MM-YYYY
            return $matches[2] . '/' . $matches[3];
        }

        // Si aucun pattern trouvé, utiliser la date de modification
        return date('m/Y', Storage::disk('ged')->lastModified($filePath));
    }

    private function getDocumentType($filePath)
    {
        $fileName = strtolower(basename($filePath));

        if (str_contains($fileName, 'bulletin') || str_contains($fileName, 'fiche') && str_contains($fileName, 'paie')) {
            return 'Bulletin de paie';
        } elseif (str_contains($fileName, 'solde')) {
            return 'Solde de tout compte';
        } elseif (str_contains($fileName, 'certificat') && str_contains($fileName, 'travail')) {
            return 'Certificat de travail';
        } elseif (str_contains($fileName, 'attestation') && (str_contains($fileName, 'pole') || str_contains($fileName, 'emploi'))) {
            return 'Attestation Pôle Emploi';
        } elseif (str_contains($fileName, 'recap') || str_contains($fileName, 'recapitulatif')) {
            return 'Récapitulatif annuel';
        } elseif (str_contains($fileName, 'prime') || str_contains($fileName, 'bonus')) {
            return 'Prime/Bonus';
        }

        return 'Document de paie';
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
