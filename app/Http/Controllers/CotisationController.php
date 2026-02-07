<?php

namespace App\Http\Controllers;

use App\Models\Cotisation;
use App\Models\Utilisateur;
use App\Models\Evenement;
use Illuminate\Http\Request;

class CotisationController extends Controller
{
    /**
     * Afficher la liste de toutes les cotisations
     */
    public function index(Request $request)
    {
        $query = Cotisation::with(['utilisateur', 'evenement', 'createur']);

        // Filtre par événement
        if ($request->filled('evenement_id')) {
            $query->where('evenement_id', $request->evenement_id);
        }

        // Filtre par membre
        if ($request->filled('utilisateur_id')) {
            $query->where('utilisateur_id', $request->utilisateur_id);
        }

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Trier par date décroissante (les plus récentes en premier)
        $cotisations = $query->orderBy('date', 'desc')
                             ->orderBy('created_at', 'desc')
                             ->paginate(15);

        // Calculer le total des cotisations actives
        $totalActif = Cotisation::where('statut', 'actif')->sum('montant');

        // Listes pour les filtres
        $evenements = Evenement::orderBy('date_debut', 'desc')->get();
        $utilisateurs = Utilisateur::where('statut', 'actif')
                                   ->orderBy('nom')
                                   ->get();

        return view('cotisations.index', compact(
            'cotisations',
            'totalActif',
            'evenements',
            'utilisateurs'
        ));
    }

    /**
     * Afficher le formulaire de création d'une cotisation
     */
    public function create(Request $request)
    {
        // Récupérer seulement les événements actifs
        $evenements = Evenement::where('statut', 'actif')
                               ->orderBy('date_debut', 'desc')
                               ->get();

        // Récupérer seulement les utilisateurs actifs
        $utilisateurs = Utilisateur::where('statut', 'actif')
                                   ->orderBy('nom')
                                   ->get();

        // Pré-sélection de l'événement si passé en query string
        $evenementSelectionne = $request->query('evenement_id');

        return view('cotisations.create', compact(
            'evenements',
            'utilisateurs',
            'evenementSelectionne'
        ));
    }

    /**
     * Enregistrer une nouvelle cotisation
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'utilisateur_id' => 'required|exists:utilisateurs,id',
            'evenement_id' => 'required|exists:evenements,id',
            'montant' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'commentaire' => 'nullable|string|max:1000',
        ], [
            'utilisateur_id.required' => 'Veuillez sélectionner un membre.',
            'utilisateur_id.exists' => 'Le membre sélectionné n\'existe pas.',
            'evenement_id.required' => 'Veuillez sélectionner un événement.',
            'evenement_id.exists' => 'L\'événement sélectionné n\'existe pas.',
            'montant.required' => 'Le montant est requis.',
            'montant.numeric' => 'Le montant doit être un nombre.',
            'montant.min' => 'Le montant doit être supérieur à 0.',
            'date.required' => 'La date est requise.',
            'date.date' => 'La date n\'est pas valide.',
            'commentaire.max' => 'Le commentaire ne peut pas dépasser 1000 caractères.',
        ]);

        // Vérifier que l'événement est actif
        $evenement = Evenement::findOrFail($validated['evenement_id']);
        if ($evenement->statut === 'termine') {
            return back()
                ->withInput()
                ->withErrors(['evenement_id' => 'Impossible d\'ajouter une cotisation à un événement terminé.']);
        }

        // Vérifier que l'utilisateur est actif
        $utilisateur = Utilisateur::findOrFail($validated['utilisateur_id']);
        if ($utilisateur->statut === 'inactif') {
            return back()
                ->withInput()
                ->withErrors(['utilisateur_id' => 'Impossible d\'enregistrer une cotisation pour un membre inactif.']);
        }

        // Créer la cotisation
        $cotisation = Cotisation::create([
            'utilisateur_id' => $validated['utilisateur_id'],
            'evenement_id' => $validated['evenement_id'],
            'montant' => $validated['montant'],
            'date' => $validated['date'],
            'commentaire' => $validated['commentaire'],
            'statut' => 'actif',
            'cree_par' => auth()->id(),
        ]);

        return redirect()
            ->route('cotisations.index')
            ->with('success', 'Cotisation enregistrée avec succès !');
    }

    /**
     * Afficher le formulaire d'édition d'une cotisation
     */
    public function edit(Cotisation $cotisation)
    {
        // Vérifier que l'événement est actif
        if ($cotisation->evenement->statut === 'termine') {
            return redirect()
                ->route('cotisations.index')
                ->withErrors(['error' => 'Impossible de modifier une cotisation d\'un événement terminé.']);
        }

        // Vérifier que la cotisation n'est pas déjà annulée
        if ($cotisation->statut === 'annule') {
            return redirect()
                ->route('cotisations.index')
                ->withErrors(['error' => 'Impossible de modifier une cotisation annulée.']);
        }

        // Récupérer seulement les événements actifs
        $evenements = Evenement::where('statut', 'actif')
                               ->orderBy('date_debut', 'desc')
                               ->get();

        // Récupérer seulement les utilisateurs actifs
        $utilisateurs = Utilisateur::where('statut', 'actif')
                                   ->orderBy('nom')
                                   ->get();

        return view('cotisations.edit', compact(
            'cotisation',
            'evenements',
            'utilisateurs'
        ));
    }

    /**
     * Mettre à jour une cotisation existante
     */
    public function update(Request $request, Cotisation $cotisation)
    {
        // Vérifier que l'événement est actif
        if ($cotisation->evenement->statut === 'termine') {
            return redirect()
                ->route('cotisations.index')
                ->withErrors(['error' => 'Impossible de modifier une cotisation d\'un événement terminé.']);
        }

        // Vérifier que la cotisation n'est pas annulée
        if ($cotisation->statut === 'annule') {
            return redirect()
                ->route('cotisations.index')
                ->withErrors(['error' => 'Impossible de modifier une cotisation annulée.']);
        }

        // Validation des données
        $validated = $request->validate([
            'utilisateur_id' => 'required|exists:utilisateurs,id',
            'evenement_id' => 'required|exists:evenements,id',
            'montant' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'commentaire' => 'nullable|string|max:1000',
        ], [
            'utilisateur_id.required' => 'Veuillez sélectionner un membre.',
            'utilisateur_id.exists' => 'Le membre sélectionné n\'existe pas.',
            'evenement_id.required' => 'Veuillez sélectionner un événement.',
            'evenement_id.exists' => 'L\'événement sélectionné n\'existe pas.',
            'montant.required' => 'Le montant est requis.',
            'montant.numeric' => 'Le montant doit être un nombre.',
            'montant.min' => 'Le montant doit être supérieur à 0.',
            'date.required' => 'La date est requise.',
            'date.date' => 'La date n\'est pas valide.',
            'commentaire.max' => 'Le commentaire ne peut pas dépasser 1000 caractères.',
        ]);

        // Vérifier que le nouvel événement est actif
        $evenement = Evenement::findOrFail($validated['evenement_id']);
        if ($evenement->statut === 'termine') {
            return back()
                ->withInput()
                ->withErrors(['evenement_id' => 'Impossible de lier une cotisation à un événement terminé.']);
        }

        // Vérifier que l'utilisateur est actif
        $utilisateur = Utilisateur::findOrFail($validated['utilisateur_id']);
        if ($utilisateur->statut === 'inactif') {
            return back()
                ->withInput()
                ->withErrors(['utilisateur_id' => 'Impossible d\'enregistrer une cotisation pour un membre inactif.']);
        }

        // Mettre à jour la cotisation
        $cotisation->update([
            'utilisateur_id' => $validated['utilisateur_id'],
            'evenement_id' => $validated['evenement_id'],
            'montant' => $validated['montant'],
            'date' => $validated['date'],
            'commentaire' => $validated['commentaire'],
        ]);

        return redirect()
            ->route('cotisations.index')
            ->with('success', 'Cotisation modifiée avec succès !');
    }

    /**
     * Annuler une cotisation (pas de suppression définitive)
     */
    public function annuler(Request $request, Cotisation $cotisation)
    {
        // Vérifier que l'événement est actif
        if ($cotisation->evenement->statut === 'termine') {
            return back()->withErrors(['error' => 'Impossible d\'annuler une cotisation d\'un événement terminé.']);
        }

        // Vérifier que la cotisation n'est pas déjà annulée
        if ($cotisation->statut === 'annule') {
            return back()->withErrors(['error' => 'Cette cotisation est déjà annulée.']);
        }

        // Validation du motif (optionnel)
        $validated = $request->validate([
            'motif_annulation' => 'nullable|string|max:500',
        ], [
            'motif_annulation.max' => 'Le motif ne peut pas dépasser 500 caractères.',
        ]);

        // Annuler la cotisation
        $cotisation->annuler($validated['motif_annulation'] ?? 'Annulée par l\'administrateur');

        return redirect()
            ->route('cotisations.index')
            ->with('success', 'Cotisation annulée avec succès.');
    }
}
