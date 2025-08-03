<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <title>Document</title>
</head>
<body>
    <div class="text-xl font-black mb-5 text-center">Liste des produits</div>
    <div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100">
        <table class="table">
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Libellée</th>
                    <th>Catégorie</th>
                    <th>Prix Unitaire Fournisseur</th>
                    <th>Prix Unitaire Client</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($produits as $produit)
                    <tr>
                        <td>{{ $produit->reference }}</td>
                        <td>{{ $produit->libelle }}</td>
                        <td>{{ $produit->categorie }}</td>
                        <td>{{ $produit->tarifFournisseur->prix_unitaire }}</td>
                        <td>{{ $produit->tarifClient->prix_unitaire }}</td>
                        <td>{{ $produit->stocks->sum('quantite') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
