# Script PowerShell de reproduction du workflow GitHub Actions: code-metrics.yml
# Génère des métriques de code avec PHPMetrics

param(
    [switch]$OpenReport = $false
)

# Configuration des couleurs
$Host.UI.RawUI.ForegroundColor = "White"

function Write-Step {
    param($Message)
    Write-Host "📋 $Message" -ForegroundColor Blue
}

function Write-Success {
    param($Message)
    Write-Host "✅ $Message" -ForegroundColor Green
}

function Write-Warning {
    param($Message)
    Write-Host "⚠️  $Message" -ForegroundColor Yellow
}

function Write-Error {
    param($Message)
    Write-Host "❌ $Message" -ForegroundColor Red
}

# Configuration d'arrêt en cas d'erreur
$ErrorActionPreference = "Stop"

try {
    Write-Host "🔍 Démarrage de l'analyse des métriques de code..." -ForegroundColor Cyan
    Write-Host ""

    # Vérification de PHP
    Write-Step "Vérification de PHP 8.3..."
    try {
        $phpVersion = php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;" 2>$null
        if ($phpVersion -ne "8.3") {
            Write-Warning "Version PHP détectée: $phpVersion (recommandé: 8.3)"
        } else {
            Write-Success "PHP 8.3 détecté"
        }
    } catch {
        Write-Error "PHP n'est pas installé ou n'est pas dans le PATH"
        exit 1
    }

    # Vérification de Composer
    Write-Step "Vérification de Composer..."
    try {
        composer --version | Out-Null
        Write-Success "Composer disponible"
    } catch {
        Write-Error "Composer n'est pas installé ou n'est pas dans le PATH"
        exit 1
    }

    # Installation des dépendances
    Write-Step "Installation des dépendances avec optimisation..."
    $composerResult = composer install --optimize-autoloader --no-dev --quiet
    if ($LASTEXITCODE -eq 0) {
        Write-Success "Dépendances installées avec succès"
    } else {
        Write-Error "Échec de l'installation des dépendances"
        exit 1
    }

    # Vérification de PHPMetrics
    Write-Step "Vérification de PHPMetrics..."
    if (-not (Test-Path "vendor\bin\phpmetrics") -and -not (Test-Path "vendor\bin\phpmetrics.bat")) {
        Write-Warning "PHPMetrics non trouvé, installation..."
        composer require --dev phpmetrics/phpmetrics --quiet

        if ($LASTEXITCODE -eq 0) {
            Write-Success "PHPMetrics installé avec succès"
        } else {
            Write-Error "Échec de l'installation de PHPMetrics"
            exit 1
        }
    } else {
        Write-Success "PHPMetrics disponible"
    }

    # Création du dossier de sortie
    Write-Step "Préparation du dossier de métriques..."
    if (Test-Path "metrics") {
        Remove-Item -Recurse -Force "metrics"
        Write-Warning "Ancien dossier metrics supprimé"
    }
    New-Item -ItemType Directory -Path "metrics" | Out-Null
    Write-Success "Dossier metrics créé"

    # Génération des métriques
    Write-Step "Génération des métriques de code..."
    Write-Host "Analyse du dossier app\ en cours..." -ForegroundColor Gray

    # Utilisation du bon exécutable selon l'OS
    $phpmetricsCmd = if (Test-Path "vendor\bin\phpmetrics.bat") { "vendor\bin\phpmetrics.bat" } else { "vendor\bin\phpmetrics" }

    & $phpmetricsCmd --report-html=metrics app\

    if ($LASTEXITCODE -eq 0) {
        Write-Success "Métriques générées avec succès"
    } else {
        Write-Error "Échec de la génération des métriques"
        exit 1
    }

    # Vérification des fichiers générés
    Write-Step "Vérification des fichiers générés..."
    if (Test-Path "metrics\index.html") {
        Write-Success "Rapport HTML généré: metrics\index.html"

        # Calcul de la taille du rapport
        $reportSize = (Get-ChildItem -Recurse "metrics" | Measure-Object -Property Length -Sum).Sum
        $reportSizeFormatted = if ($reportSize -gt 1MB) {
            "{0:N2} MB" -f ($reportSize / 1MB)
        } elseif ($reportSize -gt 1KB) {
            "{0:N2} KB" -f ($reportSize / 1KB)
        } else {
            "$reportSize bytes"
        }

        # Comptage des fichiers analysés
        $fileCount = (Get-ChildItem -Recurse "app" -Filter "*.php").Count

        Write-Host "📊 Taille du rapport: $reportSizeFormatted" -ForegroundColor Blue
        Write-Host "📁 Fichiers PHP analysés: $fileCount" -ForegroundColor Blue

    } else {
        Write-Error "Le rapport HTML n'a pas été généré"
        exit 1
    }

    # Affichage des informations finales
    Write-Host ""
    Write-Host "🎉 Analyse des métriques terminée avec succès !" -ForegroundColor Green
    Write-Host ""
    Write-Host "📋 Résumé:" -ForegroundColor Cyan
    Write-Host "  - Rapport disponible: metrics\index.html"
    Write-Host "  - Fichiers analysés: $fileCount fichiers PHP"
    Write-Host "  - Taille du rapport: $reportSizeFormatted"
    Write-Host ""
    Write-Host "💡 Pour ouvrir le rapport:" -ForegroundColor Yellow
    Write-Host "  - Windows: start metrics\index.html"
    Write-Host "  - Ou double-cliquez sur le fichier metrics\index.html"
    Write-Host ""

    # Optionnel: ouvrir automatiquement le rapport
    if ($OpenReport) {
        Start-Process "metrics\index.html"
    } else {
        $response = Read-Host "Voulez-vous ouvrir le rapport maintenant ? (y/N)"
        if ($response -match "^[Yy]") {
            Start-Process "metrics\index.html"
        }
    }

} catch {
    Write-Error "Erreur lors de l'exécution: $($_.Exception.Message)"
    exit 1
}
