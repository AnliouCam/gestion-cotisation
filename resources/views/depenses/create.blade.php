@extends('layouts.app')

@section('title', 'Enregistrer une Dépense')

@section('page-title', 'Enregistrer une Dépense')

@section('content')
    <div class="max-w-3xl mx-auto">
        {{-- En-tête --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Nouvelle Dépense</h1>
            <p class="text-gray-600 text-sm mt-1">Enregistrer une dépense pour un événement</p>
        </div>

        {{-- Formulaire --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('depenses.store') }}" method="POST">
                @csrf

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

                {{-- Sélection de la catégorie --}}
                <div class="mb-6">
                    <label for="categorie_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Catégorie <span class="text-red-500">*</span>
                    </label>
                    <select name="categorie_id" id="categorie_id" required
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('categorie_id') border-red-500 @enderror">
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                                {{ $categorie->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('categorie_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if($categories->count() === 0)
                        <p class="mt-2 text-sm text-amber-600">
                            ⚠️ Aucune catégorie disponible.
                            <a href="{{ route('categories.create') }}" class="text-blue-600 hover:text-blue-800 underline">
                                Créer une catégorie
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
                               placeholder="Ex: 25000"
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

                {{-- Description --}}
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="3" required
                              placeholder="Description de la dépense..."
                              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Maximum 1000 caractères</p>
                </div>

                {{-- Justification optionnelle --}}
                <div class="mb-6">
                    <label for="justification" class="block text-sm font-medium text-gray-700 mb-2">
                        Justification (optionnel)
                    </label>
                    <textarea name="justification" id="justification" rows="3"
                              placeholder="Ex: Facture magasin XYZ ref #123, Reçu n°456..."
                              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('justification') border-red-500 @enderror">{{ old('justification') }}</textarea>
                    @error('justification')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Référence de facture, reçu, ou autre justificatif (texte uniquement)</p>
                </div>

                {{-- Boutons --}}
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('depenses.index') }}"
                       class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                        Enregistrer la dépense
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
                        Cette dépense sera visible par tous les membres de l'organisation.
                        L'historique sera préservé même en cas d'annulation.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
