<div>
    <div class="bg-gray-100 rounded-tr-md rounded-br-md p-5">
        <h2 class="text-2xl font-bold mb-4">Documents RH</h2>
        <p class="text-gray-600 mb-6">Documents relatifs aux ressources humaines - Matricule : {{ $matricule }}</p>

        @if(session()->has('error'))
            <x-mary-alert class="alert-error mb-4" icon="o-exclamation-triangle" title="Erreur" description="{{ session('error') }}" />
        @endif

        @if(count($documents) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($documents as $document)
                    <div class="card bg-white shadow-md hover:shadow-lg transition-shadow">
                        <div class="card-body p-4">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div class="mask mask-squircle w-10 h-10 bg-blue-100 flex items-center justify-center">
                                            @if(str_ends_with(strtolower($document['name']), '.pdf'))
                                                <x-heroicon-o-document-text class="w-5 h-5 text-red-600" />
                                            @elseif(in_array(strtolower(pathinfo($document['name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                                                <x-heroicon-o-photo class="w-5 h-5 text-green-600" />
                                            @else
                                                <x-heroicon-o-document class="w-5 h-5 text-gray-600" />
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-sm text-gray-900 truncate">{{ $document['name'] }}</h3>
                                        <p class="text-xs text-gray-500">{{ $document['type'] }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-500">Taille :</span>
                                    <span class="font-medium">{{ $this->formatFileSize($document['size']) }}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-500">Modifié :</span>
                                    <span class="font-medium">{{ date('d/m/Y', $document['lastModified']) }}</span>
                                </div>
                            </div>

                            <div class="card-actions justify-end">
                                <div class="btn-group btn-group-horizontal">
                                    <a href="{{ $document['url'] }}" target="_blank" class="btn btn-ghost btn-sm">
                                        <x-heroicon-o-eye class="w-4 h-4" />
                                    </a>
                                    <button wire:click="downloadDocument('{{ $document['path'] }}')" class="btn btn-ghost btn-sm">
                                        <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <x-heroicon-o-user-group class="w-12 h-12 text-gray-400" />
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun document RH trouvé</h3>
                <p class="text-gray-500 max-w-md mx-auto">
                    Aucun document RH n'est disponible dans votre dossier personnel pour le moment.
                    Les documents tels que votre contrat, CNI, carte vitale, RIB, etc. apparaîtront ici une fois ajoutés par le service RH.
                </p>
            </div>
        @endif
    </div>
</div>
{{-- Care about people's approval and you will be their prisoner. --}}
</div>
