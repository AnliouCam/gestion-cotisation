<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categorie;

class CategorieSeeder extends Seeder
{
    /**
     * SEEDER DES CATÉGORIES DE DÉPENSES
     *
     * Crée les 4 catégories de base pour classer les dépenses.
     * Ces catégories sont essentielles pour le fonctionnement de l'application.
     * L'admin pourra en ajouter d'autres plus tard via l'interface.
     */
    public function run(): void
    {
        $categories = [
            ['nom' => 'Achat'],
            ['nom' => 'Location'],
            ['nom' => 'Aide'],
            ['nom' => 'Autre'],
        ];

        // Créer chaque catégorie si elle n'existe pas déjà
        foreach ($categories as $categorie) {
            Categorie::firstOrCreate(
                ['nom' => $categorie['nom']], // Recherche par nom
                $categorie // Données à insérer si n'existe pas
            );
        }

        echo "✅ Catégories créées avec succès !\n";
    }
}
