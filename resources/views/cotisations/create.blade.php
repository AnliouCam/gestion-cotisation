@extends('layouts.app')

@section('title', 'Enregistrer une Cotisation')

@section('page-title', 'Enregistrer une Cotisation')

@section('content')
    <div class="max-w-3xl mx-auto">
        {{-- En-tête --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Nouvelle Cotisation</h1>
            <p class="text-gray-600 text-sm mt-1">Enregistrer une cotisation pour un membre et un événement</p>
        </div>

        {{-- Formulaire --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('cotisations.store') }}" method="POST">
                @csrf

                {{-- Sélection du membre --}}
                <div class="mb-6">
                    <label for="utilisateur_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Membre <span class="text-red-500">*</span>
                    </label>
                    <select name="utilisateur_id" id="utilisateur_id" required
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('utilisateur_id') border-red-500 @enderror">
                        <option value="">Sélectionner un membre</option>
                        @foreach($utilisateurs as $utilisateur)
                            <option value="{{ $utilisateur->id }}" {{ old('utilisateur_id') == $utilisateur->id ? 'selected' : '' }}>
                                {{ $utilisateur->nom }} ({{ $utilisateur->telephone }})
                            </option>
                        @endforeach
                    </select>
                    @error('utilisateur_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Sélection de l'événement --}}
                <div class="mb-6">
                    <label for="evenement_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Événement <span class="text-red-500">*</span>
                    </label>
                    <select name="evenement_id" id="evenement_id" required
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('evenement_id') border-red-500 @enderror">
                        <option value="">Sélectionner un événement</option>
                        @foreach($evenements as $evenement)
                            <option value="{{ $evenement->id }}"
                                    {{ (old('evenement_id', $evenementSelectionne) == $evenement->id) ? 'selected' : '' }}>
                                {{ $evenement->nom }}
                                ({{ $evenement->date_debut->format('d/m/Y') }} - {{ $evenement->date_fin->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                    @error('evenement_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if($evenements->count() === 0)
                        <p class="mt-2 text-sm text-amber-600">
                            ⚠️ Aucun événement actif disponible.
                            <a href="{{ route('evenements.create') }}" class="text-blue-600 hover:text-blue-800 underline">
                                Créer un événement
                            </a>
                        </p>
                    @endif
                </div>

                {{-- Montant --}}
                <div class="mb-6">
                    <label for="montant" class="block text-sm font-medium text-gray-700 mb-2">
                        Montant (FCFA) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" name="montant" id="montant" step="0.01" min="0.01" required
                               value="{{ old('montant') }}"
                               placeholder="Ex: 50000"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('montant') border-red-500 @enderror">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">FCFA</span>
                        </div>
                    </div>
                    @error('montant')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Le montant doit être supérieur à 0</p>
                </div>

                {{-- Date --}}
                <div class="mb-6">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                        Date de la cotisation <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="date" id="date" required
                           value="{{ old('date', date('Y-m-d')) }}"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('date') border-red-500 @enderror">
                    @error('date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Commentaire optionnel --}}
                <div class="mb-6">
                    <label for="commentaire" class="block text-sm font-medium text-gray-700 mb-2">
                        Commentaire (optionnel)
                    </label>
                    <textarea name="commentaire" id="commentaire" rows="3"
                              placeholder="Ajouter une note ou un commentaire..."
                              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('commentaire') border-red-500 @enderror">{{ old('commentaire') }}</textarea>
                    @error('commentaire')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Maximum 1000 caractères</p>
                </div>

                {{-- Boutons --}}
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('cotisations.index') }}"
                       class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                        Enregistrer la cotisation
                    </button>
                </div>
            </form>
        </div>

        {{-- Informations de transparence --}}
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="text-sm font-semibold text-blue-900 mb-1">Transparence Totale</h4>
                    <p class="text-sm text-blue-700">
                        Cette cotisation sera visible par tous les membres de l'organisation.
                        L'historique sera préservé même en cas d'annulation.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
