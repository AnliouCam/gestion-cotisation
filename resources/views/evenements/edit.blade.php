@extends('layouts.app')

@section('title', 'Modifier l\'événement')

@section('page-title', 'Modifier l\'événement')

@section('content')
    {{-- Breadcrumb --}}
    <nav class="mb-6 flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('evenements.index') }}" class="text-gray-700 hover:text-blue-600 inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Événements
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
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Modifier : {{ $evenement->nom }}</h3>
            <p class="text-sm text-gray-500 mt-1">
                Créé le {{ \Carbon\Carbon::parse($evenement->created_at)->format('d/m/Y à H:i') }}
            </p>
        </div>

        <form method="POST" action="{{ route('evenements.update', $evenement) }}" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Colonne gauche - Formulaire --}}
                <div class="space-y-6">
                    {{-- Nom de l'événement --}}
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom de l'événement <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="nom"
                            name="nom"
                            value="{{ old('nom', $evenement->nom) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nom') border-red-500 @enderror"
                            placeholder="Ex: Cotisation construction temple"
                            required
                            autofocus
                        >
                        @error('nom')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                            placeholder="Décrivez l'objectif de cet événement..."
                        >{{ old('description', $evenement->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Date de début --}}
                    <div>
                        <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-2">
                            Date de début <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="date"
                            id="date_debut"
                            name="date_debut"
                            value="{{ old('date_debut', $evenement->date_debut) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('date_debut') border-red-500 @enderror"
                            required
                        >
                        @error('date_debut')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Date de fin --}}
                    <div>
                        <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-2">
                            Date de fin <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="date"
                            id="date_fin"
                            name="date_fin"
                            value="{{ old('date_fin', $evenement->date_fin) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('date_fin') border-red-500 @enderror"
                            required
                        >
                        @error('date_fin')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">
                            La date de fin doit être postérieure ou égale à la date de début
                        </p>
                    </div>
                </div>

                {{-- Colonne droite - Informations --}}
                <div class="space-y-6">
                    {{-- Statistiques --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Statistiques actuelles</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Statut :</span>
                                <span class="text-sm font-semibold {{ $evenement->statut === 'actif' ? 'text-green-600' : 'text-gray-600' }}">
                                    {{ $evenement->statut === 'actif' ? 'Actif' : 'Terminé' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Cotisations :</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $evenement->cotisations()->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Dépenses :</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $evenement->depenses()->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-gray-300">
                                <span class="text-sm text-gray-600">Dernière modification :</span>
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($evenement->updated_at)->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Info box --}}
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-5 rounded-r-lg">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm text-blue-700">
                                <p class="font-semibold mb-2">À propos des modifications</p>
                                <ul class="space-y-1">
                                    <li>• La modification affectera toutes les données liées</li>
                                    <li>• Le statut ne peut pas être modifié ici</li>
                                    <li>• Utilisez "Clôturer" depuis la page de détail</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Boutons d'action --}}
            <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row gap-3 justify-between">
                <a href="{{ route('evenements.show', $evenement) }}"
                   class="inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 text-gray-700 bg-white font-medium rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center px-8 py-3 bg-blue-600 text-white font-semibold text-lg rounded-lg hover:bg-blue-700 shadow-md hover:shadow-lg transition">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
@endsection
