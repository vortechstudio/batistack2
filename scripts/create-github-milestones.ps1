# Script PowerShell de création des jalons GitHub pour Batistack
# Nécessite GitHub CLI (gh) installé et configuré

Write-Host "🎯 Création des jalons GitHub pour Batistack" -ForegroundColor Green
Write-Host "============================================`n" -ForegroundColor Green

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

Write-Host "📅 Création des jalons basés sur la roadmap..." -ForegroundColor Cyan

# Définition des jalons avec dates ajustées (nous sommes le 26/07/2025)
$milestones = @(
    @{
        title = "🎯 Q3 2025 - Modules Prioritaires"
        description = "Développement des modules prioritaires : Produits/Services, GPAO, Facturation Avancée"
        due_date = "2025-09-30"
    },
    @{
        title = "📊 Q4 2025 - Modules de Gestion"
        description = "Développement des modules de gestion : Comptabilité, Immobilisations, GED"
        due_date = "2025-12-31"
    },
    @{
        title = "🚗 Q1 2026 - Modules Opérationnels"
        description = "Développement des modules opérationnels : Véhicules, Contrats/Abonnements"
        due_date = "2026-03-31"
    },
    @{
        title = "🎉 Q2 2026 - Finalisation & Optimisation"
        description = "Finalisation, tests d'intégration, optimisations et préparation du déploiement"
        due_date = "2026-06-30"
    },
    @{
        title = "🚀 2026-2027 - Vision Long Terme"
        description = "Évolutions futures, nouvelles fonctionnalités et expansion"
        due_date = "2027-12-31"
    },
    @{
        title = "⚙️ Infrastructure & DevOps"
        description = "Amélioration continue de l'infrastructure, CI/CD, monitoring et sécurité"
        due_date = "2026-12-31"
    },
    @{
        title = "🎨 UX/UI & Design System"
        description = "Développement du design system et amélioration de l'expérience utilisateur"
        due_date = "2026-06-30"
    }
)

$created = 0
$errors = 0

foreach ($milestone in $milestones) {
    Write-Host "📌 Création du jalon : $($milestone.title)" -ForegroundColor Yellow

    $result = gh api repos/:owner/:repo/milestones -X POST -f title="$($milestone.title)" -f description="$($milestone.description)" -f due_on="$($milestone.due_date)T23:59:59Z" 2>&1

    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Jalon '$($milestone.title)' créé avec succès" -ForegroundColor Green
        $created++
    } else {
        Write-Host "❌ Erreur lors de la création du jalon '$($milestone.title)'" -ForegroundColor Red
        Write-Host "   $result" -ForegroundColor Red
        $errors++
    }
}

Write-Host "`n📊 Résumé de la création des jalons :" -ForegroundColor Cyan
Write-Host "✅ Jalons créés : $created" -ForegroundColor Green
Write-Host "❌ Erreurs : $errors" -ForegroundColor Red

if ($created -gt 0) {
    Write-Host "`n🎉 Jalons créés avec succès !" -ForegroundColor Green
    Write-Host ""
    Write-Host "📝 Prochaines étapes recommandées :" -ForegroundColor Cyan
    Write-Host "1. Associer les issues existantes aux jalons appropriés" -ForegroundColor White
    Write-Host "2. Créer des issues pour chaque module avec les bons jalons" -ForegroundColor White
    Write-Host "3. Utiliser les jalons pour suivre l'avancement par trimestre" -ForegroundColor White
    Write-Host "4. Réviser régulièrement les dates d'échéance selon l'avancement" -ForegroundColor White
}
