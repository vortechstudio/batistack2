# Module Tiers

## Introduction

Le module Tiers de Batistack est un composant central qui permet de gérer l'ensemble des relations commerciales de votre entreprise. Il vous offre une gestion complète des clients et fournisseurs avec leurs informations détaillées, contacts, adresses, coordonnées bancaires et historique des interactions.

## Fonctionnalités principales

### Gestion des tiers

- **Tableau de bord** : Vue d'ensemble avec statistiques et derniers tiers modifiés
- **Catégorisation** : Classification par nature (client/fournisseur) et type (particulier, TPE, PME/PMI, grand compte, administration, autre)
- **Fiches détaillées** : Informations complètes sur chaque tiers
- **Recherche avancée** : Filtrage multicritères pour retrouver rapidement vos tiers

### Clients

- **Gestion des clients** : Création, modification et suivi des clients
- **Informations fiscales** : Gestion de la TVA et numéros d'identification
- **Conditions commerciales** : Paramétrage des conditions et modes de règlement spécifiques
- **Comptabilité** : Association aux comptes comptables pour l'export

### Fournisseurs

- **Gestion des fournisseurs** : Suivi complet de vos fournisseurs
- **Conditions d'achat** : Paramétrage des conditions et modes de règlement
- **Comptabilité** : Liaison avec le plan comptable
- **Suivi des interactions** : Historique des échanges et événements

### Contacts et adresses

- **Contacts multiples** : Gestion de plusieurs contacts par tiers
- **Adresses multiples** : Enregistrement de plusieurs adresses (livraison, facturation, etc.)
- **Informations détaillées** : Civilité, nom, prénom, fonction, téléphone, email, etc.

### Coordonnées bancaires

- **Comptes bancaires** : Gestion des IBAN/BIC pour les paiements
- **Multi-banques** : Possibilité d'enregistrer plusieurs comptes bancaires
- **Compte par défaut** : Définition d'un compte bancaire principal

### Intégration avec les autres modules

- **Commerce** : Liaison avec les devis, commandes, factures et avoirs
- **Chantiers** : Association des tiers aux chantiers
- **Comptabilité** : Intégration avec le plan comptable

## Structure des données

### Entité Tiers

- **Informations générales** : Nom, nature, type, code tiers
- **Informations fiscales** : SIREN, TVA, numéro de TVA
- **Relations** : Contacts, adresses, banques, logs d'activité

### Types de tiers

- **Particulier** : Clients individuels
- **TPE** : Très petites entreprises
- **PME/PMI** : Petites et moyennes entreprises/industries
- **Grand Compte** : Grandes entreprises
- **Administration** : Organismes publics
- **Autre** : Autres types d'organisations

## Utilisation

### Création d'un tiers

1. Accédez au module Tiers depuis le menu principal
2. Sélectionnez "Clients" ou "Fournisseurs" selon le type de tiers à créer
3. Cliquez sur le bouton "Ajouter"
4. Remplissez les informations dans l'assistant en plusieurs étapes :
   - Informations générales (raison sociale, type, SIREN, etc.)
   - Adresse et contacts
   - Informations comptables et conditions de règlement
   - Coordonnées bancaires (optionnel)
5. Validez pour créer le tiers

### Consultation et modification

1. Accédez à la liste des clients ou fournisseurs
2. Utilisez les filtres pour retrouver un tiers spécifique
3. Cliquez sur l'icône "œil" pour consulter la fiche détaillée
4. Dans la fiche détaillée, naviguez entre les différents onglets :
   - Tiers : informations générales
   - Contacts/Adresses : gestion des contacts et adresses
   - Client/Fournisseur : informations spécifiques selon la nature
   - Produits : produits associés
   - Mode de Règlements : coordonnées bancaires
5. Pour modifier, cliquez sur le bouton "Modifier" dans la fiche

### Gestion des contacts et adresses

1. Dans la fiche d'un tiers, accédez à l'onglet "Contacts/Adresses"
2. Utilisez les boutons d'action pour ajouter, modifier ou supprimer des contacts et adresses
3. Renseignez les informations demandées dans les formulaires

### Communication avec les tiers

1. Dans la fiche d'un tiers, utilisez le bouton "Envoyer un email" pour communiquer directement
2. Remplissez le formulaire avec le sujet et le contenu du message
3. Envoyez le message qui sera automatiquement associé à l'historique du tiers

## Bonnes pratiques

- Renseignez systématiquement les informations fiscales (SIREN, TVA) pour faciliter la facturation
- Créez des contacts nominatifs pour améliorer la qualité des relations commerciales
- Utilisez les types de tiers pour segmenter votre base et faciliter les analyses
- Vérifiez la validité des coordonnées bancaires (IBAN/BIC) avant enregistrement
- Consultez régulièrement le tableau de bord pour suivre l'évolution de votre base tiers

## Intégration avec la comptabilité

Le module Tiers est étroitement lié au plan comptable pour faciliter l'export comptable :

- Chaque client est associé à un compte client dans le plan comptable
- Chaque fournisseur est associé à un compte fournisseur
- Les conditions de règlement définissent les échéances de paiement
- Les modes de règlement permettent de gérer les différents moyens de paiement

## Sécurité et confidentialité

Les données des tiers sont sensibles et protégées dans Batistack :

- Accès contrôlé selon les profils utilisateurs
- Historisation des modifications
- Protection des données personnelles conformément au RGPD

## Conclusion

Le module Tiers constitue la base de votre gestion commerciale dans Batistack. Une bonne organisation de vos tiers facilitera l'ensemble de vos processus commerciaux, de la création de devis jusqu'à la facturation et au suivi des paiements.