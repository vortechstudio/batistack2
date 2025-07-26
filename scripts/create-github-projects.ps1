# Script PowerShell de création des projets GitHub pour les modules Batistack
# Nécessite GitHub CLI (gh) installé et configuré

Write-Host "🚀 Création des projets GitHub pour Batistack" -ForegroundColor Green
Write-Host "==============================================`n" -ForegroundColor Green

# Vérifier que gh CLI est installé
if (-not (Get-Command gh -ErrorAction SilentlyContinue)) {
    Write-Host "❌ GitHub CLI (gh) n'est pas installé. Veuillez l'installer d'abord." -ForegroundColor Red
    exit 1
}

# Vérifier l'authentification
$authStatus = gh auth status 2>&1
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Vous n'êtes pas authentifié avec GitHub CLI. Exécutez 'gh auth login' d'abord." -ForegroundColor Red
    exit 1
}

# Fonction pour créer un projet
function Create-Project {
    param(
        [string]$Title,
        [string]$Description,
        [string]$Emoji
    )

    Write-Host "📋 Création du projet: $Emoji $Title" -ForegroundColor Cyan

    # Créer le projet (syntaxe simplifiée)
    $result = gh project create --title "$Emoji $Title" 2>&1

    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Projet '$Emoji $Title' créé avec succès" -ForegroundColor Green
        Write-Host "📝 Description: $Description" -ForegroundColor Gray
    } else {
        Write-Host "❌ Erreur lors de la création du projet '$Emoji $Title'" -ForegroundColor Red
        Write-Host $result -ForegroundColor Red
    }

    Write-Host ""
}

# Projets pour les nouveaux modules (priorité haute)
Write-Host "🎯 Création des projets pour les modules prioritaires..." -ForegroundColor Yellow

Create-Project -Title "Module Produits/Services" -Description "Gestion du catalogue produits et services BTP avec spécifications techniques, normes et tarification dynamique" -Emoji "📦"

Create-Project -Title "Module GPAO" -Description "Gestion de Production Assistée par Ordinateur - Planification et suivi de la production d'éléments préfabriqués" -Emoji "🏭"

Create-Project -Title "Module Facturation Avancée" -Description "Extension du module commerce existant avec paiements SEPA, TPE virtuel et gestion des échéances" -Emoji "💰"

# Projets pour les modules de gestion (priorité moyenne)
Write-Host "📊 Création des projets pour les modules de gestion..." -ForegroundColor Yellow

Create-Project -Title "Module Comptabilité" -Description "Comptabilité en partie double avec plan comptable BTP, TVA automatique et export FEC" -Emoji "📊"

Create-Project -Title "Module Immobilisations" -Description "Gestion des immobilisations avec calcul automatique des amortissements et suivi des valeurs" -Emoji "🏢"

Create-Project -Title "Module GED" -Description "Gestionnaire Électronique de Documents avec dématérialisation et archivage légal" -Emoji "📁"

# Projets pour les modules opérationnels (priorité basse)
Write-Host "🚗 Création des projets pour les modules opérationnels..." -ForegroundColor Yellow

Create-Project -Title "Module Véhicules" -Description "Gestion du parc automobile avec suivi des contrôles techniques, entretiens et géolocalisation" -Emoji "🚗"

Create-Project -Title "Module Contrats/Abonnements" -Description "Gestion des contrats de location matériel et abonnements récurrents avec facturation automatique" -Emoji "📋"

# Projets transversaux
Write-Host "⚙️ Création des projets transversaux..." -ForegroundColor Yellow

Create-Project -Title "Infrastructure & DevOps" -Description "Amélioration de l'infrastructure, CI/CD, monitoring et déploiement automatisé" -Emoji "⚙️"

Create-Project -Title "UX/UI & Design System" -Description "Amélioration de l'expérience utilisateur et création d'un design system cohérent" -Emoji "🎨"

Write-Host "🎉 Création des projets GitHub terminée !" -ForegroundColor Green
Write-Host ""
Write-Host "📝 Prochaines étapes recommandées :" -ForegroundColor Cyan
Write-Host "1. Configurer les vues et colonnes de chaque projet" -ForegroundColor White
Write-Host "2. Créer les issues initiales pour chaque module" -ForegroundColor White
Write-Host "3. Définir les milestones et roadmap détaillée" -ForegroundColor White
Write-Host "4. Assigner les projets aux équipes" -ForegroundColor White
