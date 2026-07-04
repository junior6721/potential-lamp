@extends('layouts.app')
@section('title', 'Paramètres de la société')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Paramètres de la société</h2>
</div>

<form method="POST" action="{{ route('parametres.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">

        <!-- Informations générales -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header py-3 px-4">
                    <i class="bi bi-building me-2"></i>Informations de la société
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nom de la société *</label>
                            <input type="text" name="nom_societe" class="form-control"
                                   value="{{ old('nom_societe', $parametre->nom_societe) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="telephone" class="form-control"
                                   value="{{ old('telephone', $parametre->telephone) }}"
                                   placeholder="+229 XX XX XX XX">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email', $parametre->email) }}"
                                   placeholder="contact@societe.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Numéro IFU</label>
                            <input type="text" name="ifu" class="form-control"
                                   value="{{ old('ifu', $parametre->ifu) }}"
                                   placeholder="Ex: 3201912345678">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Site web</label>
                            <input type="url" name="site_web" class="form-control"
                                   value="{{ old('site_web', $parametre->site_web) }}"
                                   placeholder="https://www.societe.com">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Adresse</label>
                            <textarea name="adresse" class="form-control" rows="2"
                                      placeholder="Rue, Quartier, Ville, Pays">{{ old('adresse', $parametre->adresse) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logo et Cachet -->
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header py-3 px-4">
                    <i class="bi bi-image me-2"></i>Logo
                </div>
                <div class="card-body p-4 text-center">
                    @if($parametre->logo)
                        <img src="{{ asset('storage/' . $parametre->logo) }}"
                             alt="Logo" style="max-height:80px;max-width:100%;margin-bottom:12px;border-radius:8px;">
                        <br>
                    @else
                        <div style="height:80px;display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:13px;">
                            <i class="bi bi-image" style="font-size:30px;"></i>
                        </div>
                    @endif
                    <input type="file" name="logo" class="form-control form-control-sm mt-2"
                           accept="image/png,image/jpeg">
                    <small class="text-muted">PNG ou JPG, max 2Mo</small>
                </div>
            </div>

            <div class="card">
                <div class="card-header py-3 px-4">
                    <i class="bi bi-patch-check me-2"></i>Cachet / Tampon
                </div>
                <div class="card-body p-4 text-center">
                    @if($parametre->cachet)
                        <img src="{{ asset('storage/' . $parametre->cachet) }}"
                             alt="Cachet" style="max-height:80px;max-width:100%;margin-bottom:12px;">
                        <br>
                    @else
                        <div style="height:80px;display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:13px;">
                            <i class="bi bi-patch-check" style="font-size:30px;"></i>
                        </div>
                    @endif
                    <input type="file" name="cachet" class="form-control form-control-sm mt-2"
                           accept="image/png,image/jpeg">
                    <small class="text-muted">PNG avec fond transparent recommandé</small>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg me-1"></i>Enregistrer les paramètres
        </button>
    </div>
</form>

@endsection