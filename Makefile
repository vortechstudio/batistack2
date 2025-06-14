deploy:
	ssh o2switch "cd /home/mapu6796/admin.c2me.ovh && git pull origin master && make install"

install: vendor/autoload.php .env public/storage public/build/manifest.json
	php artisan cache:clear
	php artisan migrate --force
	php artisan db:seed --force
	php artisan install:cities
	php artisan install:country
	php artisan install:pcg

update:
	bash update.sh

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
