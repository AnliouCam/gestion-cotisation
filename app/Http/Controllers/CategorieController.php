<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Afficher la liste de toutes les catégories
     */
    public function index()
    {
        // Récupérer les catégories avec le nombre de dépenses associées
        $categories = Categorie::withCount('depenses')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('categories.index', compact('categories'));
    }

    /**
     * Afficher le formulaire de création d'une nouvelle catégorie
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Enregistrer une nouvelle catégorie
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom',
        ], [
            'nom.required' => 'Le nom de la catégorie est obligatoire.',
            'nom.unique' => 'Cette catégorie existe déjà.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
        ]);

        // Créer la catégorie
        Categorie::create([
            'nom' => $validated['nom'],
        ]);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Catégorie créée avec succès !');
    }

    /**
     * Afficher le formulaire d'édition d'une catégorie
     */
    public function edit(Categorie $categorie)
    {
        return view('categories.edit', compact('categorie'));
    }

    /**
     * Mettre à jour une catégorie existante
     */
    public function update(Request $request, Categorie $categorie)
    {
        // Validation des données
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom,' . $categorie->id,
        ], [
            'nom.required' => 'Le nom de la catégorie est obligatoire.',
            'nom.unique' => 'Cette catégorie existe déjà.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
        ]);

        // Mettre à jour la catégorie
        $categorie->update([
            'nom' => $validated['nom'],
        ]);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Catégorie mise à jour avec succès !');
    }

    /**
     * Supprimer une catégorie (seulement si elle n'est pas utilisée)
     */
    public function destroy(Categorie $categorie)
    {
        // Vérifier si la catégorie peut être supprimée
        if (!$categorie->peutEtreSupprimee()) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle est utilisée dans des dépenses.');
        }

        // Supprimer la catégorie
        $nomCategorie = $categorie->nom;
        $categorie->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', "La catégorie \"{$nomCategorie}\" a été supprimée avec succès.");
    }
}
