<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Utilisateur;

class AuthController extends Controller
{
    /**
     * Afficher le formulaire de connexion
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Traiter la connexion
     */
    public function login(Request $request)
    {
        // Validation
        $request->validate([
            'telephone' => 'required|string',
            'mot_de_passe' => 'required|string',
        ], [
            'telephone.required' => 'Le numéro de téléphone est requis.',
            'mot_de_passe.required' => 'Le mot de passe est requis.',
        ]);

        // Récupérer l'utilisateur par téléphone
        $utilisateur = Utilisateur::where('telephone', $request->telephone)->first();

        // Vérifier si l'utilisateur existe et que le mot de passe est correct
        if (!$utilisateur || !Hash::check($request->mot_de_passe, $utilisateur->mot_de_passe)) {
            return back()->withErrors([
                'telephone' => 'Identifiants incorrects.',
            ])->withInput($request->only('telephone'));
        }

        // Vérifier si l'utilisateur est actif
        if (!$utilisateur->estActif()) {
            return back()->withErrors([
                'telephone' => 'Votre compte est inactif. Contactez l\'administrateur.',
            ])->withInput($request->only('telephone'));
        }

        // Connecter l'utilisateur
        Auth::login($utilisateur);

        // Régénérer la session pour éviter la fixation de session
        $request->session()->regenerate();

        // Vérifier si l'utilisateur doit changer son mot de passe
        if ($utilisateur->doit_changer_mot_de_passe) {
            return redirect()->route('changer-mot-de-passe')
                ->with('info', 'Vous devez changer votre mot de passe avant de continuer.');
        }

        // Redirection selon le rôle
        return redirect()->intended('dashboard')
            ->with('success', 'Connexion réussie. Bienvenue ' . $utilisateur->nom . ' !');
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }

    /**
     * Afficher le formulaire de changement de mot de passe
     */
    public function showChangerMotDePasse()
    {
        return view('auth.changer-mot-de-passe');
    }

    /**
     * Traiter le changement de mot de passe
     */
    public function changerMotDePasse(Request $request)
    {
        // Validation
        $request->validate([
            'mot_de_passe_actuel' => 'required|string',
            'nouveau_mot_de_passe' => 'required|string|min:6|confirmed',
        ], [
            'mot_de_passe_actuel.required' => 'Le mot de passe actuel est requis.',
            'nouveau_mot_de_passe.required' => 'Le nouveau mot de passe est requis.',
            'nouveau_mot_de_passe.min' => 'Le nouveau mot de passe doit contenir au moins 6 caractères.',
            'nouveau_mot_de_passe.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $utilisateur = Auth::user();

        // Vérifier l'ancien mot de passe
        if (!Hash::check($request->mot_de_passe_actuel, $utilisateur->mot_de_passe)) {
            return back()->withErrors([
                'mot_de_passe_actuel' => 'Le mot de passe actuel est incorrect.',
            ]);
        }

        // Mettre à jour le mot de passe
        $utilisateur->mot_de_passe = Hash::make($request->nouveau_mot_de_passe);
        $utilisateur->doit_changer_mot_de_passe = false;
        $utilisateur->save();

        return redirect()->route('dashboard')
            ->with('success', 'Votre mot de passe a été changé avec succès !');
    }
}
