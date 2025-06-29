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
	php artisan install:cities
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
