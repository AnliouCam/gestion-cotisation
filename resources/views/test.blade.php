@extends('layouts.app')

@section('title', 'Page de test')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Test Tailwind CSS --}}
    <div class="card mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">🎨 Test de Tailwind CSS</h2>
        <p class="text-gray-600 mb-4">
            Si vous voyez des couleurs et un design agréable, Tailwind CSS fonctionne correctement !
        </p>

        <div class="flex space-x-4">
            <button class="btn-primary">Bouton Primary</button>
            <button class="btn-secondary">Bouton Secondary</button>
            <button class="btn-danger">Bouton Danger</button>
        </div>
    </div>

    {{-- Test Alpine.js --}}
    <div class="card mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">⚡ Test d'Alpine.js</h2>
        <p class="text-gray-600 mb-4">
            Cliquez sur le bouton pour tester l'interactivité Alpine.js :
        </p>

        {{-- Composant Alpine.js simple --}}
        <div x-data="{ count: 0 }" class="space-y-4">
            <div class="flex items-center space-x-4">
                <button @click="count++" class="btn-primary">
                    Incrémenter
                </button>
                <button @click="count--" class="btn-secondary">
                    Décrémenter
                </button>
                <button @click="count = 0" class="btn-danger">
                    Réinitialiser
                </button>
            </div>

            <div class="bg-primary-50 border border-primary-200 rounded-lg p-4">
                <p class="text-lg">
                    Compteur : <span class="font-bold text-primary-700" x-text="count"></span>
                </p>
            </div>
        </div>
    </div>

    {{-- Test des badges de statut --}}
    <div class="card">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">🏷️ Test des badges de statut</h2>
        <div class="flex space-x-4">
            <span class="badge-actif">Actif</span>
            <span class="badge-annule">Annulé</span>
            <span class="badge-termine">Terminé</span>
        </div>
    </div>

    {{-- Informations de configuration --}}
    <div class="card mt-6 bg-blue-50 border border-blue-200">
        <h3 class="text-lg font-semibold text-blue-800 mb-2">✅ Configuration réussie !</h3>
        <ul class="text-blue-700 space-y-1 text-sm">
            <li>✅ Laravel {{ app()->version() }}</li>
            <li>✅ Tailwind CSS (via Vite)</li>
            <li>✅ Alpine.js</li>
            <li>✅ Layout de base créé</li>
        </ul>
    </div>
</div>
@endsection
