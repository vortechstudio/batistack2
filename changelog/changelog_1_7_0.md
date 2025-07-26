# 📦 Journal des modifications (Changelog)
## 🗓️ Version 1.7.0 (2025-07-16)

### ✨ Nouvelles fonctionnalités

- **Monitoring** : Intégration de **Laravel Horizon** pour superviser les files d’attente.
- **Module RH** :
  - Ajout du **système de gestion des employés**, avec création, contrat, statut, et affichage dans le tableau des salaires.
  - Intégration d’un **workflow d’onboarding** et d’un **suivi de la DPAE** (génération, transmission, envoi par email).
  - Mise en place d’un **système de vérification documentaire** pour les employés.
  - Création de pages de **configuration RH**, dont une dédiée au comptable paie.
- **Tableau des salaires** : Amélioration de l'affichage (email sous le nom complet) et ajout des actions CRUD.
- **Contrats** : Ajout d’un enum pour les statuts et modification du statut par défaut en "brouillon".
- **Sécurité** : Protection contre l’assignation massive des champs employés.
- **Documentation** : Intégration du package **LaRecipe** pour la documentation interne du projet.

### 🐛 Corrections de bugs

- **Horizon** : Ajout d’un email de test à la gate d'accès Horizon.
- **Salariés** : Mise à jour conditionnelle de la vérification de la carte BTP.
- **Ressources** : Correction du formulaire de création dans le tableau des ressources.
- **Factures** : Correction du champ utilisé pour le filtrage des factures par date.
