# Guide de démarrage rapide

Bienvenue dans ce guide de démarrage rapide pour Batistack. Ce document vous guidera à travers les étapes essentielles pour configurer et commencer à utiliser la plateforme.

## Prérequis système

### Application Desktop (Tauri)
- **Système d'exploitation** : Windows 10/11, macOS 10.15+, ou Linux (Ubuntu 20.04+)
- **RAM** : 4 Go minimum (8 Go recommandés)
- **Espace disque** : 500 Mo minimum
- **Résolution d'écran** : 1280x720 minimum (1920x1080 recommandé)

### Interface Web
- **Navigateurs supportés** : Chrome 90+, Firefox 90+, Edge 90+, Safari 14+
- **Connexion Internet** : 5 Mbps minimum

## Installation

### Application Desktop

1. **Téléchargement**
   - Rendez-vous sur [notre portail de téléchargement](https://batistack.com/download)
   - Sélectionnez la version correspondant à votre système d'exploitation
   - Téléchargez le fichier d'installation

2. **Installation**
   - **Windows** : Exécutez le fichier `.exe` et suivez les instructions
   - **macOS** : Ouvrez le fichier `.dmg`, glissez l'application dans le dossier Applications
   - **Linux** : Utilisez le fichier `.AppImage` ou installez via le paquet `.deb`/`.rpm`

3. **Premier lancement**
   - Lancez l'application depuis votre menu démarrer ou dock
   - Acceptez les autorisations système si demandées

### Interface Web

1. **Accès**
   - Connectez-vous à l'URL fournie par votre administrateur système
   - Utilisez un navigateur compatible (voir prérequis)

## Configuration initiale

### Création de compte administrateur

Lors du premier lancement, vous devrez créer un compte administrateur principal :

1. Sur l'écran d'accueil, cliquez sur "Première configuration"
2. Renseignez les informations demandées :
   - Nom et prénom de l'administrateur
   - Email professionnel (servira d'identifiant)
   - Mot de passe sécurisé (minimum 8 caractères, incluant majuscules, chiffres et caractères spéciaux)
   - Numéro de téléphone (optionnel, pour récupération de compte)
3. Cliquez sur "Créer le compte administrateur"

### Configuration de l'entreprise

Après la création du compte administrateur, vous serez guidé à travers la configuration de votre entreprise :

1. **Informations générales**
   - Raison sociale
   - Numéro SIREN/SIRET
   - Numéro de TVA intracommunautaire
   - Adresse complète
   - Logo de l'entreprise (format PNG ou JPG, 300x300px minimum)

2. **Paramètres fiscaux**
   - Régime de TVA
   - Taux de TVA par défaut
   - Exercice comptable (dates de début et fin)

3. **Paramètres bancaires** (optionnel)
   - Nom de la banque principale
   - IBAN et BIC
   - RIB (upload du document)

## Configuration des modules

Batistack est organisé en modules fonctionnels. Voici comment configurer chacun d'eux :

### Module Chantiers

1. Accédez à **Paramètres > Chantiers**
2. Configurez les éléments suivants :
   - **Catégories de chantiers** : Créez des catégories adaptées à votre activité (rénovation, construction neuve, etc.)
   - **Types de dépenses** : Définissez les types de dépenses standard (matériaux, main d'œuvre, sous-traitance, etc.)
   - **Statuts personnalisés** : Adaptez les statuts de progression des chantiers à votre workflow

### Module RH

1. Accédez à **Paramètres > Ressources Humaines**
2. Configurez les éléments suivants :
   - **Profils de paie** : Créez des modèles pour différents types de contrats
   - **Horaires de travail** : Définissez les horaires standard de l'entreprise
   - **Types d'absences** : Configurez les différents types d'absences (congés payés, maladie, etc.)
   - **Taux horaires** : Définissez les taux horaires par défaut

### Module Commerce

1. Accédez à **Paramètres > Commerce**
2. Configurez les éléments suivants :
   - **Modèles de documents** : Personnalisez l'apparence des devis, factures et avoirs
   - **Conditions de règlement** : Définissez les délais de paiement standard
   - **Modes de règlement** : Configurez les modes de paiement acceptés
   - **Numérotation** : Paramétrez les séquences de numérotation des documents commerciaux

### Module Tiers

1. Accédez à **Paramètres > Tiers**
2. Configurez les éléments suivants :
   - **Catégories de tiers** : Créez des catégories pour vos clients et fournisseurs
   - **Champs personnalisés** : Ajoutez des champs spécifiques à votre activité
   - **Étiquettes** : Définissez un système d'étiquettes pour faciliter la recherche

## Premiers pas avec Batistack

### Création de votre premier chantier

1. Dans le menu principal, cliquez sur **Chantiers > Nouveau chantier**
2. Remplissez les informations de base :
   - Nom du chantier
   - Client (sélectionnez dans la liste ou créez un nouveau client)
   - Adresse du chantier
   - Dates prévisionnelles (début et fin)
   - Budget prévisionnel
3. Cliquez sur "Créer le chantier"
4. Vous serez redirigé vers la fiche du chantier où vous pourrez :
   - Ajouter des tâches et interventions
   - Affecter des ressources humaines
   - Enregistrer des dépenses
   - Uploader des photos

### Création de votre premier devis

1. Depuis la fiche chantier, cliquez sur **Actions > Créer un devis**
2. Ou depuis le menu principal : **Commerce > Nouveau devis**
3. Remplissez les informations requises :
   - Client
   - Chantier associé (optionnel)
   - Date d'émission et validité
   - Conditions de règlement
4. Ajoutez des lignes au devis :
   - Description
   - Quantité
   - Prix unitaire HT
   - Taux de TVA
5. Cliquez sur "Enregistrer" ou "Enregistrer et générer PDF"

### Ajout d'un employé

1. Dans le menu principal, cliquez sur **RH > Nouvel employé**
2. Remplissez les informations personnelles :
   - Nom et prénom
   - Date de naissance
   - Coordonnées
   - Numéro de sécurité sociale
3. Créez un contrat de travail :
   - Type de contrat
   - Date de début
   - Salaire et taux horaire
   - Profil de paie
4. Cliquez sur "Enregistrer l'employé"

## Astuces et bonnes pratiques

### Organisation efficace

- **Structure des chantiers** : Adoptez une nomenclature cohérente pour nommer vos chantiers
- **Catégorisation** : Utilisez les étiquettes et catégories pour faciliter les recherches futures
- **Photos** : Prenez des photos régulières de l'avancement des chantiers
- **Sauvegardes** : Exportez régulièrement vos données importantes

### Optimisation des performances

- **Nettoyage** : Archivez les chantiers terminés pour alléger l'interface
- **Médias** : Compressez les photos avant import pour optimiser l'espace disque
- **Mises à jour** : Installez régulièrement les mises à jour de l'application

## Assistance et support

Si vous rencontrez des difficultés lors de la configuration ou de l'utilisation de Batistack :

- Consultez notre [documentation complète](../index)
- Contactez notre support technique via l'interface (Menu Aide > Support)
- Rejoignez notre communauté d'utilisateurs sur [notre forum](https://forum.batistack.com)

---

*Dernière mise à jour : Janvier 2025*
