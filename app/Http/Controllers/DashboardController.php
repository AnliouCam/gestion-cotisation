<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Utilisateur;
use App\Models\Cotisation;
use App\Models\Depense;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Afficher le dashboard selon le rôle de l'utilisateur
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return $this->dashboardAdmin();
        } else {
            return $this->dashboardMembre();
        }
    }

    /**
     * Dashboard pour l'administrateur
     */
    private function dashboardAdmin()
    {
        // Statistiques globales
        $stats = [
            'total_evenements' => Evenement::count(),
            'evenements_actifs' => Evenement::where('statut', 'actif')->count(),
            'evenements_termines' => Evenement::where('statut', 'termine')->count(),

            'total_membres' => Utilisateur::where('role', 'membre')->count(),
            'membres_actifs' => Utilisateur::where('role', 'membre')->where('statut', 'actif')->count(),
            'membres_inactifs' => Utilisateur::where('role', 'membre')->where('statut', 'inactif')->count(),

            'total_cotisations' => Cotisation::where('statut', 'actif')->sum('montant'),
            'nombre_cotisations' => Cotisation::where('statut', 'actif')->count(),

            'total_depenses' => Depense::where('statut', 'actif')->sum('montant'),
            'nombre_depenses' => Depense::where('statut', 'actif')->count(),
        ];

        // Calcul du solde global
        $stats['solde_global'] = $stats['total_cotisations'] - $stats['total_depenses'];

        // Événements récents (5 derniers)
        $evenements_recents = Evenement::orderBy('created_at', 'desc')
                                        ->take(5)
                                        ->get();

        // Cotisations récentes (5 dernières)
        $cotisations_recentes = Cotisation::with(['utilisateur', 'evenement'])
                                          ->where('statut', 'actif')
                                          ->orderBy('created_at', 'desc')
                                          ->take(5)
                                          ->get();

        // Dépenses récentes (5 dernières)
        $depenses_recentes = Depense::with(['evenement', 'categorie'])
                                    ->where('statut', 'actif')
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get();

        // Événements actifs avec détails financiers
        $evenements_actifs = Evenement::where('statut', 'actif')
                                      ->withCount(['cotisations', 'depenses'])
                                      ->orderBy('date_debut', 'desc')
                                      ->get();

        return view('dashboard', compact(
            'stats',
            'evenements_recents',
            'cotisations_recentes',
            'depenses_recentes',
            'evenements_actifs'
        ));
    }

    /**
     * Dashboard pour le membre (lecture seule)
     */
    private function dashboardMembre()
    {
        $user = auth()->user();

        // Statistiques globales (lecture seule)
        $stats = [
            'evenements_actifs' => Evenement::where('statut', 'actif')->count(),
            'total_evenements' => Evenement::count(),

            'total_cotisations' => Cotisation::where('statut', 'actif')->sum('montant'),
            'nombre_cotisations' => Cotisation::where('statut', 'actif')->count(),

            'total_depenses' => Depense::where('statut', 'actif')->sum('montant'),
            'nombre_depenses' => Depense::where('statut', 'actif')->count(),
        ];

        // Calcul du solde global
        $stats['solde_global'] = $stats['total_cotisations'] - $stats['total_depenses'];

        // Mes cotisations personnelles
        $mes_cotisations = Cotisation::with(['evenement'])
                                     ->where('utilisateur_id', $user->id)
                                     ->where('statut', 'actif')
                                     ->orderBy('date', 'desc')
                                     ->take(5)
                                     ->get();

        $stats['mes_cotisations_total'] = Cotisation::where('utilisateur_id', $user->id)
                                                    ->where('statut', 'actif')
                                                    ->sum('montant');

        // Événements récents
        $evenements_recents = Evenement::orderBy('created_at', 'desc')
                                        ->take(5)
                                        ->get();

        // Événements actifs
        $evenements_actifs = Evenement::where('statut', 'actif')
                                      ->orderBy('date_debut', 'desc')
                                      ->get();

        return view('dashboard', compact(
            'stats',
            'mes_cotisations',
            'evenements_recents',
            'evenements_actifs'
        ));
    }
}
