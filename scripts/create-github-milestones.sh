#!/bin/bash

# Script Bash de création des jalons GitHub pour Batistack
# Nécessite GitHub CLI (gh) installé et configuré

echo "🎯 Création des jalons GitHub pour Batistack"
echo "============================================"
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

echo "📅 Création des jalons basés sur la roadmap..."

# Fonction pour créer un jalon
create_milestone() {
    local title="$1"
    local description="$2"
    local due_date="$3"

    echo "📌 Création du jalon : $title"

    if gh api repos/:owner/:repo/milestones -X POST -f title="$title" -f description="$description" -f due_on="${due_date}T23:59:59Z" &> /dev/null; then
        echo "✅ Jalon '$title' créé avec succès"
        return 0
    else
        echo "❌ Erreur lors de la création du jalon '$title'"
        return 1
    fi
}

# Compteurs
created=0
errors=0

# Création des jalons avec dates ajustées (nous sommes le 26/07/2025)
create_milestone "🎯 Q3 2025 - Modules Prioritaires" \
    "Développement des modules prioritaires : Produits/Services, GPAO, Facturation Avancée" \
    "2025-09-30"
if [ $? -eq 0 ]; then ((created++)); else ((errors++)); fi

create_milestone "📊 Q4 2025 - Modules de Gestion" \
    "Développement des modules de gestion : Comptabilité, Immobilisations, GED" \
    "2025-12-31"
if [ $? -eq 0 ]; then ((created++)); else ((errors++)); fi

create_milestone "🚗 Q1 2026 - Modules Opérationnels" \
    "Développement des modules opérationnels : Véhicules, Contrats/Abonnements" \
    "2026-03-31"
if [ $? -eq 0 ]; then ((created++)); else ((errors++)); fi

create_milestone "🎉 Q2 2026 - Finalisation & Optimisation" \
    "Finalisation, tests d'intégration, optimisations et préparation du déploiement" \
    "2026-06-30"
if [ $? -eq 0 ]; then ((created++)); else ((errors++)); fi

create_milestone "🚀 2026-2027 - Vision Long Terme" \
    "Évolutions futures, nouvelles fonctionnalités et expansion" \
    "2027-12-31"
if [ $? -eq 0 ]; then ((created++)); else ((errors++)); fi

create_milestone "⚙️ Infrastructure & DevOps" \
    "Amélioration continue de l'infrastructure, CI/CD, monitoring et sécurité" \
    "2026-12-31"
if [ $? -eq 0 ]; then ((created++)); else ((errors++)); fi

create_milestone "🎨 UX/UI & Design System" \
    "Développement du design system et amélioration de l'expérience utilisateur" \
    "2026-06-30"
if [ $? -eq 0 ]; then ((created++)); else ((errors++)); fi

echo ""
echo "📊 Résumé de la création des jalons :"
echo "✅ Jalons créés : $created"
echo "❌ Erreurs : $errors"

if [ $created -gt 0 ]; then
    echo ""
    echo "🎉 Jalons créés avec succès !"
    echo ""
    echo "📝 Prochaines étapes recommandées :"
    echo "1. Associer les issues existantes aux jalons appropriés"
    echo "2. Créer des issues pour chaque module avec les bons jalons"
    echo "3. Utiliser les jalons pour suivre l'avancement par trimestre"
    echo "4. Réviser régulièrement les dates d'échéance selon l'avancement"
fi
