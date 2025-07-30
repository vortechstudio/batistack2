# 📚 Documentation du Module Commerce

## 🏢 Présentation du module
Le module Commerce permet de gérer l'ensemble du cycle commercial de votre entreprise BTP :
- 📋 Création et gestion des devis
- 📦 Suivi des commandes
- 🧾 Facturation clients et fournisseurs
- 💰 Gestion des paiements et règlements
- 📄 Avoirs et remboursements
- 📊 Suivi commercial et analyses

## 🚀 Premiers pas
### Accéder au module
1. Dans le menu principal, cliquez sur "Commerce"
2. Sélectionnez la section souhaitée (Devis, Commandes, Factures)

## 📋 Gestion des devis

### Vue d'ensemble
Les devis constituent le point de départ du processus commercial. Ils permettent de proposer vos services et produits aux clients avec une tarification détaillée.

### Statuts des devis
- **Brouillon** 📝 : En cours de rédaction, modifiable
- **Envoyé** ✈️ : Transmis au client, en attente de réponse
- **Accepté** ✅ : Validé par le client, peut être transformé en commande
- **Refusé** ❌ : Rejeté par le client
- **Annulé** 🚫 : Annulé par l'entreprise

### Créer un devis
1. Accédez à "Devis" dans le module Commerce
2. Cliquez sur "Nouveau devis"
3. Renseignez les informations obligatoires :
   - **Client** : Sélection depuis le carnet d'adresses
   - **Chantier** : Association à un projet (optionnel)
   - **Date du devis**
   - **Responsable** : Commercial en charge
4. Ajoutez les lignes de devis avec les produits/services
5. Validez et envoyez au client

### Lignes de devis
Chaque ligne peut contenir :
- **Produit/Service** : Depuis le catalogue ou saisie libre
- **Description** détaillée
- **Quantité** et **unité de mesure**
- **Prix unitaire HT**
- **Taux de TVA**
- **Remise** éventuelle
- **Total ligne** calculé automatiquement

### Intégration avec les produits
- **Catalogue intégré** : Sélection directe des produits
- **Prix automatiques** : Tarifs clients appliqués selon les conditions
- **Calculs automatiques** : Totaux HT, TVA, TTC
- **Remises** : Application des conditions commerciales

## 📦 Gestion des commandes

### Vue d'ensemble
Les commandes résultent de l'acceptation des devis et permettent de suivre la réalisation des prestations.

### Statuts des commandes
- **En attente** ⏳ : Commande créée, en attente de confirmation
- **Confirmé** ✅ : Commande validée, en cours de préparation
- **En cours** 🚛 : Livraison/réalisation en cours
- **Livré** 📦 : Prestation terminée et livrée
- **Annulé** ❌ : Commande annulée

### Workflow des commandes
1. **Création** : Depuis un devis accepté ou création directe
2. **Confirmation** : Validation des conditions et délais
3. **Préparation** : Organisation des ressources et matériaux
4. **Réalisation** : Exécution des travaux ou livraison
5. **Livraison** : Finalisation et réception client

### Suivi des commandes
- **Planning** : Dates de début et fin prévues
- **Ressources** : Affectation des équipes et matériels
- **Avancement** : Pourcentage de réalisation
- **Facturation** : Génération des factures d'acompte ou finales

## 🧾 Gestion des factures

### Types de factures
- **Facture client** : Facturation des prestations réalisées
- **Facture fournisseur** : Enregistrement des achats
- **Facture d'acompte** : Demande d'avance sur travaux
- **Facture de situation** : Facturation progressive des travaux

### Statuts des factures
- **Non payée** 🔴 : Facture émise, en attente de paiement
- **Partiellement payée** 🟡 : Paiement partiel reçu
- **Payée** 🟢 : Soldée intégralement
- **En retard** ⚠️ : Échéance dépassée

### Créer une facture
1. Depuis une commande livrée ou création directe
2. Renseignement automatique des informations client
3. Ajout des lignes de facturation
4. Calcul automatique des totaux et TVA
5. Génération du PDF et envoi client

### Gestion des paiements
- **Saisie des règlements** : Enregistrement des paiements reçus
- **Modes de paiement** : Chèque, virement, espèces, carte
- **Échéanciers** : Gestion des paiements échelonnés
- **Relances** : Automatisation des rappels de paiement

## 📄 Gestion des avoirs

### Vue d'ensemble
Les avoirs permettent de gérer les remboursements, retours de marchandises ou corrections de facturation.

### Cas d'usage
- **Retour de marchandise** : Produits défectueux ou non conformes
- **Erreur de facturation** : Correction d'une facture erronée
- **Geste commercial** : Remise exceptionnelle accordée
- **Annulation partielle** : Modification d'une prestation

### Processus d'avoir
1. **Création** : Depuis une facture existante ou création directe
2. **Justification** : Motif et détail de l'avoir
3. **Validation** : Approbation par le responsable
4. **Imputation** : Application sur la facture d'origine
5. **Remboursement** : Traitement du remboursement client

## 💰 Gestion financière

### Suivi des encaissements
- **Tableau de bord** : Vue d'ensemble des créances
- **Échéancier** : Planning des paiements attendus
- **Retards** : Identification des impayés
- **Relances** : Automatisation des rappels

### Analyse commerciale
- **Chiffre d'affaires** : Évolution mensuelle et annuelle
- **Marges** : Analyse de la rentabilité par projet
- **Clients** : Performance par client et secteur
- **Produits** : Ventes par catégorie de produits

## 🔗 Intégrations

### Module Chantiers
- **Liaison directe** : Devis et factures liés aux projets
- **Suivi budgétaire** : Comparaison prévisionnel/réalisé
- **Ressources** : Affectation des équipes aux commandes

### Module Produits & Services
- **Catalogue intégré** : Sélection des produits dans les devis
- **Tarification automatique** : Application des prix clients
- **Gestion des stocks** : Mise à jour des quantités disponibles

### Module Tiers
- **Carnet d'adresses** : Clients et fournisseurs centralisés
- **Conditions commerciales** : Remises et tarifs spécifiques
- **Historique** : Suivi des relations commerciales

### Module Comptabilité
- **Export comptable** : Génération des écritures
- **TVA** : Déclarations automatisées
- **Analytique** : Répartition par centres de coûts

## 📊 Rapports et analyses

### Tableaux de bord
- **Vue d'ensemble** : Indicateurs clés de performance
- **Évolution** : Graphiques de tendances
- **Comparaisons** : Analyses périodiques

### Rapports disponibles
- **État des devis** : Suivi des propositions commerciales
- **Carnet de commandes** : Commandes en cours et à venir
- **Facturation** : Chiffre d'affaires et encaissements
- **Impayés** : Créances en retard et actions de recouvrement

### Exports
- **Excel** : Données détaillées pour analyses
- **PDF** : Documents commerciaux et rapports
- **Comptabilité** : Fichiers d'import pour logiciels comptables

## ⚙️ Configuration

### Paramètres généraux
- **Numérotation** : Format des numéros de documents
- **TVA** : Taux par défaut et gestion des régimes
- **Conditions** : Délais de paiement et pénalités
- **Modèles** : Personnalisation des documents PDF

### Droits d'accès
- **Commerciaux** : Création et modification des devis
- **Responsables** : Validation des remises et conditions
- **Comptabilité** : Accès aux factures et paiements
- **Direction** : Vue d'ensemble et analyses

## 🔐 Bonnes pratiques

### Pour les commerciaux
- ✅ Utilisez le catalogue produits pour la cohérence des prix
- ✅ Renseignez systématiquement les chantiers associés
- ✅ Vérifiez les conditions clients avant validation
- ✅ Suivez régulièrement l'état de vos devis
- ❌ N'accordez pas de remises sans autorisation

### Pour la facturation
- ✅ Vérifiez les livraisons avant facturation
- ✅ Respectez les échéances de facturation
- ✅ Contrôlez les calculs de TVA
- ✅ Archivez les justificatifs de livraison
- ❌ Ne facturez jamais sans bon de livraison

### Pour le recouvrement
- ✅ Relancez systématiquement les retards
- ✅ Négociez des échéanciers en cas de difficultés
- ✅ Documentez tous les échanges clients
- ✅ Informez la direction des impayés importants

## 🆕 Nouveautés récentes

### Fonctionnalités ajoutées
- **Intégration produits** : Liaison complète avec le catalogue
- **Calculs automatiques** : Prix et remises selon les conditions
- **Workflow amélioré** : Processus de validation optimisé
- **Rapports enrichis** : Nouvelles analyses de performance

### Améliorations prévues
- **Signature électronique** : Validation des devis en ligne
- **Facturation récurrente** : Automatisation des abonnements
- **Prévisionnel** : Outils de planification commerciale
- **CRM intégré** : Gestion de la relation client

## ❓ Support
Pour toute question sur le module Commerce : commerce-support@batistack.fr

---

**Version de la documentation** : 1.12.0  
**Dernière mise à jour** : Janvier 2025  
*Cette documentation reflète les dernières fonctionnalités de Batistack.*