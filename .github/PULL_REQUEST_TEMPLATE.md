## 📋 Description
<!-- Expliquez en détail les changements apportés et leur impact -->

## 🏗️ Modules affectés
<!-- Cochez tous les modules impactés par cette PR -->
- [ ] 🏗️ **Chantiers** - Gestion des chantiers et interventions
- [ ] 👥 **RH** - Ressources humaines et employés
- [ ] 🏢 **Tiers** - Clients, fournisseurs et contacts
- [ ] 💼 **Commerce** - Devis, commandes et factures
- [ ] ⚙️ **Core** - Fonctionnalités transversales
- [ ] 📦 **Produits/Services** *(futur)*
- [ ] 🏭 **GPAO** *(futur)*
- [ ] 💰 **Facturation** *(futur)*
- [ ] 📊 **Comptabilité** *(futur)*
- [ ] 🚗 **Véhicules** *(futur)*
- [ ] 📁 **GED** *(futur)*
- [ ] 🔧 **Infrastructure** - Configuration, déploiement
- [ ] 🎨 **Frontend** - Interface utilisateur
- [ ] 📚 **Documentation**

## 🔄 Type de changement
<!-- Cochez le type principal de cette PR -->
- [ ] 🐛 **Correction de bug** - Résout un problème existant
- [ ] ✨ **Nouvelle fonctionnalité** - Ajoute une nouvelle capacité
- [ ] 🔧 **Refactoring** - Améliore le code sans changer le comportement
- [ ] 📚 **Documentation** - Met à jour ou ajoute de la documentation
- [ ] 🚀 **Performance** - Améliore les performances
- [ ] 🔒 **Sécurité** - Corrige une vulnérabilité ou améliore la sécurité
- [ ] 🧪 **Tests** - Ajoute ou améliore les tests
- [ ] 🔨 **Configuration** - Modifie la configuration ou les workflows
- [ ] 💥 **Breaking change** - Changement qui casse la compatibilité

## 🎯 Références aux tickets
<!-- Utilisez la syntaxe 'Fixes #123' pour lier automatiquement les issues -->
<!-- Les issues liées seront fermées automatiquement lors du merge -->

**Issues résolues :**
- Fixes #

**Issues liées :**
- Related to #

**Exemples :**
<!-- 
- Fixes #456 - Problème de calcul des taxes dans le module Commerce
- Fixes #789 - Amélioration des performances des requêtes Chantiers
- Related to #123 - Epic: Refonte du module RH
-->

## 🧪 Tests et validation

### Tests automatiques
- [ ] ✅ Les tests unitaires passent (`php artisan test`)
- [ ] ✅ Les tests d'intégration passent
- [ ] ✅ La qualité de code est maintenue (Pint, PHPStan)
- [ ] ✅ La couverture de test est maintenue/améliorée
- [ ] ✅ Les tests de sécurité passent

### Tests manuels
- [ ] ✅ Testé localement sur l'environnement de développement
- [ ] ✅ Testé sur les navigateurs principaux (si frontend)
- [ ] ✅ Testé sur mobile/responsive (si applicable)
- [ ] ✅ Testé les cas d'erreur et edge cases

### Validation métier
- [ ] ✅ Validé par le product owner / métier
- [ ] ✅ Respecte les spécifications fonctionnelles
- [ ] ✅ Interface utilisateur validée (si applicable)

## 📊 Impact et risques

### Niveau d'impact
- [ ] 🟢 **Faible** - Changements mineurs, peu de risques
- [ ] 🟡 **Moyen** - Changements modérés, risques contrôlés
- [ ] 🟠 **Élevé** - Changements importants, nécessite attention
- [ ] 🔴 **Critique** - Changements majeurs, risques élevés

### Zones de risque
- [ ] 🗄️ **Base de données** - Migrations, changements de schéma
- [ ] 🔐 **Authentification/Autorisation** - Sécurité des accès
- [ ] 💰 **Calculs financiers** - Facturation, taxes, prix
- [ ] 📊 **Rapports/Exports** - Génération de données
- [ ] 🔄 **Intégrations externes** - APIs tierces, services
- [ ] ⚡ **Performance** - Requêtes, temps de réponse

## 🚀 Déploiement

### Prérequis de déploiement
- [ ] 📦 Nouvelles dépendances ajoutées (`composer install`)
- [ ] 🗄️ Migrations de base de données requises
- [ ] ⚙️ Variables d'environnement à configurer
- [ ] 📁 Fichiers de configuration à mettre à jour
- [ ] 🔄 Cache à vider après déploiement
- [ ] 📊 Seeders à exécuter

### Instructions spéciales
<!-- Ajoutez ici toute instruction spécifique pour le déploiement -->
```bash
# Exemple d'instructions de déploiement
php artisan migrate
php artisan config:cache
php artisan route:cache
```

## 📚 Documentation

### Documentation mise à jour
- [ ] 📖 README.md mis à jour (si nécessaire)
- [ ] 📋 Documentation API mise à jour
- [ ] 🏗️ Documentation des modules mise à jour
- [ ] 📝 Changelog mis à jour
- [ ] 🎯 Guide utilisateur mis à jour (si applicable)

### Documentation des modules
<!-- Si vous avez modifié des modules, indiquez la documentation associée -->
- [ ] `docs/modules/chantiers.md`
- [ ] `docs/modules/rh.md`
- [ ] `docs/modules/tiers.md`
- [ ] `docs/modules/commerce.md`
- [ ] `docs/modules/core.md`

## 🔍 Checklist de révision

### Code quality
- [ ] 🧹 Code propre et bien structuré
- [ ] 📝 Commentaires ajoutés pour la logique complexe
- [ ] 🏗️ Architecture respectée (séparation des responsabilités)
- [ ] 🔄 Pas de code dupliqué
- [ ] ⚡ Performance optimisée
- [ ] 🔒 Sécurité prise en compte

### Standards du projet
- [ ] 📏 Conventions de nommage respectées
- [ ] 🎨 Standards de code respectés (PSR-12)
- [ ] 🏷️ Types et interfaces utilisés correctement
- [ ] 🧪 Tests appropriés ajoutés
- [ ] 📊 Logging approprié ajouté

### Intégration
- [ ] 🔄 Pas de conflit de merge
- [ ] 🌿 Branche à jour avec develop/master
- [ ] 🏗️ CI/CD passe sans erreur
- [ ] 📦 Pas de dépendances cassées

## 🎯 Validation finale

### Avant le merge
- [ ] ✅ **Review approuvée** par au moins un développeur senior
- [ ] ✅ **Tests CI/CD** passent tous
- [ ] ✅ **Validation métier** effectuée (si applicable)
- [ ] ✅ **Documentation** complète et à jour
- [ ] ✅ **Pas de TODO** ou FIXME dans le code de production

### Post-merge
- [ ] 🚀 **Déploiement** planifié et validé
- [ ] 📊 **Monitoring** en place pour surveiller l'impact
- [ ] 🔄 **Rollback plan** défini si nécessaire

## 💬 Informations complémentaires

### Contexte technique
<!-- Décrivez les décisions d'architecture, les compromis techniques, etc. -->

### Captures d'écran
<!-- Ajoutez des captures d'écran pour les changements d'interface -->

### Notes pour les reviewers
<!-- Indiquez les points spécifiques à vérifier lors de la review -->

### Liens utiles
<!-- Ajoutez des liens vers la documentation, les spécifications, etc. -->

---

## 🤖 Automatisation

> **Note :** Cette PR sera automatiquement traitée par nos workflows :
> - 🧪 **Tests automatiques** sur les modules modifiés
> - 🔍 **Analyse de qualité** de code (Pint, PHPStan)
> - 🔒 **Scan de sécurité** des dépendances
> - ⚡ **Vérification des performances**
> - 📊 **Génération de rapports** de couverture
> 
> En cas d'échec, une issue sera automatiquement créée avec le template `ci-failure.yml`.

<!-- 
🎯 Conseils pour une PR efficace :
1. Gardez vos PR petites et focalisées
2. Utilisez des commits atomiques avec des messages clairs
3. Testez localement avant de pousser
4. Documentez les changements complexes
5. Demandez une review dès que possible
-->
