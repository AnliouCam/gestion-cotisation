# 🧪 SCÉNARIO DE TESTS - PHASE 5
## Gestion des Utilisateurs (Membres)

**Date** : 2025-12-24
**Phase testée** : Phase 5 - Gestion des Utilisateurs (Membres)

---

## 📋 PRÉREQUIS

- [ ] Application Laravel démarrée (`php artisan serve`)
- [ ] Base de données migrée et seedée
- [ ] Compte admin créé (Téléphone: `0123456789`, Mot de passe: `mdp123`)
- [ ] Navigateur web ouvert

---

## 🔐 TEST 1 : SÉCURITÉ - Middleware IsAdmin

### Objectif
Vérifier que seuls les administrateurs peuvent accéder à la gestion des membres

### Étapes
1. [ ] Se connecter avec le compte admin (`0123456789` / `mdp123`)
2. [ ] Accéder à l'URL `/utilisateurs`
3. [ ] **Résultat attendu** : Page de liste des membres s'affiche ✅

### Test négatif (créer un membre test d'abord)
1. [ ] Se déconnecter
2. [ ] Créer un membre "Test Membre" (suivre TEST 2)
3. [ ] Se déconnecter du compte admin
4. [ ] Se connecter avec le compte du membre test
5. [ ] Essayer d'accéder à `/utilisateurs` directement dans l'URL
6. [ ] **Résultat attendu** : Redirection vers dashboard avec message d'erreur "Accès refusé" ✅

---

## ➕ TEST 2 : CRÉATION D'UN MEMBRE

### Objectif
Vérifier que l'admin peut créer un nouveau membre avec génération automatique du mot de passe

### Étapes
1. [ ] Se connecter en tant qu'admin
2. [ ] Cliquer sur "Membres" dans la sidebar
3. [ ] **Vérifier** : Le lien est surligné en bleu (menu actif) ✅
4. [ ] Cliquer sur "Ajouter un membre"
5. [ ] **Vérifier** : Breadcrumb affiche "Membres > Ajouter" ✅
6. [ ] Remplir le formulaire :
   - Nom : `Jean Dupont`
   - Téléphone : `0987654321`
   - Date d'adhésion : `2025-12-24` (date du jour)
7. [ ] Cliquer sur "Créer le membre"
8. [ ] **Résultat attendu** :
   - ✅ Redirection vers la liste des membres
   - ✅ Message de succès vert affiché
   - ✅ Message contient le téléphone ET le mot de passe généré
   - ✅ Message warning jaune "IMPORTANT : Notez bien ce mot de passe"
9. [ ] **NOTER LE MOT DE PASSE** affiché : `___________`
10. [ ] **Vérifier** : Le nouveau membre apparaît dans la liste ✅
11. [ ] **Vérifier** : Badge "Membre" (gris) et badge "Actif" (vert) ✅

---

## 🔍 TEST 3 : AFFICHAGE DE LA LISTE

### Objectif
Vérifier que la liste des membres affiche correctement toutes les informations

### Étapes
1. [ ] Sur la page liste des membres
2. [ ] **Vérifier** pour chaque membre :
   - ✅ Avatar avec initiale (lettre dans un cercle bleu)
   - ✅ Nom complet
   - ✅ Numéro de téléphone
   - ✅ Badge rôle (Admin = violet, Membre = gris)
   - ✅ Date d'adhésion au format DD/MM/YYYY
   - ✅ Badge statut (Actif = vert, Inactif = rouge)
   - ✅ Boutons d'action : Modifier (bleu) et Activer/Désactiver (orange/vert)
3. [ ] **Vérifier** : Total affiché en haut "Total : X" ✅
4. [ ] **Vérifier** : Info box bleue en bas avec les instructions ✅

---

## ✏️ TEST 4 : MODIFICATION D'UN MEMBRE

### Objectif
Vérifier que l'admin peut modifier les informations d'un membre

### Étapes
1. [ ] Cliquer sur l'icône "Modifier" (crayon) du membre "Jean Dupont"
2. [ ] **Vérifier** :
   - ✅ Breadcrumb "Membres > Modifier"
   - ✅ Avatar avec initiale "J"
   - ✅ Nom, rôle et statut affichés en haut
   - ✅ Formulaire pré-rempli avec les données actuelles
   - ✅ Encadré bleu d'information en bas
3. [ ] Modifier le nom : `Jean Dupont Modifié`
4. [ ] Modifier le téléphone : `0987654322`
5. [ ] Cliquer sur "Mettre à jour"
6. [ ] **Résultat attendu** :
   - ✅ Redirection vers liste
   - ✅ Message succès "Membre mis à jour avec succès !"
   - ✅ Modifications visibles dans la liste

---

## 🔄 TEST 5 : ACTIVATION / DÉSACTIVATION

### Objectif
Vérifier que l'admin peut activer/désactiver un membre

### Test 5.1 : Désactivation
1. [ ] Cliquer sur l'icône "Désactiver" (croix orange) pour "Jean Dupont Modifié"
2. [ ] **Vérifier** : Message de confirmation apparaît ✅
3. [ ] Confirmer
4. [ ] **Résultat attendu** :
   - ✅ Message succès "Le membre Jean Dupont Modifié a été désactivé"
   - ✅ Badge statut passe à "Inactif" (rouge)
   - ✅ Icône change en check vert (pour réactiver)

### Test 5.2 : Réactivation
1. [ ] Cliquer sur l'icône "Activer" (check vert)
2. [ ] Confirmer
3. [ ] **Résultat attendu** :
   - ✅ Message succès "Le membre Jean Dupont Modifié a été activé"
   - ✅ Badge statut repasse à "Actif" (vert)
   - ✅ Icône redevient croix orange

### Test 5.3 : Protection désactivation propre compte
1. [ ] Trouver le compte admin dans la liste
2. [ ] Essayer de cliquer sur "Désactiver" pour le compte admin
3. [ ] Confirmer
4. [ ] **Résultat attendu** : Message d'erreur "Vous ne pouvez pas désactiver votre propre compte" ✅

---

## ❌ TEST 6 : VALIDATION DES DONNÉES

### Objectif
Vérifier que les validations fonctionnent correctement

### Test 6.1 : Champs obligatoires
1. [ ] Aller sur "Ajouter un membre"
2. [ ] Laisser tous les champs vides
3. [ ] Cliquer sur "Créer le membre"
4. [ ] **Résultat attendu** : Messages d'erreur rouges sous chaque champ ✅

### Test 6.2 : Téléphone unique
1. [ ] Essayer de créer un membre avec le téléphone `0987654322` (déjà utilisé)
2. [ ] **Résultat attendu** : Erreur "Ce numéro de téléphone est déjà utilisé" ✅

### Test 6.3 : Date invalide
1. [ ] Essayer d'entrer une date invalide
2. [ ] **Résultat attendu** : Erreur de validation ✅

---

## 🔐 TEST 7 : PREMIÈRE CONNEXION DU MEMBRE

### Objectif
Vérifier que le membre créé peut se connecter et doit changer son mot de passe

### Étapes
1. [ ] Se déconnecter du compte admin
2. [ ] Aller sur la page de connexion
3. [ ] Se connecter avec :
   - Téléphone : `0987654322`
   - Mot de passe : `[le mot de passe noté au TEST 2]`
4. [ ] **Résultat attendu** :
   - ✅ Connexion réussie
   - ✅ Redirection forcée vers "Changer mot de passe"
   - ✅ Impossible d'accéder au dashboard sans changer le mot de passe
5. [ ] Changer le mot de passe :
   - Ancien : `[mot de passe généré]`
   - Nouveau : `nouveauMdp123`
   - Confirmation : `nouveauMdp123`
6. [ ] **Résultat attendu** :
   - ✅ Redirection vers dashboard
   - ✅ Message succès affiché
7. [ ] **Vérifier** : Le membre voit le dashboard mais PAS le menu "Membres" dans la sidebar ✅

---

## 🔍 TEST 8 : MEMBRE NE PEUT PAS ACCÉDER À LA GESTION

### Objectif
Vérifier qu'un membre simple ne peut pas gérer les utilisateurs

### Étapes (connecté en tant que membre)
1. [ ] Essayer d'accéder directement à `/utilisateurs` via l'URL
2. [ ] **Résultat attendu** :
   - ✅ Redirection vers dashboard
   - ✅ Message erreur "Accès refusé. Cette fonctionnalité est réservée aux administrateurs."
3. [ ] Essayer d'accéder à `/utilisateurs/create`
4. [ ] **Résultat attendu** : Même message d'erreur ✅

---

## 🎨 TEST 9 : INTERFACE & UX

### Objectif
Vérifier que l'interface est professionnelle et responsive

### Test 9.1 : Design
1. [ ] **Vérifier** :
   - ✅ Couleurs cohérentes (bleu principal)
   - ✅ Espacement correct
   - ✅ Icônes claires et lisibles
   - ✅ Breadcrumb fonctionnel

### Test 9.2 : Messages Flash
1. [ ] **Vérifier** que les messages flash :
   - ✅ S'affichent correctement (vert=succès, rouge=erreur, jaune=warning)
   - ✅ Disparaissent automatiquement après 5 secondes
   - ✅ Ont un bouton de fermeture manuelle (X)
   - ✅ Ont des transitions fluides

### Test 9.3 : Responsive
1. [ ] Réduire la fenêtre du navigateur (mode mobile)
2. [ ] **Vérifier** :
   - ✅ Tableau scrollable horizontalement
   - ✅ Formulaires adaptés au mobile
   - ✅ Boutons accessibles

---

## 🚫 TEST 10 : PAS DE SUPPRESSION

### Objectif
Vérifier qu'il est impossible de supprimer un membre

### Étapes
1. [ ] **Vérifier** : Aucun bouton "Supprimer" visible dans la liste ✅
2. [ ] Essayer d'accéder à la route DELETE via l'URL (si possible)
3. [ ] **Résultat attendu** : La route n'existe pas (404) ou erreur ✅

---

## ✅ RÉSUMÉ DES TESTS

| Test | Statut | Notes |
|------|--------|-------|
| 1. Middleware IsAdmin | ⬜ | |
| 2. Création membre | ⬜ | Mot de passe généré : ______ |
| 3. Affichage liste | ⬜ | |
| 4. Modification membre | ⬜ | |
| 5. Activation/Désactivation | ⬜ | |
| 6. Validation données | ⬜ | |
| 7. Première connexion membre | ⬜ | |
| 8. Membre ne peut pas gérer | ⬜ | |
| 9. Interface & UX | ⬜ | |
| 10. Pas de suppression | ⬜ | |

---

## 🐛 BUGS TROUVÉS

_Documenter ici les bugs rencontrés pendant les tests :_

1.
2.
3.

---

## 📝 NOTES ADDITIONNELLES

_Ajouter ici des observations ou suggestions :_

-
-

---

**Tests effectués par** : _______________
**Date** : _______________
**Signature** : _______________
