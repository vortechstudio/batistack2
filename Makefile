deploy:
	ssh o2switch "cd /home/mapu6796/admin.c2me.ovh && git pull origin master && make install"

install: vendor/autoload.php .env public/storage public/build/manifest.json
	php artisan cache:clear
	php artisan migrate --force
	php artisan install:cities
	php artisan install:country
	php artisan install:pcg
	php artisan install:bank
	php artisan install:condition-reglement
	php artisan install:mode-reglement
	php artisan db:seed --force
	php artisan filament:optimize
	php artisan filament:optimize-clear
	php artisan optimize
	php artisan up

update-start:
	ssh o2switch "cd /home/mapu6796/admin.c2me.ovh && git reset --hard && git pull origin production && make update"

update:
	bash update.sh
	composer install --prefer-dist --no-interaction
	npm install
	npm run build
	php artisan migrate --force
	php artisan install:bank
	php artisan filament:optimize
	php artisan filament:optimize-clear
	php artisan optimize
	php artisan clear
	php artisan up

.env:
	cp .env.example .env
	php artisan key:generate
	@echo "\nConfiguration des variables d'environnement..."
	bash config.sh

public/storage:
	php artisan storage:link

vendor/autoload.php: composer.lock
	@if [ -f composer.lock ]; then \
		php artisan down; \
		composer update; \
	else \
		composer install; \
	fi
	touch vendor/autoload.php

public/build/manifest.json: package.json
	npm i
	npm run build

reset:
	php artisan migrate:fresh
	php artisan install:bank
	php artisan install:country
	php artisan install:pcg
	php artisan install:condition-reglement
	php artisan install:mode-reglement
	php artisan db:seed
	php artisan optimize:clear

clear:
	php artisan config:clear
	php artisan cache:clear
	php artisan route:clear
	php artisan view:clear
	php artisan optimize:clear
	composer dumpautoload

pr:
	sh pr-agent.sh

# Création du projet GitHub unifié
github-project:
ifeq ($(OS),Windows_NT)
	@echo "Création du projet GitHub unifié...";
	@powershell -ExecutionPolicy Bypass -File scripts/create-github-project-unified.ps1
else
	@echo "Création du projet GitHub unifié...";
	@bash scripts/create-github-project-unified.sh
endif

github-milestones:
ifeq ($(OS),Windows_NT)
	@echo "Création des jalons GitHub...";
	@powershell -ExecutionPolicy Bypass -File scripts/create-github-milestones.ps1
else
	@echo "Création des jalons GitHub...";
	@bash scripts/create-github-milestones.sh
endif

# Création des issues pour le Module Produits/Services
github-issues-produits:
ifeq ($(OS),Windows_NT)
	@echo "Création des issues Module Produits/Services...";
	@powershell -ExecutionPolicy Bypass -File scripts/create-issues-produits-services.ps1
else
	@echo "Création des issues Module Produits/Services...";
	@bash scripts/create-issues-produits-services.sh
endif

# Création des labels GitHub (inclus dans le projet unifié)
github-labels:
	@echo "Les labels sont créés automatiquement avec le projet unifié";

# Configuration GitHub complète
github-setup: github-project github-milestones
	@echo "Configuration GitHub terminée !";

# Métriques de code (reproduction du workflow GitHub)
metrics:
ifeq ($(OS),Windows_NT)
	@echo "Génération des métriques de code (Windows)...";
	@powershell -ExecutionPolicy Bypass -File scripts/code-metrics.ps1
else
	@echo "Génération des métriques de code (Unix)...";
	@bash scripts/code-metrics.sh
endif

metrics-open:
ifeq ($(OS),Windows_NT)
	@echo "Génération des métriques avec ouverture automatique...";
	@powershell -ExecutionPolicy Bypass -File scripts/code-metrics.ps1 -OpenReport
else
	@echo "Génération des métriques avec ouverture automatique...";
	@bash scripts/code-metrics.sh && open metrics/index.html 2>/dev/null || xdg-open metrics/index.html 2>/dev/null || echo "Ouvrez manuellement: metrics/index.html"
endif

security-scan:
	@echo "Exécution du scan de sécurité...";
	@bash scripts/security-scan.sh

security-scan-quick:
	@echo "Exécution du scan de sécurité (sans ZAP)...";
	@bash scripts/security-scan.sh --skip-zap

module-ci:
	@echo "Exécution du Module CI/CD complet...";
	@bash scripts/module-ci.sh --all

module-ci-detect:
	@echo "Détection des modules modifiés...";
	@bash scripts/module-ci.sh --detect-only

module-ci-tests:
	@echo "Exécution des tests de modules uniquement...";
	@bash scripts/module-ci.sh --tests-only

module-ci-security:
	@echo "Exécution de l'analyse de sécurité uniquement...";
	@bash scripts/module-ci.sh --security-only

module-ci-performance:
	@echo "Exécution des tests de performance uniquement...";
	@bash scripts/module-ci.sh --performance-only

module-ci-quality:
	@echo "Exécution de l'analyse de qualité uniquement...";
	@bash scripts/module-ci.sh --quality-only

# Tests spécifiques par module
module-ci-chantiers:
	@echo "CI/CD pour le module Chantiers...";
	@bash scripts/module-ci.sh chantiers

module-ci-rh:
	@echo "CI/CD pour le module RH...";
	@bash scripts/module-ci.sh rh

module-ci-tiers:
	@echo "CI/CD pour le module Tiers...";
	@bash scripts/module-ci.sh tiers

module-ci-commerce:
	@echo "CI/CD pour le module Commerce...";
	@bash scripts/module-ci.sh commerce

module-ci-core:
	@echo "CI/CD pour le module Core...";
	@bash scripts/module-ci.sh core

# Alias pour compatibilité
ci-modules: module-ci
ci-detect: module-ci-detect
ci-tests: module-ci-tests
ci-security: module-ci-security
ci-performance: module-ci-performance
ci-quality: module-ci-quality

# CI/CD local complet (tous les workflows)
ci-local: metrics security-scan module-ci
	@echo "🎉 CI/CD local complet terminé !";

# Aide pour les nouvelles commandes
help-ci:
	@echo "🏗️ Commandes Module CI/CD disponibles:";
	@echo "";
	@echo "  module-ci              - CI/CD complet (tous modules)";
	@echo "  module-ci-detect       - Détecter les modules modifiés";
	@echo "  module-ci-tests        - Tests uniquement";
	@echo "  module-ci-security     - Sécurité uniquement";
	@echo "  module-ci-performance  - Performance uniquement";
	@echo "  module-ci-quality      - Qualité uniquement";
	@echo "";
	@echo "  module-ci-chantiers    - CI/CD module Chantiers";
	@echo "  module-ci-rh           - CI/CD module RH";
	@echo "  module-ci-tiers        - CI/CD module Tiers";
	@echo "  module-ci-commerce     - CI/CD module Commerce";
	@echo "  module-ci-core         - CI/CD module Core";
	@echo "";
	@echo "  ci-local               - CI/CD complet (métriques + sécurité + modules)";
	@echo "";
	@echo "📊 Autres workflows:";
	@echo "  metrics                - Générer les métriques de code";
	@echo "  security-scan          - Scan de sécurité complet";
	@echo "  security-scan-quick    - Scan de sécurité rapide";
