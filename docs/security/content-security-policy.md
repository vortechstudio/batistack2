# Configuration Content-Security-Policy pour Batistack

## Vue d'ensemble

Ce document explique la configuration de la Content-Security-Policy (CSP) mise en place pour sécuriser l'application Batistack contre les attaques XSS et autres vulnérabilités liées au contenu.

## Fichiers de configuration

### 1. Middleware CSP
- **Fichier** : `app/Http/Middleware/ContentSecurityPolicy.php`
- **Fonction** : Applique les en-têtes CSP à toutes les réponses HTTP

### 2. Configuration CSP
- **Fichier** : `config/csp.php`
- **Fonction** : Définit les directives CSP et les en-têtes de sécurité

### 3. Variables d'environnement
- **Fichier** : `.env`
- **Variables principales** :
  - `CSP_REPORT_ONLY=false` : Mode rapport uniquement (true/false)
  - `CSP_DEVELOPMENT_MODE=true` : Mode développement (true/false)

## Directives CSP configurées

### Directives par défaut
- `default-src 'self'` : Restriction par défaut au domaine actuel
- `script-src 'self' 'unsafe-inline' 'unsafe-eval'` : Scripts autorisés (avec CDN)
- `style-src 'self' 'unsafe-inline'` : Styles autorisés (avec Google Fonts)
- `img-src 'self' data: https: blob:` : Images autorisées
- `connect-src 'self' ws: wss:` : Connexions autorisées (WebSockets pour Livewire)

### Mode développement
En mode développement local, les directives sont étendues pour inclure :
- `localhost:*` et `127.0.0.1:*` pour tous les ports
- Support WebSocket pour le hot-reload

## En-têtes de sécurité supplémentaires

- `X-Content-Type-Options: nosniff`
- `X-Frame-Options: SAMEORIGIN`
- `X-XSS-Protection: 1; mode=block`
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Permissions-Policy: geolocation=(), microphone=(), camera=()`

## Personnalisation

### Via variables d'environnement
Vous pouvez surcharger les directives via le fichier `.env` :

```env
CSP_SCRIPT_SRC="'self' 'unsafe-inline' https://cdn.example.com"
CSP_STYLE_SRC="'self' 'unsafe-inline' https://fonts.googleapis.com"
CSP_IMG_SRC="'self' data: https:"
```

### Mode rapport uniquement
Pour tester la CSP sans bloquer le contenu :

```env
CSP_REPORT_ONLY=true
CSP_REPORT_URI="/csp-report"
```

## Déploiement

### Environnement de production
- `CSP_DEVELOPMENT_MODE=false`
- `CSP_REPORT_ONLY=false`
- Directive `upgrade-insecure-requests` activée

### Environnement de développement
- `CSP_DEVELOPMENT_MODE=true`
- Directives étendues pour localhost
- Pas de `upgrade-insecure-requests`

## Compatibilité Livewire

La configuration est optimisée pour Laravel Livewire :
- Support des WebSockets (`ws:` et `wss:`)
- `'unsafe-inline'` pour les styles dynamiques
- `'unsafe-eval'` pour les scripts Livewire (si nécessaire)

## Surveillance et débogage

1. **Mode rapport** : Activez `CSP_REPORT_ONLY=true` pour surveiller les violations
2. **Console navigateur** : Vérifiez les erreurs CSP dans les outils de développement
3. **Logs Laravel** : Les violations peuvent être loggées si un endpoint de rapport est configuré

## Recommandations

1. **Test progressif** : Commencez en mode `report-only`
2. **Surveillance** : Surveillez les violations en production
3. **Mise à jour** : Ajustez les directives selon les besoins de l'application
4. **CDN** : Ajoutez les domaines CDN utilisés aux directives appropriées