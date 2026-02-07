<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;

class EvenementController extends Controller
{
    /**
     * Afficher la liste de tous les événements
     */
    public function index()
    {
        // Récupérer les événements avec les totaux de cotisations et dépenses
        $evenements = Evenement::withCount('cotisations', 'depenses')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('evenements.index', compact('evenements'));
    }

    /**
     * Afficher le formulaire de création d'un nouvel événement
     */
    public function create()
    {
        return view('evenements.create');
    }

    /**
     * Enregistrer un nouvel événement
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
        ], [
            'nom.required' => 'Le nom de l\'événement est obligatoire.',
            'date_debut.required' => 'La date de début est obligatoire.',
            'date_debut.date' => 'La date de début n\'est pas valide.',
            'date_fin.required' => 'La date de fin est obligatoire.',
            'date_fin.date' => 'La date de fin n\'est pas valide.',
            'date_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
        ]);

        // Créer l'événement
        Evenement::create([
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'date_debut' => $validated['date_debut'],
            'date_fin' => $validated['date_fin'],
            'statut' => 'actif',
        ]);

        return redirect()
            ->route('evenements.index')
            ->with('success', 'Événement créé avec succès !');
    }

    /**
     * Afficher les détails d'un événement avec résumé financier
     */
    public function show(Evenement $evenement)
    {
        // Charger les relations avec les totaux
        $evenement->load(['cotisations' => function($query) {
            $query->where('statut', 'actif')->with('utilisateur');
        }, 'depenses' => function($query) {
            $query->where('statut', 'actif')->with('categorie');
        }]);

        // Calculer les totaux
        $totalCotisations = $evenement->totalCotisations();
        $totalDepenses = $evenement->totalDepenses();
        $solde = $evenement->solde();

        return view('evenements.show', compact('evenement', 'totalCotisations', 'totalDepenses', 'solde'));
    }

    /**
     * Afficher le formulaire d'édition d'un événement
     */
    public function edit(Evenement $evenement)
    {
        // Vérifier que l'événement n'est pas terminé
        if ($evenement->estTermine()) {
            return redirect()
                ->route('evenements.index')
                ->with('error', 'Impossible de modifier un événement terminé.');
        }

        return view('evenements.edit', compact('evenement'));
    }

    /**
     * Mettre à jour un événement existant
     */
    public function update(Request $request, Evenement $evenement)
    {
        // Vérifier que l'événement n'est pas terminé
        if ($evenement->estTermine()) {
            return redirect()
                ->route('evenements.index')
                ->with('error', 'Impossible de modifier un événement terminé.');
        }

        // Validation des données
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
        ], [
            'nom.required' => 'Le nom de l\'événement est obligatoire.',
            'date_debut.required' => 'La date de début est obligatoire.',
            'date_debut.date' => 'La date de début n\'est pas valide.',
            'date_fin.required' => 'La date de fin est obligatoire.',
            'date_fin.date' => 'La date de fin n\'est pas valide.',
            'date_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
        ]);

        // Mettre à jour l'événement
        $evenement->update([
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'date_debut' => $validated['date_debut'],
            'date_fin' => $validated['date_fin'],
        ]);

        return redirect()
            ->route('evenements.show', $evenement)
            ->with('success', 'Événement mis à jour avec succès !');
    }

    /**
     * Clôturer un événement (passer le statut à 'termine')
     */
    public function cloturer(Evenement $evenement)
    {
        // Vérifier que l'événement est actif
        if ($evenement->estTermine()) {
            return redirect()
                ->route('evenements.index')
                ->with('error', 'Cet événement est déjà clôturé.');
        }

        // Clôturer l'événement
        $evenement->update(['statut' => 'termine']);

        return redirect()
            ->route('evenements.show', $evenement)
            ->with('success', 'Événement clôturé avec succès. Il est maintenant en lecture seule.');
    }

    /**
     * Pas de suppression d'événements
     */
    public function destroy(Evenement $evenement)
    {
        return redirect()
            ->route('evenements.index')
            ->with('error', 'La suppression d\'événements n\'est pas autorisée. Vous pouvez clôturer l\'événement.');
    }
}
