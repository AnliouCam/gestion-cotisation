@extends('layouts.app')

@section('title', 'Gestion des Cotisations')

@section('page-title', 'Gestion des Cotisations')

@section('content')
    {{-- En-tête avec bouton d'ajout --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Liste des Cotisations</h1>
            <p class="text-gray-600 text-sm mt-1">Gérez et consultez toutes les cotisations enregistrées</p>
        </div>
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('cotisations.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Enregistrer une cotisation
        </a>
        @endif
    </div>

    {{-- Carte Total des cotisations actives --}}
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-md p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium mb-1">Total des Cotisations Actives</p>
                <p class="text-4xl font-bold">{{ number_format($totalActif, 0, ',', ' ') }} FCFA</p>
            </div>
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('cotisations.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Filtre par événement --}}
            <div>
                <label for="evenement_id" class="block text-sm font-medium text-gray-700 mb-1">Événement</label>
                <select name="evenement_id" id="evenement_id"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Tous les événements</option>
                    @foreach($evenements as $evenement)
                        <option value="{{ $evenement->id }}" {{ request('evenement_id') == $evenement->id ? 'selected' : '' }}>
                            {{ $evenement->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filtre par membre --}}
            <div>
                <label for="utilisateur_id" class="block text-sm font-medium text-gray-700 mb-1">Membre</label>
                <select name="utilisateur_id" id="utilisateur_id"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Tous les membres</option>
                    @foreach($utilisateurs as $utilisateur)
                        <option value="{{ $utilisateur->id }}" {{ request('utilisateur_id') == $utilisateur->id ? 'selected' : '' }}>
                            {{ $utilisateur->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filtre par statut --}}
            <div>
                <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="statut" id="statut"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Tous les statuts</option>
                    <option value="actif" {{ request('statut') === 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="annule" {{ request('statut') === 'annule' ? 'selected' : '' }}>Annulé</option>
                </select>
            </div>

            {{-- Boutons --}}
            <div class="flex items-end gap-2">
                <button type="submit"
                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                    Filtrer
                </button>
                <a href="{{ route('cotisations.index') }}"
                   class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    {{-- Tableau des cotisations --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        @if($cotisations->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Événement</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commentaire</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            @if(Auth::user()->role === 'admin')
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($cotisations as $cotisation)
                            <tr class="{{ $cotisation->statut === 'annule' ? 'bg-red-50' : 'hover:bg-gray-50' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $cotisation->date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $cotisation->utilisateur->nom }}</div>
                                    <div class="text-sm text-gray-500">{{ $cotisation->utilisateur->telephone }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $cotisation->evenement->nom }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $cotisation->evenement->date_debut->format('d/m/Y') }} - {{ $cotisation->evenement->date_fin->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold {{ $cotisation->statut === 'annule' ? 'text-red-600 line-through' : 'text-green-600' }}">
                                        {{ number_format($cotisation->montant, 0, ',', ' ') }} FCFA
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($cotisation->commentaire)
                                        <p class="text-sm text-gray-600 max-w-xs truncate" title="{{ $cotisation->commentaire }}">
                                            {{ $cotisation->commentaire }}
                                        </p>
                                    @else
                                        <span class="text-sm text-gray-400 italic">Aucun commentaire</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($cotisation->statut === 'actif')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Actif
                                        </span>
                                    @else
                                        <div>
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Annulé
                                            </span>
                                            @if($cotisation->motif_annulation)
                                                <p class="text-xs text-gray-500 mt-1" title="{{ $cotisation->motif_annulation }}">
                                                    {{ Str::limit($cotisation->motif_annulation, 30) }}
                                                </p>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                @if(Auth::user()->role === 'admin')
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        @if($cotisation->statut === 'actif' && $cotisation->evenement->statut === 'actif')
                                            <a href="{{ route('cotisations.edit', $cotisation) }}"
                                               class="text-blue-600 hover:text-blue-900">
                                                Modifier
                                            </a>
                                            <button type="button"
                                                    onclick="annulerCotisation({{ $cotisation->id }})"
                                                    class="text-red-600 hover:text-red-900">
                                                Annuler
                                            </button>
                                        @else
                                            <span class="text-gray-400 italic">Aucune action</span>
                                        @endif
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $cotisations->links() }}
            </div>
        @else
            {{-- Message si aucune cotisation --}}
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <p class="text-gray-500 text-lg">Aucune cotisation trouvée</p>
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('cotisations.create') }}"
                   class="inline-block mt-4 text-blue-600 hover:text-blue-700 font-medium">
                    Enregistrer la première cotisation →
                </a>
                @endif
            </div>
        @endif
    </div>

    {{-- Modal d'annulation --}}
    <div id="modalAnnulation" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Annuler cette cotisation</h3>
                <form id="formAnnulation" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="motif_annulation" class="block text-sm font-medium text-gray-700 mb-2">
                            Motif d'annulation (optionnel)
                        </label>
                        <textarea name="motif_annulation" id="motif_annulation" rows="3"
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Raison de l'annulation..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="fermerModal()"
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition">
                            Annuler
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition">
                            Confirmer l'annulation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function annulerCotisation(id) {
            const modal = document.getElementById('modalAnnulation');
            const form = document.getElementById('formAnnulation');
            form.action = `/cotisations/${id}/annuler`;
            modal.classList.remove('hidden');
        }

        function fermerModal() {
            const modal = document.getElementById('modalAnnulation');
            modal.classList.add('hidden');
            document.getElementById('motif_annulation').value = '';
        }

        // Fermer le modal en cliquant en dehors
        document.getElementById('modalAnnulation')?.addEventListener('click', function(e) {
            if (e.target === this) {
                fermerModal();
            }
        });
    </script>
    @endpush
@endsection
