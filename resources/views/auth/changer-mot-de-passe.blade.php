<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer le mot de passe - Gestion des Cotisations</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full">
        <!-- Logo / Titre -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Gestion des Cotisations</h1>
            <p class="text-gray-600 mt-2">Changement de mot de passe</p>
        </div>

        <!-- Carte de changement de mot de passe -->
        <div class="card">
            <h2 class="text-2xl font-semibold text-gray-800 mb-2">Nouveau mot de passe</h2>
            <p class="text-gray-600 mb-6">Pour votre sécurité, veuillez définir un nouveau mot de passe.</p>

            <!-- Messages flash -->
            @if (session('info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4" role="alert">
                    {{ session('info') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Formulaire de changement de mot de passe -->
            <form method="POST" action="{{ route('changer-mot-de-passe') }}">
                @csrf

                <!-- Mot de passe actuel -->
                <div class="mb-4">
                    <label for="mot_de_passe_actuel" class="form-label">Mot de passe actuel</label>
                    <input
                        type="password"
                        id="mot_de_passe_actuel"
                        name="mot_de_passe_actuel"
                        class="form-input @error('mot_de_passe_actuel') border-red-500 @enderror"
                        placeholder="••••••••"
                        autofocus
                        required
                    >
                    @error('mot_de_passe_actuel')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nouveau mot de passe -->
                <div class="mb-4">
                    <label for="nouveau_mot_de_passe" class="form-label">Nouveau mot de passe</label>
                    <input
                        type="password"
                        id="nouveau_mot_de_passe"
                        name="nouveau_mot_de_passe"
                        class="form-input @error('nouveau_mot_de_passe') border-red-500 @enderror"
                        placeholder="••••••••"
                        required
                    >
                    @error('nouveau_mot_de_passe')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">Minimum 6 caractères</p>
                </div>

                <!-- Confirmation du nouveau mot de passe -->
                <div class="mb-6">
                    <label for="nouveau_mot_de_passe_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                    <input
                        type="password"
                        id="nouveau_mot_de_passe_confirmation"
                        name="nouveau_mot_de_passe_confirmation"
                        class="form-input"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <!-- Bouton de validation -->
                <button type="submit" class="btn-primary w-full">
                    Changer le mot de passe
                </button>
            </form>

            <!-- Bouton de déconnexion -->
            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="btn-secondary w-full">
                    Se déconnecter
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-gray-600 text-sm">
            <p>Transparence financière totale pour votre organisation</p>
        </div>
    </div>
</body>
</html>
