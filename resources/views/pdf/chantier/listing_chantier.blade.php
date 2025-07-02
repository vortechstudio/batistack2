<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <title>Document</title>
</head>
<body>
    <header class="bg-gray-300 text-white mb-10 rounded-lg p-5">
        <div class="flex flex-row justify-between items-start text-xs">
            <span>SARL C2ME</span>
            <span>Liste des chantiers</span>
            <span>{{ now()->format('d/m/Y à H:i') }}</span>
        </div>
    </header>
    <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100">
        <table class="table">
            <!-- head -->
            <thead>
            <tr>
                <th>Client / Chantiers</th>
                <th>Avancement</th>
                <th>Date de début</th>
                <th>Devis</th>
                <th>Factures</th>
                <th>Equipes</th>
            </tr>
            </thead>
            <tbody>
            @foreach($chantiers as $chantier)
                <tr>
                    <td>{{ $chantier->tiers->name }}</td>
                    <td>{{ $chantier->getAvancements()['percent'] }} %</td>
                    <td>{{ $chantier->date_debut->format('d/m/Y') }}</td>
                    <td>0</td>
                    <td>0</td>
                    <td>Aucune Actuellement</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
