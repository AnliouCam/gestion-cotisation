<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * SEEDER PRINCIPAL DE L'APPLICATION
     *
     * Lance tous les seeders nécessaires pour initialiser l'application.
     * Ordre d'exécution important :
     * 1. Catégories (nécessaires pour les dépenses)
     * 2. Premier administrateur (pour se connecter)
     */
    public function run(): void
    {
        echo "🌱 Démarrage du seeding...\n\n";

        // 1. Créer les catégories de dépenses
        echo "📦 Création des catégories...\n";
        $this->call(CategorieSeeder::class);

        echo "\n";

        // 2. Créer le premier administrateur
        echo "👤 Création de l'administrateur...\n";
        $this->call(UtilisateurSeeder::class);

        echo "\n🎉 Seeding terminé avec succès !\n";
    }
}
