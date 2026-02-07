<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

class UtilisateurSeeder extends Seeder
{
    /**
     * SEEDER DU PREMIER ADMINISTRATEUR
     *
     * Crée le compte du responsable principal de l'organisation.
     * Ce compte sera utilisé pour se connecter la première fois.
     * Le responsable devra changer son mot de passe à la première connexion.
     */
    public function run(): void
    {
        // Vérifier si un admin existe déjà
        $adminExiste = Utilisateur::where('role', 'admin')->exists();

        if ($adminExiste) {
            echo "⚠️  Un administrateur existe déjà. Seeder ignoré.\n";
            return;
        }

        // Créer le premier administrateur
        $admin = Utilisateur::create([
            'nom' => 'Administrateur',
            'telephone' => '0123456789', // À changer selon vos besoins
            'mot_de_passe' => Hash::make('password'), // Mot de passe temporaire
            'role' => 'admin',
            'date_adhesion' => now(),
            'statut' => 'actif',
            'doit_changer_mot_de_passe' => true, // Obligera à changer le mot de passe
        ]);

        echo "✅ Administrateur créé avec succès !\n";
        echo "📱 Téléphone: 0123456789\n";
        echo "🔑 Mot de passe: password\n";
        echo "⚠️  Le mot de passe devra être changé à la première connexion.\n";
    }
}
