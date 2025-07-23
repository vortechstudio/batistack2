# Module Ressources Humaines

## Introduction

Le module Ressources Humaines de Batistack est une solution complète pour gérer l'ensemble du cycle de vie des collaborateurs dans votre entreprise du BTP. Il couvre tous les aspects de la gestion RH, depuis le recrutement jusqu'à la gestion de la paie, en passant par le suivi des temps de travail et la gestion des absences.

Ce module s'intègre parfaitement avec les autres composants de Batistack, notamment le module Chantiers pour l'affectation des ressources humaines aux projets, et les modules de facturation pour le calcul des coûts de main d'œuvre.

## Fonctionnalités principales

### Gestion des employés

- **Fiches employés complètes** : Enregistrez toutes les informations personnelles et professionnelles de vos collaborateurs
- **Gestion des documents** : Stockage sécurisé des pièces d'identité, contrats, certificats et autres documents RH
- **Suivi du processus d'embauche** : Workflow automatisé depuis la création de la fiche jusqu'à l'activation du compte
- **Portail employé** : Interface dédiée permettant aux salariés de consulter leurs informations et documents

### Gestion des contrats

- **Types de contrats multiples** : Support des CDI, CDD, contrats d'apprentissage et d'intérim
- **Workflow de validation** : Processus structuré de création, validation et signature des contrats
- **Signature électronique** : Intégration avec des solutions de signature électronique pour la dématérialisation
- **Suivi des échéances** : Alertes automatiques pour les fins de contrats et renouvellements

### Système de paie

- **Profils de paie personnalisables** : Création de modèles pour différents types de contrats et postes
- **Calcul automatique** : Génération automatique des fiches de paie avec calculs des cotisations
- **Gestion des variables** : Prise en compte des heures supplémentaires, primes et déductions
- **Export comptable** : Interface avec les logiciels de comptabilité

### Pointage et absences

- **Système de pointage** : Enregistrement des heures d'arrivée et de départ
- **Gestion des absences** : Suivi des congés payés, arrêts maladie et autres absences
- **Affectation aux chantiers** : Liaison avec le module Chantiers pour le suivi des heures par projet
- **Calcul des heures** : Comptabilisation automatique des heures travaillées et supplémentaires

### Processus DPAE

- **Génération automatique** : Création automatique des déclarations préalables à l'embauche
- **Export PDF** : Génération de documents PDF pour transmission aux organismes
- **Transmission automatique** : Envoi automatique aux experts-comptables et organismes sociaux
- **Suivi des déclarations** : Historique et statut des DPAE transmises

### Tableau de bord RH

- **Indicateurs clés** : Visualisation des métriques importantes (effectif, turnover, absences)
- **Alertes et notifications** : Système d'alertes pour les échéances importantes
- **Statistiques** : Analyses des données RH pour le pilotage de l'entreprise
- **Rapports** : Génération de rapports personnalisés

## Structure des données

### Entité Employé

L'entité principale du module avec les informations suivantes :

- **Informations personnelles** : nom, prénom, date de naissance, adresse, contacts
- **Informations professionnelles** : poste, service, date d'embauche, salaire de base
- **Statut** : actif, inactif, en congé, absent
- **Processus** : étape actuelle dans le workflow d'embauche

### Contrats de travail

Gestion complète des contrats avec :

- **Type de contrat** : CDI, CDD, apprentissage, intérim
- **Durée** : dates de début et fin, période d'essai
- **Conditions** : salaire, horaires, lieu de travail
- **Statut** : brouillon, vérifié, actif, suspendu, terminé

### Informations complémentaires

- **Données bancaires** : RIB pour les virements de salaire
- **Documents** : stockage sécurisé des pièces justificatives
- **Historique** : traçabilité de toutes les modifications

### Pointages

Enregistrement des temps de travail :

- **Heures d'arrivée et départ** : pointage quotidien
- **Affectation aux chantiers** : répartition du temps par projet
- **Calculs automatiques** : heures normales et supplémentaires

### Absences

Suivi des différents types d'absences :

- **Congés payés** : demandes et validation
- **Arrêts maladie** : suivi médical
- **Autres absences** : formation, événements familiaux

### Fiches de paie

Génération et archivage des bulletins de salaire :

- **Calculs automatiques** : salaire brut, cotisations, net à payer
- **Variables** : primes, heures supplémentaires, déductions
- **Archivage** : conservation légale des documents

## Utilisation

### Création d'un employé

1. Accédez au module Ressources Humaines
2. Cliquez sur "Ajouter un salarié"
3. Remplissez les informations obligatoires :
   - Informations personnelles (nom, prénom, date de naissance)
   - Adresse et coordonnées
   - Poste et service
   - Salaire de base
4. Uploadez les documents requis (CNI, CV, diplômes)
5. Validez la création

### Gestion des contrats

1. Dans la fiche employé, accédez à l'onglet "Contrat"
2. Créez un nouveau contrat en spécifiant :
   - Type de contrat
   - Dates de début et fin
   - Conditions de travail
   - Salaire et avantages
3. Soumettez le contrat pour validation
4. Une fois validé, le contrat est envoyé pour signature électronique
5. Après signature, le compte employé est automatiquement activé

### Suivi des pointages

1. Les employés pointent via l'interface dédiée ou l'application mobile
2. Les heures sont automatiquement calculées et réparties par chantier
3. Les responsables peuvent consulter et valider les pointages
4. Les données alimentent automatiquement le calcul de la paie

### Gestion des absences

1. Les employés saisissent leurs demandes d'absence via le portail
2. Les responsables reçoivent une notification pour validation
3. Les absences validées sont automatiquement déduites du temps de travail
4. Le système met à jour les compteurs de congés

### Génération de la paie

1. Le système calcule automatiquement les éléments de paie
2. Les variables (primes, heures sup.) sont intégrées
3. Les fiches de paie sont générées et mises à disposition
4. Les données sont exportées vers la comptabilité

## Processus d'embauche

Le module suit un workflow structuré pour l'embauche :

### 1. Création de la fiche
- Saisie des informations de base
- Upload des documents obligatoires
- Statut : "En création"

### 2. Validation des documents
- Vérification des pièces justificatives
- Contrôle de conformité
- Statut : "Documents vérifiés"

### 3. Génération DPAE
- Création automatique de la déclaration
- Transmission à l'expert-comptable
- Statut : "DPAE transmise"

### 4. Établissement du contrat
- Rédaction du contrat de travail
- Vérification juridique
- Statut : "Contrat établi"

### 5. Signature électronique
- Envoi du contrat pour signature
- Délai de 72h pour signature
- Statut : "En attente de signature"

### 6. Activation du compte
- Génération des identifiants
- Configuration des accès
- Statut : "Actif"

## Intégration avec les autres modules

### Module Chantiers
- Affectation des employés aux chantiers
- Suivi des heures par projet
- Calcul des coûts de main d'œuvre

### Module Comptabilité
- Export des données de paie
- Interface avec les logiciels comptables
- Suivi des charges sociales

### Module Facturation
- Intégration des coûts de main d'œuvre dans les devis
- Calcul de la rentabilité des chantiers
- Facturation des heures travaillées

## Sécurité et conformité

### Protection des données
- Chiffrement des données sensibles
- Contrôle d'accès par rôles
- Audit trail complet

### Conformité légale
- Respect du RGPD
- Conservation légale des documents
- Déclarations obligatoires automatisées

### Sauvegarde
- Sauvegarde automatique des données
- Archivage sécurisé des documents
- Plan de continuité d'activité

## Bonnes pratiques

### Organisation efficace
- Adoptez une nomenclature cohérente pour les postes et services
- Mettez à jour régulièrement les informations employés
- Utilisez les alertes pour anticiper les échéances importantes
- Formez les utilisateurs aux procédures RH

### Gestion documentaire
- Numérisez systématiquement tous les documents
- Respectez les délais de conservation légale
- Organisez les documents par catégories
- Vérifiez régulièrement la validité des pièces d'identité

### Suivi des temps
- Encouragez la ponctualité dans les pointages
- Validez régulièrement les heures travaillées
- Surveillez les heures supplémentaires
- Analysez la productivité par chantier

### Gestion de la paie
- Vérifiez les calculs avant validation
- Archivez les fiches de paie de manière sécurisée
- Respectez les délais de paiement
- Tenez à jour les taux de cotisations

## Support et assistance

### Documentation
- Guides utilisateur détaillés
- Tutoriels vidéo
- FAQ mise à jour régulièrement

### Formation
- Sessions de formation initiale
- Formations de mise à jour
- Support personnalisé

### Assistance technique
- Support par email et téléphone
- Intervention à distance
- Maintenance préventive

## Conclusion

Le module Ressources Humaines de Batistack offre une solution complète et intégrée pour gérer efficacement vos collaborateurs. En automatisant les processus administratifs et en centralisant toutes les informations RH, il vous permet de vous concentrer sur l'essentiel : le développement de votre entreprise et le bien-être de vos équipes.
