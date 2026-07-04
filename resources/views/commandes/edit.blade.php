@extends('layouts.app')
@section('title', 'Modifier la commande')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Modifier : {{ $commande->numero }}</h2>
    <a href="{{ route('commandes.show', $commande) }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger mb-4">
        <ul class="mb-0">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('commandes.update', $commande) }}" id="form-commande">
    @csrf
    @method('PUT')

    <div class="card mb-3">
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Type de commande *</label>
                    <select name="type" id="type-commande" class="form-select" required>
                        <option value="">— Choisir —</option>
                        <option value="fournisseur" {{ old('type', $commande->type) == 'fournisseur' ? 'selected' : '' }}>
                            Commande Fournisseur (achat de stock)
                        </option>
                        <option value="client" {{ old('type', $commande->type) == 'client' ? 'selected' : '' }}>
                            Commande Client (vente)
                        </option>
                    </select>
                </div>

                <div class="col-md-4" id="bloc-fournisseur" style="display:none;">
                    <label class="form-label">Fournisseur *</label>
                    <select name="fournisseur_id" class="form-select">
                        <option value="">— Choisir —</option>
                        @foreach($fournisseurs as $f)
                            <option value="{{ $f->id }}" {{ old('fournisseur_id', $commande->fournisseur_id) == $f->id ? 'selected' : '' }}>
                                {{ $f->societe }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4" id="bloc-client" style="display:none;">
                    <label class="form-label">Client *</label>
                    <select name="client_id" class="form-select">
                        <option value="">— Choisir —</option>
                        @foreach($clients as $c)
                            <option value="{{ $c->id }}" {{ old('client_id', $commande->client_id) == $c->id ? 'selected' : '' }}>
                                {{ $c->societe }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Date de commande *</label>
                    <input type="date" name="date_commande" class="form-control"
                           value="{{ old('date_commande', \Carbon\Carbon::parse($commande->date_commande)->format('Y-m-d')) }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Notes (optionnel)</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $commande->notes) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- ── LIGNES DE PRODUITS ── -->
    <div class="card">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Produits commandés</h5>
                <button type="button" class="btn btn-sm btn-outline-primary" id="btn-ajouter-ligne">
                    <i class="bi bi-plus"></i> Ajouter un produit
                </button>
            </div>

            <div class="table-responsive">
            <table class="table" id="table-lignes">
                <thead>
                    <tr>
                        <th style="width:40%">Produit</th>
                        <th style="width:15%">Quantité</th>
                        <th style="width:20%">Prix unitaire</th>
                        <th style="width:15%">Sous-total</th>
                        <th style="width:10%"></th>
                    </tr>
                </thead>
                <tbody id="lignes-body">
                    <!-- Les lignes existantes sont injectées ici par JavaScript au chargement -->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Total :</td>
                        <td class="fw-bold" id="total-general">0</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            </div>

            <div id="message-vide" class="text-center text-muted py-3" style="display:none;">
                Cliquez sur "Ajouter un produit" pour commencer.
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg"></i> Enregistrer les modifications
        </button>
        <a href="{{ route('commandes.show', $commande) }}" class="btn btn-outline-secondary">Annuler</a>
    </div>
</form>

@endsection

@section('scripts')
<script>
    // Liste des produits disponibles, injectée depuis Laravel
    const produitsDisponibles = [
        @foreach($produits as $p)
            { id: {{ $p->id }}, nom: "{{ addslashes($p->nom) }}", prix: {{ $p->prix_vente ?? 0 }} },
        @endforeach
    ];

    // Lignes déjà existantes de la commande, injectées depuis Laravel
    const lignesExistantes = [
        @foreach($commande->lignes as $ligne)
            { produit_id: {{ $ligne->produit_id }}, quantite: {{ $ligne->quantite }}, prix: {{ $ligne->prix_unitaire }} },
        @endforeach
    ];

    let compteurLigne = 0;

    function toggleBlocsTiers() {
        const type = document.getElementById('type-commande').value;
        document.getElementById('bloc-fournisseur').style.display = (type === 'fournisseur') ? 'block' : 'none';
        document.getElementById('bloc-client').style.display      = (type === 'client') ? 'block' : 'none';
    }

    function ajouterLigne(donnees = null) {
        compteurLigne++;
        const id = compteurLigne;

        let optionsHtml = '<option value="">— Choisir un produit —</option>';
        produitsDisponibles.forEach(p => {
            const selected = donnees && donnees.produit_id === p.id ? 'selected' : '';
            optionsHtml += `<option value="${p.id}" data-prix="${p.prix}" ${selected}>${p.nom}</option>`;
        });

        const quantiteVal = donnees ? donnees.quantite : 1;
        const prixVal     = donnees ? donnees.prix : 0;

        const tr = document.createElement('tr');
        tr.id = 'ligne-' + id;
        tr.innerHTML = `
            <td>
                <select name="produits[${id}][id]" class="form-select form-select-sm select-produit" required onchange="majPrixEtTotal(${id})">
                    ${optionsHtml}
                </select>
            </td>
            <td>
                <input type="number" name="produits[${id}][quantite]" class="form-control form-control-sm input-quantite"
                       min="1" value="${quantiteVal}" required oninput="majTotalLigne(${id})">
            </td>
            <td>
                <input type="number" name="produits[${id}][prix]" class="form-control form-control-sm input-prix"
                       min="0" step="0.01" value="${parseFloat(prixVal).toFixed(2)}" required oninput="majTotalLigne(${id})">
            </td>
            <td>
                <span class="sous-total" id="sous-total-${id}">0</span>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="supprimerLigne(${id})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

        document.getElementById('lignes-body').appendChild(tr);
        document.getElementById('message-vide').style.display = 'none';
        majTotalLigne(id);
    }

    function majPrixEtTotal(id) {
        const select = document.querySelector(`#ligne-${id} .select-produit`);
        const prixInput = document.querySelector(`#ligne-${id} .input-prix`);
        const option = select.options[select.selectedIndex];
        const prix = option ? (option.getAttribute('data-prix') || 0) : 0;
        prixInput.value = parseFloat(prix).toFixed(2);
        majTotalLigne(id);
    }

    function majTotalLigne(id) {
        const quantite = parseFloat(document.querySelector(`#ligne-${id} .input-quantite`).value) || 0;
        const prix     = parseFloat(document.querySelector(`#ligne-${id} .input-prix`).value) || 0;
        const sousTotal = quantite * prix;
        document.getElementById('sous-total-' + id).textContent = sousTotal.toLocaleString('fr-FR', {maximumFractionDigits: 0});
        majTotalGeneral();
    }

    function majTotalGeneral() {
        let total = 0;
        document.querySelectorAll('.sous-total').forEach(el => {
            total += parseFloat(el.textContent.replace(/\s/g, '').replace(',', '.')) || 0;
        });
        document.getElementById('total-general').textContent = total.toLocaleString('fr-FR', {maximumFractionDigits: 0});
    }

    function supprimerLigne(id) {
        document.getElementById('ligne-' + id).remove();
        majTotalGeneral();
        if (document.querySelectorAll('#lignes-body tr').length === 0) {
            document.getElementById('message-vide').style.display = 'block';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('type-commande').addEventListener('change', toggleBlocsTiers);
        document.getElementById('btn-ajouter-ligne').addEventListener('click', () => ajouterLigne());
        toggleBlocsTiers();

        // Pré-remplissage avec les lignes existantes de la commande
        if (lignesExistantes.length > 0) {
            lignesExistantes.forEach(l => ajouterLigne(l));
        } else {
            ajouterLigne();
        }
    });

    // Empêche d'envoyer le formulaire sans aucune ligne de produit
    document.getElementById('form-commande').addEventListener('submit', function (e) {
        if (document.querySelectorAll('#lignes-body tr').length === 0) {
            e.preventDefault();
            alert('Ajoutez au moins un produit à la commande.');
        }
    });
</script>
@endsection