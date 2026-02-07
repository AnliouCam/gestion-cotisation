@extends('layouts.app')

@section('title', 'Modifier un membre')

@section('page-title', 'Modifier un membre')

@section('content')
    {{-- Breadcrumb --}}
    <nav class="mb-6 flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('utilisateurs.index') }}" class="text-gray-700 hover:text-blue-600 inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Membres
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-gray-500 md:ml-2">Modifier</span>
                </div>
            </li>
        </ol>
    </nav>

    {{-- Formulaire --}}
    <div class="max-w-2xl">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="mb-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold text-xl">
                        {{ strtoupper(substr($utilisateur->nom, 0, 1)) }}
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $utilisateur->nom }}</h3>
                        <p class="text-sm text-gray-500">
                            {{ $utilisateur->role === 'admin' ? 'Administrateur' : 'Membre' }} -
                            <span class="{{ $utilisateur->statut === 'actif' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $utilisateur->statut === 'actif' ? 'Actif' : 'Inactif' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('utilisateurs.update', $utilisateur) }}">
                @csrf
                @method('PUT')

                {{-- Nom complet --}}
                <div class="mb-4">
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                        Nom complet <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="nom"
                        name="nom"
                        value="{{ old('nom', $utilisateur->nom) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nom') border-red-500 @enderror"
                        placeholder="Ex: Jean Dupont"
                        required
                        autofocus
                    >
                    @error('nom')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Numéro de téléphone --}}
                <div class="mb-4">
                    <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">
                        Numéro de téléphone <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="telephone"
                        name="telephone"
                        value="{{ old('telephone', $utilisateur->telephone) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('telephone') border-red-500 @enderror"
                        placeholder="Ex: 0123456789"
                        required
                    >
                    @error('telephone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-xs mt-1">
                        Le téléphone sera utilisé comme identifiant de connexion
                    </p>
                </div>

                {{-- Date d'adhésion (lecture seule) --}}
                <div class="mb-6">
                    <label for="date_adhesion" class="block text-sm font-medium text-gray-700 mb-2">
                        Date d'adhésion
                    </label>
                    <input
                        type="text"
                        id="date_adhesion"
                        value="{{ \Carbon\Carbon::parse($utilisateur->date_adhesion)->format('d/m/Y') }}"
                        class="w-full px-4 py-2 border border-gray-300 bg-gray-50 rounded-lg text-gray-600 cursor-not-allowed"
                        disabled
                        readonly
                    >
                    <p class="text-gray-500 text-xs mt-1">
                        La date d'adhésion ne peut pas être modifiée
                    </p>
                </div>

                {{-- Info box --}}
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-sm text-blue-700">
                            <p class="font-medium mb-1">Informations</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Vous ne pouvez pas modifier le mot de passe ici</li>
                                <li>Le membre peut changer son mot de passe depuis son compte</li>
                                <li>Le rôle ne peut pas être modifié (sécurité)</li>
                                <li>Pour désactiver ce membre, utilisez le bouton dans la liste</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Boutons d'action --}}
                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <a href="{{ route('utilisateurs.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour à la liste
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>

        {{-- Informations supplémentaires --}}
        <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h4 class="text-sm font-semibold text-gray-800 mb-3">Informations du compte</h4>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Rôle :</dt>
                    <dd class="text-gray-900 font-medium">{{ $utilisateur->role === 'admin' ? 'Administrateur' : 'Membre' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Statut :</dt>
                    <dd class="font-medium {{ $utilisateur->statut === 'actif' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $utilisateur->statut === 'actif' ? 'Actif' : 'Inactif' }}
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Créé le :</dt>
                    <dd class="text-gray-900">{{ \Carbon\Carbon::parse($utilisateur->created_at)->format('d/m/Y à H:i') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Dernière modification :</dt>
                    <dd class="text-gray-900">{{ \Carbon\Carbon::parse($utilisateur->updated_at)->format('d/m/Y à H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>
@endsection
