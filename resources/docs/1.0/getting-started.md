# Guide de démarrage rapide

Bienvenue dans Batistack ! Ce guide vous accompagne dans vos premiers pas pour prendre en main rapidement la plateforme et commencer à gérer efficacement votre entreprise du BTP avec les dernières fonctionnalités.

## Première connexion

### Accès à votre compte
1. **Réception de vos identifiants** : Votre administrateur vous a fourni vos identifiants de connexion
2. **Connexion** : Rendez-vous sur l'interface web ou lancez l'application desktop
3. **Première authentification** : Saisissez votre email et mot de passe temporaire
4. **Changement de mot de passe** : Créez un mot de passe personnel sécurisé

### Configuration de votre profil
1. **Informations personnelles** : Complétez votre nom, prénom et coordonnées
2. **Photo de profil** : Ajoutez une photo pour personnaliser votre compte
3. **Préférences** : Choisissez votre langue et thème d'affichage
4. **Notifications** : Configurez vos préférences de notification (email, SMS pour la signature)

## Découverte de l'interface

### Navigation principale
- **Menu principal** : Accès aux différents modules (Chantiers, RH, Tiers, Commerce)
- **Tableau de bord** : Vue d'ensemble de votre activité
- **Recherche globale** : Trouvez rapidement n'importe quelle information
- **Notifications** : Alertes et messages importants
- **Profil utilisateur** : Paramètres personnels et déconnexion

### Organisation des modules
Batistack est organisé en modules spécialisés :
- **Chantiers** : Gestion de vos projets de construction
- **RH** : Ressources humaines et paie avec signature électronique
- **Tiers** : Clients et fournisseurs
- **Commerce** : Devis, factures et commandes
- **Portail Salarié** : Espace personnel des employés (si applicable)

## Premiers pas pratiques

### 1. Créer votre premier client

**Objectif** : Enregistrer un client pour pouvoir créer des devis et chantiers

1. **Accès** : Menu principal > Tiers > Clients
2. **Création** : Cliquez sur "Nouveau client"
3. **Informations essentielles** :
   - Nom ou raison sociale
   - Type de client (particulier, entreprise)
   - Coordonnées (adresse, téléphone, email)
   - Informations fiscales (SIREN si entreprise)
4. **Validation** : Enregistrez votre client

**💡 Conseil** : Commencez par vos clients les plus importants pour tester le système

### 2. Créer votre premier chantier

**Objectif** : Démarrer le suivi d'un projet de construction

1. **Accès** : Menu principal > Chantiers > Nouveau chantier
2. **Informations de base** :
   - Nom du chantier (ex: "Rénovation Maison Dupont")
   - Client (sélectionnez dans la liste créée précédemment)
   - Adresse du chantier
   - Dates prévisionnelles (début et fin)
   - Budget prévisionnel
3. **Équipe** : Assignez un responsable de chantier
4. **Validation** : Créez le chantier

**💡 Conseil** : Choisissez un chantier en cours pour voir immédiatement les bénéfices

### 3. Créer votre premier devis

**Objectif** : Établir un devis professionnel pour un client

1. **Accès** : Depuis la fiche chantier > "Créer un devis" ou Menu Commerce > Nouveau devis
2. **En-tête** :
   - Client et chantier associé
   - Date d'émission et de validité
   - Conditions de règlement
3. **Lignes de devis** :
   - Description des prestations
   - Quantités et unités
   - Prix unitaires HT
   - Taux de TVA
4. **Finalisation** : Enregistrez et générez le PDF

**💡 Conseil** : Utilisez des descriptions claires pour faciliter la compréhension client

### 4. 🆕 Ajouter votre premier employé avec les nouvelles fonctionnalités

**Objectif** : Enregistrer un employé pour la gestion RH avec les dernières améliorations

1. **Accès** : Menu principal > RH > Nouveau salarié
2. **Informations personnelles** :
   - État civil complet
   - Coordonnées (incluant numéro de téléphone pour SMS)
   - Numéro de sécurité sociale
   - **🆕 Génération automatique** : UUID et matricule RH
3. **Contrat de travail** :
   - Type de contrat (CDI, CDD, etc.)
   - Date de début
   - Poste et qualification
   - Salaire et taux horaire
4. **🆕 Informations bancaires** :
   - IBAN et BIC avec validation automatique
   - Vérification via OpenIban
5. **Validation** : Enregistrez l'employé
6. **🆕 Notification automatique** : L'équipe RH est alertée

**💡 Conseil** : Commencez par vous enregistrer ou un employé de confiance

### 5. 🆕 Tester la signature électronique

**Objectif** : Découvrir le processus de signature électronique sécurisée

1. **Préparation** : Créez un contrat pour l'employé ajouté
2. **Envoi pour signature** : Lancez le workflow de signature
3. **Côté employé** :
   - Réception de l'email de notification
   - Connexion au portail salarié
   - Réception du code SMS (OTP)
   - Signature sur le pad électronique
4. **Suivi** : Vérifiez le statut de signature dans l'interface RH

**💡 Conseil** : Testez d'abord avec votre propre compte employé

### 6. 🆕 Créer et valider une note de frais

**Objectif** : Tester la nouvelle validation sélective des notes de frais

1. **Création** (côté employé) :
   - Accès : Menu RH > Notes de frais > Nouvelle note
   - Ajout de plusieurs détails de frais (professionnels et personnels)
   - Soumission de la note
2. **Validation** (côté responsable) :
   - Ouverture de la note soumise
   - Utilisation de la validation sélective
   - Sélection des frais professionnels uniquement
   - Validation avec commentaire

**💡 Conseil** : Mélangez frais professionnels et personnels pour tester la sélection

## Fonctionnalités essentielles à maîtriser

### Recherche et filtres
- **Recherche globale** : Utilisez la barre de recherche en haut de l'écran
- **Filtres par module** : Chaque liste dispose de filtres spécifiques
- **Tri des colonnes** : Cliquez sur les en-têtes pour trier
- **Sauvegarde de vues** : Enregistrez vos filtres favoris

### 🆕 Gestion des documents et signature
- **Upload** : Glissez-déposez vos fichiers dans les zones prévues
- **Génération PDF** : Créez automatiquement vos devis, factures et contrats
- **🆕 Signature électronique** : Signez et faites signer vos documents avec Yousign
- **🆕 Vérification SMS** : Sécurisez les signatures avec des codes OTP
- **Archivage** : Tous vos documents sont automatiquement archivés

### Notifications et alertes
- **Notifications en temps réel** : Restez informé des événements importants
- **🆕 Notifications SMS** : Codes de sécurité pour la signature
- **Emails automatiques** : Recevez des résumés périodiques
- **Alertes personnalisées** : Configurez vos propres alertes
- **Centre de notifications** : Consultez l'historique de vos notifications

## 🆕 Nouvelles fonctionnalités à découvrir

### Signature électronique sécurisée
- **Service Yousign** : Plateforme certifiée de signature
- **Vérification SMS** : Code OTP pour sécuriser chaque signature
- **Pad de signature** : Interface intuitive de signature
- **Traçabilité complète** : Historique de toutes les signatures

### Validation sélective des notes de frais
- **Interface améliorée** : Affichage détaillé des frais
- **Sélection granulaire** : Validez uniquement les frais professionnels
- **Traitement automatique** : Marquage des frais personnels
- **Recalcul automatique** : Montants ajustés selon la sélection

### Gestion des comptes bancaires
- **Validation IBAN/BIC** : Vérification automatique via OpenIban
- **Interface dédiée** : Gestion simplifiée des coordonnées
- **Sécurité renforcée** : Chiffrement des données bancaires

### Module de gestion des congés
- **Demandes en ligne** : Interface simplifiée pour les employés
- **Workflow de validation** : Processus d'approbation hiérarchique
- **Suivi des soldes** : Gestion des droits et consommations
- **Calendrier intégré** : Vue d'ensemble des absences

## Conseils pour bien démarrer

### Organisation efficace

**📁 Structure des données**
- Adoptez une nomenclature cohérente pour vos chantiers
- Utilisez les catégories et étiquettes pour classer vos tiers
- Créez des modèles de documents pour gagner du temps
- **🆕 Configurez les notifications SMS** pour la signature électronique

**⏰ Routine quotidienne**
- Consultez votre tableau de bord chaque matin
- Mettez à jour l'avancement de vos chantiers
- Traitez vos notifications régulièrement
- **🆕 Vérifiez les signatures en attente**
- **🆕 Validez les notes de frais avec la nouvelle interface**

**👥 Collaboration d'équipe**
- Formez vos collaborateurs aux fonctionnalités de base
- **🆕 Expliquez le processus de signature électronique**
- Définissez des responsabilités claires pour chaque module
- Utilisez les commentaires pour communiquer
- **🆕 Sensibilisez à la distinction frais professionnels/personnels**

### Optimisation progressive

**Semaine 1 : Prise en main**
- Créez vos premiers clients et chantiers
- Testez la création de devis
- Explorez l'interface et les menus
- **🆕 Testez la signature électronique avec un contrat simple**

**Semaine 2 : Approfondissement**
- Configurez vos modèles de documents
- Paramétrez vos notifications (email et SMS)
- Commencez le suivi budgétaire des chantiers
- **🆕 Explorez la validation sélective des notes de frais**
- **🆕 Configurez les comptes bancaires des employés**

**Semaine 3 : Automatisation**
- Utilisez les fonctionnalités avancées
- Configurez les relances automatiques
- Optimisez vos workflows
- **🆕 Mettez en place le module de gestion des congés**
- **🆕 Formez vos équipes aux nouvelles fonctionnalités**

### Erreurs à éviter

**❌ À ne pas faire**
- Ne pas sauvegarder régulièrement vos données importantes
- Négliger la formation de vos équipes aux nouvelles fonctionnalités
- Oublier de mettre à jour les informations clients
- Ignorer les notifications importantes
- **🆕 Ne pas tester la signature électronique avant utilisation**
- **🆕 Valider automatiquement tous les frais sans vérification**

**✅ Bonnes pratiques**
- Sauvegardez vos données critiques
- Documentez vos processus internes
- Formez progressivement vos équipes
- Utilisez les fonctionnalités d'aide intégrées
- **🆕 Testez la signature électronique en interne d'abord**
- **🆕 Utilisez la validation sélective pour optimiser les remboursements**
- **🆕 Vérifiez les coordonnées bancaires avec OpenIban**

## Ressources d'aide

### Documentation
- **Guides détaillés** : Consultez la documentation complète de chaque module
- **🆕 Tutoriels signature électronique** : Guides spécifiques pour Yousign
- **Tutoriels** : Suivez les guides pas à pas pour les tâches courantes
- **FAQ** : Trouvez rapidement des réponses aux questions fréquentes

### Support
- **Aide contextuelle** : Cliquez sur les icônes d'aide dans l'interface
- **Support technique** : Contactez l'équipe via le menu Aide
- **🆕 Support signature** : Assistance spécialisée pour la signature électronique
- **Formation** : Demandez des sessions de formation pour vos équipes

### Communauté
- **Forum utilisateurs** : Échangez avec d'autres utilisateurs
- **Retours d'expérience** : Partagez vos bonnes pratiques
- **🆕 Témoignages** : Découvrez comment d'autres utilisent les nouvelles fonctionnalités
- **Suggestions** : Proposez des améliorations

## Prochaines étapes

Une fois ces premiers pas maîtrisés, vous pourrez :

1. **Approfondir chaque module** : Explorez les fonctionnalités avancées
2. **🆕 Maîtriser la signature électronique** : Déployez massivement la signature
3. **🆕 Optimiser la validation des frais** : Utilisez pleinement la validation sélective
4. **Personnaliser votre environnement** : Adaptez Batistack à vos processus
5. **Former vos équipes** : Accompagnez vos collaborateurs dans l'adoption
6. **Optimiser vos workflows** : Automatisez vos tâches répétitives
7. **🆕 Intégrer les nouvelles fonctionnalités** : Congés, comptes bancaires, etc.

## 🆕 Checklist de démarrage avec les nouvelles fonctionnalités

### Configuration initiale
- [ ] Créer votre premier client
- [ ] Créer votre premier chantier
- [ ] Créer votre premier devis
- [ ] Ajouter votre premier employé avec UUID/matricule
- [ ] **🆕 Configurer les informations bancaires avec validation IBAN**
- [ ] **🆕 Tester la signature électronique avec un contrat simple**
- [ ] **🆕 Créer une note de frais et tester la validation sélective**

### Formation équipe
- [ ] Former aux fonctionnalités de base
- [ ] **🆕 Expliquer le processus de signature électronique**
- [ ] **🆕 Sensibiliser à la validation sélective des frais**
- [ ] **🆕 Présenter le nouveau portail salarié**
- [ ] **🆕 Former à la gestion des congés**

### Optimisation
- [ ] Configurer les notifications (email + SMS)
- [ ] Paramétrer les modèles de documents
- [ ] **🆕 Mettre en place les workflows de signature**
- [ ] **🆕 Optimiser les processus de validation des frais**
- [ ] **🆕 Configurer le module de gestion des congés**

---

**Version du guide** : 1.12.0  
**Dernière mise à jour** : Janvier 2025  
*Ce guide reflète les dernières fonctionnalités de Batistack.*
