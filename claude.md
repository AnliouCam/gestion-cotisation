# RÈGLES & CONVENTIONS DU PROJET
## Application de Transparence Financière

---

## 🎯 VISION DU PROJET

> « Toute personne qui donne doit pouvoir voir clairement où va chaque franc. »

**Objectif principal** : Garantir une transparence financière totale pour éviter les conflits, soupçons et détournements dans les organisations religieuses et associatives.

---

## 🏗️ ARCHITECTURE GLOBALE

### Type d'application
- **Mono-organisation** : Une instance = une organisation
- **Pas de multi-tenant**
- Installation dédiée par église/mosquée/association
- Données isolées et sécurisées

### Stack technique
```
Backend  : Laravel
Frontend : Blade + Tailwind CSS + Alpine.js
Database : MySQL / PostgreSQL
Devise   : FCFA (fixe)
Langue   : Français (partout)
```

---

## 📐 RÈGLES DE NOMMAGE

### Base de données
**TOUT EN FRANÇAIS** - Tables, colonnes, relations

```
✅ CORRECT :
- utilisateurs (pas users)
- evenements (pas events)
- cotisations (pas contributions)
- depenses (pas expenses)
- mot_de_passe (pas password)
- date_adhesion (pas membership_date)

❌ INCORRECT :
- users
- events
- contributions
- password
- created_by
```

### Models Laravel
```php
// Noms de classes en français (PascalCase)
Utilisateur.php
Evenement.php
Cotisation.php
Depense.php
Categorie.php
```

### Variables et méthodes
```php
// Français avec camelCase
$utilisateur->dateAdhesion
$evenement->estTermine()
$cotisation->montantTotal()
```

---

## 👥 STRUCTURE DES UTILISATEURS

### Règle fondamentale
**UN MEMBRE = UN UTILISATEUR**
- Pas de table `members` séparée
- Table unique `utilisateurs`
- Tous les utilisateurs ont : date_adhesion + statut

### Rôles
```php
enum Role {
    'admin'   // Responsable de l'organisation
    'membre'  // Membre simple (lecture seule)
}
```

### Champs utilisateur
```
- id
- nom (nom complet)
- telephone (unique - utilisé pour la connexion)
- mot_de_passe
- role (admin/membre)
- date_adhesion
- statut (actif/inactif)
- doit_changer_mot_de_passe (boolean)
```

---

## 🔐 AUTHENTIFICATION

### Connexion
```
Champs requis :
- Téléphone (pas d'email)
- Mot de passe

Processus :
1. Utilisateur entre téléphone + mot de passe
2. Si doit_changer_mot_de_passe = true → redirection forcée
3. Sinon → dashboard selon le rôle
```

### Création d'un utilisateur
```
1. Le concepteur crée le premier admin
2. L'admin crée les membres
3. Mot de passe généré automatiquement
4. doit_changer_mot_de_passe = true par défaut
5. L'utilisateur change son mot de passe à la première connexion
```

---

## 🎯 PHILOSOPHIE DES DROITS

### Admin peut
- ✅ Créer/modifier/désactiver les utilisateurs
- ✅ Créer/modifier/clôturer les événements
- ✅ Enregistrer les cotisations
- ✅ Enregistrer les dépenses
- ✅ Annuler cotisations et dépenses
- ✅ Gérer les catégories
- ✅ Voir tous les rapports

### Membre peut
- ✅ Voir tous les événements
- ✅ Voir toutes les cotisations (qui a donné combien)
- ✅ Voir toutes les dépenses (ce qui a été dépensé)
- ✅ Voir tous les soldes et historiques
- ❌ AUCUNE modification

---

## 🔒 RÈGLES DE SÉCURITÉ CRITIQUES

### 1. AUCUNE SUPPRESSION
```
❌ INTERDIT :
- Supprimer une cotisation
- Supprimer une dépense

✅ AUTORISÉ :
- Annuler (statut passe à 'annule')
- L'historique reste visible
- Le montant est exclu des calculs
```

### 2. Traçabilité
```
Chaque cotisation/dépense stocke :
- cree_par (utilisateur_id)
- created_at
- Si annulée : motif_annulation + annule_le
```

### 3. Événement terminé
```
Quand statut = 'termine' :
- Lecture seule totale
- Impossible d'ajouter cotisations/dépenses
- Impossible de modifier
- Toujours consultable
```

### 4. Validation des montants
```php
// Toujours vérifier
$montant > 0
$montant est numérique
```

---

## 🧮 CALCULS FINANCIERS

### Formules
```php
// Total cotisations d'un événement
SUM(cotisations.montant)
WHERE evenement_id = X
AND statut = 'actif'

// Total dépenses d'un événement
SUM(depenses.montant)
WHERE evenement_id = X
AND statut = 'actif'

// Solde
Total cotisations actives - Total dépenses actives
```

### Règles
- **Exclure les annulées** des calculs
- Calcul en temps réel
- Affichage immédiat après modification

---

## 📊 STRUCTURE DES ENTITÉS

### Tables principales
```
1. utilisateurs (users + members fusionnés)
2. evenements (cœur du système)
3. cotisations (liées à utilisateur + événement)
4. depenses (liées à événement + catégorie)
5. categories (dynamique, CRUD)
```

### Relations
```
Utilisateur → Cotisations (1:N)
Utilisateur → Dépenses via cree_par (1:N)
Événement → Cotisations (1:N)
Événement → Dépenses (1:N)
Catégorie → Dépenses (1:N)
```

---

## 🎨 CONVENTIONS UI/UX

### Principes
- Simple et sobre
- Professionnel
- Accessible (utilisateurs non techniques)
- Transparence visuelle (tout est clair)

### Catégories de dépenses
```
Table dynamique (pas enum fixe)
Valeurs de base :
- Achat
- Location
- Aide
- Autre

L'admin peut ajouter/modifier/supprimer (si non utilisée)
```

### Justificatifs
```
Type : Texte descriptif uniquement
Pas d'upload de fichiers (MVP)
Exemple : "Facture magasin XYZ ref #123"
```

### Notifications
```
Affichage interface uniquement
Pas de stockage en base de données
Messages flash temporaires
```

---

## 🚫 CE QU'ON NE FAIT PAS (MVP)

- ❌ Multi-organisations
- ❌ Upload de fichiers (logo, justificatifs)
- ❌ Mobile Money
- ❌ SMS/WhatsApp
- ❌ Exports PDF avancés
- ❌ Application mobile
- ❌ Email (uniquement téléphone)
- ❌ Notifications stockées en base

---

## 📢 COMMUNICATION LORS DU DÉVELOPPEMENT

### Règle importante
**TOUJOURS expliquer ce qu'on va faire AVANT de développer une fonctionnalité**

Avant de commencer à coder une nouvelle fonctionnalité :

1. **Annoncer clairement** ce qui va être fait
2. **Lister les fichiers** qui vont être créés ou modifiés
3. **Expliquer l'approche** technique choisie
4. **Utiliser TodoWrite** pour créer une liste de tâches si nécessaire

### Exemple de communication
```
Je vais maintenant créer la fonctionnalité de gestion des membres.

Voici ce que je vais faire :
1. Créer le controller UtilisateurController avec les méthodes CRUD
2. Créer les vues :
   - utilisateurs/index.blade.php (liste)
   - utilisateurs/create.blade.php (formulaire création)
   - utilisateurs/edit.blade.php (formulaire édition)
3. Créer les routes dans web.php
4. Créer le middleware IsAdmin pour sécuriser les routes
5. Ajouter la validation des données

Approche : Je vais utiliser un Resource Controller et générer automatiquement
un mot de passe sécurisé lors de la création d'un utilisateur.
```

### Objectif
- Permettre au développeur/client de comprendre où on va
- Permettre de corriger l'approche avant de perdre du temps
- Documenter le processus de développement
- Faciliter la collaboration

---

## 🧪 TESTS APRÈS CHAQUE FONCTIONNALITÉ

### Règle importante
**TOUJOURS tester après chaque fonctionnalité, PAS à la fin d'une phase complète**

### Pourquoi ?
- Les phases contiennent plusieurs fonctionnalités
- Tester à la fin risque de rater des bugs
- Plus facile de corriger immédiatement que plus tard
- Évite l'accumulation de problèmes

### Quand tester ?
**Après chaque fonctionnalité complète**, par exemple :
- ✅ Créé le middleware IsAdmin → **TESTER**
- ✅ Créé le formulaire de création → **TESTER**
- ✅ Créé l'activation/désactivation → **TESTER**

**Pas comme ça** :
- ❌ Créé toute la phase 5 (8 fonctionnalités) → Tester tout à la fin

### Comment documenter les tests ?
- Utiliser le fichier `TEST_PHASE.md`
- Ajouter un scénario de test pour chaque fonctionnalité
- Cocher les tests au fur et à mesure

### Exemple
```
Phase 5 : Gestion des Utilisateurs
├─ Fonctionnalité 1 : Middleware IsAdmin
│  └─ ✅ TESTER → Créer scénario test 1
├─ Fonctionnalité 2 : Création membre
│  └─ ✅ TESTER → Créer scénario test 2
├─ Fonctionnalité 3 : Modification membre
│  └─ ✅ TESTER → Créer scénario test 3
...
```

---

## ✅ CHECKLIST AVANT CHAQUE FEATURE

Avant de coder, vérifier :

1. **Nommage**
   - [ ] Tout est en français ?
   - [ ] Conventions respectées ?

2. **Sécurité**
   - [ ] Pas de suppression, seulement annulation ?
   - [ ] Vérification du rôle (admin only) ?
   - [ ] Validation des données ?

3. **Traçabilité**
   - [ ] On sait qui a créé ?
   - [ ] On sait quand ?
   - [ ] L'historique est préservé ?

4. **Transparence**
   - [ ] Les membres peuvent voir ?
   - [ ] Les calculs sont corrects ?
   - [ ] Tout est clair et visible ?

5. **Événement terminé**
   - [ ] Respect de la lecture seule ?

---

## 🎯 PRIORITÉS DU PROJET

1. **Transparence** avant tout
2. **Simplicité** d'utilisation
3. **Sécurité** des données (traçabilité)
4. **Performance** acceptable (pas critique)

---

## 📝 WORKFLOW TYPIQUE

```
1. Concepteur crée compte Responsable
2. Responsable se connecte, change mot de passe
3. Responsable crée catégories de base
4. Responsable crée les membres
5. Responsable crée un événement
6. Responsable enregistre les cotisations
7. Membres consultent en temps réel
8. Responsable enregistre les dépenses
9. Tout le monde voit le solde mis à jour
10. Si erreur → Annulation (pas suppression)
11. Événement terminé → Clôture (lecture seule)
```

---

## 🔧 COMMANDES UTILES

```bash
# Créer le premier admin (à créer)
php artisan make:admin

# Reset base de données
php artisan migrate:fresh --seed

# Lancer l'application
php artisan serve
```

---

## 💡 POINTS D'ATTENTION

### Lors du développement
- Toujours penser "transparence"
- Jamais de suppression définitive
- Validation stricte des montants
- Interface simple (utilisateurs non techniques)
- Messages clairs en français
- Calculs en temps réel

### Lors des tests
- Tester avec événement terminé (lecture seule)
- Tester annulation (historique préservé)
- Tester calculs avec annulations
- Tester accès membre (lecture seule partout)
- Tester changement mot de passe obligatoire

---

## 📚 RÉFÉRENCES

- **Plan complet** : `plan.md`
- **État d'avancement** : `etat_avancement.md`
- **Règles (ce fichier)** : `claude.md`

---

**Dernière mise à jour** : 2025-12-24

**Ajouts** :
- Règle de communication lors du développement - toujours expliquer avant de coder
- Règle de tests après chaque fonctionnalité - ne pas attendre la fin d'une phase complète
