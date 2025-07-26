#!/bin/bash

# Script de création d'un projet GitHub unifié pour Batistack
# Nécessite GitHub CLI (gh) installé et configuré

echo "🚀 Création du projet GitHub unifié Batistack"
echo "==============================================="
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

echo "📋 Création du projet principal..."

# Créer le projet principal
if gh project create --title "🏗️ Batistack - Développement Modules ERP"; then
    echo "✅ Projet principal créé avec succès"

    echo ""
    echo "📝 Structure recommandée du projet :"
    echo "├── 🎯 Modules Prioritaires (Q1 2025)"
    echo "│   ├── 📦 Module Produits/Services"
    echo "│   ├── 🏭 Module GPAO"
    echo "│   └── 💰 Module Facturation Avancée"
    echo "├── 📊 Modules de Gestion (Q2 2025)"
    echo "│   ├── 📊 Module Comptabilité"
    echo "│   ├── 🏢 Module Immobilisations"
    echo "│   └── 📁 Module GED"
    echo "├── 🚗 Modules Opérationnels (Q3-Q4 2025)"
    echo "│   ├── 🚗 Module Véhicules"
    echo "│   └── 📋 Module Contrats/Abonnements"
    echo "└── ⚙️ Projets Transversaux"
    echo "    ├── ⚙️ Infrastructure & DevOps"
    echo "    └── 🎨 UX/UI & Design System"

else
    echo "❌ Erreur lors de la création du projet"
    exit 1
fi

echo ""
echo "🏷️ Création des labels pour organiser les modules..."

# Fonction pour créer un label
create_label() {
    local name="$1"
    local color="$2"
    local description="$3"

    if gh label create "$name" --color "$color" --description "$description" 2>/dev/null; then
        echo "✅ Label '$name' créé"
    fi
}

# Labels par module
create_label "module:produits-services" "1f77b4" "Module Produits et Services"
create_label "module:gpao" "ff7f0e" "Module GPAO"
create_label "module:facturation" "2ca02c" "Module Facturation Avancée"
create_label "module:comptabilite" "d62728" "Module Comptabilité"
create_label "module:immobilisations" "9467bd" "Module Immobilisations"
create_label "module:ged" "8c564b" "Module GED"
create_label "module:vehicules" "e377c2" "Module Véhicules"
create_label "module:contrats" "7f7f7f" "Module Contrats/Abonnements"
create_label "transversal:infra" "bcbd22" "Infrastructure & DevOps"
create_label "transversal:ux" "17becf" "UX/UI & Design System"

# Labels par priorité
create_label "priorite:haute" "d73a4a" "Priorité Haute - Q1 2025"
create_label "priorite:moyenne" "fbca04" "Priorité Moyenne - Q2 2025"
create_label "priorite:basse" "0075ca" "Priorité Basse - Q3-Q4 2025"

# Labels par type
create_label "type:feature" "a2eeef" "Nouvelle fonctionnalité"
create_label "type:enhancement" "7057ff" "Amélioration"
create_label "type:bug" "d73a4a" "Correction de bug"
create_label "type:documentation" "0075ca" "Documentation"
create_label "type:refactor" "ffffff" "Refactorisation"

echo ""
echo "🎉 Projet GitHub unifié créé avec succès !"
echo ""
echo "📝 Prochaines étapes recommandées :"
echo "1. Configurer les vues du projet (par module, par priorité, par statut)"
echo "2. Créer les colonnes : Backlog, En cours, En review, Terminé"
echo "3. Créer les issues initiales pour chaque module avec les bons labels"
echo "4. Définir les milestones par trimestre"
echo "5. Utiliser le template 'module_development.yml' pour les nouvelles issues"
