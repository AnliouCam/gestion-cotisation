<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UtilisateurController extends Controller
{
    /**
     * Afficher la liste de tous les utilisateurs (membres)
     */
    public function index()
    {
        $utilisateurs = Utilisateur::orderBy('created_at', 'desc')->paginate(15);

        return view('utilisateurs.index', compact('utilisateurs'));
    }

    /**
     * Afficher le formulaire de création d'un nouveau membre
     */
    public function create()
    {
        return view('utilisateurs.create');
    }

    /**
     * Enregistrer un nouveau membre avec génération automatique du mot de passe
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'required|string|unique:utilisateurs,telephone|max:20',
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'telephone.required' => 'Le numéro de téléphone est obligatoire.',
            'telephone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
        ]);

        // Générer un mot de passe aléatoire sécurisé
        $motDePasseGenere = $this->genererMotDePasse();

        // Créer l'utilisateur avec la date d'adhésion automatique (aujourd'hui)
        $utilisateur = Utilisateur::create([
            'nom' => $validated['nom'],
            'telephone' => $validated['telephone'],
            'mot_de_passe' => Hash::make($motDePasseGenere),
            'role' => 'membre',
            'date_adhesion' => now(), // Date automatique = aujourd'hui
            'statut' => 'actif',
            'doit_changer_mot_de_passe' => true,
        ]);

        // Rediriger avec le mot de passe généré (affiché UNE SEULE FOIS)
        return redirect()
            ->route('utilisateurs.index')
            ->with('success', "Membre créé avec succès ! Téléphone : {$utilisateur->telephone} | Mot de passe : {$motDePasseGenere}")
            ->with('warning', 'IMPORTANT : Notez bien ce mot de passe, il ne sera plus affiché.');
    }

    /**
     * Afficher le formulaire d'édition d'un membre
     */
    public function edit(Utilisateur $utilisateur)
    {
        return view('utilisateurs.edit', compact('utilisateur'));
    }

    /**
     * Mettre à jour les informations d'un membre
     */
    public function update(Request $request, Utilisateur $utilisateur)
    {
        // Validation des données
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20|unique:utilisateurs,telephone,' . $utilisateur->id,
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'telephone.required' => 'Le numéro de téléphone est obligatoire.',
            'telephone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
        ]);

        // Mettre à jour l'utilisateur (date_adhesion n'est PAS modifiable)
        $utilisateur->update([
            'nom' => $validated['nom'],
            'telephone' => $validated['telephone'],
        ]);

        return redirect()
            ->route('utilisateurs.index')
            ->with('success', 'Membre mis à jour avec succès !');
    }

    /**
     * Activer ou désactiver un membre (toggle du statut)
     */
    public function toggleStatut(Utilisateur $utilisateur)
    {
        // Empêcher de désactiver son propre compte
        if ($utilisateur->id === auth()->id()) {
            return redirect()
                ->route('utilisateurs.index')
                ->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        // Toggle du statut
        $nouveauStatut = $utilisateur->statut === 'actif' ? 'inactif' : 'actif';
        $utilisateur->update(['statut' => $nouveauStatut]);

        $message = $nouveauStatut === 'actif'
            ? "Le membre {$utilisateur->nom} a été activé."
            : "Le membre {$utilisateur->nom} a été désactivé.";

        return redirect()
            ->route('utilisateurs.index')
            ->with('success', $message);
    }

    /**
     * Générer un mot de passe aléatoire sécurisé
     * Format : 8 caractères avec majuscules, minuscules et chiffres
     */
    private function genererMotDePasse(): string
    {
        $longueur = 8;
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $motDePasse = '';

        for ($i = 0; $i < $longueur; $i++) {
            $motDePasse .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }

        return $motDePasse;
    }

    /**
     * Pas de suppression définitive - seulement désactivation
     * Cette méthode ne sera pas utilisée
     */
    public function destroy(Utilisateur $utilisateur)
    {
        return redirect()
            ->route('utilisateurs.index')
            ->with('error', 'La suppression n\'est pas autorisée. Vous pouvez désactiver le membre.');
    }
}
