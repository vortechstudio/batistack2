#!/bin/bash

# Script de reproduction du workflow GitHub Actions: code-metrics.yml
# Génère des métriques de code avec PHPMetrics

set -e  # Arrêter le script en cas d'erreur

echo "🔍 Démarrage de l'analyse des métriques de code..."

# Couleurs pour l'affichage
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Fonction d'affichage avec couleurs
print_step() {
    echo -e "${BLUE}📋 $1${NC}"
}

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Vérification de PHP
print_step "Vérification de PHP 8.3..."
if ! command -v php &> /dev/null; then
    print_error "PHP n'est pas installé ou n'est pas dans le PATH"
    exit 1
fi

PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
if [[ "$PHP_VERSION" != "8.3" ]]; then
    print_warning "Version PHP détectée: $PHP_VERSION (recommandé: 8.3)"
else
    print_success "PHP 8.3 détecté"
fi

# Vérification de Composer
print_step "Vérification de Composer..."
if ! command -v composer &> /dev/null; then
    print_error "Composer n'est pas installé ou n'est pas dans le PATH"
    exit 1
fi
print_success "Composer disponible"

# Vérification de PHPMetrics
print_step "Vérification de PHPMetrics..."
if [ ! -f "vendor/bin/phpmetrics" ]; then
    print_warning "PHPMetrics non trouvé, installation..."
    composer require --dev phpmetrics/phpmetrics --quiet

    if [ $? -eq 0 ]; then
        print_success "PHPMetrics installé avec succès"
    else
        print_error "Échec de l'installation de PHPMetrics"
        exit 1
    fi
else
    print_success "PHPMetrics disponible"
fi

# Création du dossier de sortie
print_step "Préparation du dossier de métriques..."
if [ -d "metrics" ]; then
    rm -rf metrics
    print_warning "Ancien dossier metrics supprimé"
fi
mkdir -p metrics
print_success "Dossier metrics créé"

# Génération des métriques
print_step "Génération des métriques de code..."
echo "Analyse du dossier app/ en cours..."

vendor/bin/phpmetrics --report-html=metrics app/

if [ $? -eq 0 ]; then
    print_success "Métriques générées avec succès"
else
    print_error "Échec de la génération des métriques"
    exit 1
fi

# Vérification des fichiers générés
print_step "Vérification des fichiers générés..."
if [ -f "metrics/index.html" ]; then
    print_success "Rapport HTML généré: metrics/index.html"

    # Calcul de la taille du rapport
    REPORT_SIZE=$(du -sh metrics/ | cut -f1)
    echo -e "${BLUE}📊 Taille du rapport: $REPORT_SIZE${NC}"

    # Comptage des fichiers analysés
    FILE_COUNT=$(find app/ -name "*.php" | wc -l)
    echo -e "${BLUE}📁 Fichiers PHP analysés: $FILE_COUNT${NC}"

else
    print_error "Le rapport HTML n'a pas été généré"
    exit 1
fi

# Affichage des informations finales
echo ""
echo "🎉 Analyse des métriques terminée avec succès !"
echo ""
echo "📋 Résumé:"
echo "  - Rapport disponible: metrics/index.html"
echo "  - Fichiers analysés: $FILE_COUNT fichiers PHP"
echo "  - Taille du rapport: $REPORT_SIZE"
echo ""
echo "💡 Pour ouvrir le rapport:"
echo "  - Linux/Mac: open metrics/index.html"
echo "  - Windows: start metrics/index.html"
echo ""

# Optionnel: ouvrir automatiquement le rapport
read -p "Voulez-vous ouvrir le rapport maintenant ? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    if command -v xdg-open &> /dev/null; then
        xdg-open metrics/index.html
    elif command -v open &> /dev/null; then
        open metrics/index.html
    else
        echo "Impossible d'ouvrir automatiquement. Ouvrez manuellement: metrics/index.html"
    fi
fi
