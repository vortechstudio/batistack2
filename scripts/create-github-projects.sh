#!/bin/bash

# Script de création des projets GitHub pour les modules Batistack
# Nécessite GitHub CLI (gh) installé et configuré

echo "🚀 Création des projets GitHub pour Batistack"
echo "=============================================="
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

# Fonction pour créer un projet
create_project() {
    local title="$1"
    local description="$2"
    local emoji="$3"

    echo "📋 Création du projet: $emoji $title"

    # Créer le projet (syntaxe simplifiée)
    if gh project create --title "$emoji $title"; then
        echo "✅ Projet '$emoji $title' créé avec succès"
        echo "📝 Description: $description"
    else
        echo "❌ Erreur lors de la création du projet '$emoji $title'"
    fi

    echo ""
}

# Projets pour les nouveaux modules (priorité haute)
echo "🎯 Création des projets pour les modules prioritaires..."

create_project "Module Produits/Services" "Gestion du catalogue produits et services BTP avec spécifications techniques, normes et tarification dynamique" "📦"

create_project "Module GPAO" "Gestion de Production Assistée par Ordinateur - Planification et suivi de la production d'éléments préfabriqués" "🏭"

create_project "Module Facturation Avancée" "Extension du module commerce existant avec paiements SEPA, TPE virtuel et gestion des échéances" "💰"

# Projets pour les modules de gestion (priorité moyenne)
echo "📊 Création des projets pour les modules de gestion..."

create_project "Module Comptabilité" "Comptabilité en partie double avec plan comptable BTP, TVA automatique et export FEC" "📊"

create_project "Module Immobilisations" "Gestion des immobilisations avec calcul automatique des amortissements et suivi des valeurs" "🏢"

create_project "Module GED" "Gestionnaire Électronique de Documents avec dématérialisation et archivage légal" "📁"

# Projets pour les modules opérationnels (priorité basse)
echo "🚗 Création des projets pour les modules opérationnels..."

create_project "Module Véhicules" "Gestion du parc automobile avec suivi des contrôles techniques, entretiens et géolocalisation" "🚗"

create_project "Module Contrats/Abonnements" "Gestion des contrats de location matériel et abonnements récurrents avec facturation automatique" "📋"

# Projets transversaux
echo "⚙️ Création des projets transversaux..."

create_project "Infrastructure & DevOps" "Amélioration de l'infrastructure, CI/CD, monitoring et déploiement automatisé" "⚙️"

create_project "UX/UI & Design System" "Amélioration de l'expérience utilisateur et création d'un design system cohérent" "🎨"

echo "🎉 Création des projets GitHub terminée !"
echo ""
echo "📝 Prochaines étapes recommandées :"
echo "1. Configurer les vues et colonnes de chaque projet"
echo "2. Créer les issues initiales pour chaque module"
echo "3. Définir les milestones et roadmap détaillée"
echo "4. Assigner les projets aux équipes"
