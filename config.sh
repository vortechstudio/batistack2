#!/bin/bash

# Configuration des variables d'environnement
sed -i "s/^APP_URL=.*/APP_URL=https:\/\/admin.c2me.ovh/" .env

sed -i "s/^DB_HOST=.*/DB_HOST=functions.o2switch.net/" .env
sed -i "s/^DB_DATABASE=.*/=mapu6796_batisDB_DATABASEtack_c2me/" .env
sed -i "s/^DB_USERNAME=.*/DB_USERNAME=mapu6796_root/" .env
sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=rbU89a-4/" .env

sed -i "s/^MAIL_HOST=.*/MAIL_HOST=functions.o2switch.net/" .env
sed -i "s/^MAIL_USERNAME=.*/MAIL_USERNAME=noreply@c2me.ovh/" .env
sed -i "s/^MAIL_PASSWORD=.*/MAIL_PASSWORD=2gU)]7k&![{0/" .env
sed -i "s/^MAIL_FROM_ADDRESS=\".*\"/MAIL_FROM_ADDRESS=\"noreply@c2me.ovh\"/" .env

sed -i "s/^GITHUB_TOKEN=\".*\"/GITHUB_TOKEN=/" .env
sed -i "s/^GITHUB_USERNAME=\".*\"/GITHUB_USERNAME=vortechstudio/" .env
sed -i "s/^GITHUB_REPOSITORY=\".*\"/GITHUB_REPOSITORY=batistack2/" .env

sed -i "s/^BRIDGE_CLIENT_ID=\".*\"/BRIDGE_CLIENT_ID=sandbox_id_4d775bc33db849938327555d5287e6c6/" .env
sed -i "s/^BRIDGE_CLIENT_SECRET=\".*\"/BRIDGE_CLIENT_SECRET=sandbox_secret_aUH13Wm3ON27lYek7plXtxDPYxZBhdQpd0lW5Ok7MtwnlQccpqJcSNtkh4K8svL1/" .env

sed -i "s/^PUSHER_APP_KEY=\".*\"/PUSHER_APP_KEY=7edfa96845d9f9a080e9/" .env
sed -i "s/^PUSHER_APP_SECRET=\".*\"/PUSHER_APP_SECRET=1e7ccaba47065ed0c632/" .env
sed -i "s/^PUSHER_APP_ID=\".*\"/PUSHER_APP_ID=2008361/" .env

sed -i "s/^BUGSNAG_API_KEY=\".*\"/BUGSNAG_API_KEY=f75b3bf691ff27f2d77053c946fef9fe/" .env
