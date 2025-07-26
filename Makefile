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
