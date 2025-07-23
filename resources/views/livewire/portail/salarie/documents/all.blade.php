<div>
    <div class="bg-gray-100 rounded-tr-md rounded-br-md p-5">
        <h2 class="text-2xl font-bold mb-4">Tous les documents</h2>
        <p class="text-gray-600 mb-6">Matricule : {{ $matricule }}</p>

        @if(session()->has('error'))
            <x-mary-alert class="alert-error mb-4" icon="o-exclamation-triangle" title="Erreur" description="{{ session('error') }}" />
        @endif

        @if(count($documents) > 0)
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>Nom du document</th>
                            <th>Catégorie</th>
                            <th>Taille</th>
                            <th>Dernière modification</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $document)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="avatar">
                                            <div class="mask mask-squircle w-8 h-8 bg-blue-100 flex items-center justify-center">
                                                @if(str_ends_with(strtolower($document['name']), '.pdf'))
                                                    <x-heroicon-o-document-text class="w-4 h-4 text-red-600" />
                                                @elseif(in_array(strtolower(pathinfo($document['name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                                                    <x-heroicon-o-photo class="w-4 h-4 text-green-600" />
                                                @else
                                                    <x-heroicon-o-document class="w-4 h-4 text-gray-600" />
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-bold">{{ $document['name'] }}</div>
                                            <div class="text-sm opacity-50">{{ dirname($document['path']) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-outline
                                        @if($document['category'] === 'RH') badge-primary
                                        @elseif($document['category'] === 'Paie') badge-success
                                        @elseif($document['category'] === 'Contrat') badge-warning
                                        @else badge-neutral
                                        @endif">
                                        {{ $document['category'] }}
                                    </span>
                                </td>
                                <td>{{ $this->formatFileSize($document['size']) }}</td>
                                <td>{{ date('d/m/Y H:i', $document['lastModified']) }}</td>
                                <td>
                                    <div class="flex gap-2">
                                        <a href="{{ $document['url'] }}" target="_blank" class="btn btn-ghost btn-xs">
                                            <x-heroicon-o-eye class="w-4 h-4" />
                                            Voir
                                        </a>
                                        <button wire:click="downloadDocument('{{ $document['path'] }}')" class="btn btn-ghost btn-xs">
                                            <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
                                            Télécharger
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <x-heroicon-o-folder-open class="w-16 h-16 mx-auto text-gray-400 mb-4" />
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun document trouvé</h3>
                <p class="text-gray-500">Aucun document n'est disponible dans votre dossier personnel pour le moment.</p>
            </div>
        @endif
    </div>

    @script
    <script>
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Exposer la fonction globalement pour Livewire
        window.formatFileSize = formatFileSize;
    </script>
    @endscript
</div>
