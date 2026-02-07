<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DoitChangerMotDePasse
{
    /**
     * MIDDLEWARE : FORCER LE CHANGEMENT DE MOT DE PASSE
     *
     * Ce middleware vérifie si l'utilisateur connecté doit changer son mot de passe.
     * Si oui, il le redirige automatiquement vers la page de changement de mot de passe.
     *
     * Cas d'usage :
     * - Utilisateur créé par l'admin avec un mot de passe temporaire
     * - Premier administrateur créé par le concepteur
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (Auth::check()) {
            $utilisateur = Auth::user();

            // Vérifier si l'utilisateur doit changer son mot de passe
            if ($utilisateur->doit_changer_mot_de_passe) {
                // Exception : Si on est déjà sur la page de changement de mot de passe
                // ou sur la page de déconnexion, on laisse passer (sinon boucle infinie)
                if (!$request->routeIs('changer-mot-de-passe') && !$request->routeIs('logout')) {
                    return redirect()->route('changer-mot-de-passe')
                        ->with('info', 'Vous devez changer votre mot de passe avant de continuer.');
                }
            }
        }

        // Laisser passer la requête normalement
        return $next($request);
    }
}
