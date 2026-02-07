<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use App\Models\Evenement;
use App\Models\Categorie;
use Illuminate\Http\Request;

class DepenseController extends Controller
{
    /**
     * Afficher la liste de toutes les dépenses
     */
    public function index(Request $request)
    {
        $query = Depense::with(['evenement', 'categorie', 'createur']);

        // Filtre par événement
        if ($request->filled('evenement_id')) {
            $query->where('evenement_id', $request->evenement_id);
        }

        // Filtre par catégorie
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Trier par date de création décroissante (les plus récentes en premier)
        $depenses = $query->orderBy('created_at', 'desc')->paginate(15);

        // Calculer le total des dépenses actives
        $totalActif = Depense::where('statut', 'actif')->sum('montant');

        // Listes pour les filtres
        $evenements = Evenement::orderBy('date_debut', 'desc')->get();
        $categories = Categorie::orderBy('nom')->get();

        return view('depenses.index', compact(
            'depenses',
            'totalActif',
            'evenements',
            'categories'
        ));
    }

    /**
     * Afficher le formulaire de création d'une dépense
     */
    public function create(Request $request)
    {
        // Récupérer seulement les événements actifs
        $evenements = Evenement::where('statut', 'actif')
                               ->orderBy('date_debut', 'desc')
                               ->get();

        // Récupérer toutes les catégories
        $categories = Categorie::orderBy('nom')->get();

        // Pré-sélection de l'événement si passé en query string
        $evenementSelectionne = $request->query('evenement_id');

        return view('depenses.create', compact(
            'evenements',
            'categories',
            'evenementSelectionne'
        ));
    }

    /**
     * Enregistrer une nouvelle dépense
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'evenement_id' => 'required|exists:evenements,id',
            'categorie_id' => 'required|exists:categories,id',
            'montant' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:1000',
            'justification' => 'nullable|string|max:1000',
        ], [
            'evenement_id.required' => 'Veuillez sélectionner un événement.',
            'evenement_id.exists' => 'L\'événement sélectionné n\'existe pas.',
            'categorie_id.required' => 'Veuillez sélectionner une catégorie.',
            'categorie_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
            'montant.required' => 'Le montant est requis.',
            'montant.numeric' => 'Le montant doit être un nombre.',
            'montant.min' => 'Le montant doit être supérieur à 0.',
            'description.required' => 'La description est requise.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            'justification.max' => 'La justification ne peut pas dépasser 1000 caractères.',
        ]);

        // Vérifier que l'événement est actif
        $evenement = Evenement::findOrFail($validated['evenement_id']);
        if ($evenement->statut === 'termine') {
            return back()
                ->withInput()
                ->withErrors(['evenement_id' => 'Impossible d\'ajouter une dépense à un événement terminé.']);
        }

        // Créer la dépense
        $depense = Depense::create([
            'evenement_id' => $validated['evenement_id'],
            'categorie_id' => $validated['categorie_id'],
            'montant' => $validated['montant'],
            'description' => $validated['description'],
            'justification' => $validated['justification'],
            'statut' => 'actif',
            'cree_par' => auth()->id(),
        ]);

        return redirect()
            ->route('depenses.index')
            ->with('success', 'Dépense enregistrée avec succès !');
    }

    /**
     * Afficher le formulaire d'édition d'une dépense
     */
    public function edit(Depense $depense)
    {
        // Vérifier que l'événement est actif
        if ($depense->evenement->statut === 'termine') {
            return redirect()
                ->route('depenses.index')
                ->withErrors(['error' => 'Impossible de modifier une dépense d\'un événement terminé.']);
        }

        // Vérifier que la dépense n'est pas déjà annulée
        if ($depense->statut === 'annule') {
            return redirect()
                ->route('depenses.index')
                ->withErrors(['error' => 'Impossible de modifier une dépense annulée.']);
        }

        // Récupérer seulement les événements actifs
        $evenements = Evenement::where('statut', 'actif')
                               ->orderBy('date_debut', 'desc')
                               ->get();

        // Récupérer toutes les catégories
        $categories = Categorie::orderBy('nom')->get();

        return view('depenses.edit', compact(
            'depense',
            'evenements',
            'categories'
        ));
    }

    /**
     * Mettre à jour une dépense existante
     */
    public function update(Request $request, Depense $depense)
    {
        // Vérifier que l'événement est actif
        if ($depense->evenement->statut === 'termine') {
            return redirect()
                ->route('depenses.index')
                ->withErrors(['error' => 'Impossible de modifier une dépense d\'un événement terminé.']);
        }

        // Vérifier que la dépense n'est pas annulée
        if ($depense->statut === 'annule') {
            return redirect()
                ->route('depenses.index')
                ->withErrors(['error' => 'Impossible de modifier une dépense annulée.']);
        }

        // Validation des données
        $validated = $request->validate([
            'evenement_id' => 'required|exists:evenements,id',
            'categorie_id' => 'required|exists:categories,id',
            'montant' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:1000',
            'justification' => 'nullable|string|max:1000',
        ], [
            'evenement_id.required' => 'Veuillez sélectionner un événement.',
            'evenement_id.exists' => 'L\'événement sélectionné n\'existe pas.',
            'categorie_id.required' => 'Veuillez sélectionner une catégorie.',
            'categorie_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
            'montant.required' => 'Le montant est requis.',
            'montant.numeric' => 'Le montant doit être un nombre.',
            'montant.min' => 'Le montant doit être supérieur à 0.',
            'description.required' => 'La description est requise.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            'justification.max' => 'La justification ne peut pas dépasser 1000 caractères.',
        ]);

        // Vérifier que le nouvel événement est actif
        $evenement = Evenement::findOrFail($validated['evenement_id']);
        if ($evenement->statut === 'termine') {
            return back()
                ->withInput()
                ->withErrors(['evenement_id' => 'Impossible de lier une dépense à un événement terminé.']);
        }

        // Mettre à jour la dépense
        $depense->update([
            'evenement_id' => $validated['evenement_id'],
            'categorie_id' => $validated['categorie_id'],
            'montant' => $validated['montant'],
            'description' => $validated['description'],
            'justification' => $validated['justification'],
        ]);

        return redirect()
            ->route('depenses.index')
            ->with('success', 'Dépense modifiée avec succès !');
    }

    /**
     * Annuler une dépense (pas de suppression définitive)
     */
    public function annuler(Request $request, Depense $depense)
    {
        // Vérifier que l'événement est actif
        if ($depense->evenement->statut === 'termine') {
            return back()->withErrors(['error' => 'Impossible d\'annuler une dépense d\'un événement terminé.']);
        }

        // Vérifier que la dépense n'est pas déjà annulée
        if ($depense->statut === 'annule') {
            return back()->withErrors(['error' => 'Cette dépense est déjà annulée.']);
        }

        // Validation du motif (optionnel)
        $validated = $request->validate([
            'motif_annulation' => 'nullable|string|max:500',
        ], [
            'motif_annulation.max' => 'Le motif ne peut pas dépasser 500 caractères.',
        ]);

        // Annuler la dépense
        $depense->annuler($validated['motif_annulation'] ?? 'Annulée par l\'administrateur');

        return redirect()
            ->route('depenses.index')
            ->with('success', 'Dépense annulée avec succès.');
    }
}
