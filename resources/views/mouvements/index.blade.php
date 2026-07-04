@extends('layouts.app')
@section('title', 'Entrées / Sorties')
@section('content')

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <select name="type" class="form-select">
                    <option value="">Tous les types</option>
                    <option value="entree" {{ request('type')=='entree'?'selected':'' }}>Entrées</option>
                    <option value="sortie" {{ request('type')=='sortie'?'selected':'' }}>Sorties</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="produit_id" class="form-select">
                    <option value="">Tous les produits</option>
                    @foreach($produits as $p)
                        <option value="{{ $p->id }}" {{ request('produit_id')==$p->id?'selected':'' }}>{{ $p->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}"/>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}"/>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-search"></i></button>
                <a href="{{ route('mouvements.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x"></i></a>
                <a href="{{ route('mouvements.create') }}" class="btn btn-success"><i class="bi bi-plus"></i></a>
                <a href="{{ route('pdf.mouvements') }}?type={{ request('type') }}&date_debut={{ request('date_debut') }}&date_fin={{ request('date_fin') }}"
                   class="btn btn-danger" target="_blank" title="Exporter PDF">
                    <i class="bi bi-file-earmark-pdf"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header py-3 px-4">
        <i class="bi bi-arrow-left-right me-2"></i>{{ $mouvements->total() }} mouvement(s)
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Produit</th>
                    <th>Type</th>
                    <th>Qté</th>
                    <th>Avant</th>
                    <th>Après</th>
                    <th>Motif</th>
                    <th>Réf. doc</th>
                    <th>Opérateur</th>
                    @if(auth()->user()->isAdmin())
                        <th class="text-end">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($mouvements as $m)
                <tr>
                    <td style="font-size:12px;color:#9e9e9e;">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('produits.show', $m->produit_id) }}" style="color:#4d9fff;text-decoration:none;">
                            {{ $m->produit->nom ?? '-' }}
                        </a>
                    </td>
                    <td>
                        @if($m->type === 'entree')
                            <span class="badge-entree"><i class="bi bi-arrow-down-circle me-1"></i>Entrée</span>
                        @else
                            <span class="badge-sortie"><i class="bi bi-arrow-up-circle me-1"></i>Sortie</span>
                        @endif
                    </td>
                    <td><strong>{{ $m->quantite }}</strong></td>
                    <td style="color:#9e9e9e;">{{ $m->stock_avant }}</td>
                    <td><strong>{{ $m->stock_apres }}</strong></td>
                    <td style="font-size:12px;">{{ $m->motif ?? '-' }}</td>
                    <td style="font-size:12px;color:#9e9e9e;">{{ $m->reference_doc ?? '-' }}</td>
                    <td style="font-size:12px;">{{ $m->user->name ?? '-' }}</td>
                    @if(auth()->user()->isAdmin())
                    <td class="text-end">
                    <form action="{{ route('mouvements.destroy', $m) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Supprimer ce mouvement de l\'historique ?\n\nNote : cette action ne modifie pas le stock actuel.');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer ce mouvement">
                      <i class="bi bi-trash"></i>
                  </button>
              </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->isAdmin() ? 10 : 9 }}" class="text-center py-5" style="color:#9e9e9e;">
                        Aucun mouvement enregistré
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($mouvements->hasPages())
        <div class="card-footer py-3 d-flex justify-content-center">
            {{ $mouvements->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection