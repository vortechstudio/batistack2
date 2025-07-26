<div class="p-6 bg-white rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">📊 Rapport PHPStan</h1>
            <p class="text-gray-600 mt-1">Analyse statique du code PHP</p>
        </div>
        <div class="flex gap-2">
            <button wire:click="reloadReport"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                🔄 Recharger
            </button>
            @if ($errorMessage)
                <button wire:click="fixExistingReport"
                        class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 transition-colors">
                    🔧 Corriger Fichier
                </button>
            @endif
            <button wire:click="generateReport"
                    class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors">
                🔧 Générer Rapport
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    @if ($errorMessage)
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <h3 class="text-lg font-semibold text-red-800 mb-2">❌ Erreur de traitement</h3>
            <p class="text-red-700">{{ $errorMessage }}</p>
            <p class="text-sm text-red-600 mt-2">
                💡 <strong>Solution :</strong> Cliquez sur "Corriger Fichier" pour nettoyer automatiquement le fichier JSON existant.
            </p>
            <details class="mt-3">
                <summary class="cursor-pointer text-red-600 hover:text-red-800">Voir les détails de débogage</summary>
                <div class="mt-2 p-3 bg-red-100 rounded text-sm">
                    @foreach ($debugInfo as $info)
                        <div class="mb-1">{{ $info }}</div>
                    @endforeach
                </div>
            </details>
        </div>
    @endif

    @if ($hasReport && !empty($reportData))
        <div class="space-y-6">
            <!-- Statistiques générales -->
            @if (isset($reportData['totals']))
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <h3 class="text-lg font-semibold text-blue-800">📊 Total des erreurs</h3>
                        <p class="text-2xl font-bold text-blue-600">{{ $reportData['totals']['errors'] ?? 0 }}</p>
                    </div>
                    <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
                        <h3 class="text-lg font-semibold text-orange-800">📁 Fichiers avec erreurs</h3>
                        <p class="text-2xl font-bold text-orange-600">{{ $reportData['totals']['file_errors'] ?? 0 }}</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <h3 class="text-lg font-semibold text-green-800">📈 Statut</h3>
                        <p class="text-lg font-semibold {{ ($reportData['totals']['errors'] ?? 0) > 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ ($reportData['totals']['errors'] ?? 0) > 0 ? 'Erreurs détectées' : 'Aucune erreur' }}
                        </p>
                    </div>
                </div>
            @endif

            <!-- Liste des fichiers avec erreurs -->
            @if (isset($reportData['files']) && !empty($reportData['files']))
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">📋 Détails des erreurs par fichier</h3>

                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        @foreach ($reportData['files'] as $filePath => $fileData)
                            <div class="bg-white p-4 rounded border border-gray-200">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium text-gray-900 break-all">
                                        📄 {{ basename($filePath) }}
                                    </h4>
                                    <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded">
                                        {{ $fileData['errors'] ?? 0 }} erreur(s)
                                    </span>
                                </div>

                                <p class="text-sm text-gray-600 mb-3 break-all">{{ $filePath }}</p>

                                @if (isset($fileData['messages']) && !empty($fileData['messages']))
                                    <div class="space-y-2">
                                        @foreach ($fileData['messages'] as $message)
                                            <div class="bg-red-50 p-3 rounded border-l-4 border-red-400">
                                                <div class="flex justify-between items-start">
                                                    <div class="flex-1">
                                                        <p class="text-sm text-red-800 font-medium">
                                                            Ligne {{ $message['line'] ?? 'N/A' }}: {{ $message['message'] ?? 'Message non disponible' }}
                                                        </p>
                                                        @if (isset($message['tip']))
                                                            <p class="text-xs text-red-600 mt-1">💡 {{ $message['tip'] }}</p>
                                                        @endif
                                                        @if (isset($message['identifier']))
                                                            <p class="text-xs text-gray-500 mt-1">ID: {{ $message['identifier'] }}</p>
                                                        @endif
                                                    </div>
                                                    @if (isset($message['ignorable']) && $message['ignorable'])
                                                        <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded ml-2">
                                                            Ignorable
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @elseif (!$hasReport)
        <div class="text-center py-12">
            <div class="text-6xl mb-4">📊</div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucun rapport PHPStan disponible</h3>
            <p class="text-gray-600 mb-6">Cliquez sur "Générer Rapport" pour créer un nouveau rapport d'analyse.</p>

            @if (!empty($debugInfo))
                <details class="mt-4 text-left">
                    <summary class="cursor-pointer text-blue-600 hover:text-blue-800">Voir les informations de débogage</summary>
                    <div class="mt-2 p-4 bg-gray-100 rounded text-sm">
                        @foreach ($debugInfo as $info)
                            <div class="mb-1">{{ $info }}</div>
                        @endforeach
                    </div>
                </details>
            @endif
        </div>
    @endif

    <!-- Informations sur le chemin du rapport -->
    @if ($reportPath)
        <div class="mt-6 p-3 bg-gray-100 rounded text-sm">
            <strong>Chemin du rapport:</strong> <code class="bg-gray-200 px-1 rounded">{{ $reportPath }}</code>
        </div>
    @endif
</div>
