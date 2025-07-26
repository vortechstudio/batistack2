<?php

declare(strict_types=1);

namespace App\Livewire\Core\Analyse;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

final class PhpStan extends Component
{
    public $reportData = [];

    public $hasReport = false;

    public $reportPath = '';

    public $debugInfo = [];

    public $errorMessage = '';

    public function mount()
    {
        $this->loadReport();
    }

    public function loadReport()
    {
        $this->debugInfo = [];
        $this->errorMessage = '';
        $content = null;

        // Chercher le rapport dans différents emplacements
        $possiblePaths = [
            'logs/phpstan-report.json', // Storage public
            base_path('reports/code-quality/phpstan-report.json'), // CI reports
            base_path('phpstan-report.json'), // Racine du projet
            storage_path('logs/phpstan-report.json'), // Storage logs
        ];

        foreach ($possiblePaths as $path) {
            if (str_starts_with($path, 'logs/')) {
                // Utiliser Storage pour les chemins relatifs
                $fullPath = storage_path('app/public/'.$path);
                $this->debugInfo[] = "Vérification Storage: {$path} -> {$fullPath}";

                if (Storage::disk('public')->exists($path)) {
                    $content = Storage::disk('public')->get($path);
                    $this->reportPath = $fullPath;
                    $this->debugInfo[] = "✅ Trouvé via Storage: {$path}";
                    $this->debugInfo[] = 'Taille du contenu: '.mb_strlen($content).' caractères';
                    break;
                }
                $this->debugInfo[] = "❌ Non trouvé via Storage: {$path}";

            } else {
                // Utiliser File pour les chemins absolus
                $this->debugInfo[] = "Vérification File: {$path}";

                if (File::exists($path)) {
                    $content = File::get($path);
                    $this->reportPath = $path;
                    $this->debugInfo[] = "✅ Trouvé via File: {$path}";
                    $this->debugInfo[] = 'Taille du contenu: '.mb_strlen($content).' caractères';
                    break;
                }
                $this->debugInfo[] = "❌ Non trouvé via File: {$path}";

            }
        }

        if (isset($content) && ! empty($content)) {
            $this->debugInfo[] = 'Tentative de nettoyage et décodage JSON...';

            // Nettoyer le contenu pour résoudre les problèmes d'encodage
            $cleanContent = $this->cleanJsonContent($content);

            $decoded = json_decode($cleanContent, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $this->debugInfo[] = '✅ JSON décodé avec succès';
                $this->debugInfo[] = 'Clés trouvées: '.implode(', ', array_keys($decoded));

                if (! empty($decoded)) {
                    $this->reportData = $decoded;
                    $this->hasReport = true;
                    $this->debugInfo[] = '✅ Rapport chargé avec succès';
                } else {
                    $this->debugInfo[] = '❌ JSON vide';
                }
            } else {
                $this->errorMessage = 'Erreur JSON: '.json_last_error_msg();
                $this->debugInfo[] = '❌ '.$this->errorMessage;
                $this->debugInfo[] = 'Début du contenu original: '.mb_substr($content, 0, 200).'...';
                $this->debugInfo[] = 'Début du contenu nettoyé: '.mb_substr($cleanContent, 0, 200).'...';

                // Essayer de sauvegarder une version nettoyée
                $this->saveCleanedReport($cleanContent);
            }
        } else {
            $this->debugInfo[] = '❌ Aucun contenu trouvé';
        }
    }

    public function generateReport()
    {
        try {
            // Créer le répertoire s'il n'existe pas
            $reportsDir = storage_path('app/public/logs');
            if (! File::exists($reportsDir)) {
                File::makeDirectory($reportsDir, 0755, true);
            }

            $outputPath = storage_path('app/public/logs/phpstan-report.json');

            // Exécuter PHPStan avec format JSON et encodage UTF-8
            $command = base_path('vendor/bin/phpstan').' analyse --memory-limit=2G --error-format=json --no-progress';

            // Définir l'encodage pour la commande
            putenv('LANG=en_US.UTF-8');
            putenv('LC_ALL=en_US.UTF-8');

            exec($command.' 2>&1', $output, $returnCode);

            $jsonOutput = implode("\n", $output);

            // Nettoyer la sortie avant de la sauvegarder
            $cleanOutput = $this->cleanJsonContent($jsonOutput);

            // Sauvegarder le rapport nettoyé
            File::put($outputPath, $cleanOutput);

            $this->loadReport();

            session()->flash('message', 'Rapport PHPStan généré avec succès !');

        } catch (Exception $e) {
            session()->flash('error', 'Erreur lors de la génération du rapport : '.$e->getMessage());
        }
    }

    public function reloadReport()
    {
        $this->loadReport();
        session()->flash('message', 'Rapport rechargé !');
    }

    /**
     * Méthode pour corriger manuellement le fichier existant
     */
    public function fixExistingReport()
    {
        try {
            $originalPath = storage_path('app/public/logs/phpstan-report.json');

            if (File::exists($originalPath)) {
                $content = File::get($originalPath);
                $cleanContent = $this->cleanJsonContent($content);

                // Sauvegarder le fichier corrigé
                File::put($originalPath, $cleanContent);

                $this->loadReport();
                session()->flash('message', 'Fichier PHPStan corrigé avec succès !');
            } else {
                session()->flash('error', 'Fichier PHPStan non trouvé.');
            }
        } catch (Exception $e) {
            session()->flash('error', 'Erreur lors de la correction : '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.core.analyse.php-stan');
    }

    /**
     * Nettoie le contenu JSON pour résoudre les problèmes d'encodage
     */
    private function cleanJsonContent($content)
    {
        // Supprimer le BOM UTF-8 (EF BB BF) et autres BOMs
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content); // UTF-8 BOM
        $content = preg_replace('/^\xFF\xFE/', '', $content);     // UTF-16 LE BOM
        $content = preg_replace('/^\xFE\xFF/', '', $content);     // UTF-16 BE BOM
        $content = preg_replace('/^\xFF\xFE\x00\x00/', '', $content); // UTF-32 LE BOM
        $content = preg_replace('/^\x00\x00\xFE\xFF/', '', $content); // UTF-32 BE BOM

        // Supprimer les caractères de remplacement Unicode (�) et autres caractères problématiques
        $content = str_replace(["\xEF\xBF\xBD", '�', '??', '?'], '', $content);

        // Convertir en UTF-8 si nécessaire
        if (! mb_check_encoding($content, 'UTF-8')) {
            $content = mb_convert_encoding($content, 'UTF-8', 'auto');
        }

        // Supprimer les caractères de contrôle invisibles (sauf les espaces normaux)
        $content = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $content);

        // Remplacer les backslashes Windows par des slashes normaux dans les chemins
        $content = str_replace('\\\\', '/', $content);
        $content = str_replace('\\', '/', $content);

        // Nettoyer les caractères problématiques
        $content = str_replace(["\r\n", "\r", "\n"], '', $content);

        // Supprimer les espaces en début et fin
        $content = trim($content);

        // Rechercher le début du JSON valide
        $jsonStart = mb_strpos($content, '{');
        if ($jsonStart !== false && $jsonStart > 0) {
            $content = mb_substr($content, $jsonStart);
            $this->debugInfo[] = "JSON trouvé à la position {$jsonStart}, contenu tronqué";
        }

        return $content;
    }

    /**
     * Sauvegarde une version nettoyée du rapport
     */
    private function saveCleanedReport($cleanContent)
    {
        try {
            $cleanPath = storage_path('app/public/logs/phpstan-report-clean.json');
            File::put($cleanPath, $cleanContent);
            $this->debugInfo[] = "Version nettoyée sauvegardée: {$cleanPath}";
        } catch (Exception $e) {
            $this->debugInfo[] = 'Erreur lors de la sauvegarde nettoyée: '.$e->getMessage();
        }
    }
}
