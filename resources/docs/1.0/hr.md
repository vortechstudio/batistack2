# 📚 Documentation du Module Ressources Humaines

## 🏢 Présentation du module
Le module RH permet de gérer l'ensemble du cycle de vie des collaborateurs :
- 👤 Fiches salariés complètes avec identifiants uniques
- 📑 Gestion des contrats avec signature électronique
- 💰 Gestion des notes de frais avec validation sélective
- 🏦 Gestion des comptes bancaires salariés
- 🏖️ Gestion des congés et absences
- 📊 Tableau de bord RH
- 🕒 Suivi des temps et pointages

## 🚀 Premiers pas
### Accéder au module
1. Dans le menu principal, cliquez sur "Ressources Humaines"
2. Sélectionnez "Gestion des salariés"

## 👥 Gestion des salariés

### Identifiants uniques
Chaque salarié dispose désormais de :
- **UUID unique** : Identifiant universel pour la sécurité
- **Matricule RH** : Numéro d'identification interne
- **Navigation par onglets** : Interface améliorée pour accéder aux différentes informations

### Créer une fiche
1. Cliquez sur "Ajouter un salarié"
2. Renseignez les informations obligatoires (*marquées d'un astérisque*)
3. Le système génère automatiquement l'UUID et le matricule
4. Validez avec le bouton "Enregistrer"

![Capture d'écran du formulaire](/_media/rh-formulaire.png)

### Modifier un profil
1. Recherchez le salarié via la barre de recherche
2. Cliquez sur l'icône ✏️ dans la liste
3. Utilisez la navigation par onglets pour accéder aux différentes sections
4. Apportez vos modifications
5. Sauvegardez les changements

### Notifications automatiques
- **Création de salarié** : Notification automatique à l'équipe RH
- **Modifications importantes** : Alertes pour les changements de statut
- **Documents en attente** : Rappels pour les actions à effectuer

## 🏦 Gestion des comptes bancaires

### Interface de gestion
Nouvelle fonctionnalité permettant de gérer les comptes bancaires de chaque salarié :

#### Ajouter un compte bancaire
1. Accédez à la fiche salarié
2. Onglet "Informations bancaires"
3. Cliquez sur "Ajouter un compte"
4. Saisissez l'IBAN et le BIC
5. **Validation automatique** via OpenIban
6. Enregistrez les informations

#### Fonctionnalités
- **Validation IBAN/BIC** : Vérification automatique de la validité
- **Historique des modifications** : Traçabilité complète
- **Sécurité renforcée** : Chiffrement des données bancaires
- **Notifications** : Alertes en cas de modification

## 🏖️ Gestion des congés

### Nouveau module de gestion des congés
Interface Filament dédiée à la gestion des congés et absences :

#### Fonctionnalités principales
- **Demandes de congés** : Interface de saisie pour les employés
- **Validation hiérarchique** : Workflow d'approbation
- **Calendrier des absences** : Vue d'ensemble des congés
- **Soldes de congés** : Suivi des droits et consommations

#### Types de congés supportés
- Congés payés
- RTT
- Congés maladie
- Congés formation
- Congés exceptionnels

#### Workflow de validation
1. **Demande** : L'employé saisit sa demande
2. **Validation** : Le responsable approuve ou refuse
3. **Notification** : Information automatique des parties
4. **Planification** : Intégration au planning général

## 📑 Module contrat avec signature électronique

### Types de contrats disponibles
- CDI
- CDD
- Stage
- Alternance

### Workflow de validation amélioré
1. **Création de la fiche employé**
2. **Dépôt des documents obligatoires** :
   - Contrat de travail
   - Pièce d'identité
   - RIB
3. **Demande de DPAE** (Validation par l'expert RH/API)
4. **Établissement du contrat** :
   - Rédaction
   - Vérification juridique
5. **Signature électronique** :
   - **Intégration Yousign** : Service de signature sécurisée
   - **Vérification SMS (OTP)** : Code de sécurité par SMS
   - **Pad de signature** : Signature en ligne
   - **Compte à rebours** : Délai de signature (72h)
6. **Activation du compte** :
   - Génération identifiants
   - Configuration accès
7. **Envoi des informations** :
   - Email de bienvenue
   - Guide utilisateur
8. **État Actif** :
   - Contrat signé électroniquement
   - Documents validés
   - Compte opérationnel

### Signature électronique sécurisée
- **Service Yousign** : Plateforme certifiée de signature électronique
- **Vérification par SMS** : Code OTP pour sécuriser la signature
- **Traçabilité complète** : Historique de toutes les signatures
- **Validité juridique** : Conformité aux réglementations européennes

## 💰 Gestion des notes de frais avec validation sélective

### Vue d'ensemble
Le système de notes de frais permet aux employés de soumettre leurs frais professionnels pour remboursement. Le processus comprend la création, la soumission, la validation sélective et le paiement des notes de frais.

### Workflow des notes de frais
1. **Brouillon** 📝 : Création et modification libre par l'employé
2. **Soumise** ✈️ : Envoyée pour validation (plus modifiable)
3. **Validée** ✅ : Approuvée par le responsable (totalement ou partiellement)
4. **Refusée** ❌ : Rejetée avec commentaires
5. **Payée** 💳 : Remboursement effectué

### Types de frais supportés
- **Transport** 🚛 : Déplacements professionnels, kilométrage
- **Hébergement** 🏠 : Nuitées d'hôtel, logement temporaire
- **Restauration** 🍰 : Repas d'affaires, frais de bouche
- **Carburant** 🔥 : Essence, diesel (avec kilométrage)
- **Péage** 💰 : Frais d'autoroute
- **Parking** 🅿️ : Stationnement
- **Matériel** 🔧 : Outils, équipements professionnels
- **Formation** 🎓 : Frais de formation, séminaires
- **Communication** 📞 : Téléphone, internet mobile
- **Autre** ⚡ : Frais divers non catégorisés

### Modes de paiement
- **Espèces** 💵 : Paiement en liquide
- **Carte bancaire** 💳 : Carte personnelle
- **Chèque** 📄 : Paiement par chèque
- **Virement** ➡️ : Virement bancaire
- **Carte entreprise** 🏢 : Carte de l'entreprise
- **Avance sur frais** ⬆️ : Avance remboursable
- **Autre** ❓ : Autre mode de paiement

### Créer une note de frais
1. Accédez à "Notes de frais" dans le module RH
2. Cliquez sur "Nouvelle note de frais"
3. La note est créée automatiquement avec un numéro unique (format : NF-YYYY-XXXX)
4. Ajoutez vos détails de frais un par un

### Ajouter un détail de frais
1. Dans votre note de frais, cliquez sur "Ajouter un frais"
2. Sélectionnez le **type de frais** approprié
3. Renseignez les informations obligatoires :
   - **Date du frais**
   - **Libellé** descriptif
   - **Montant HT**
   - **Taux de TVA**
   - **Mode de paiement**
4. Informations optionnelles selon le type :
   - **Chantier** associé
   - **Lieu de départ/arrivée** (transport)
   - **Kilométrage** (transport, carburant)
   - **Commentaires**
5. **Justificatif** : Joignez le reçu/facture (obligatoire sauf transport)
6. Cochez **"Remboursable"** si le frais doit être remboursé

### Justificatifs
- **Obligatoires** pour tous les types sauf transport
- Formats acceptés : PDF, images (JPG, PNG)
- Stockage sécurisé avec gestion des médias
- Possibilité de supprimer/remplacer un justificatif

### Soumettre une note de frais
1. Vérifiez que tous les détails sont corrects
2. Cliquez sur "Soumettre la note"
3. ⚠️ **Attention** : Une fois soumise, la note ne peut plus être modifiée
4. La note passe au statut "Soumise" et attend validation

### 🆕 Validation sélective par le responsable

#### Nouvelle fonctionnalité de validation
Le responsable RH peut désormais valider partiellement une note de frais :

1. **Interface de validation améliorée** :
   - Liste détaillée de tous les frais avec format : `{date - libellé - montant}`
   - Cases à cocher pour sélectionner les frais à valider
   - Montant total affiché pour information

2. **Validation sélective** :
   - Sélectionnez uniquement les frais professionnels à rembourser
   - Les frais non sélectionnés sont automatiquement marqués comme "Frais personnel non remboursable"
   - Le montant validé est recalculé automatiquement

3. **Traitement automatique** :
   - **Frais validés** : Marqués comme remboursables
   - **Frais refusés** : Commentaire automatique "Frais personnel non remboursable"
   - **Recalcul du montant** : Seuls les frais sélectionnés sont pris en compte

#### Avantages de la validation sélective
- **Flexibilité** : Validation partielle des notes de frais
- **Transparence** : Distinction claire entre frais professionnels et personnels
- **Automatisation** : Traitement automatique des frais non validés
- **Traçabilité** : Historique complet des décisions de validation

### Paiement
1. Les notes validées peuvent être marquées comme "Payées"
2. Le responsable RH effectue cette action après remboursement
3. Le montant payé correspond au montant validé (peut être partiel)
4. Suivi complet de l'historique des paiements

### Fonctionnalités avancées

#### Calculs automatiques
- **Montant TTC** calculé automatiquement (HT + TVA)
- **Total de la note** mis à jour en temps réel
- **Montant validé** recalculé selon la sélection
- **Kilométrage** pour les frais de transport et carburant

#### Filtres et recherche
- Filtrage par statut, période, employé
- Recherche par numéro de note ou libellé
- Export des données pour comptabilité

#### Notifications
- Email automatique lors des changements de statut
- Rappels pour les notes en attente
- Notifications de validation/refus avec détail des frais

### Bonnes pratiques

#### Pour les employés
- ✅ Conservez tous vos justificatifs
- ✅ Saisissez vos frais rapidement après la dépense
- ✅ Soyez précis dans les libellés
- ✅ Vérifiez les montants avant soumission
- ✅ Distinguez clairement les frais professionnels des frais personnels
- ❌ Ne soumettez pas de frais personnels

#### Pour les responsables
- ✅ Validez les notes dans les délais
- ✅ Vérifiez la cohérence des justificatifs
- ✅ Utilisez la validation sélective pour les frais mixtes
- ✅ Ajoutez des commentaires en cas de refus
- ✅ Suivez les politiques de remboursement

### Règles de gestion
- **Numérotation automatique** : Format NF-YYYY-XXXX
- **Unicité** : Chaque note a un numéro unique
- **Validation sélective** : Possibilité de valider partiellement
- **Traçabilité** : Historique complet des modifications
- **Sécurité** : Accès contrôlé selon les rôles
- **Archivage** : Conservation des justificatifs

### Rapports et statistiques
- Suivi des frais par employé/période
- Analyse des types de frais les plus fréquents
- Taux de validation par responsable
- Montants remboursés vs montants demandés
- Temps moyen de traitement des notes

## 💼 Tables de paie et congés

### Gestion des fiches de paie
Nouveaux tableaux dédiés dans l'administration RH :
- **Historique des paies** : Consultation de toutes les fiches de paie
- **Génération automatique** : Création des bulletins selon les profils
- **Export comptable** : Intégration avec les logiciels de comptabilité

### Gestion des droits à congés
- **Calcul automatique** : Droits acquis selon l'ancienneté
- **Suivi des soldes** : Congés pris vs congés disponibles
- **Reports** : Gestion des congés non pris
- **Planification** : Vue d'ensemble des absences

## 📊 Tableau de bord
Accédez aux indicateurs clés :
- Effectif total avec répartition par statut
- Taux de turnover
- Absences à venir et congés planifiés
- Contrats arrivant à échéance
- Notes de frais en attente de validation
- Signatures de contrats en cours

## 🔐 Bonnes pratiques
- Vérifier régulièrement les dates d'échéance des contrats
- Utiliser le masque de saisie pour les numéros de sécurité sociale
- Valider les comptes bancaires avec OpenIban
- Utiliser la validation sélective pour optimiser les remboursements
- Former les équipes à la signature électronique
- Exporter régulièrement les données sensibles

## 🆕 Nouveautés récentes

### Version 1.11.0 (Juillet 2025)
- ✨ **Gestion des comptes bancaires salariés** avec validation IBAN/BIC
- ✨ **Module de gestion des congés** avec interface Filament
- ✨ **Tables de paie et congés** dans l'administration RH
- ✨ **Notifications RH automatiques** pour les nouveaux salariés

### Version 1.9.0 (Juillet 2025)
- ✨ **Navigation par onglets** dans les fiches salariés
- ✨ **Identifiants uniques** (UUID et matricule) pour chaque salarié
- ✨ **Workflow complet de validation** et signature des contrats

### Version 1.8.0 (Juillet 2025)
- ✨ **Signature électronique** avec Yousign
- ✨ **Vérification SMS (OTP)** pour sécuriser les signatures
- ✨ **Pad de signature** en ligne

## ❓ Support
Pour toute question, contactez l'équipe RH : rh-support@batistack.fr

---

**Version de la documentation** : 1.12.0  
**Dernière mise à jour** : Janvier 2025  
*Cette documentation reflète les dernières fonctionnalités de Batistack.*
