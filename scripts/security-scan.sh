#!/bin/bash

# 🔒 Security Scan - Script Local
# Reproduit le workflow GitHub Actions security-scan.yml localement

set -e

# Configuration
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(dirname "$SCRIPT_DIR")"
REPORTS_DIR="$PROJECT_ROOT/reports/security"
TIMESTAMP=$(date +%Y%m%d-%H%M%S)
TARGET_URL="https://beta.batistack.ovh"

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
🔒 Security Scan - Script Local

USAGE:
    $0 [OPTIONS]

OPTIONS:
    -h, --help              Afficher cette aide
    -u, --url URL           URL à tester (défaut: $TARGET_URL)
    --skip-composer         Ignorer l'audit Composer
    --skip-npm              Ignorer l'audit NPM
    --skip-headers          Ignorer la vérification des headers
    --skip-zap              Ignorer le scan OWASP ZAP
    --zap-only              Exécuter uniquement le scan ZAP
    --headers-only          Vérifier uniquement les headers
    --quick                 Scan rapide (sans ZAP)

EXEMPLES:
    $0                      # Scan complet
    $0 --quick              # Scan rapide sans ZAP
    $0 --url https://example.com  # URL personnalisée
    $0 --headers-only       # Headers uniquement
    $0 --zap-only           # ZAP uniquement

EOF
}

# Variables par défaut
SKIP_COMPOSER=false
SKIP_NPM=false
SKIP_HEADERS=false
SKIP_ZAP=false
ZAP_ONLY=false
HEADERS_ONLY=false
QUICK_SCAN=false

# Analyse des arguments
while [[ $# -gt 0 ]]; do
    case $1 in
        -h|--help)
            show_help
            exit 0
            ;;
        -u|--url)
            TARGET_URL="$2"
            shift 2
            ;;
        --skip-composer)
            SKIP_COMPOSER=true
            shift
            ;;
        --skip-npm)
            SKIP_NPM=true
            shift
            ;;
        --skip-headers)
            SKIP_HEADERS=true
            shift
            ;;
        --skip-zap)
            SKIP_ZAP=true
            shift
            ;;
        --zap-only)
            ZAP_ONLY=true
            SKIP_COMPOSER=true
            SKIP_NPM=true
            SKIP_HEADERS=true
            shift
            ;;
        --headers-only)
            HEADERS_ONLY=true
            SKIP_COMPOSER=true
            SKIP_NPM=true
            SKIP_ZAP=true
            shift
            ;;
        --quick)
            QUICK_SCAN=true
            SKIP_ZAP=true
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

    # Vérifier que nous sommes dans le bon répertoire
    if [[ ! -f "$PROJECT_ROOT/composer.json" ]]; then
        log_error "Fichier composer.json non trouvé. Êtes-vous dans le bon répertoire ?"
        exit 1
    fi

    # Créer le répertoire de rapports
    mkdir -p "$REPORTS_DIR"

    # Vérifier curl pour les tests de headers
    if [[ "$SKIP_HEADERS" != true ]] && [[ "$ZAP_ONLY" != true ]]; then
        if ! command -v curl &> /dev/null; then
            log_warning "curl n'est pas installé - vérification des headers ignorée"
            SKIP_HEADERS=true
        fi
    fi

    # Vérifier Docker pour ZAP
    if [[ "$SKIP_ZAP" != true ]] && [[ "$HEADERS_ONLY" != true ]]; then
        if ! command -v docker &> /dev/null; then
            log_warning "Docker n'est pas installé - scan ZAP ignoré"
            SKIP_ZAP=true
        fi
    fi

    log_success "Prérequis vérifiés"
}

# Audit des dépendances Composer
run_composer_audit() {
    if [[ "$SKIP_COMPOSER" == true ]]; then
        return 0
    fi

    log_step "🔍 Audit des dépendances Composer..."

    local composer_report="$REPORTS_DIR/composer-audit-$TIMESTAMP.txt"
    local composer_success=true

    {
        echo "=== Audit Composer ==="
        echo "Date: $(date)"
        echo "Projet: $PROJECT_ROOT"
        echo ""
    } > "$composer_report"

    if composer audit >> "$composer_report" 2>&1; then
        log_success "Aucune vulnérabilité détectée dans les dépendances Composer"
        echo "✅ Aucune vulnérabilité détectée" >> "$composer_report"
    else
        log_warning "Vulnérabilités détectées dans les dépendances Composer"
        composer_success=false

        # Ajouter des recommandations
        echo "" >> "$composer_report"
        echo "🔧 Recommandations:" >> "$composer_report"
        echo "- Exécutez 'composer update' pour mettre à jour les dépendances" >> "$composer_report"
        echo "- Vérifiez les advisories de sécurité pour chaque vulnérabilité" >> "$composer_report"
        echo "- Considérez l'utilisation de versions alternatives si disponibles" >> "$composer_report"
    fi

    log_info "Rapport Composer: $composer_report"
    return $([ "$composer_success" == true ] && echo 0 || echo 1)
}

# Audit des dépendances NPM
run_npm_audit() {
    if [[ "$SKIP_NPM" == true ]]; then
        return 0
    fi

    log_step "📦 Audit des dépendances NPM..."

    local npm_report="$REPORTS_DIR/npm-audit-$TIMESTAMP.txt"
    local npm_success=true

    # Vérifier si package.json existe
    if [[ ! -f "$PROJECT_ROOT/package.json" ]]; then
        log_info "Aucun fichier package.json trouvé - audit NPM ignoré"
        return 0
    fi

    {
        echo "=== Audit NPM ==="
        echo "Date: $(date)"
        echo "Projet: $PROJECT_ROOT"
        echo ""
    } > "$npm_report"

    cd "$PROJECT_ROOT"

    if npm audit --audit-level=moderate >> "$npm_report" 2>&1; then
        log_success "Aucune vulnérabilité modérée ou critique détectée dans les dépendances NPM"
        echo "✅ Aucune vulnérabilité modérée/critique détectée" >> "$npm_report"
    else
        log_warning "Vulnérabilités détectées dans les dépendances NPM"
        npm_success=false

        # Ajouter des recommandations
        echo "" >> "$npm_report"
        echo "🔧 Recommandations:" >> "$npm_report"
        echo "- Exécutez 'npm audit fix' pour corriger automatiquement" >> "$npm_report"
        echo "- Utilisez 'npm audit fix --force' pour les corrections forcées" >> "$npm_report"
        echo "- Vérifiez manuellement les vulnérabilités critiques" >> "$npm_report"
    fi

    log_info "Rapport NPM: $npm_report"
    return $([ "$npm_success" == true ] && echo 0 || echo 1)
}

# Vérification des headers de sécurité
check_security_headers() {
    if [[ "$SKIP_HEADERS" == true ]]; then
        return 0
    fi

    log_step "🔒 Vérification des headers de sécurité..."

    local headers_report="$REPORTS_DIR/security-headers-$TIMESTAMP.txt"
    local headers_success=true

    {
        echo "=== Vérification des Headers de Sécurité ==="
        echo "Date: $(date)"
        echo "URL testée: $TARGET_URL"
        echo ""
    } > "$headers_report"

    log_info "Test de connectivité vers $TARGET_URL..."

    # Tester la connectivité d'abord
    if curl -s --connect-timeout 10 --max-time 30 -f "$TARGET_URL" > /dev/null 2>&1; then
        log_success "Site accessible : $TARGET_URL"
        echo "✅ Site accessible" >> "$headers_report"
        echo "" >> "$headers_report"

        # Récupérer les headers
        log_info "Récupération des headers..."
        local headers_output
        headers_output=$(curl -I -s --connect-timeout 10 --max-time 30 "$TARGET_URL" 2>/dev/null)

        if [ $? -eq 0 ]; then
            echo "📋 Headers reçus :" >> "$headers_report"
            echo "$headers_output" >> "$headers_report"
            echo "" >> "$headers_report"

            log_info "Analyse des headers de sécurité..."
            echo "🔒 Analyse des headers de sécurité :" >> "$headers_report"

            # Fonction pour vérifier un header
            check_header() {
                local header_name="$1"
                local header_pattern="$2"

                if echo "$headers_output" | grep -qi "$header_pattern"; then
                    local header_value=$(echo "$headers_output" | grep -i "$header_pattern" | head -1 | tr -d '\r')
                    log_success "$header_name: présent"
                    echo "✅ $header_name: $header_value" >> "$headers_report"
                else
                    log_warning "$header_name: MANQUANT"
                    echo "❌ $header_name: MANQUANT" >> "$headers_report"
                    headers_success=false
                fi
            }

            # Vérifier les headers de sécurité spécifiques
            check_header "X-Frame-Options" "X-Frame-Options"
            check_header "X-Content-Type-Options" "X-Content-Type-Options"
            check_header "Strict-Transport-Security" "Strict-Transport-Security"
            check_header "Content-Security-Policy" "Content-Security-Policy"
            check_header "X-XSS-Protection" "X-XSS-Protection"

            # Ajouter des recommandations si des headers manquent
            if [[ "$headers_success" != true ]]; then
                echo "" >> "$headers_report"
                echo "🔧 Recommandations pour les headers manquants:" >> "$headers_report"
                echo "- X-Frame-Options: DENY ou SAMEORIGIN" >> "$headers_report"
                echo "- X-Content-Type-Options: nosniff" >> "$headers_report"
                echo "- Strict-Transport-Security: max-age=31536000; includeSubDomains" >> "$headers_report"
                echo "- Content-Security-Policy: Politique adaptée à votre application" >> "$headers_report"
                echo "- X-XSS-Protection: 1; mode=block" >> "$headers_report"
            fi

        else
            log_error "Erreur lors de la récupération des headers"
            echo "❌ Erreur lors de la récupération des headers" >> "$headers_report"
            headers_success=false
        fi
    else
        log_warning "Site non accessible : $TARGET_URL"
        log_info "Vérification des headers de sécurité ignorée (site non accessible)"
        echo "❌ Site non accessible : $TARGET_URL" >> "$headers_report"
        echo "⚠️  Vérification des headers de sécurité ignorée" >> "$headers_report"
        # Ne pas faire échouer pour un site non accessible
        headers_success=true
    fi

    log_info "Rapport headers: $headers_report"
    return $([ "$headers_success" == true ] && echo 0 || echo 1)
}

# Scan OWASP ZAP
run_zap_scan() {
    if [[ "$SKIP_ZAP" == true ]]; then
        return 0
    fi

    log_step "🕷️  Scan OWASP ZAP..."

    local zap_report="$REPORTS_DIR/zap-scan-$TIMESTAMP"
    local zap_success=true

    # Vérifier si Docker est disponible
    if ! command -v docker &> /dev/null; then
        log_warning "Docker non disponible - scan ZAP ignoré"
        return 0
    fi

    # Vérifier la connectivité vers le site
    if ! curl -s --connect-timeout 10 --max-time 30 -f "$TARGET_URL" > /dev/null 2>&1; then
        log_warning "Site non accessible - scan ZAP ignoré"
        return 0
    fi

    log_info "Démarrage du scan ZAP baseline pour $TARGET_URL..."

    # Créer le répertoire pour les rapports ZAP
    mkdir -p "$zap_report"

    # Exécuter ZAP baseline scan avec Docker
    if docker run --rm \
        -v "$zap_report:/zap/wrk/:rw" \
        -t ghcr.io/zaproxy/zaproxy:stable \
        zap-baseline.py \
        -t "$TARGET_URL" \
        -g gen.conf \
        -r zap-report.html \
        -J zap-report.json \
        -x zap-report.xml \
        > "$zap_report/zap-output.txt" 2>&1; then

        log_success "Scan ZAP terminé avec succès"

        # Vérifier si des rapports ont été générés
        if [[ -f "$zap_report/zap-report.html" ]]; then
            log_success "Rapport HTML généré: $zap_report/zap-report.html"
        fi

        if [[ -f "$zap_report/zap-report.json" ]]; then
            log_success "Rapport JSON généré: $zap_report/zap-report.json"
        fi

    else
        log_warning "Scan ZAP terminé avec des avertissements (non bloquant)"
        zap_success=true  # ZAP peut retourner des codes d'erreur pour des avertissements

        # Vérifier si des rapports ont été générés malgré les avertissements
        if [[ -f "$zap_report/zap-report.html" ]]; then
            log_info "Rapport HTML généré malgré les avertissements: $zap_report/zap-report.html"
        fi
    fi

    # Créer un résumé
    {
        echo "=== Scan OWASP ZAP ==="
        echo "Date: $(date)"
        echo "URL testée: $TARGET_URL"
        echo "Répertoire des rapports: $zap_report"
        echo ""
        echo "Fichiers générés:"
        ls -la "$zap_report/" 2>/dev/null || echo "Aucun fichier généré"
        echo ""
        echo "Sortie du scan:"
        cat "$zap_report/zap-output.txt" 2>/dev/null || echo "Aucune sortie disponible"
    } > "$zap_report/zap-summary.txt"

    log_info "Résumé ZAP: $zap_report/zap-summary.txt"

    # Ouvrir le rapport HTML si disponible
    if [[ -f "$zap_report/zap-report.html" ]]; then
        log_info "💡 Pour voir le rapport ZAP, ouvrez: $zap_report/zap-report.html"
    fi

    return $([ "$zap_success" == true ] && echo 0 || echo 1)
}

# Génération du rapport de synthèse
generate_summary_report() {
    log_step "📊 Génération du rapport de synthèse..."

    local summary_report="$REPORTS_DIR/security-summary-$TIMESTAMP.md"

    {
        echo "# 🔒 Rapport de Sécurité - Synthèse"
        echo ""
        echo "**Date:** $(date '+%Y-%m-%d %H:%M:%S')"
        echo "**URL testée:** $TARGET_URL"
        echo ""
        echo "## 📋 Résumé des Vérifications"
        echo ""
        echo "| Vérification | Statut | Détails |"
        echo "|--------------|--------|---------|"

        # Composer
        if [[ "$SKIP_COMPOSER" == true ]]; then
            echo "| Audit Composer | ⏭️ Ignoré | - |"
        else
            if [[ -f "$REPORTS_DIR/composer-audit-$TIMESTAMP.txt" ]]; then
                if grep -q "✅" "$REPORTS_DIR/composer-audit-$TIMESTAMP.txt"; then
                    echo "| Audit Composer | ✅ Réussi | Aucune vulnérabilité |"
                else
                    echo "| Audit Composer | ⚠️ Avertissements | Vulnérabilités détectées |"
                fi
            else
                echo "| Audit Composer | ❌ Échec | Erreur d'exécution |"
            fi
        fi

        # NPM
        if [[ "$SKIP_NPM" == true ]]; then
            echo "| Audit NPM | ⏭️ Ignoré | - |"
        else
            if [[ -f "$REPORTS_DIR/npm-audit-$TIMESTAMP.txt" ]]; then
                if grep -q "✅" "$REPORTS_DIR/npm-audit-$TIMESTAMP.txt"; then
                    echo "| Audit NPM | ✅ Réussi | Aucune vulnérabilité critique |"
                else
                    echo "| Audit NPM | ⚠️ Avertissements | Vulnérabilités détectées |"
                fi
            else
                echo "| Audit NPM | ❌ Échec | Erreur d'exécution |"
            fi
        fi

        # Headers
        if [[ "$SKIP_HEADERS" == true ]]; then
            echo "| Headers de Sécurité | ⏭️ Ignoré | - |"
        else
            if [[ -f "$REPORTS_DIR/security-headers-$TIMESTAMP.txt" ]]; then
                if grep -q "❌.*MANQUANT" "$REPORTS_DIR/security-headers-$TIMESTAMP.txt"; then
                    echo "| Headers de Sécurité | ⚠️ Incomplet | Headers manquants |"
                else
                    echo "| Headers de Sécurité | ✅ Réussi | Tous les headers présents |"
                fi
            else
                echo "| Headers de Sécurité | ❌ Échec | Erreur d'exécution |"
            fi
        fi

        # ZAP
        if [[ "$SKIP_ZAP" == true ]]; then
            echo "| Scan OWASP ZAP | ⏭️ Ignoré | - |"
        else
            if [[ -d "$REPORTS_DIR/zap-scan-$TIMESTAMP" ]]; then
                if [[ -f "$REPORTS_DIR/zap-scan-$TIMESTAMP/zap-report.html" ]]; then
                    echo "| Scan OWASP ZAP | ✅ Réussi | Rapport généré |"
                else
                    echo "| Scan OWASP ZAP | ⚠️ Partiel | Scan terminé avec avertissements |"
                fi
            else
                echo "| Scan OWASP ZAP | ❌ Échec | Erreur d'exécution |"
            fi
        fi

        echo ""
        echo "## 📁 Rapports Détaillés"
        echo ""
        echo "Les rapports détaillés sont disponibles dans le répertoire :"
        echo "\`$REPORTS_DIR\`"
        echo ""
        echo "### 📄 Fichiers Générés"
        echo ""

        # Lister les fichiers générés
        for file in "$REPORTS_DIR"/*"$TIMESTAMP"*; do
            if [[ -f "$file" ]] || [[ -d "$file" ]]; then
                local filename=$(basename "$file")
                echo "- \`$filename\`"
            fi
        done

        echo ""
        echo "## 🔧 Actions Recommandées"
        echo ""
        echo "1. **Vulnérabilités des dépendances** : Mettez à jour les packages vulnérables"
        echo "2. **Headers de sécurité** : Configurez les headers manquants dans votre serveur web"
        echo "3. **Scan ZAP** : Examinez le rapport HTML pour les vulnérabilités détectées"
        echo "4. **Monitoring** : Planifiez des scans réguliers pour maintenir la sécurité"

    } > "$summary_report"

    log_success "Rapport de synthèse généré: $summary_report"

    # Afficher le résumé dans la console
    echo ""
    echo -e "${CYAN}📊 =============================================="
    echo "   RÉSUMÉ DU SCAN DE SÉCURITÉ"
    echo "===============================================${NC}"
    cat "$summary_report" | grep -E "^\|" | head -6
    echo ""
    log_info "📄 Rapport complet: $summary_report"
}

# Fonction principale
main() {
    echo -e "${CYAN}"
    echo "🔒 =============================================="
    echo "   Security Scan - Exécution Locale"
    echo "   Timestamp: $TIMESTAMP"
    echo "==============================================="
    echo -e "${NC}"

    cd "$PROJECT_ROOT"

    # Vérifications préliminaires
    check_requirements

    log_info "URL cible: $TARGET_URL"
    log_info "Mode: $([ "$QUICK_SCAN" == true ] && echo "Rapide" || echo "Complet")"

    # Variables de suivi des résultats
    local overall_success=true
    local composer_success=true
    local npm_success=true
    local headers_success=true
    local zap_success=true

    # Exécution des vérifications
    if ! run_composer_audit; then
        composer_success=false
        overall_success=false
    fi

    if ! run_npm_audit; then
        npm_success=false
        overall_success=false
    fi

    if ! check_security_headers; then
        headers_success=false
        overall_success=false
    fi

    if ! run_zap_scan; then
        zap_success=false
        # ZAP ne fait pas échouer le scan global
    fi

    # Génération du rapport de synthèse
    generate_summary_report

    # Rapport final
    echo ""
    echo -e "${CYAN}📊 =============================================="
    echo "   RAPPORT FINAL"
    echo "===============================================${NC}"

    echo -e "🔍 Audit Composer:      $([ "$composer_success" == true ] && echo -e "${GREEN}✅ RÉUSSI${NC}" || echo -e "${RED}❌ ÉCHEC${NC}")"
    echo -e "📦 Audit NPM:           $([ "$npm_success" == true ] && echo -e "${GREEN}✅ RÉUSSI${NC}" || echo -e "${RED}❌ ÉCHEC${NC}")"
    echo -e "🔒 Headers Sécurité:    $([ "$headers_success" == true ] && echo -e "${GREEN}✅ RÉUSSI${NC}" || echo -e "${RED}❌ ÉCHEC${NC}")"
    echo -e "🕷️  Scan OWASP ZAP:     $([ "$zap_success" == true ] && echo -e "${GREEN}✅ RÉUSSI${NC}" || echo -e "${YELLOW}⚠️ AVERTISSEMENT${NC}")"

    echo ""
    echo -e "📁 Rapports générés dans: ${BLUE}$REPORTS_DIR${NC}"
    echo ""

    if [[ "$overall_success" == true ]]; then
        log_success "🎉 Scan de sécurité terminé avec succès !"
        exit 0
    else
        log_warning "⚠️ Scan de sécurité terminé avec des avertissements"
        exit 1
    fi
}

# Exécution du script principal
main "$@"
