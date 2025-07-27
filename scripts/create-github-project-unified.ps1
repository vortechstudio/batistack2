# Script PowerShell de création d'un projet GitHub unifié pour Batistack
# Nécessite GitHub CLI (gh) installé et configuré

Write-Host "🚀 Création du projet GitHub unifié Batistack" -ForegroundColor Green
Write-Host "===============================================`n" -ForegroundColor Green

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

Write-Host "📋 Création du projet principal..." -ForegroundColor Cyan

# Créer le projet principal
$result = gh project create --title "🏗️ Batistack - Développement Modules ERP" 2>&1

if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Projet principal créé avec succès" -ForegroundColor Green
    
    Write-Host "`n📝 Structure recommandée du projet :" -ForegroundColor Yellow
    Write-Host "├── 🎯 Modules Prioritaires (Q1 2025)" -ForegroundColor White
    Write-Host "│   ├── 📦 Module Produits/Services" -ForegroundColor Gray
    Write-Host "│   ├── 🏭 Module GPAO" -ForegroundColor Gray
    Write-Host "│   └── 💰 Module Facturation Avancée" -ForegroundColor Gray
    Write-Host "├── 📊 Modules de Gestion (Q2 2025)" -ForegroundColor White
    Write-Host "│   ├── 📊 Module Comptabilité" -ForegroundColor Gray
    Write-Host "│   ├── 🏢 Module Immobilisations" -ForegroundColor Gray
    Write-Host "│   └── 📁 Module GED" -ForegroundColor Gray
    Write-Host "├── 🚗 Modules Opérationnels (Q3-Q4 2025)" -ForegroundColor White
    Write-Host "│   ├── 🚗 Module Véhicules" -ForegroundColor Gray
    Write-Host "│   └── 📋 Module Contrats/Abonnements" -ForegroundColor Gray
    Write-Host "└── ⚙️ Projets Transversaux" -ForegroundColor White
    Write-Host "    ├── ⚙️ Infrastructure & DevOps" -ForegroundColor Gray
    Write-Host "    └── 🎨 UX/UI & Design System" -ForegroundColor Gray
    
} else {
    Write-Host "❌ Erreur lors de la création du projet" -ForegroundColor Red
    Write-Host $result -ForegroundColor Red
    exit 1
}

Write-Host "`n🏷️ Création des labels pour organiser les modules..." -ForegroundColor Cyan

# Labels par module
$modules = @(
    @{name="module:produits-services"; color="1f77b4"; description="Module Produits et Services"},
    @{name="module:gpao"; color="ff7f0e"; description="Module GPAO"},
    @{name="module:facturation"; color="2ca02c"; description="Module Facturation Avancée"},
    @{name="module:comptabilite"; color="d62728"; description="Module Comptabilité"},
    @{name="module:immobilisations"; color="9467bd"; description="Module Immobilisations"},
    @{name="module:ged"; color="8c564b"; description="Module GED"},
    @{name="module:vehicules"; color="e377c2"; description="Module Véhicules"},
    @{name="module:contrats"; color="7f7f7f"; description="Module Contrats/Abonnements"},
    @{name="transversal:infra"; color="bcbd22"; description="Infrastructure & DevOps"},
    @{name="transversal:ux"; color="17becf"; description="UX/UI & Design System"}
)

# Labels par priorité
$priorities = @(
    @{name="priorite:haute"; color="d73a4a"; description="Priorité Haute - Q1 2025"},
    @{name="priorite:moyenne"; color="fbca04"; description="Priorité Moyenne - Q2 2025"},
    @{name="priorite:basse"; color="0075ca"; description="Priorité Basse - Q3-Q4 2025"}
)

# Labels par type
$types = @(
    @{name="type:feature"; color="a2eeef"; description="Nouvelle fonctionnalité"},
    @{name="type:enhancement"; color="7057ff"; description="Amélioration"},
    @{name="type:bug"; color="d73a4a"; description="Correction de bug"},
    @{name="type:documentation"; color="0075ca"; description="Documentation"},
    @{name="type:refactor"; color="ffffff"; description="Refactorisation"}
)

foreach ($module in $modules) {
    gh label create $module.name --color $module.color --description $module.description 2>$null
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Label '$($module.name)' créé" -ForegroundColor Green
    }
}

foreach ($priority in $priorities) {
    gh label create $priority.name --color $priority.color --description $priority.description 2>$null
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Label '$($priority.name)' créé" -ForegroundColor Green
    }
}

foreach ($type in $types) {
    gh label create $type.name --color $type.color --description $type.description 2>$null
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Label '$($type.name)' créé" -ForegroundColor Green
    }
}

Write-Host "`n🎉 Projet GitHub unifié créé avec succès !" -ForegroundColor Green
Write-Host ""
Write-Host "📝 Prochaines étapes recommandées :" -ForegroundColor Cyan
Write-Host "1. Configurer les vues du projet (par module, par priorité, par statut)" -ForegroundColor White
Write-Host "2. Créer les colonnes : Backlog, En cours, En review, Terminé" -ForegroundColor White
Write-Host "3. Créer les issues initiales pour chaque module avec les bons labels" -ForegroundColor White
Write-Host "4. Définir les milestones par trimestre" -ForegroundColor White
Write-Host "5. Utiliser le template 'module_development.yml' pour les nouvelles issues" -ForegroundColor White
