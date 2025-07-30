# Module Produits & Services

## Introduction

Le module Produits & Services de Batistack est un composant essentiel qui permet de gérer l'ensemble de votre catalogue de produits, matériaux, outillages et services. Il offre une gestion complète avec catégorisation hiérarchique, unités de mesure, tarification avancée et intégration avec les autres modules de l'application.

## Fonctionnalités principales

### Gestion des produits et services

- **Catalogue unifié** : Gestion centralisée de tous vos produits et services
- **Typologie complète** : Support de 9 types différents (matériaux, outillage, main d'œuvre, location, transport, études, conseil, forfait, consommables)
- **Catégorisation hiérarchique** : Organisation en arbre avec catégories et sous-catégories
- **Unités de mesure** : Système complet avec conversions automatiques
- **Tarification avancée** : Gestion des prix fournisseurs et clients avec remises et conditions

### Types de produits supportés

#### Matériels
- **Matériaux** : Matériaux de construction, fournitures
- **Outillage** : Outils, équipements, machines
- **Consommables** : Produits consommables, petites fournitures

#### Services
- **Main d'œuvre** : Prestations de main d'œuvre
- **Location** : Location d'équipements, véhicules
- **Transport** : Transport, livraison, déplacement
- **Études** : Études techniques, diagnostics
- **Conseil** : Conseil, expertise, formation
- **Forfait** : Prestations forfaitaires

### Catégorisation

- **Structure hiérarchique** : Catégories et sous-catégories illimitées
- **Codes automatiques** : Génération automatique des codes de catégories
- **Couleurs personnalisées** : Attribution de couleurs pour l'identification visuelle
- **Ordre d'affichage** : Contrôle de l'ordre d'affichage des catégories
- **Métadonnées** : Stockage d'informations complémentaires

### Unités de mesure

- **Unités de base** : Mètre, kilogramme, heure, unité, etc.
- **Unités dérivées** : Millimètre, centimètre, gramme, tonne, etc.
- **Conversions automatiques** : Calculs automatiques entre unités
- **Types d'unités** : Longueur, poids, surface, volume, temps, quantité, énergie

### Tarification

#### Tarifs fournisseurs
- **Multi-fournisseurs** : Plusieurs tarifs par produit
- **Tarifs préférés** : Définition de fournisseurs préférés
- **Remises par quantité** : Jusqu'à 3 seuils de remise
- **Conditions de paiement** : Gestion des conditions spécifiques
- **Délais de livraison** : Suivi des délais par fournisseur
- **Frais de port** : Gestion des frais fixes et pourcentages

#### Tarifs clients
- **Tarification flexible** : Tarifs généraux et spécifiques par client
- **Types de clients** : Particuliers, professionnels, entreprises
- **Tarifs dégressifs** : Jusqu'à 3 seuils de prix dégressifs
- **Remises multiples** : Volume, commerciale, fidélité
- **Marges contrôlées** : Calcul automatique des marges
- **Prix encadrés** : Prix minimum et maximum
- **Négociabilité** : Indication des tarifs négociables

## Structure des données

### Entité Produit

- **Informations générales** : Référence, nom, description, type
- **Classification** : Catégorie, unité de mesure
- **États** : Actif, vendable, achetable
- **Spécificités matériels** : Poids, code-barres, dimensions, garantie
- **Spécificités services** : Durée, compétences, zones d'intervention
- **Métadonnées** : Certifications, documents joints

### Entité Catégorie

- **Hiérarchie** : Parent, enfants, niveau de profondeur
- **Identification** : Code, nom, description
- **Présentation** : Couleur, ordre d'affichage
- **État** : Actif/inactif

### Entité Unité de mesure

- **Identification** : Code, nom, symbole
- **Classification** : Type d'unité
- **Conversion** : Unité de base, facteur de conversion
- **État** : Actif/inactif

### Entité Tarif fournisseur

- **Références** : Produit, fournisseur, référence fournisseur
- **Prix** : Prix d'achat, devise
- **Conditions** : Quantité minimale, délai de livraison
- **Remises** : Par quantité (3 seuils), générale
- **Frais** : Port fixe et pourcentage
- **Validité** : Dates de début et fin, priorité
- **Services** : Tarif horaire, coût déplacement, majorations

### Entité Tarif client

- **Références** : Produit, client, type de client
- **Prix** : Prix de vente, devise
- **Dégressivité** : 3 seuils de prix dégressifs
- **Remises** : Volume, commerciale, fidélité
- **Encadrement** : Prix minimum/maximum, marge
- **Conditions** : Quantité minimale, délai de livraison
- **Frais** : Livraison, installation, formation
- **Services** : Tarif horaire, grille tarifaire, majorations

## Utilisation

### Création d'un produit

1. Accédez au module Produits depuis le menu principal
2. Cliquez sur "Ajouter un produit"
3. Remplissez les informations dans l'assistant :
   - **Type de produit** : Sélectionnez le type approprié
   - **Informations générales** : Référence, nom, description
   - **Classification** : Catégorie et unité de mesure
   - **Spécificités** : Selon le type (matériel ou service)
   - **États** : Actif, vendable, achetable
4. Validez pour créer le produit

### Gestion des catégories

1. Accédez à la section "Catégories"
2. Visualisez l'arbre hiérarchique des catégories
3. Pour ajouter une catégorie :
   - Cliquez sur "Ajouter une catégorie"
   - Sélectionnez la catégorie parent (optionnel)
   - Renseignez le nom et la description
   - Choisissez une couleur d'identification
   - Définissez l'ordre d'affichage
4. Pour modifier : cliquez sur l'icône d'édition

### Configuration des unités de mesure

1. Accédez à la section "Unités de mesure"
2. Consultez les unités existantes par type
3. Pour ajouter une unité :
   - Cliquez sur "Ajouter une unité"
   - Renseignez le code, nom et symbole
   - Sélectionnez le type d'unité
   - Définissez l'unité de base et le facteur de conversion
4. Les conversions sont automatiquement calculées

### Gestion des tarifs fournisseurs

1. Dans la fiche d'un produit, accédez à l'onglet "Tarifs fournisseurs"
2. Cliquez sur "Ajouter un tarif fournisseur"
3. Remplissez les informations :
   - **Fournisseur** : Sélectionnez dans la liste des tiers
   - **Prix et conditions** : Prix d'achat, quantité minimale
   - **Remises** : Configurez les seuils et pourcentages
   - **Délais** : Délai de livraison, conditions de paiement
   - **Validité** : Dates de début et fin, priorité
4. Marquez comme "préféré" si nécessaire

### Gestion des tarifs clients

1. Dans la fiche d'un produit, accédez à l'onglet "Tarifs clients"
2. Cliquez sur "Ajouter un tarif client"
3. Configurez le tarif :
   - **Client** : Spécifique ou général par type
   - **Prix** : Prix de vente, marge souhaitée
   - **Dégressivité** : Seuils et prix dégressifs
   - **Remises** : Volume, commerciale, fidélité
   - **Encadrement** : Prix min/max, négociabilité
   - **Frais** : Livraison, installation, formation
4. Définissez la période de validité

### Calcul automatique des prix

Le système calcule automatiquement :
- **Prix avec remises** : Application des remises par quantité
- **Prix dégressifs** : Selon les seuils configurés
- **Marges** : Calcul automatique par rapport aux tarifs fournisseurs
- **Totaux avec frais** : Intégration des frais de livraison/installation

## Intégration avec les autres modules

### Module Tiers
- **Fournisseurs** : Liaison avec les tarifs fournisseurs
- **Clients** : Liaison avec les tarifs clients spécifiques
- **Types de clients** : Tarification différenciée

### Module Commerce
- **Devis** : Utilisation des tarifs clients pour les lignes
- **Commandes** : Application des prix négociés
- **Factures** : Calcul automatique avec remises et frais
- **Achats** : Utilisation des tarifs fournisseurs

### Module Chantiers
- **Ressources** : Association des produits aux chantiers
- **Consommations** : Suivi des quantités utilisées
- **Coûts** : Calcul des coûts réels par chantier

### Module Comptabilité
- **Comptes** : Association aux comptes comptables
- **TVA** : Gestion des taux de TVA par produit
- **Analytique** : Répartition par centres de coûts

## Fonctionnalités avancées

### Gestion des services

Pour les services, le module offre des fonctionnalités spécifiques :
- **Tarification horaire** : Prix à l'heure avec majorations
- **Zones d'intervention** : Définition des zones couvertes
- **Compétences requises** : Qualification nécessaire
- **Délais d'intervention** : Temps de réponse standard
- **Majorations** : Weekend, nuit, urgence
- **Grilles tarifaires** : Tarification complexe par critères

### Gestion des matériels

Pour les matériels, des champs spécifiques sont disponibles :
- **Caractéristiques physiques** : Poids, dimensions
- **Identification** : Code-barres, référence fournisseur
- **Durée de vie** : Estimation de la durée d'utilisation
- **Garantie** : Période de garantie
- **Certifications** : Normes et certifications

### Import/Export

- **Import en masse** : Importation de catalogues fournisseurs
- **Export** : Extraction des données pour analyses
- **Synchronisation** : Mise à jour automatique des tarifs

## Bonnes pratiques

### Organisation des catégories
- Créez une hiérarchie logique et cohérente
- Utilisez des codes mnémotechniques
- Attribuez des couleurs distinctives par famille
- Limitez le nombre de niveaux (3-4 maximum)

### Gestion des références
- Adoptez une nomenclature standardisée
- Intégrez le type de produit dans la référence
- Utilisez des préfixes par catégorie
- Évitez les caractères spéciaux

### Tarification
- Mettez à jour régulièrement les tarifs fournisseurs
- Définissez des marges cohérentes par famille
- Utilisez les remises pour fidéliser les clients
- Contrôlez les prix minimum pour préserver la rentabilité

### Unités de mesure
- Utilisez les unités standards du secteur
- Configurez correctement les facteurs de conversion
- Testez les conversions avant mise en production
- Documentez les unités spécifiques

## Sécurité et droits d'accès

- **Consultation** : Accès en lecture aux catalogues
- **Modification** : Droits de modification des fiches produits
- **Tarification** : Droits spécifiques pour la gestion des prix
- **Administration** : Gestion des catégories et unités de mesure

## Rapports et analyses

Le module propose plusieurs rapports :
- **Catalogue produits** : Liste complète avec tarifs
- **Analyse des marges** : Rentabilité par produit/famille
- **Évolution des prix** : Historique des tarifs
- **Rotation des stocks** : Produits les plus/moins vendus

## Conclusion

Le module Produits & Services constitue le référentiel central de votre activité dans Batistack. Une bonne organisation de votre catalogue facilitera l'ensemble de vos processus commerciaux, de l'établissement des devis jusqu'à la facturation, en passant par la gestion des achats et le suivi des chantiers.