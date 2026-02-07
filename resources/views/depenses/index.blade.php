@extends('layouts.app')

@section('title', 'Gestion des Dépenses')

@section('page-title', 'Gestion des Dépenses')

@section('content')
    {{-- En-tête avec bouton d'ajout --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Liste des Dépenses</h1>
            <p class="text-gray-600 text-sm mt-1">Gérez et consultez toutes les dépenses enregistrées</p>
        </div>
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('depenses.create') }}"
           class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Enregistrer une dépense
        </a>
        @endif
    </div>

    {{-- Carte Total des dépenses actives --}}
    <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-md p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-100 text-sm font-medium mb-1">Total des Dépenses Actives</p>
                <p class="text-4xl font-bold">{{ number_format($totalActif, 0, ',', ' ') }} FCFA</p>
            </div>
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
            </div>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('depenses.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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

            {{-- Filtre par catégorie --}}
            <div>
                <label for="categorie_id" class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                <select name="categorie_id" id="categorie_id"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}" {{ request('categorie_id') == $categorie->id ? 'selected' : '' }}>
                            {{ $categorie->nom }}
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
                <a href="{{ route('depenses.index') }}"
                   class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    {{-- Tableau des dépenses --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        @if($depenses->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Événement</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            @if(Auth::user()->role === 'admin')
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($depenses as $depense)
                            <tr class="{{ $depense->statut === 'annule' ? 'bg-red-50' : 'hover:bg-gray-50' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $depense->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $depense->evenement->nom }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $depense->evenement->date_debut->format('d/m/Y') }} - {{ $depense->evenement->date_fin->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        {{ $depense->categorie->nom }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900 max-w-xs truncate" title="{{ $depense->description }}">
                                        {{ $depense->description }}
                                    </p>
                                    @if($depense->justification)
                                        <p class="text-xs text-gray-500 mt-1" title="{{ $depense->justification }}">
                                            Justif: {{ Str::limit($depense->justification, 30) }}
                                        </p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold {{ $depense->statut === 'annule' ? 'text-gray-600 line-through' : 'text-red-600' }}">
                                        {{ number_format($depense->montant, 0, ',', ' ') }} FCFA
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($depense->statut === 'actif')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Actif
                                        </span>
                                    @else
                                        <div>
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Annulé
                                            </span>
                                            @if($depense->motif_annulation)
                                                <p class="text-xs text-gray-500 mt-1" title="{{ $depense->motif_annulation }}">
                                                    {{ Str::limit($depense->motif_annulation, 30) }}
                                                </p>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                @if(Auth::user()->role === 'admin')
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        @if($depense->statut === 'actif' && $depense->evenement->statut === 'actif')
                                            <a href="{{ route('depenses.edit', $depense) }}"
                                               class="text-blue-600 hover:text-blue-900">
                                                Modifier
                                            </a>
                                            <button type="button"
                                                    onclick="annulerDepense({{ $depense->id }})"
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
                {{ $depenses->links() }}
            </div>
        @else
            {{-- Message si aucune dépense --}}
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <p class="text-gray-500 text-lg">Aucune dépense trouvée</p>
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('depenses.create') }}"
                   class="inline-block mt-4 text-red-600 hover:text-red-700 font-medium">
                    Enregistrer la première dépense →
                </a>
                @endif
            </div>
        @endif
    </div>

    {{-- Modal d'annulation --}}
    <div id="modalAnnulation" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Annuler cette dépense</h3>
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
        function annulerDepense(id) {
            const modal = document.getElementById('modalAnnulation');
            const form = document.getElementById('formAnnulation');
            form.action = `/depenses/${id}/annuler`;
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
