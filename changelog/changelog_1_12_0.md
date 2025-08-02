# 📦 Journal des modifications (Changelog)
## 🗓️ Version 1.12.0 (2025-07-27)

## ✨ Nouvelles fonctionnalités

### 🧾 Gestion des notes de frais (RH)
- Mise en place complète du **système de gestion des notes de frais**
- Ajout des **formulaires de création** avec champs dynamiques
- Fonctionnalité de **paiement des frais validés**
- Génération automatique des **factures liées aux frais**
- **Envoi d'e-mails** de notification pour les remboursements
- **Tableaux de suivi** des frais avec filtres, avatars et améliorations visuelles
- Intégration de **widgets de statistiques** RH dans le tableau de bord
- Amélioration du **schéma de base de données** et des seeders

### 🔐 Sécurité
- Ajout d’un **middleware Content Security Policy (CSP)** pour renforcer la sécurité des contenus web

### ⚙️ Intégration et déploiement (CI/CD)
- Réorganisation des **workflows GitHub Actions** pour une CI plus performante
- Automatisation de la création d’issues
- Ajout d’une **commande de génération automatique de stubs de tests de modèles**

### 🔧 Configuration GitHub
- Mise en place des **scripts de configuration du projet GitHub**

## ✅ Corrections de bugs

- Correction des **méthodes Faker** dans les factories utilisées pour les tests
- Ajout d’un **filtre de statut** et amélioration du formatage des tableaux de frais

---
Cette version marque une avancée majeure dans le module RH avec une gestion complète des notes de frais, ainsi qu'une amélioration significative de l'expérience développeur via l'automatisation et la qualité du code.
