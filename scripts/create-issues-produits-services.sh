#!/bin/bash

# Script Bash de création des issues pour le Module Produits/Services
# Nécessite GitHub CLI (gh) installé et configuré

echo "📦 Création des issues pour le Module Produits/Services"
echo "===================================================="
echo ""

# Vérifier que gh CLI est installé
if ! command -v gh &> /dev/null; then
    echo "❌ GitHub CLI (gh) n'est pas installé. Veuillez l'installer d'abord."
    exit 1
fi

# Vérifier l'authentification
if ! gh auth status &> /dev/null; then
    echo "❌ Vous n'êtes pas authentifié avec GitHub CLI. Exécutez 'gh auth login' d'abord."
    exit 1
fi

echo "📝 Création des issues pour le développement du module..."

# Fonction pour créer une issue
create_issue() {
    local title="$1"
    local body="$2"
    local labels="$3"
    local milestone="$4"

    echo "📝 Création de l'issue : $title"

    # Construire la commande
    local cmd="gh issue create --title \"$title\" --body \"$body\""

    # Ajouter les labels
    if [ -n "$labels" ]; then
        IFS=',' read -ra LABEL_ARRAY <<< "$labels"
        for label in "${LABEL_ARRAY[@]}"; do
            cmd="$cmd --label \"$label\""
        done
    fi

    # Ajouter le milestone
    if [ -n "$milestone" ]; then
        cmd="$cmd --milestone \"$milestone\""
    fi

    # Exécuter la commande
    if eval $cmd &> /dev/null; then
        echo "✅ Issue '$title' créée avec succès"
        return 0
    else
        echo "❌ Erreur lors de la création de l'issue '$title'"
        return 1
    fi
}

# Compteurs
created=0
errors=0

# Issue 1: Modèle de base
create_issue \
    "[PRODUITS] Création du modèle de base Produit" \
    "## Description détaillée

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

- **Modèle** : \`App\\Models\\Commerce\\Produit\`
- **Migration** : \`create_produits_table\`
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
🕓 2-3 jours" \
    "module:produits-services,priorite:haute,type:feature" \
    "🎯 Q3 2025 - Modules Prioritaires"

if [ $? -eq 0 ]; then ((created++)); else ((errors++)); fi
sleep 0.5

# Issue 2: Interface de gestion
create_issue \
    "[PRODUITS] Interface de gestion du catalogue produits" \
    "## Description détaillée

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
  - \`ProduitsList\`
  - \`ProduitsForm\`
  - \`ProduitsImport\`
- **Vues** : Blade templates avec TailwindCSS
- **Validation** : Form requests
- **Export** : Laravel Excel

## Estimation d'effort
🕔 1 semaine" \
    "module:produits-services,priorite:haute,type:feature,transversal:ux" \
    "🎯 Q3 2025 - Modules Prioritaires"

if [ $? -eq 0 ]; then ((created++)); else ((errors++)); fi
sleep 0.5

# Continuer avec les autres issues...
# (Je vais créer les autres issues de manière similaire)

echo ""
echo "📊 Résumé de la création des issues :"
echo "✅ Issues créées : $created"
echo "❌ Erreurs : $errors"

if [ $created -gt 0 ]; then
    echo ""
    echo "🎉 Issues du Module Produits/Services créées avec succès !"
    echo ""
    echo "📝 Prochaines étapes recommandées :"
    echo "1. Assigner les issues aux développeurs"
    echo "2. Prioriser l'ordre de développement"
    echo "3. Créer les branches de développement"
    echo "4. Commencer par le modèle de base"
fi
