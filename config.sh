#!/bin/bash

# Configuration des variables d'environnement
sed -i "s/^APP_URL=.*/APP_URL=https:\/\/admin.c2me.ovh/" .env

sed -i "s/^DB_DATABASE=.*/DB_DATABASE=mapu6796_batistack_c2me/" .env
sed -i "s/^DB_USERNAME=.*/DB_USERNAME=mapu6796_root/" .env
sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=rbU89a-4/" .env

sed -i "s/^MAIL_HOST=.*/MAIL_HOST=functions.o2switch.net/" .env
sed -i "s/^MAIL_USERNAME=.*/MAIL_USERNAME=noreply@c2me.ovh/" .env
sed -i "s/^MAIL_PASSWORD=.*/MAIL_PASSWORD=2gU)]7k&![{0/" .env
sed -i "s/^MAIL_FROM_ADDRESS=\".*\"/MAIL_FROM_ADDRESS=\"noreply@c2me.ovh\"/" .env
