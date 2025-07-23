<div>
    <div class="bg-gray-100 rounded-tr-md rounded-br-md p-5">
        <h2 class="text-2xl font-bold mb-4">Fiches de Paie</h2>
        <p class="text-gray-600 mb-6">Bulletins de paie et documents de rémunération - Matricule : {{ $matricule }}</p>

        @if(session()->has('error'))
            <x-mary-alert class="alert-error mb-4" icon="o-exclamation-triangle" title="Erreur" description="{{ session('error') }}" />
        @endif

        @if(count($documents) > 0)
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-base-200">
                            <th class="text-left">Document</th>
                            <th class="text-center">Période</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">Taille</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $document)
                            <tr class="hover:bg-base-50">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar">
                                            <div class="mask mask-squircle w-10 h-10 bg-green-100 flex items-center justify-center">
                                                @if(str_ends_with(strtolower($document['name']), '.pdf'))
                                                    <x-heroicon-o-document-text class="w-5 h-5 text-red-600" />
                                                @elseif(in_array(strtolower(pathinfo($document['name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                                                    <x-heroicon-o-photo class="w-5 h-5 text-green-600" />
                                                @else
                                                    <x-heroicon-o-document class="w-5 h-5 text-gray-600" />
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-sm">{{ $document['name'] }}</div>
                                            <div class="text-xs text-gray-500">{{ $this->formatFileSize($document['size']) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-outline badge-lg">{{ $document['period'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge
                                        @if($document['type'] === 'Bulletin de paie') badge-success
                                        @elseif($document['type'] === 'Solde de tout compte') badge-warning
                                        @elseif($document['type'] === 'Attestation Pôle Emploi') badge-info
                                        @elseif($document['type'] === 'Prime/Bonus') badge-secondary
                                        @else badge-neutral
                                        @endif">
                                        {{ $document['type'] }}
                                    </span>
                                </td>
                                <td class="text-center text-sm">{{ $this->formatFileSize($document['size']) }}</td>
                                <td class="text-center text-sm">{{ date('d/m/Y', $document['lastModified']) }}</td>
                                <td class="text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ $document['url'] }}" target="_blank"
                                           class="btn btn-ghost btn-sm tooltip" data-tip="Visualiser">
                                            <x-heroicon-o-eye class="w-4 h-4" />
                                        </a>
                                        <button wire:click="downloadDocument('{{ $document['path'] }}')"
                                                class="btn btn-ghost btn-sm tooltip" data-tip="Télécharger">
                                            <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <div class="flex items-start gap-3">
                    <x-heroicon-o-information-circle class="w-5 h-5 text-blue-600 mt-0.5" />
                    <div class="text-sm text-blue-800">
                        <p class="font-medium mb-1">Information importante</p>
                        <p>Vos bulletins de paie sont conservés de manière sécurisée. En cas de perte ou de besoin d'un bulletin antérieur, contactez le service RH.</p>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <x-heroicon-o-banknotes class="w-12 h-12 text-gray-400" />
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune fiche de paie trouvée</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-4">
                    Aucun bulletin de paie n'est disponible dans votre dossier personnel pour le moment.
                </p>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 max-w-md mx-auto">
                    <div class="flex items-start gap-3">
                        <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-yellow-600 mt-0.5" />
                        <div class="text-sm text-yellow-800">
                            <p class="font-medium">Première paie ?</p>
                            <p>Vos bulletins de paie apparaîtront ici après le traitement de votre première paie par le service comptabilité.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
    {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}
</div>
