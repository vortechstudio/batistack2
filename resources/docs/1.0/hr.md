# 📚 Documentation du Module Ressources Humaines

## 🏢 Présentation du module
Le module RH permet de gérer l'ensemble du cycle de vie des collaborateurs :
- 👤 Fiches salariés complètes
- 📑 Gestion des contrats
- 💰 Gestion des notes de frais
- 📊 Tableau de bord RH
- 🕒 Suivi des temps et absences

## 🚀 Premiers pas
### Accéder au module
1. Dans le menu principal, cliquez sur "Ressources Humaines"
2. Sélectionnez "Gestion des salariés"

## 👥 Gestion des salariés
### Créer une fiche
1. Cliquez sur "Ajouter un salarié"
2. Renseignez les informations obligatoires (*marquées d'un astérisque*)
3. Validez avec le bouton "Enregistrer"

![Capture d'écran du formulaire](/_media/rh-formulaire.png)

### Modifier un profil
1. Recherchez le salarié via la barre de recherche
2. Cliquez sur l'icône ✏️ dans la liste
3. Apportez vos modifications
4. Sauvegardez les changements

## 📑 Module contrat
### Types de contrats disponibles
- CDI
- CDD
- Stage
- Alternance

### Workflow de validation
1. **Création de la fiche employé**
2. **Dépôt des documents obligatoires** :
   - Contrat de travail
   - Pièce d'identité
   - RIB
3. **Demande de DAE** (Validation par l'expert RH/API)
4. **Établissement du contrat** :
   - Rédaction
   - Vérification juridique
5. **Alerte employé** :
   - Notification email
   - Échéancier de signature (72h)
6. **Activation du compte** :
   - Génération identifiants
   - Configuration accès
7. **Envoi des informations** :
   - Email de bienvenue
   - Guide utilisateur
8. **État Actif** :
   - Contrat signé
   - Documents validés
   - Compte opérationnel

*Les étapes 5 et 7 sont automatiques après validation*

## 💰 Gestion des notes de frais

### Vue d'ensemble
Le système de notes de frais permet aux employés de soumettre leurs frais professionnels pour remboursement. Le processus comprend la création, la soumission, la validation et le paiement des notes de frais.

### Workflow des notes de frais
1. **Brouillon** 📝 : Création et modification libre par l'employé
2. **Soumise** ✈️ : Envoyée pour validation (plus modifiable)
3. **Validée** ✅ : Approuvée par le responsable
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

### Validation par le responsable
- Seuls les responsables RH peuvent valider/refuser
- Possibilité d'ajouter des commentaires lors du refus
- Les notes validées passent au statut "Validée"
- Les notes refusées repassent en "Brouillon" pour correction

### Paiement
1. Les notes validées peuvent être marquées comme "Payées"
2. Le responsable RH effectue cette action après remboursement
3. Suivi complet de l'historique des paiements

### Fonctionnalités avancées

#### Calculs automatiques
- **Montant TTC** calculé automatiquement (HT + TVA)
- **Total de la note** mis à jour en temps réel
- **Kilométrage** pour les frais de transport et carburant

#### Filtres et recherche
- Filtrage par statut, période, employé
- Recherche par numéro de note ou libellé
- Export des données pour comptabilité

#### Notifications
- Email automatique lors des changements de statut
- Rappels pour les notes en attente
- Notifications de validation/refus

### Bonnes pratiques

#### Pour les employés
- ✅ Conservez tous vos justificatifs
- ✅ Saisissez vos frais rapidement après la dépense
- ✅ Soyez précis dans les libellés
- ✅ Vérifiez les montants avant soumission
- ❌ Ne soumettez pas de frais personnels

#### Pour les responsables
- ✅ Validez les notes dans les délais
- ✅ Vérifiez la cohérence des justificatifs
- ✅ Ajoutez des commentaires en cas de refus
- ✅ Suivez les politiques de remboursement

### Règles de gestion
- **Numérotation automatique** : Format NF-YYYY-XXXX
- **Unicité** : Chaque note a un numéro unique
- **Traçabilité** : Historique complet des modifications
- **Sécurité** : Accès contrôlé selon les rôles
- **Archivage** : Conservation des justificatifs

### Rapports et statistiques
- Suivi des frais par employé/période
- Analyse des types de frais les plus fréquents
- Temps moyen de traitement des notes
- Montants remboursés par catégorie

*Les étapes 5 et 7 sont automatiques après validation*

## 📊 Tableau de bord
Accédez aux indicateurs clés :
- Effectif total
- Taux de turnover
- Absences à venir
- Contrats arrivant à échéance

## 🔐 Bonnes pratiques
- Vérifier régulièrement les dates d'échéance des contrats
- Utiliser le masque de saisie pour les numéros de sécurité sociale
- Exporter régulièrement les données sensibles

## ❓ Support
Pour toute question, contactez l'équipe RH : rh-support@batistack.fr
