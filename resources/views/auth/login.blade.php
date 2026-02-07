<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion des Cotisations</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full">
        <!-- Logo / Titre -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Gestion des Cotisations</h1>
            <p class="text-gray-600 mt-2">Application de Transparence Financière</p>
        </div>

        <!-- Carte de connexion -->
        <div class="card">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Connexion</h2>

            <!-- Messages flash -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4" role="alert">
                    {{ session('info') }}
                </div>
            @endif

            <!-- Formulaire de connexion -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Téléphone -->
                <div class="mb-4">
                    <label for="telephone" class="form-label">Numéro de téléphone</label>
                    <input
                        type="text"
                        id="telephone"
                        name="telephone"
                        value="{{ old('telephone') }}"
                        class="form-input @error('telephone') border-red-500 @enderror"
                        placeholder="Ex: 0123456789"
                        autofocus
                        required
                    >
                    @error('telephone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mot de passe -->
                <div class="mb-6">
                    <label for="mot_de_passe" class="form-label">Mot de passe</label>
                    <input
                        type="password"
                        id="mot_de_passe"
                        name="mot_de_passe"
                        class="form-input @error('mot_de_passe') border-red-500 @enderror"
                        placeholder="••••••••"
                        required
                    >
                    @error('mot_de_passe')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bouton de connexion -->
                <button type="submit" class="btn-primary w-full">
                    Se connecter
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
