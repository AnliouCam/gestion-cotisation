# 🔐 INFORMATIONS DE CONNEXION
## Application de Gestion des Cotisations

---

## 👤 COMPTES UTILISATEURS

### Administrateur Principal

**Rôle** : Admin (Responsable de l'organisation)

**Identifiants de connexion :**
- **Téléphone** : `0123456789`
- **Mot de passe** : `mdp123`

✅ **Mot de passe mis à jour** : Le mot de passe initial était `password` mais a été changé lors de la première connexion (obligatoire).

---

### Comptes de Test

#### Membre de test (Jean Dupont)

**Rôle** : Membre (lecture seule)

**Identifiants de connexion :**
- **Téléphone** : `0987654321`
- **Mot de passe** : `password`

✅ **Statut** : Actif
✅ **Droits** : Consultation uniquement (aucune modification)

**Utilisation :** Ce compte permet de tester l'interface membre avec accès en lecture seule totale.

---

## 🎯 TYPES DE COMPTES

### 1. Administrateur (Admin)

**Droits :**
- ✅ Créer/modifier/désactiver les utilisateurs (membres)
- ✅ Créer/modifier/clôturer les événements
- ✅ Enregistrer les cotisations
- ✅ Enregistrer les dépenses
- ✅ Annuler cotisations et dépenses
- ✅ Gérer les catégories de dépenses
- ✅ Voir tous les rapports et statistiques

**Restrictions :**
- ❌ Ne peut PAS supprimer définitivement (seulement annuler)
- ❌ Ne peut PAS modifier un événement terminé

### 2. Membre (Member)

**Droits :**
- ✅ Voir tous les événements
- ✅ Voir toutes les cotisations (qui a donné combien)
- ✅ Voir toutes les dépenses (ce qui a été dépensé)
- ✅ Voir tous les soldes et historiques
- ✅ Consulter en lecture seule totale

**Restrictions :**
- ❌ AUCUNE modification
- ❌ Aucun ajout
- ❌ Aucune suppression

---

## 📊 CATÉGORIES DE DÉPENSES (Créées automatiquement)

Les catégories suivantes sont créées lors du seeding :

1. **Achat** - Pour les achats de matériel, fournitures, etc.
2. **Location** - Pour les locations (salle, matériel, etc.)
3. **Aide** - Pour les aides sociales, dons, etc.
4. **Autre** - Pour toute autre dépense non classée

L'administrateur peut ajouter d'autres catégories via l'interface.

---

## 🚀 COMMENT CRÉER DE NOUVEAUX UTILISATEURS

### Pour l'Admin :

1. Se connecter avec les identifiants ci-dessus
2. Aller dans le menu "Utilisateurs" ou "Membres"
3. Cliquer sur "Ajouter un membre"
4. Remplir :
   - Nom complet
   - Numéro de téléphone (unique)
   - Date d'adhésion
5. Le système génère automatiquement un mot de passe
6. Communiquer le téléphone + mot de passe au nouveau membre
7. Le membre devra changer son mot de passe à la première connexion

---

## 🔒 SÉCURITÉ

### Règles de mot de passe :
- Changement obligatoire à la première connexion
- Hash sécurisé (bcrypt)
- Pas de récupération par email (uniquement téléphone)

### Traçabilité :
- Chaque cotisation/dépense enregistre qui l'a créée
- Impossible de supprimer (seulement annuler)
- L'historique est préservé pour toujours

---

## 🧪 COMMANDES UTILES

### Lancer les seeders (créer admin + catégories)
```bash
php artisan db:seed
```

### Réinitialiser la base de données complètement
```bash
php artisan migrate:fresh --seed
```

### Créer un nouvel admin (si besoin)
```bash
php artisan tinker
> use App\Models\Utilisateur;
> use Illuminate\Support\Facades\Hash;
> Utilisateur::create([
    'nom' => 'Votre Nom',
    'telephone' => '0987654321',
    'mot_de_passe' => Hash::make('password123'),
    'role' => 'admin',
    'date_adhesion' => now(),
    'statut' => 'actif',
    'doit_changer_mot_de_passe' => true,
]);
```

---

## 📝 NOTES IMPORTANTES

1. **Téléphone = Identifiant** : Pas d'email, seulement le numéro de téléphone
2. **Un membre = Un utilisateur** : Pas de séparation entre users et members
3. **Tout en français** : Tables, colonnes, code, interface
4. **Devise fixe** : FCFA uniquement (pas de sélection)
5. **Transparence totale** : Les membres voient TOUT (principe fondamental)

---

**Dernière mise à jour** : 2025-12-25

**Ajouts** :
- Section "Comptes de Test" avec identifiants du membre de test (Jean Dupont)
