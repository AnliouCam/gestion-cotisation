# Application de Transparence Financière
## Église / Mosquée / Association

---

## 🎯 Objectif du projet

Créer une application web simple et moderne permettant aux églises, mosquées et associations de :

- Gérer leurs membres
- Suivre les cotisations liées aux événements
- Enregistrer les dépenses
- Garantir une **transparence financière totale**
- Éviter les conflits, soupçons et détournements d’argent

L’application est pensée pour être :
- simple à utiliser
- accessible même aux personnes non techniques
- 100 % transparente pour les membres

---

## 🏛️ Type d’application

- Une application = une organisation
- Pas de multi-associations
- Installation dédiée par église / mosquée / association
- Données isolées et sécurisées

---

## 👥 Utilisateurs & rôles

### Rôles
- **Admin**
- **Membre**

### Philosophie des droits
- Tous les membres peuvent **voir** :
  - événements
  - cotisations
  - dépenses
  - soldes et historiques
- Seul l’admin peut :
  - ajouter / modifier des membres
  - créer des événements
  - enregistrer des cotisations
  - enregistrer ou annuler des dépenses
  - clôturer les événements

---

## 👤 Gestion des membres

### Informations d’un membre
- Nom complet
- Numéro de téléphone
- Date d’adhésion
- Statut : Actif / Inactif

### Règles
- Les membres ne peuvent pas s’ajouter eux-mêmes
- Un membre peut se connecter et consulter les informations (lecture seule)

---

## 🎉 Gestion des événements (cœur du système)

### Exemple
> Cotisation pour construction du temple  
> Cotisation pour événement religieux  
> Cotisation pour aide sociale

### Champs
- Nom de l’événement
- Description
- Date de début
- Date de fin
- Statut : Actif / Terminé

### Règles
- Toutes les cotisations et dépenses sont liées à un événement
- Un événement terminé devient en lecture seule

---

## 💰 Cotisations

### Principes
- Montant libre (chacun donne ce qu’il peut)
- Cotisation liée à un événement
- Enregistrement manuel par l’admin

### Données enregistrées
- Membre
- Événement
- Montant
- Date
- Commentaire (optionnel)
- Statut : Actif / Annulé

### Transparence
- Toutes les cotisations sont visibles par tous les membres

---

## 📉 Dépenses

### Principes
- Dépenses liées à un événement
- Justificatif optionnel
- Aucune suppression possible

### Données enregistrées
- Événement
- Catégorie (achat, location, aide, autre)
- Montant
- Description
- Justificatif (optionnel)
- Statut : Actif / Annulé

---

## 🔄 Annulation (sécurité)

- Une cotisation ou dépense ne peut **jamais être supprimée**
- Elle peut seulement être **annulée**
- L’historique reste visible
- Les montants annulés ne sont pas comptabilisés

---

## 📊 Tableaux de bord & rapports

### Dashboard (Admin & Membres)
- Événements en cours
- Total collecté par événement
- Total dépensé
- Solde restant

### Rapports disponibles
- Historique par événement
- Historique des cotisations
- Historique des dépenses

---

## 🔔 Notifications (MVP)

- Notification interne :
  - nouvel événement
  - nouvelle cotisation
  - nouvelle dépense

---

## 🎨 Interface & UX

### Style
- Moderne
- Sobre
- Professionnel
- Axé lisibilité et confiance

### Technologies Front
- Blade
- Tailwind CSS
- Alpine.js

---

## ⚙️ Stack technique

- Laravel
- Blade
- Tailwind CSS
- Alpine.js
- Base de données MySQL / PostgreSQL

---

## 🧱 Entités principales

### Tables (TOUT EN FRANÇAIS)
- **utilisateurs** (users + members fusionnés en une seule table)
- **evenements** (events)
- **cotisations** (contributions)
- **depenses** (expenses)
- **categories** (categories)

### Règle importante
**UN MEMBRE = UN UTILISATEUR** : Pas de table members séparée. Tous les utilisateurs ont une date d'adhésion et un statut.

---

## 🗃️ STRUCTURE DE LA BASE DE DONNÉES

### Table `utilisateurs`
```
- id
- nom (nom complet)
- telephone (unique)
- mot_de_passe
- role (enum: 'admin', 'membre')
- date_adhesion
- statut (enum: 'actif', 'inactif')
- doit_changer_mot_de_passe (boolean)
- created_at, updated_at
```

### Table `evenements`
```
- id
- nom
- description (text)
- date_debut
- date_fin
- statut (enum: 'actif', 'termine')
- created_at, updated_at
```

### Table `cotisations`
```
- id
- utilisateur_id (foreign key)
- evenement_id (foreign key)
- montant (decimal)
- date
- commentaire (nullable text)
- statut (enum: 'actif', 'annule')
- motif_annulation (nullable)
- annule_le (nullable datetime)
- cree_par (utilisateur_id)
- created_at, updated_at
```

### Table `depenses`
```
- id
- evenement_id (foreign key)
- categorie_id (foreign key)
- montant (decimal)
- description (text)
- justification (nullable text - descriptif uniquement)
- statut (enum: 'actif', 'annule')
- motif_annulation (nullable)
- annule_le (nullable datetime)
- cree_par (utilisateur_id)
- created_at, updated_at
```

### Table `categories`
```
- id
- nom (achat, location, aide, autre...)
- created_at, updated_at
```

### Relations
```
Utilisateur (1) → (N) Cotisations
Utilisateur (1) → (N) Dépenses (via cree_par)
Événement (1) → (N) Cotisations
Événement (1) → (N) Dépenses
Catégorie (1) → (N) Dépenses
```

---

## 🚀 MVP (version 1)

### Inclus
- Authentification
- Gestion des membres
- Gestion des événements
- Cotisations (montant libre)
- Dépenses
- Dashboard de transparence
- Annulation (pas de suppression)

### Exclu (pour plus tard)
- Paiement mobile money
- SMS / WhatsApp
- Multi-organisations
- Exports avancés
- Upload de fichiers (logo, justificatifs)
- Email (uniquement téléphone)

---

## ⚙️ DÉCISIONS TECHNIQUES & AJUSTEMENTS

### Nommage
- **TOUT EN FRANÇAIS** : Tables, colonnes, variables, méthodes
- Conventions Laravel respectées (snake_case pour DB, camelCase pour code)

### Authentification
- **Champs de connexion** : Téléphone + Mot de passe (pas d'email)
- **Changement obligatoire** du mot de passe à la première connexion
- Mot de passe généré automatiquement lors de la création d'un utilisateur

### Devise
- **FCFA fixe** pour le MVP (pas de sélection)

### Catégories
- **Table dynamique** (pas enum fixe)
- L'admin peut gérer les catégories (CRUD)
- Catégories de base : Achat, Location, Aide, Autre

### Justificatifs
- **Texte descriptif uniquement** (pas d'upload de fichiers)
- Champ texte libre dans la table dépenses

### Notifications
- **Interface uniquement** (messages flash)
- Pas de stockage en base de données pour le MVP

### Rôles
- **Concepteur** : Toi (créateur des comptes responsables, accès total)
- **Admin/Responsable** : Gestion complète de l'organisation
- **Membre** : Lecture seule totale

---

## 💡 Vision long terme

- Paiement Mobile Money
- Rapports PDF
- Historique annuel
- Version SaaS
- Application mobile

---

## 🧠 Valeur clé du projet

> « Toute personne qui donne peut voir clairement où va l’argent. »

La confiance est la fonctionnalité principale de l’application.




# WORKFLOW COMPLET
## Application de Transparence Financière
## Église / Mosquée / Association

---

## 👑 RÔLES DU SYSTÈME

### 1. Concepteur (Toi / Créateur de l'application)
- Crée les comptes des responsables
- Accès complet à l'application
- C'est une application normale, pas de système multi-organisation
- Tu es le concepteur qui met en place l'application

### 2. Responsable / Admin (Église, Mosquée, Association)
- Gère les membres (utilisateurs)
- Gère les événements
- Enregistre cotisations et dépenses
- Garant de la transparence
- Compte créé par le concepteur avec mot de passe temporaire

### 3. Membre
- Consulte toutes les informations
- Suit l'utilisation de l'argent
- Lecture seule (aucune modification)
- Compte créé par le responsable avec mot de passe généré

---

## 🟢 WORKFLOW 0 — CRÉATION DU COMPTE RESPONSABLE (CONCEPTEUR)

### Action Concepteur
1. Installation de l'application Laravel
2. Configuration de la base de données
3. Création du premier compte admin (via seeder ou commande artisan)
4. Renseigner :
   - Nom du responsable
   - Téléphone (unique)
   - Mot de passe temporaire
   - Role : admin
   - doit_changer_mot_de_passe : true

### Résultat
- Le compte responsable est créé
- L'application est prête à être utilisée
- Le responsable peut se connecter avec ses identifiants

---

## 🟢 WORKFLOW 1 — PREMIÈRE CONNEXION DU RESPONSABLE

### Action Responsable
1. Connexion avec téléphone + mot de passe temporaire
2. Redirection forcée vers page "Changer mot de passe"
3. Définir un nouveau mot de passe
4. Redirection vers le dashboard admin

### Résultat
- Le responsable a changé son mot de passe
- Accès au dashboard admin
- Prêt à commencer la gestion

---

## 🟢 WORKFLOW 2 — CRÉATION DES MEMBRES

### Action Responsable
1. Menu : `Utilisateurs` ou `Membres`
2. Bouton : `Ajouter un membre`
3. Champs :
   - Nom complet
   - Numéro de téléphone (unique)
   - Date d'adhésion
4. Enregistrer

### Résultat
- Le membre est créé dans la table `utilisateurs`
- Statut : Actif
- Role : membre
- Mot de passe généré automatiquement
- doit_changer_mot_de_passe : true
- Le responsable communique le téléphone et mot de passe au membre

---

## 🟢 WORKFLOW 3 — CRÉATION D’UN ÉVÉNEMENT

### Action Responsable
1. Menu : `Événements`
2. Bouton : `Créer un événement`
3. Renseigner :
   - Nom de l’événement
   - Description
   - Date de début
   - Date de fin
4. Statut : Actif

### Résultat
- L’événement est visible par tous les membres
- Les cotisations peuvent commencer

---

## 🟢 WORKFLOW 4 — ENREGISTREMENT DES COTISATIONS

### Contexte réel
- Cotisation pour un événement précis
- Montant libre (chacun donne ce qu’il peut)

### Action Responsable
1. Menu : `Cotisations`
2. Sélectionner :
   - Membre
   - Événement
   - Montant
   - Date
3. Ajouter un commentaire (optionnel)
4. Enregistrer

### Résultat
- La cotisation est visible par tous
- Le total collecté est mis à jour
- Transparence immédiate

---

## 🟢 WORKFLOW 5 — CONSULTATION CÔTÉ MEMBRE

### Action Membre
1. Connexion à l’application
2. Accès au dashboard

### Le membre peut voir :
- Tous les événements
- Total collecté par événement
- Liste des cotisations
- Liste des dépenses
- Solde restant

### Restrictions
- Lecture seule
- Aucune modification possible

---

## 🟢 WORKFLOW 6 — ENREGISTREMENT DES DÉPENSES

### Exemple
> Achat de matériel / aide sociale / location

### Action Responsable
1. Menu : `Dépenses`
2. Sélectionner :
   - Événement concerné
   - Catégorie
   - Montant
   - Description
3. Ajouter un justificatif (optionnel)
4. Enregistrer

### Résultat
- Dépense visible par tous
- Solde automatiquement recalculé

---

## 🟢 WORKFLOW 7 — ANNULATION (ERREUR OU RECTIFICATION)

### Règle importante
- Aucune suppression définitive

### Action Responsable
1. Ouvrir une cotisation ou dépense
2. Cliquer sur `Annuler`
3. Motif d’annulation (optionnel)

### Résultat
- Statut : ANNULÉ
- Ligne toujours visible
- Montant retiré des calculs
- Historique intact

---

## 🟢 WORKFLOW 8 — CLÔTURE D’UN ÉVÉNEMENT

### Action Responsable
1. Ouvrir l’événement
2. Cliquer sur `Clôturer`

### Résultat
- Statut : Terminé
- Lecture seule
- Historique archivé
- Toujours consultable par les membres

---

## 🟢 WORKFLOW 9 — CONTRÔLE & TRANSPARENCE CONTINUE

### À tout moment
- Les membres peuvent :
  - vérifier les montants
  - comparer entrées et sorties
  - suivre l’utilisation de l’argent

### Effet recherché
- Zéro suspicion
- Confiance totale
- Responsables protégés contre les accusations

---

## 🔐 PRINCIPES DE SÉCURITÉ

- Historique non supprimable
- Traçabilité totale
- Rôles stricts
- Données isolées par organisation

---

## 🧠 PHILOSOPHIE DU PRODUIT

> « Celui qui donne doit pouvoir voir clairement
> où va chaque franc. »

La transparence est la fonctionnalité principale.

---

## 📚 FICHIERS DE RÉFÉRENCE

- **plan.md** (ce fichier) : Vision globale, fonctionnalités, workflows
- **claude.md** : Règles strictes et conventions du projet
- **etat_avancement.md** : Suivi détaillé du développement avec checklist

---

**Dernière mise à jour** : 2025-12-22
**Ajustements** :
- Structure en français (tables, colonnes)
- Un membre = un utilisateur (table unique)
- Authentification par téléphone + mot de passe
- Catégories dynamiques (table CRUD)
- Justificatifs en texte uniquement

