# Script PowerShell de création des issues pour le Module Produits/Services
# Nécessite GitHub CLI (gh) installé et configuré

Write-Host "📦 Création des issues pour le Module Produits/Services" -ForegroundColor Green
Write-Host "====================================================`n" -ForegroundColor Green

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

Write-Host "📝 Création des issues pour le développement du module..." -ForegroundColor Cyan

# Définition des issues pour le Module Produits/Services
$issues = @(
    @{
        title = "[PRODUITS] Création du modèle de base Produit"
        body = @"
## Description détaillée

Créer le modèle de base pour la gestion des produits dans le contexte BTP, incluant les matériaux, fournitures, et services.

### Contexte métier
- Besoin de gérer un catalogue de produits/services pour les devis et factures
- Distinction entre matériaux (quantifiables) et services (temps/forfait)
- Gestion des prix d'achat et de vente
- Suivi des stocks pour les matériaux

### Solution proposée
Modèle Produit avec les caractéristiques spécifiques au BTP

## Critères d'acceptation

- [ ] Modèle Produit créé avec les champs essentiels
- [ ] Migration de base de données
- [ ] Relations avec les autres entités (fournisseurs, catégories)
- [ ] Validation des données
- [ ] Tests unitaires
- [ ] Documentation du modèle

## Spécifications techniques

- **Modèle** : `App\Models\Commerce\Produit`
- **Migration** : `create_produits_table`
- **Champs principaux** :
  - Référence, nom, description
  - Type (matériau/service)
  - Unité de mesure
  - Prix d'achat/vente
  - TVA applicable
  - Stock minimum/actuel
  - Fournisseur principal
  - Catégorie

## Estimation d'effort
🕓 2-3 jours
"@
        labels = @("module:produits-services", "priorite:haute", "type:feature")
        milestone = "🎯 Q3 2025 - Modules Prioritaires"
    },
    @{
        title = "[PRODUITS] Interface de gestion du catalogue produits"
        body = @"
## Description détaillée

Développer l'interface utilisateur pour la gestion complète du catalogue de produits et services.

### Contexte métier
- Les utilisateurs doivent pouvoir créer, modifier et consulter les produits
- Interface intuitive pour la saisie rapide
- Recherche et filtrage efficaces
- Import/export pour la gestion en masse

### Solution proposée
Interface Livewire complète avec CRUD et fonctionnalités avancées

## Critères d'acceptation

- [ ] Page de liste des produits avec recherche/filtres
- [ ] Formulaire de création/édition
- [ ] Gestion des catégories
- [ ] Import CSV/Excel
- [ ] Export des données
- [ ] Interface responsive
- [ ] Tests fonctionnels

## Spécifications techniques

- **Composants Livewire** :
  - `ProduitsList`
  - `ProduitsForm`
  - `ProduitsImport`
- **Vues** : Blade templates avec TailwindCSS
- **Validation** : Form requests
- **Export** : Laravel Excel

## Estimation d'effort
🕔 1 semaine
"@
        labels = @("module:produits-services", "priorite:haute", "type:feature", "transversal:ux")
        milestone = "🎯 Q3 2025 - Modules Prioritaires"
    },
    @{
        title = "[PRODUITS] Gestion des catégories et sous-catégories"
        body = @"
## Description détaillée

Système de catégorisation hiérarchique pour organiser les produits et services selon les métiers du BTP.

### Contexte métier
- Organisation par corps de métier (maçonnerie, électricité, plomberie, etc.)
- Sous-catégories pour affiner la classification
- Faciliter la recherche et la navigation
- Statistiques par catégorie

### Solution proposée
Modèle de catégories avec structure hiérarchique

## Critères d'acceptation

- [ ] Modèle Categorie avec parent/enfants
- [ ] Interface de gestion des catégories
- [ ] Arbre hiérarchique dans l'interface
- [ ] Association produits/catégories
- [ ] Migration des données existantes
- [ ] Tests unitaires et fonctionnels

## Spécifications techniques

- **Modèle** : `App\Models\Commerce\Categorie`
- **Relations** : Self-referencing (parent_id)
- **Package** : Nested Set ou Adjacency List
- **Interface** : Tree view avec drag & drop

## Estimation d'effort
🕓 2-3 jours
"@
        labels = @("module:produits-services", "priorite:haute", "type:feature")
        milestone = "🎯 Q3 2025 - Modules Prioritaires"
    },
    @{
        title = "[PRODUITS] Gestion des prix et tarifs"
        body = @"
## Description détaillée

Système de gestion des prix complexe adapté aux spécificités du BTP (prix dégressifs, tarifs clients, etc.).

### Contexte métier
- Prix d'achat et de vente variables
- Tarifs spéciaux par client/fournisseur
- Prix dégressifs selon quantités
- Gestion des remises
- Historique des prix

### Solution proposée
Modèle de tarification flexible avec historique

## Critères d'acceptation

- [ ] Modèle Prix avec versioning
- [ ] Tarifs par client/fournisseur
- [ ] Grilles de prix dégressifs
- [ ] Gestion des remises
- [ ] Interface de saisie des prix
- [ ] Historique et traçabilité
- [ ] Tests de calculs

## Spécifications techniques

- **Modèles** :
  - `ProduitPrix`
  - `TarifClient`
  - `GrillePrix`
- **Calculs** : Service de pricing
- **Validation** : Règles métier complexes

## Estimation d'effort
🕔 1 semaine
"@
        labels = @("module:produits-services", "priorite:haute", "type:feature")
        milestone = "🎯 Q3 2025 - Modules Prioritaires"
    },
    @{
        title = "[PRODUITS] Gestion des stocks et approvisionnement"
        body = @"
## Description détaillée

Module de gestion des stocks pour les matériaux avec alertes et suivi des mouvements.

### Contexte métier
- Suivi des stocks en temps réel
- Alertes de stock minimum
- Mouvements d'entrée/sortie
- Inventaires périodiques
- Valorisation des stocks

### Solution proposée
Système de gestion des stocks intégré

## Critères d'acceptation

- [ ] Modèle Stock avec mouvements
- [ ] Alertes de stock minimum
- [ ] Interface de gestion des stocks
- [ ] Mouvements d'entrée/sortie
- [ ] Inventaires
- [ ] Rapports de valorisation
- [ ] Tests de cohérence

## Spécifications techniques

- **Modèles** :
  - `Stock`
  - `MouvementStock`
  - `Inventaire`
- **Jobs** : Alertes automatiques
- **Rapports** : Valorisation FIFO/LIFO

## Estimation d'effort
🕔 1 semaine
"@
        labels = @("module:produits-services", "priorite:moyenne", "type:feature")
        milestone = "🎯 Q3 2025 - Modules Prioritaires"
    },
    @{
        title = "[PRODUITS] API REST pour intégration externe"
        body = @"
## Description détaillée

API REST pour permettre l'intégration avec des systèmes externes (fournisseurs, e-commerce, etc.).

### Contexte métier
- Synchronisation avec catalogues fournisseurs
- Intégration e-commerce
- Échange de données avec partenaires
- Automatisation des approvisionnements

### Solution proposée
API REST complète avec authentification

## Critères d'acceptation

- [ ] Endpoints CRUD pour produits
- [ ] Authentification API (Sanctum)
- [ ] Documentation OpenAPI
- [ ] Rate limiting
- [ ] Validation des données
- [ ] Tests API
- [ ] Versioning

## Spécifications techniques

- **Routes** : `/api/v1/produits`
- **Auth** : Laravel Sanctum
- **Documentation** : Swagger/OpenAPI
- **Tests** : Feature tests API
- **Validation** : API Resources

## Estimation d'effort
🕓 2-3 jours
"@
        labels = @("module:produits-services", "priorite:moyenne", "type:feature", "transversal:infra")
        milestone = "🎯 Q3 2025 - Modules Prioritaires"
    },
    @{
        title = "[PRODUITS] Tests et documentation complète"
        body = @"
## Description détaillée

Finalisation du module avec tests complets et documentation utilisateur/développeur.

### Contexte métier
- Assurer la qualité du module
- Documentation pour les utilisateurs
- Guide d'intégration pour les développeurs
- Tests de non-régression

### Solution proposée
Suite de tests complète et documentation

## Critères d'acceptation

- [ ] Tests unitaires (>90% coverage)
- [ ] Tests fonctionnels complets
- [ ] Tests d'intégration
- [ ] Documentation utilisateur
- [ ] Documentation technique
- [ ] Guide d'installation
- [ ] Exemples d'utilisation

## Spécifications techniques

- **Tests** : PHPUnit + Pest
- **Coverage** : PHPUnit coverage
- **Documentation** : LaRecipe
- **Exemples** : Seeders et factories

## Estimation d'effort
🕓 2-3 jours
"@
        labels = @("module:produits-services", "priorite:moyenne", "type:documentation", "type:tests")
        milestone = "🎯 Q3 2025 - Modules Prioritaires"
    }
)

$created = 0
$errors = 0

foreach ($issue in $issues) {
    Write-Host "📝 Création de l'issue : $($issue.title)" -ForegroundColor Yellow

    # Construire la commande avec les labels
    $labelArgs = @()
    foreach ($label in $issue.labels) {
        $labelArgs += "--label"
        $labelArgs += $label
    }

    # Ajouter le milestone si spécifié
    $milestoneArgs = @()
    if ($issue.milestone) {
        $milestoneArgs += "--milestone"
        $milestoneArgs += $issue.milestone
    }

    # Créer l'issue
    $result = & gh issue create --title $issue.title --body $issue.body @labelArgs @milestoneArgs 2>&1

    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Issue '$($issue.title)' créée avec succès" -ForegroundColor Green
        $created++
    } else {
        Write-Host "❌ Erreur lors de la création de l'issue '$($issue.title)'" -ForegroundColor Red
        Write-Host "   $result" -ForegroundColor Red
        $errors++
    }

    Start-Sleep -Milliseconds 500  # Éviter le rate limiting
}

Write-Host "`n📊 Résumé de la création des issues :" -ForegroundColor Cyan
Write-Host "✅ Issues créées : $created" -ForegroundColor Green
Write-Host "❌ Erreurs : $errors" -ForegroundColor Red

if ($created -gt 0) {
    Write-Host "`n🎉 Issues du Module Produits/Services créées avec succès !" -ForegroundColor Green
    Write-Host ""
    Write-Host "📝 Prochaines étapes recommandées :" -ForegroundColor Cyan
    Write-Host "1. Assigner les issues aux développeurs" -ForegroundColor White
    Write-Host "2. Prioriser l'ordre de développement" -ForegroundColor White
    Write-Host "3. Créer les branches de développement" -ForegroundColor White
    Write-Host "4. Commencer par le modèle de base" -ForegroundColor White
}
