@extends('layouts.app')
@section('title', 'Nouveau mouvement de stock')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="card">
      <div class="card-header py-3 px-4">
        <i class="bi bi-arrow-left-right me-2"></i>Enregistrer un mouvement de stock
      </div>
      <div class="card-body p-4">
        <form method="POST" action="{{ route('mouvements.store') }}">
          @csrf

          @if($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
          @endif

          <div class="mb-3">
            <label class="form-label">Produit *</label>
            <select name="produit_id" class="form-select" required id="produit-select">
              <option value="">-- Sélectionner un produit --</option>
              @foreach($produits as $p)
                <option value="{{ $p->id }}"
                  data-stock="{{ $p->quantite_stock }}"
                  data-unite="{{ $p->unite }}"
                  {{ (old('produit_id', request('produit_id')) == $p->id) ? 'selected' : '' }}>
                  {{ $p->nom }} — {{ $p->reference }} (stock: {{ $p->quantite_stock }} {{ $p->unite }})
                </option>
              @endforeach
            </select>
          </div>

          <!-- Info stock actuel -->
          <div id="stock-info" class="alert mb-3" style="display:none;background:rgba(26,115,232,0.1);border:1px solid rgba(26,115,232,0.3);color:#4d9fff;border-radius:10px;">
            <i class="bi bi-info-circle me-2"></i>
            Stock actuel : <strong id="stock-val">-</strong> <span id="stock-unite"></span>
          </div>

          <div class="mb-3">
            <label class="form-label">Type de mouvement *</label>
            <div class="row g-2">
              <div class="col-6">
                <label class="d-block p-3 text-center rounded-3 cursor-pointer" style="border: 2px solid {{ old('type')=='entree' ? '#28a745' : 'rgba(255,255,255,0.1)' }};background:{{ old('type')=='entree' ? 'rgba(40,167,69,0.1)' : 'transparent' }};cursor:pointer;" id="label-entree">
                  <input type="radio" name="type" value="entree" class="d-none" {{ old('type','entree')=='entree' ? 'checked' : '' }}>
                  <i class="bi bi-arrow-down-circle text-success" style="font-size:24px;"></i>
                  <div class="mt-1 text-success fw-600">Entrée</div>
                  <small style="color:#9e9e9e;">Réception / achat</small>
                </label>
              </div>
              <div class="col-6">
                <label class="d-block p-3 text-center rounded-3" style="border: 2px solid {{ old('type')=='sortie' ? '#dc3545' : 'rgba(255,255,255,0.1)' }};background:{{ old('type')=='sortie' ? 'rgba(220,53,69,0.1)' : 'transparent' }};cursor:pointer;" id="label-sortie">
                  <input type="radio" name="type" value="sortie" class="d-none" {{ old('type')=='sortie' ? 'checked' : '' }}>
                  <i class="bi bi-arrow-up-circle text-danger" style="font-size:24px;"></i>
                  <div class="mt-1 text-danger fw-600">Sortie</div>
                  <small style="color:#9e9e9e;">Vente / consommation</small>
                </label>
              </div>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Quantité *</label>
              <input type="number" name="quantite" class="form-control" value="{{ old('quantite', 1) }}" min="1" required/>
            </div>
            <div class="col-md-6">
              <label class="form-label">Référence document</label>
              <input type="text" name="reference_doc" class="form-control" value="{{ old('reference_doc') }}" placeholder="BL-001, FAC-002..."/>
            </div>
            <div class="col-12">
              <label class="form-label">Motif</label>
              <input type="text" name="motif" class="form-control" value="{{ old('motif') }}" placeholder="Achat fournisseur, vente client, inventaire..."/>
            </div>
          </div>

          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check me-1"></i>Enregistrer le mouvement
            </button>
            <a href="{{ route('mouvements.index') }}" class="btn btn-outline-secondary">Annuler</a>
          </div>

          <div class="col-md-6" id="bloc-fournisseur" style="display:none;">
    <label class="form-label">Fournisseur (optionnel)</label>
    <select name="fournisseur_id" class="form-select">
        <option value="">— Aucun —</option>
        @foreach(\App\Models\Fournisseur::where('actif', true)->orderBy('societe')->get() as $f)
            <option value="{{ $f->id }}" {{ old('fournisseur_id') == $f->id ? 'selected' : '' }}>
                {{ $f->societe }}
            </option>
        @endforeach
    </select>
</div>

<div class="col-md-6" id="bloc-client" style="display:none;">
    <label class="form-label">Client (optionnel)</label>
    <select name="client_id" class="form-select">
        <option value="">— Aucun —</option>
        @foreach(\App\Models\Client::where('actif', true)->orderBy('societe')->get() as $c)
            <option value="{{ $c->id }}" {{ old('client_id') == $c->id ? 'selected' : '' }}>
                {{ $c->societe }}
            </option>
        @endforeach
    </select>
</div>


        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  // Afficher stock actuel
  document.getElementById('produit-select').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    const stock = opt.dataset.stock;
    const unite = opt.dataset.unite;
    if (stock !== undefined) {
      document.getElementById('stock-val').textContent = stock;
      document.getElementById('stock-unite').textContent = unite;
      document.getElementById('stock-info').style.display = 'block';
    } else {
      document.getElementById('stock-info').style.display = 'none';
    }
  });

  // Style radios entrée/sortie
  document.querySelectorAll('input[name="type"]').forEach(radio => {
    radio.addEventListener('change', function() {
      document.getElementById('label-entree').style.borderColor = 'rgba(255,255,255,0.1)';
      document.getElementById('label-entree').style.background = 'transparent';
      document.getElementById('label-sortie').style.borderColor = 'rgba(255,255,255,0.1)';
      document.getElementById('label-sortie').style.background = 'transparent';
      if (this.value === 'entree') {
        document.getElementById('label-entree').style.borderColor = '#28a745';
        document.getElementById('label-entree').style.background = 'rgba(40,167,69,0.1)';
      } else {
        document.getElementById('label-sortie').style.borderColor = '#dc3545';
        document.getElementById('label-sortie').style.background = 'rgba(220,53,69,0.1)';
      }
    });
  });

  // Trigger change si produit pré-sélectionné
  if (document.getElementById('produit-select').value) {
    document.getElementById('produit-select').dispatchEvent(new Event('change'));
  }

  function toggleBlocsFournisseurClient() {
        const type = document.querySelector('select[name="type"]')?.value
                  || document.querySelector('input[name="type"]:checked')?.value;

        document.getElementById('bloc-fournisseur').style.display = (type === 'entree') ? 'block' : 'none';
        document.getElementById('bloc-client').style.display      = (type === 'sortie') ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
        toggleBlocsFournisseurClient();

        // Si "type" est un <select>
        const selectType = document.querySelector('select[name="type"]');
        if (selectType) selectType.addEventListener('change', toggleBlocsFournisseurClient);

        // Si "type" est des boutons radio
        document.querySelectorAll('input[name="type"]').forEach(function (radio) {
            radio.addEventListener('change', toggleBlocsFournisseurClient);
        });
    });

</script>
@endsection
