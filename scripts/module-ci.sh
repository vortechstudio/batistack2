#!/bin/bash

# 🏗️ Module CI/CD - Script Local
# Reproduit le workflow GitHub Actions module-ci.yml localement

set -e

# Configuration
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"
REPORTS_DIR="$PROJECT_ROOT/reports/module-ci"
TIMESTAMP=$(date +%Y%m%d-%H%M%S)

# Couleurs pour l'affichage
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Fonctions utilitaires
log_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

log_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

log_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

log_error() {
    echo -e "${RED}❌ $1${NC}"
}

log_step() {
    echo -e "${PURPLE}🔄 $1${NC}"
}

# Fonction d'aide
show_help() {
    cat << EOF
🏗️ Module CI/CD - Script Local

USAGE:
    $0 [OPTIONS] [MODULES...]

OPTIONS:
    -h, --help              Afficher cette aide
    -a, --all               Exécuter tous les modules
    -t, --tests-only        Exécuter uniquement les tests
    -s, --security-only     Exécuter uniquement l'analyse de sécurité
    -p, --performance-only  Exécuter uniquement les tests de performance
    -q, --quality-only      Exécuter uniquement l'analyse de qualité
    -d, --detect-only       Détecter uniquement les modules modifiés
    --skip-deps            Ignorer l'installation des dépendances
    --php-version VERSION   Version PHP à utiliser (défaut: 8.3)

MODULES:
    chantiers, rh, tiers, commerce, core, produit

EXEMPLES:
    $0 --all                    # Tous les modules et toutes les vérifications
    $0 chantiers rh             # Modules spécifiques
    $0 --detect-only            # Détecter les modules modifiés
    $0 --tests-only chantiers   # Tests uniquement pour le module chantiers
    $0 --quality-only           # Analyse de qualité uniquement

EOF
}

# Variables par défaut
MODULES=()
RUN_ALL=false
TESTS_ONLY=false
SECURITY_ONLY=false
PERFORMANCE_ONLY=false
QUALITY_ONLY=false
DETECT_ONLY=false
SKIP_DEPS=false
PHP_VERSION="8.3"

# Analyse des arguments
while [[ $# -gt 0 ]]; do
    case $1 in
        -h|--help)
            show_help
            exit 0
            ;;
        -a|--all)
            RUN_ALL=true
            shift
            ;;
        -t|--tests-only)
            TESTS_ONLY=true
            shift
            ;;
        -s|--security-only)
            SECURITY_ONLY=true
            shift
            ;;
        -p|--performance-only)
            PERFORMANCE_ONLY=true
            shift
            ;;
        -q|--quality-only)
            QUALITY_ONLY=true
            shift
            ;;
        -d|--detect-only)
            DETECT_ONLY=true
            shift
            ;;
        --skip-deps)
            SKIP_DEPS=true
            shift
            ;;
        --php-version)
            PHP_VERSION="$2"
            shift 2
            ;;
        chantiers|rh|tiers|commerce|core|produit)
            MODULES+=("$1")
            shift
            ;;
        *)
            log_error "Option inconnue: $1"
            show_help
            exit 1
            ;;
    esac
done

# Vérifications préliminaires
check_requirements() {
    log_step "Vérification des prérequis..."

    # Vérifier PHP
    if ! command -v php &> /dev/null; then
        log_error "PHP n'est pas installé"
        exit 1
    fi

    local current_php_version=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
    log_info "Version PHP détectée: $current_php_version"

    # Vérifier Composer
    if ! command -v composer &> /dev/null; then
        log_error "Composer n'est pas installé"
        exit 1
    fi

    # Vérifier que nous sommes dans le bon répertoire
    if [[ ! -f "$PROJECT_ROOT/composer.json" ]]; then
        log_error "Fichier composer.json non trouvé. Êtes-vous dans le bon répertoire ?"
        exit 1
    fi

    # Créer le répertoire de rapports
    mkdir -p "$REPORTS_DIR"

    log_success "Prérequis vérifiés"
}

# Détection des modules modifiés
detect_changed_modules() {
    log_step "🔍 Détection des modules modifiés..."

    local detected_modules=()

    # Vérifier s'il y a des changements Git
    if git rev-parse --git-dir > /dev/null 2>&1; then
        log_info "Détection basée sur Git..."

        # Obtenir les fichiers modifiés (non commitées + derniers commits)
        local changed_files
        changed_files=$(git diff --name-only HEAD~1 2>/dev/null || git ls-files --modified --others --exclude-standard)

        if [[ -n "$changed_files" ]]; then
            # Chantiers
            if echo "$changed_files" | grep -E "(app/Models/Chantiers/|app/Livewire/Chantier|app/Livewire/Chantiers/|database/migrations/.*chantier|tests/.*Chantier)" > /dev/null; then
                detected_modules+=("chantiers")
                log_success "Module Chantiers détecté"
            fi

            # RH
            if echo "$changed_files" | grep -E "(app/Models/RH/|app/Livewire/Humans/|database/migrations/.*employe|database/migrations/.*rh|tests/.*RH|tests/.*Employe)" > /dev/null; then
                detected_modules+=("rh")
                log_success "Module RH détecté"
            fi

            # Tiers
            if echo "$changed_files" | grep -E "(app/Models/Tiers/|app/Livewire/Tiers/|database/migrations/.*tiers|tests/.*Tiers)" > /dev/null; then
                detected_modules+=("tiers")
                log_success "Module Tiers détecté"
            fi

            # Commerce
            if echo "$changed_files" | grep -E "(app/Models/Commerce/|database/migrations/.*devis|database/migrations/.*facture|database/migrations/.*commande|tests/.*Commerce)" > /dev/null; then
                detected_modules+=("commerce")
                log_success "Module Commerce détecté"
            fi

            # Core
            if echo "$changed_files" | grep -E "(app/Models/Core/|app/Livewire/Core/|database/migrations/.*core|tests/.*Core)" > /dev/null; then
                detected_modules+=("core")
                log_success "Module Core détecté"
            fi

            if echo "$changed_files" | grep -E "(app/Models/Produit/|app/Livewire/Produit/|database/migrations/.*produit|tests/.*Produit)" > /dev/null; then
                detected_modules+=("produit")
                log_success "Module Produit détecté"
            fi
        else
            log_info "Aucun changement détecté"
        fi
    else
        log_warning "Pas de dépôt Git détecté, vérification de tous les modules"
        detected_modules=("chantiers" "rh" "tiers" "commerce" "core")
    fi

    if [[ ${#detected_modules[@]} -eq 0 ]]; then
        log_info "Aucun module détecté"
        return 1
    fi

    log_info "Modules détectés: ${detected_modules[*]}"

    # Sauvegarder la liste des modules détectés
    printf '%s\n' "${detected_modules[@]}" > "$REPORTS_DIR/detected-modules.txt"

    # Si DETECT_ONLY est activé, on s'arrête ici
    if [[ "$DETECT_ONLY" == true ]]; then
        log_success "Détection terminée"
        exit 0
    fi

    # Utiliser les modules détectés si aucun module spécifique n'a été fourni
    if [[ ${#MODULES[@]} -eq 0 ]]; then
        MODULES=("${detected_modules[@]}")
    fi

    return 0
}

# Installation des dépendances
install_dependencies() {
    if [[ "$SKIP_DEPS" == true ]]; then
        log_info "Installation des dépendances ignorée"
        return 0
    fi

    log_step "📦 Installation des dépendances..."
}

# Configuration de l'environnement de test
setup_test_environment() {
    log_step "🔧 Configuration de l'environnement de test..."

    # Copier le fichier .env si nécessaire
    if [[ ! -f "$PROJECT_ROOT/.env" ]]; then
        if [[ -f "$PROJECT_ROOT/.env.github" ]]; then
            cp "$PROJECT_ROOT/.env.github" "$PROJECT_ROOT/.env"
            log_info "Fichier .env.github copié vers .env"
        elif [[ -f "$PROJECT_ROOT/.env.example" ]]; then
            cp "$PROJECT_ROOT/.env.example" "$PROJECT_ROOT/.env"
            log_info "Fichier .env.example copié vers .env"
        else
            log_warning "Aucun fichier .env de référence trouvé"
        fi
    fi

    # Générer la clé d'application
    if ! grep -q "APP_KEY=" "$PROJECT_ROOT/.env" || grep -q "APP_KEY=$" "$PROJECT_ROOT/.env"; then
        log_info "Génération de la clé d'application..."
        php artisan key:generate --quiet
        log_success "Clé d'application générée"
    fi

    log_success "Environnement de test configuré"
}

# Tests spécifiques par module
run_module_tests() {
    local module="$1"
    log_step "🧪 Tests du module: $module"

    local test_results="$REPORTS_DIR/tests-$module-$TIMESTAMP.txt"
    local test_passed=true

    {
        echo "=== Tests du module $module ==="
        echo "Date: $(date)"
        echo "PHP Version: $(php -v | head -n1)"
        echo ""
    } > "$test_results"

    # Tests unitaires du module
    if [[ -d "$PROJECT_ROOT/tests/Unit" ]]; then
        log_info "Exécution des tests unitaires pour $module..."
        if php artisan test "tests/Unit" --filter="$module" --coverage-text >> "$test_results" 2>&1; then
            log_success "Tests unitaires réussis pour $module"
        else
            log_warning "Échec ou absence de tests unitaires pour $module"
            test_passed=false
        fi
    fi

    # Tests fonctionnels du module
    if [[ -d "$PROJECT_ROOT/tests/Feature" ]]; then
        log_info "Exécution des tests fonctionnels pour $module..."
        if php artisan test "tests/Feature" --filter="$module" --coverage-text >> "$test_results" 2>&1; then
            log_success "Tests fonctionnels réussis pour $module"
        else
            log_warning "Échec ou absence de tests fonctionnels pour $module"
            test_passed=false
        fi
    fi

    # Tests génériques si pas de tests spécifiques
    log_info "Recherche de tests génériques pour $module..."
    if php artisan test --filter="$module" --coverage-text >> "$test_results" 2>&1; then
        log_success "Tests génériques réussis pour $module"
    else
        log_warning "Aucun test spécifique trouvé pour $module"
        echo "⚠️ Aucun test spécifique trouvé pour $module" >> "$test_results"
    fi

    # Vérification de la documentation du module
    local module_doc="$PROJECT_ROOT/resources/docs/1.0/$module.md"
    if [[ ! -f "$module_doc" ]]; then
        log_warning "Documentation manquante pour le module $module"
        echo "📝 Créez le fichier: $module_doc" >> "$test_results"
    else
        log_success "Documentation trouvée pour $module"
    fi

    if [[ "$test_passed" == true ]]; then
        log_success "Tous les tests réussis pour le module $module"
        return 0
    else
        log_error "Échec de certains tests pour le module $module"
        return 1
    fi
}

# Analyse de sécurité
run_security_scan() {
    log_step "🔒 Analyse de sécurité..."

    local security_report="$REPORTS_DIR/security-$TIMESTAMP.txt"
    local security_passed=true

    {
        echo "=== Rapport de Sécurité ==="
        echo "Date: $(date)"
        echo ""
    } > "$security_report"

    # Audit des dépendances Composer
    log_info "Audit des dépendances Composer..."
    if composer audit >> "$security_report" 2>&1; then
        log_success "Aucune vulnérabilité détectée dans les dépendances"
    else
        log_warning "Vulnérabilités détectées dans les dépendances"
        security_passed=false
    fi

    # Vérification des fichiers sensibles
    log_info "Vérification des fichiers sensibles..."
    echo "" >> "$security_report"
    echo "=== Vérification des mots-clés sensibles ===" >> "$security_report"

    if grep -r "password\|secret\|key" app/ --include="*.php" | grep -v "// " | grep -v "/*" | head -5 >> "$security_report" 2>/dev/null; then
        log_warning "Mots-clés sensibles détectés - Vérifiez qu'aucun secret n'est en dur"
        security_passed=false
    else
        log_success "Aucun mot-clé sensible détecté"
        echo "✅ Aucun mot-clé sensible détecté" >> "$security_report"
    fi

    if [[ "$security_passed" == true ]]; then
        log_success "Analyse de sécurité réussie"
        return 0
    else
        log_error "Problèmes de sécurité détectés"
        return 1
    fi
}

# Vérification des performances
run_performance_check() {
    log_step "⚡ Vérification des performances..."

    local perf_report="$REPORTS_DIR/performance-$TIMESTAMP.txt"
    local perf_passed=true

    {
        echo "=== Rapport de Performance ==="
        echo "Date: $(date)"
        echo ""
    } > "$perf_report"

    # Tests de performance si ils existent
    if [[ -d "$PROJECT_ROOT/tests/Performance" ]]; then
        log_info "Exécution des tests de performance..."

        # Vérifier que les factories sont correctes avant les tests
        log_info "Vérification des factories..."
        echo "=== Vérification des factories ===" >> "$perf_report"

        php artisan tinker --execute="
            try {
                \App\Models\RH\Employe::factory()->make();
                echo 'EmployeFactory: OK' . PHP_EOL;
            } catch (Exception \$e) {
                echo 'EmployeFactory: ERREUR - ' . \$e->getMessage() . PHP_EOL;
            }

            try {
                \App\Models\Chantiers\Chantiers::factory()->make();
                echo 'ChantiersFactory: OK' . PHP_EOL;
            } catch (Exception \$e) {
                echo 'ChantiersFactory: ERREUR - ' . \$e->getMessage() . PHP_EOL;
            }
        " >> "$perf_report" 2>&1 || log_warning "Erreur lors de la vérification des factories"

        # Exécuter les tests de performance avec gestion d'erreur
        if php artisan test tests/Performance --coverage-text >> "$perf_report" 2>&1; then
            log_success "Tests de performance réussis"
        else
            log_error "Échec des tests de performance"
            perf_passed=false

            log_info "Vérification des patterns N+1 dans le code..."
            echo "" >> "$perf_report"
            echo "=== Analyse des patterns N+1 ===" >> "$perf_report"

            # Analyse statique des patterns N+1
            if grep -r "foreach.*->.*->" app/Models/ app/Livewire/ --include="*.php" | head -5 >> "$perf_report" 2>/dev/null; then
                log_warning "Patterns N+1 potentiels détectés"
                echo "" >> "$perf_report"
                echo "💡 Recommandations:" >> "$perf_report"
                echo "   - Utilisez with() pour l'eager loading" >> "$perf_report"
                echo "   - Considérez load() pour le lazy eager loading" >> "$perf_report"
                echo "   - Vérifiez les relations dans vos modèles" >> "$perf_report"
            else
                log_success "Aucun pattern N+1 évident détecté"
            fi
        fi
    else
        log_info "Aucun test de performance configuré"

        # Vérification basique des requêtes N+1 dans le code
        log_info "Recherche de patterns N+1 potentiels..."
        echo "=== Recherche de patterns N+1 ===" >> "$perf_report"

        if grep -r "foreach.*->.*->" app/Models/ app/Livewire/ --include="*.php" | head -3 >> "$perf_report" 2>/dev/null; then
            log_warning "Patterns N+1 potentiels détectés - Vérifiez l'utilisation d'eager loading"
        else
            log_success "Aucun pattern N+1 évident détecté"
            echo "✅ Aucun pattern N+1 évident détecté" >> "$perf_report"
        fi
    fi

    if [[ "$perf_passed" == true ]]; then
        log_success "Vérification des performances réussie"
        return 0
    else
        log_warning "Problèmes de performance détectés (non bloquant)"
        return 0  # Ne pas faire échouer pour les performances
    fi
}

# Analyse de la qualité du code
run_code_quality() {
    log_step "📊 Analyse de la qualité du code..."

    local quality_report="$REPORTS_DIR/code-quality-$TIMESTAMP.md"
    local pint_status="success"
    local phpstan_status="success"

    # Créer le répertoire de rapports de qualité
    mkdir -p "$REPORTS_DIR/code-quality"

    {
        echo "# 📊 Rapport de Qualité du Code"
        echo ""
        echo "**Date:** $(date '+%Y-%m-%d %H:%M:%S')"
        echo "**Modules:** ${MODULES[*]}"
        echo ""
    } > "$quality_report"

    # Laravel Pint
    log_info "Exécution de Laravel Pint..."
    echo "## 🎨 Laravel Pint (Code Style)" >> "$quality_report"

    if command -v ./vendor/bin/pint &> /dev/null; then
        log_success "Laravel Pint détecté"

        # Exécuter Pint avec capture de sortie
        if ./vendor/bin/pint --test --format=json > "$REPORTS_DIR/code-quality/pint-report.json" 2>&1; then
            log_success "Laravel Pint: Aucun problème de style détecté"
            echo "**Statut:** ✅ Succès" >> "$quality_report"
            echo "" >> "$quality_report"
            echo "```" >> "$quality_report"
            echo "✅ Code style conforme aux standards Laravel" >> "$quality_report"
            echo "```" >> "$quality_report"
        else
            log_error "Laravel Pint: Problèmes de style détectés"
            pint_status="failure"

            # Générer un rapport lisible
            ./vendor/bin/pint --test > "$REPORTS_DIR/code-quality/pint-output.txt" 2>&1 || true

            echo "**Statut:** ❌ Échec" >> "$quality_report"
            echo "" >> "$quality_report"
            echo "```" >> "$quality_report"
            head -20 "$REPORTS_DIR/code-quality/pint-output.txt" >> "$quality_report"
            echo "```" >> "$quality_report"
        fi
    else
        log_warning "Laravel Pint non configuré"
        pint_status="skipped"
        echo "**Statut:** ⚠️ Ignoré" >> "$quality_report"
        echo "" >> "$quality_report"
        echo "```" >> "$quality_report"
        echo "⚠️ Laravel Pint non configuré dans le projet" >> "$quality_report"
        echo "```" >> "$quality_report"
    fi

    echo "" >> "$quality_report"

    # PHPStan
    log_info "Exécution de PHPStan..."
    echo "## 🔍 PHPStan (Analyse Statique)" >> "$quality_report"

    if [[ -f "$PROJECT_ROOT/phpstan.neon.dist" ]] && command -v ./vendor/bin/phpstan &> /dev/null; then
        log_success "PHPStan détecté"

        # Exécuter PHPStan avec capture de sortie
        if ./vendor/bin/phpstan analyse --memory-limit=2G --error-format=json > "$REPORTS_DIR/code-quality/phpstan-report.json" 2>&1; then
            log_success "PHPStan: Aucune erreur détectée"
            echo "**Statut:** ✅ Succès" >> "$quality_report"
            echo "" >> "$quality_report"
            echo "```" >> "$quality_report"
            echo "✅ Analyse statique réussie - Aucune erreur détectée" >> "$quality_report"
            echo "```" >> "$quality_report"
        else
            log_error "PHPStan: Erreurs détectées"
            phpstan_status="failure"

            # Générer un rapport lisible
            ./vendor/bin/phpstan analyse --memory-limit=2G > "$REPORTS_DIR/code-quality/phpstan-output.txt" 2>&1 || true

            echo "**Statut:** ❌ Échec" >> "$quality_report"
            echo "" >> "$quality_report"
            echo "```" >> "$quality_report"
            head -30 "$REPORTS_DIR/code-quality/phpstan-output.txt" >> "$quality_report"
            echo "```" >> "$quality_report"
        fi
    else
        log_warning "PHPStan non configuré"
        phpstan_status="skipped"
        echo "**Statut:** ⚠️ Ignoré" >> "$quality_report"
        echo "" >> "$quality_report"
        echo "```" >> "$quality_report"
        echo "⚠️ PHPStan non configuré dans le projet" >> "$quality_report"
        echo "```" >> "$quality_report"
    fi

    # Résumé
    echo "" >> "$quality_report"
    echo "## 📋 Résumé" >> "$quality_report"
    echo "" >> "$quality_report"
    echo "| Outil | Statut | Description |" >> "$quality_report"
    echo "|-------|--------|-------------|" >> "$quality_report"
    echo "| Laravel Pint | $pint_status | Vérification du style de code |" >> "$quality_report"
    echo "| PHPStan | $phpstan_status | Analyse statique du code |" >> "$quality_report"
    echo "" >> "$quality_report"

    # Actions recommandées
    echo "## 🔧 Actions Recommandées" >> "$quality_report"
    echo "" >> "$quality_report"

    if [[ "$pint_status" == "failure" ]]; then
        echo "### 🎨 Corrections Laravel Pint" >> "$quality_report"
        echo "- Exécutez \`./vendor/bin/pint\` pour corriger automatiquement les problèmes de style" >> "$quality_report"
        echo "- Vérifiez la configuration dans \`pint.json\`" >> "$quality_report"
        echo "" >> "$quality_report"
    fi

    if [[ "$phpstan_status" == "failure" ]]; then
        echo "### 🔍 Corrections PHPStan" >> "$quality_report"
        echo "- Corrigez les erreurs d'analyse statique détectées" >> "$quality_report"
        echo "- Ajoutez les annotations de type manquantes" >> "$quality_report"
        echo "- Vérifiez la configuration dans \`phpstan.neon.dist\`" >> "$quality_report"
        echo "" >> "$quality_report"
    fi

    log_info "Rapport généré: $quality_report"

    if [[ "$pint_status" == "failure" ]] || [[ "$phpstan_status" == "failure" ]]; then
        log_error "Échec des vérifications de qualité du code"
        log_info "📊 Laravel Pint: $pint_status"
        log_info "🔍 PHPStan: $phpstan_status"
        log_info "📄 Consultez le rapport détaillé: $quality_report"
        return 1
    else
        log_success "Analyse de qualité réussie"
        return 0
    fi
}

# Fonction principale
main() {
    echo -e "${CYAN}"
    echo "🏗️ =============================================="
    echo "   Module CI/CD - Exécution Locale"
    echo "   Timestamp: $TIMESTAMP"
    echo "==============================================="
    echo -e "${NC}"

    cd "$PROJECT_ROOT"

    # Vérifications préliminaires
    check_requirements

    # Si RUN_ALL est activé, définir tous les modules
    if [[ "$RUN_ALL" == true ]]; then
        MODULES=("chantiers" "rh" "tiers" "commerce" "core")
        log_info "Mode --all activé: tous les modules seront traités"
    fi

    # Détection des modules modifiés (sauf si des modules spécifiques sont fournis)
    if [[ ${#MODULES[@]} -eq 0 ]] || [[ "$DETECT_ONLY" == true ]]; then
        if ! detect_changed_modules; then
            log_info "Aucun module à traiter"
            exit 0
        fi
    fi

    # Installation des dépendances
    install_dependencies

    # Variables de suivi des résultats
    local overall_success=true
    local tests_success=true
    local security_success=true
    local performance_success=true
    local quality_success=true

    # Exécution des tests par module
    if [[ "$SECURITY_ONLY" != true ]] && [[ "$PERFORMANCE_ONLY" != true ]] && [[ "$QUALITY_ONLY" != true ]]; then
        log_step "🧪 Exécution des tests par module..."
        for module in "${MODULES[@]}"; do
            if ! run_module_tests "$module"; then
                tests_success=false
                overall_success=false
            fi
        done

        if [[ "$tests_success" == true ]]; then
            log_success "Tous les tests de modules réussis"
        else
            log_error "Échec de certains tests de modules"
        fi
    fi

    # Analyse de sécurité
    if [[ "$TESTS_ONLY" != true ]] && [[ "$PERFORMANCE_ONLY" != true ]] && [[ "$QUALITY_ONLY" != true ]]; then
        if ! run_security_scan; then
            security_success=false
            overall_success=false
        fi
    fi

    # Vérification des performances
    if [[ "$TESTS_ONLY" != true ]] && [[ "$SECURITY_ONLY" != true ]] && [[ "$QUALITY_ONLY" != true ]]; then
        if ! run_performance_check; then
            performance_success=false
            # Note: les performances ne font pas échouer le build global
        fi
    fi

    # Analyse de la qualité du code
    if [[ "$TESTS_ONLY" != true ]] && [[ "$SECURITY_ONLY" != true ]] && [[ "$PERFORMANCE_ONLY" != true ]]; then
        if ! run_code_quality; then
            quality_success=false
            overall_success=false
        fi
    fi

    # Rapport final
    echo ""
    echo -e "${CYAN}📊 =============================================="
    echo "   RAPPORT FINAL"
    echo "===============================================${NC}"

    echo -e "🧪 Tests de modules:     $([ "$tests_success" == true ] && echo -e "${GREEN}✅ RÉUSSI${NC}" || echo -e "${RED}❌ ÉCHEC${NC}")"
    echo -e "🔒 Analyse de sécurité:  $([ "$security_success" == true ] && echo -e "${GREEN}✅ RÉUSSI${NC}" || echo -e "${RED}❌ ÉCHEC${NC}")"
    echo -e "⚡ Vérif. performances: $([ "$performance_success" == true ] && echo -e "${GREEN}✅ RÉUSSI${NC}" || echo -e "${YELLOW}⚠️ AVERTISSEMENT${NC}")"
    echo -e "📊 Qualité du code:     $([ "$quality_success" == true ] && echo -e "${GREEN}✅ RÉUSSI${NC}" || echo -e "${RED}❌ ÉCHEC${NC}")"

    echo ""
    echo -e "📁 Rapports générés dans: ${BLUE}$REPORTS_DIR${NC}"
    echo ""

    if [[ "$overall_success" == true ]]; then
        log_success "🎉 Toutes les vérifications sont réussies !"
        exit 0
    else
        log_error "❌ Certaines vérifications ont échoué"
        exit 1
    fi
}

# Exécution du script principal
main "$@"
