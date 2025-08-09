# 📦 Journal des modifications (Changelog)
## 🗓️ Version 1.13.0 (2025-07-30)

Cette version apporte une **refonte importante de la gestion des notes de frais**, avec de **nouvelles fonctionnalités** pour les salariés et le service RH, ainsi que plusieurs **améliorations techniques** et **correctifs d’interface**.

## ✨ Nouvelles fonctionnalités

### Espace Salarié

- Ajout de la fonctionnalité **"Notes de frais"** pour les employés.
- Ajout d’une **page de consultation détaillée** d’une note de frais.
- Amélioration de l’interface avec **filtres dynamiques** et **colonnes personnalisées**.

### Espace RH

- Ajout d’une **table de détails** pour les lignes de frais.
- Ajout d’actions : **soumettre**, **refuser**, avec indicateur de **remboursabilité**.
- Mise en place de **notifications automatiques** lors de la **soumission** ou du **paiement**.
- Ajout d’une **validation partielle** des lignes de frais.

### Système

- Ajout de la **fonction d’usurpation d’identité (impersonation)** pour administrateurs.
- **Routage dynamique** basé sur les rôles utilisateurs.

## 🐛 Corrections

- Amélioration de l’affichage des notes de frais en cas de **dates manquantes**.
- Filtrage des **détails remboursables** et amélioration de l’**ergonomie** générale.
- **Affichage conditionnel** de la table bancaire et suppression des pièces jointes inutiles.
- Ajout des **scripts manquants** dans la barre latérale du portail.
- Mise à jour des **méthodes Faker** dépréciées dans les tests.

