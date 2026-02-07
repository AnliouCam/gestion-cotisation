@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('page-title', 'Tableau de bord')

@section('content')
    {{-- Message de bienvenue --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                    <span class="text-2xl">👋</span>
                </div>
            </div>
            <div class="ml-4">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                    Bienvenue, {{ Auth::user()->nom }} !
                </h2>
                <p class="text-gray-600">
                    @if(Auth::user()->role === 'admin')
                        Vous êtes connecté en tant qu'<strong class="text-blue-600">administrateur</strong>.
                        Voici un aperçu de l'activité de votre organisation.
                    @else
                        Vous êtes connecté en tant que <strong class="text-gray-700">membre</strong>.
                        Consultez toutes les informations financières en toute transparence.
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Statistiques principales --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        {{-- Événements Actifs --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Événements Actifs</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['evenements_actifs'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">
                @if(Auth::user()->role === 'admin')
                    Sur {{ $stats['total_evenements'] }} total
                @else
                    Événements en cours
                @endif
            </p>
        </div>

        {{-- Total Collecté --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Collecté</p>
                    <p class="text-3xl font-bold text-green-600">{{ number_format($stats['total_cotisations'], 0, ',', ' ') }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">{{ $stats['nombre_cotisations'] }} cotisation(s) FCFA</p>
        </div>

        {{-- Total Dépensé --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Dépensé</p>
                    <p class="text-3xl font-bold text-red-600">{{ number_format($stats['total_depenses'], 0, ',', ' ') }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">{{ $stats['nombre_depenses'] }} dépense(s) FCFA</p>
        </div>

        {{-- Solde Global --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Solde Restant</p>
                    <p class="text-3xl font-bold {{ $stats['solde_global'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                        {{ number_format($stats['solde_global'], 0, ',', ' ') }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">
                {{ $stats['solde_global'] >= 0 ? 'Excédent' : 'Déficit' }} FCFA
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- SECTION ADMIN --}}
        @if(Auth::user()->role === 'admin')
            {{-- Actions rapides --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Actions Rapides
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('evenements.create') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-blue-50 hover:border-blue-300 transition">
                        <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="text-gray-700 font-medium">Créer un événement</span>
                    </a>
                    <a href="{{ route('cotisations.create') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-green-50 hover:border-green-300 transition">
                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="text-gray-700 font-medium">Enregistrer une cotisation</span>
                    </a>
                    <a href="{{ route('depenses.create') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-red-50 hover:border-red-300 transition">
                        <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="text-gray-700 font-medium">Enregistrer une dépense</span>
                    </a>
                    <a href="{{ route('utilisateurs.create') }}" class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-purple-50 hover:border-purple-300 transition">
                        <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        <span class="text-gray-700 font-medium">Ajouter un membre</span>
                    </a>
                </div>
            </div>

            {{-- Dernières cotisations --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Dernières Cotisations
                    </h3>
                    <a href="{{ route('cotisations.index') }}" class="text-sm text-blue-600 hover:text-blue-700">Voir tout →</a>
                </div>
                @if($cotisations_recentes->count() > 0)
                    <div class="space-y-3">
                        @foreach($cotisations_recentes as $cotisation)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $cotisation->utilisateur ? $cotisation->utilisateur->nom : 'Utilisateur supprimé' }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $cotisation->evenement ? $cotisation->evenement->nom : 'Événement supprimé' }} • {{ $cotisation->date->format('d/m/Y') }}
                                    </p>
                                </div>
                                <p class="text-sm font-semibold text-green-600">{{ number_format($cotisation->montant, 0, ',', ' ') }} FCFA</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Aucune cotisation enregistrée</p>
                @endif
            </div>

            {{-- Événements actifs --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Événements Actifs ({{ $evenements_actifs->count() }})
                    </h3>
                    <a href="{{ route('evenements.index') }}" class="text-sm text-blue-600 hover:text-blue-700">Voir tout →</a>
                </div>
                @if($evenements_actifs->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Événement</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Période</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Cotisations</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Dépenses</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($evenements_actifs as $evenement)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $evenement->nom }}</p>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">
                                            {{ $evenement->date_debut->format('d/m/Y') }} - {{ $evenement->date_fin->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm text-gray-600">
                                            {{ $evenement->cotisations_count }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm text-gray-600">
                                            {{ $evenement->depenses_count }}
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route('evenements.show', $evenement) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                                Voir détails →
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Aucun événement actif</p>
                @endif
            </div>

        {{-- SECTION MEMBRE --}}
        @else
            {{-- Mes cotisations --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Mes Cotisations
                    </h3>
                    <p class="text-sm font-semibold text-green-600">{{ number_format($stats['mes_cotisations_total'], 0, ',', ' ') }} FCFA</p>
                </div>
                @if($mes_cotisations->count() > 0)
                    <div class="space-y-3">
                        @foreach($mes_cotisations as $cotisation)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $cotisation->evenement ? $cotisation->evenement->nom : 'Événement supprimé' }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $cotisation->date->format('d/m/Y') }}</p>
                                </div>
                                <p class="text-sm font-semibold text-green-600">{{ number_format($cotisation->montant, 0, ',', ' ') }} FCFA</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Aucune cotisation</p>
                @endif
            </div>

            {{-- Événements récents --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Événements Récents
                    </h3>
                    <a href="{{ route('evenements.index') }}" class="text-sm text-blue-600 hover:text-blue-700">Voir tout →</a>
                </div>
                @if($evenements_recents->count() > 0)
                    <div class="space-y-3">
                        @foreach($evenements_recents->take(5) as $evenement)
                            <a href="{{ route('evenements.show', $evenement) }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $evenement->nom }}</p>
                                    <p class="text-xs text-gray-500">{{ $evenement->date_debut->format('d/m/Y') }} - {{ $evenement->date_fin->format('d/m/Y') }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $evenement->statut === 'actif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $evenement->statut === 'actif' ? 'Actif' : 'Terminé' }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Aucun événement</p>
                @endif
            </div>

            {{-- Informations de transparence --}}
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border border-blue-200 p-6 lg:col-span-2">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-semibold text-blue-800 mb-2">Transparence Totale</h4>
                        <p class="text-sm text-blue-700">
                            « Toute personne qui donne doit pouvoir voir clairement où va chaque franc. »
                        </p>
                        <p class="text-sm text-blue-600 mt-3">
                            Cette application garantit une transparence financière totale.
                            Vous pouvez consulter tous les événements, toutes les cotisations et toutes les dépenses en temps réel.
                        </p>
                        <div class="mt-4 flex gap-3">
                            <a href="{{ route('evenements.index') }}" class="text-sm font-medium text-blue-700 hover:text-blue-800 underline">
                                Voir les événements
                            </a>
                            <a href="{{ route('cotisations.index') }}" class="text-sm font-medium text-blue-700 hover:text-blue-800 underline">
                                Voir les cotisations
                            </a>
                            <a href="{{ route('depenses.index') }}" class="text-sm font-medium text-blue-700 hover:text-blue-800 underline">
                                Voir les dépenses
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
