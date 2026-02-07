@extends('layouts.app')

@section('title', $evenement->nom)

@section('page-title', 'Détail de l\'événement')

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
                    <span class="ml-1 text-gray-500 md:ml-2">{{ Str::limit($evenement->nom, 30) }}</span>
                </div>
            </li>
        </ol>
    </nav>

    {{-- En-tête de l'événement --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex items-start justify-between">
                <div class="flex items-center">
                    <div class="w-14 h-14 rounded-full bg-purple-500 flex items-center justify-center text-white font-bold text-2xl">
                        {{ strtoupper(substr($evenement->nom, 0, 1)) }}
                    </div>
                    <div class="ml-4">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $evenement->nom }}</h3>
                        <div class="flex items-center mt-1 space-x-3">
                            <span class="text-sm text-gray-500">
                                Du {{ \Carbon\Carbon::parse($evenement->date_debut)->format('d/m/Y') }}
                                au {{ \Carbon\Carbon::parse($evenement->date_fin)->format('d/m/Y') }}
                            </span>
                            @if($evenement->statut === 'actif')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3" />
                                    </svg>
                                    Actif
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3" />
                                    </svg>
                                    Terminé
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @if(Auth::user()->estAdmin())
                    <div class="flex space-x-2">
                        @if($evenement->statut === 'actif')
                            <a href="{{ route('evenements.edit', $evenement) }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 bg-white font-medium rounded-lg hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Modifier
                            </a>
                            <form method="POST" action="{{ route('evenements.cloturer', $evenement) }}"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir clôturer cet événement ? Il deviendra lecture seule.');">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Clôturer
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
            @if($evenement->description)
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-700">{{ $evenement->description }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- RÉSUMÉ FINANCIER (CŒUR DU SYSTÈME) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        {{-- Total Cotisations --}}
        <div class="bg-white rounded-lg shadow-sm border-2 border-green-200 overflow-hidden">
            <div class="px-6 py-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Cotisations</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($totalCotisations, 0, ',', ' ') }} FCFA</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $evenement->cotisations->count() }} cotisation(s)</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Dépenses --}}
        <div class="bg-white rounded-lg shadow-sm border-2 border-red-200 overflow-hidden">
            <div class="px-6 py-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Dépenses</p>
                        <p class="text-2xl font-bold text-red-600">{{ number_format($totalDepenses, 0, ',', ' ') }} FCFA</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $evenement->depenses->count() }} dépense(s)</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Solde --}}
        <div class="bg-white rounded-lg shadow-sm border-2 border-blue-200 overflow-hidden">
            <div class="px-6 py-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Solde</p>
                        <p class="text-2xl font-bold {{ $solde >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                            {{ number_format($solde, 0, ',', ' ') }} FCFA
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $solde >= 0 ? 'Excédent' : 'Déficit' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs : Cotisations / Dépenses --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200" x-data="{ tab: 'cotisations' }">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button @click="tab = 'cotisations'"
                        :class="tab === 'cotisations' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="px-6 py-3 border-b-2 font-medium text-sm transition">
                    Cotisations ({{ $evenement->cotisations->count() }})
                </button>
                <button @click="tab = 'depenses'"
                        :class="tab === 'depenses' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="px-6 py-3 border-b-2 font-medium text-sm transition">
                    Dépenses ({{ $evenement->depenses->count() }})
                </button>
            </nav>
        </div>

        {{-- Contenu Cotisations --}}
        <div x-show="tab === 'cotisations'" class="p-6">
            {{-- Bouton d'ajout (si admin et événement actif) --}}
            @if(Auth::user()->role === 'admin' && $evenement->statut === 'actif')
                <div class="mb-4">
                    <a href="{{ route('cotisations.create', ['evenement_id' => $evenement->id]) }}"
                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Enregistrer une cotisation pour cet événement
                    </a>
                </div>
            @endif

            @if($evenement->cotisations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Membre</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Commentaire</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                @if(Auth::user()->role === 'admin' && $evenement->statut === 'actif')
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($evenement->cotisations as $cotisation)
                                <tr class="{{ $cotisation->statut === 'annule' ? 'bg-red-50' : '' }}">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $cotisation->utilisateur->nom }}</td>
                                    <td class="px-4 py-3 text-sm font-semibold {{ $cotisation->statut === 'annule' ? 'text-red-600 line-through' : 'text-green-600' }}">
                                        {{ number_format($cotisation->montant, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($cotisation->date)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500">
                                        {{ $cotisation->commentaire ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @if($cotisation->statut === 'actif')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Actif
                                            </span>
                                        @else
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Annulé
                                            </span>
                                        @endif
                                    </td>
                                    @if(Auth::user()->role === 'admin' && $evenement->statut === 'actif')
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm">
                                        @if($cotisation->statut === 'actif')
                                            <a href="{{ route('cotisations.edit', $cotisation) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                                Modifier
                                            </a>
                                        @endif
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <p class="text-gray-500 mb-3">Aucune cotisation pour cet événement</p>
                    @if(Auth::user()->role === 'admin' && $evenement->statut === 'actif')
                        <a href="{{ route('cotisations.create', ['evenement_id' => $evenement->id]) }}"
                           class="inline-flex items-center text-green-600 hover:text-green-700 font-medium">
                            Enregistrer la première cotisation →
                        </a>
                    @endif
                </div>
            @endif
        </div>

        {{-- Contenu Dépenses --}}
        <div x-show="tab === 'depenses'" class="p-6">
            {{-- Bouton d'ajout (si admin et événement actif) --}}
            @if(Auth::user()->role === 'admin' && $evenement->statut === 'actif')
                <div class="mb-4">
                    <a href="{{ route('depenses.create', ['evenement_id' => $evenement->id]) }}"
                       class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Enregistrer une dépense pour cet événement
                    </a>
                </div>
            @endif

            @if($evenement->depenses->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catégorie</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Justification</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                @if(Auth::user()->role === 'admin' && $evenement->statut === 'actif')
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($evenement->depenses as $depense)
                                <tr class="{{ $depense->statut === 'annule' ? 'bg-red-50' : '' }}">
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            {{ $depense->categorie->nom }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ Str::limit($depense->description, 50) }}</td>
                                    <td class="px-4 py-3 text-sm font-semibold {{ $depense->statut === 'annule' ? 'text-gray-600 line-through' : 'text-red-600' }}">
                                        {{ number_format($depense->montant, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500">
                                        {{ $depense->justification ? Str::limit($depense->justification, 30) : '-' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @if($depense->statut === 'actif')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Actif
                                            </span>
                                        @else
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Annulé
                                            </span>
                                        @endif
                                    </td>
                                    @if(Auth::user()->role === 'admin' && $evenement->statut === 'actif')
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm">
                                        @if($depense->statut === 'actif')
                                            <a href="{{ route('depenses.edit', $depense) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                                Modifier
                                            </a>
                                        @endif
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                    </svg>
                    <p class="text-gray-500 mb-3">Aucune dépense pour cet événement</p>
                    @if(Auth::user()->role === 'admin' && $evenement->statut === 'actif')
                        <a href="{{ route('depenses.create', ['evenement_id' => $evenement->id]) }}"
                           class="inline-flex items-center text-red-600 hover:text-red-700 font-medium">
                            Enregistrer la première dépense →
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Avertissement si événement terminé --}}
    @if($evenement->statut === 'termine')
        <div class="mt-6 bg-orange-50 border-l-4 border-orange-500 p-4 rounded-r-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-orange-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div class="text-sm text-orange-700">
                    <p class="font-medium mb-1">Événement clôturé</p>
                    <p>Cet événement est terminé et en lecture seule. Aucune modification n'est possible.</p>
                </div>
            </div>
        </div>
    @endif
@endsection
