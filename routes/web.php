<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\CotisationController;
use App\Http\Controllers\DepenseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirection de la page d'accueil vers la connexion
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Routes d'authentification (accessible sans être connecté)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Afficher le formulaire de connexion
    Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');

    // Traiter la connexion
    Route::post('/connexion', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Routes protégées (nécessitent une authentification)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Déconnexion (accessible même si l'utilisateur doit changer son mot de passe)
    Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout');

    // Changement de mot de passe obligatoire (accessible sans le middleware doit.changer.mot.de.passe)
    Route::get('/changer-mot-de-passe', [AuthController::class, 'showChangerMotDePasse'])->name('changer-mot-de-passe');
    Route::post('/changer-mot-de-passe', [AuthController::class, 'changerMotDePasse']);
});

/*
|--------------------------------------------------------------------------
| Routes nécessitant que le mot de passe ait été changé
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'doit.changer.mot.de.passe'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Routes de consultation (accessibles à tous les utilisateurs authentifiés)
    |--------------------------------------------------------------------------
    */
    // Événements - Consultation
    Route::get('/evenements', [EvenementController::class, 'index'])->name('evenements.index');
    Route::get('/evenements/{evenement}', [EvenementController::class, 'show'])->name('evenements.show');

    // Cotisations - Consultation
    Route::get('/cotisations', [CotisationController::class, 'index'])->name('cotisations.index');

    // Dépenses - Consultation
    Route::get('/depenses', [DepenseController::class, 'index'])->name('depenses.index');

    /*
    |--------------------------------------------------------------------------
    | Routes réservées aux administrateurs
    |--------------------------------------------------------------------------
    */
    Route::middleware('is.admin')->group(function () {
        // Gestion des utilisateurs (membres)
        Route::resource('utilisateurs', UtilisateurController::class)->except(['show', 'destroy']);

        // Route spéciale pour activer/désactiver un membre
        Route::patch('/utilisateurs/{utilisateur}/toggle-statut', [UtilisateurController::class, 'toggleStatut'])
            ->name('utilisateurs.toggle-statut');

        // Gestion des catégories
        Route::resource('categories', CategorieController::class)
            ->except(['show'])
            ->parameters(['categories' => 'categorie']);

        // Événements - Gestion (create, store, edit, update, cloturer)
        Route::get('/evenements/create', [EvenementController::class, 'create'])->name('evenements.create');
        Route::post('/evenements', [EvenementController::class, 'store'])->name('evenements.store');
        Route::get('/evenements/{evenement}/edit', [EvenementController::class, 'edit'])->name('evenements.edit');
        Route::put('/evenements/{evenement}', [EvenementController::class, 'update'])->name('evenements.update');
        Route::patch('/evenements/{evenement}/cloturer', [EvenementController::class, 'cloturer'])
            ->name('evenements.cloturer');

        // Cotisations - Gestion (create, store, edit, update, annuler)
        Route::get('/cotisations/create', [CotisationController::class, 'create'])->name('cotisations.create');
        Route::post('/cotisations', [CotisationController::class, 'store'])->name('cotisations.store');
        Route::get('/cotisations/{cotisation}/edit', [CotisationController::class, 'edit'])->name('cotisations.edit');
        Route::put('/cotisations/{cotisation}', [CotisationController::class, 'update'])->name('cotisations.update');
        Route::patch('/cotisations/{cotisation}/annuler', [CotisationController::class, 'annuler'])
            ->name('cotisations.annuler');

        // Dépenses - Gestion (create, store, edit, update, annuler)
        Route::get('/depenses/create', [DepenseController::class, 'create'])->name('depenses.create');
        Route::post('/depenses', [DepenseController::class, 'store'])->name('depenses.store');
        Route::get('/depenses/{depense}/edit', [DepenseController::class, 'edit'])->name('depenses.edit');
        Route::put('/depenses/{depense}', [DepenseController::class, 'update'])->name('depenses.update');
        Route::patch('/depenses/{depense}/annuler', [DepenseController::class, 'annuler'])
            ->name('depenses.annuler');
    });
});

// Route de test pour vérifier Tailwind CSS + Alpine.js
Route::get('/test', function () {
    return view('test');
})->name('test');
