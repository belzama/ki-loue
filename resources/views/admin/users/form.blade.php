@csrf

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label>Code Parrainage <span class="text-danger">*</span></label>
        <input type="text" name="code" class="form-control" 
            value="{{ old('code', $user->code ?? '') }}" >
    </div>

    <div class="col-md-4">
        <label>Nom <span class="text-danger">*</span></label>
        <input type="text" name="nom" class="form-control" 
            value="{{ old('nom', $user->nom ?? '') }}" required>
    </div>

    <div class="col-md-4">
        <label>Prénom(s) <span class="text-danger">*</span></label>
        <input type="text" name="prenom" class="form-control" 
            value="{{ old('prenom', $user->prenom ?? '') }}" required>
    </div>
</div>

<div class="row g-3 mb-3">

    {{-- ROLE --}}
    <div class="col-md-4">
        <label class="form-label">Rôle <span class="text-danger">*</span></label>
        <div class="btn-group w-100" role="group">
            <input type="radio" class="btn-check" name="role" id="role1" value="Admin"
                    {{ old('role', $user->role ?? '') === 'Admin' ? 'checked' : '' }}>
            <label class="btn btn-outline-danger" for="role1">Administrateur</label>

            <input type="radio" class="btn-check" name="role" id="role2" value="User"
                    {{ old('role', $user->role ?? '') === 'User' ? 'checked' : '' }}>
            <label class="btn btn-outline-danger" for="role2">Utilisateur</label>
        </div>
    </div>

    {{-- TYPE UTILISATEUR --}}
    <div class="col-md-4">
        <label class="form-label">Type <span class="text-danger">*</span></label>
        <div class="btn-group w-100" role="group">
            <input type="radio" class="btn-check" name="type" id="type1" value="Société"
                    {{ old('type', $user->type) === 'Société' ? 'checked' : '' }}>
            <label class="btn btn-outline-primary" for="type1">Société</label>

            <input type="radio" class="btn-check" name="type" id="type2" value="Particulier"
                    {{ old('type', $user->type) === 'Particulier' ? 'checked' : '' }}>
            <label class="btn btn-outline-primary" for="type2">Particulier</label>
        </div>
    </div>

    {{-- RAISON SOCIALE --}}
    <div class="col-md-4" id="raison_sociale_block">
        <label class="form-label">Raison sociale</label>
        <input type="text"
            name="raison_sociale"
            value="{{ old('raison_sociale'), $user->raison_sociale ?? '' }}"
            class="form-control">
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label>Parrain</label>
        <select name="user_id" class="form-select">
            <option value="">Sélectionner</option>
            @foreach($users as $u)
                <option value="{{ $u->id }}" 
                    {{ (old('user_id', $user->user_id ?? '') == $u->id) ? 'selected' : '' }}>
                    {{ $u->nom }} ({{ $u->code }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label>Email <span class="text-danger">*</span></label>
        <input type="text" name="email" class="form-control" 
            value="{{ old('email', $user->email ?? '') }}" required>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label>Pays <span class="text-danger">*</span></label>
        <select name="pays_id" class="form-select" required>
            <option value="">Sélectionner</option>
            @foreach($pays as $p)
                <option value="{{ $p->id }}" 
                    {{ (old('pays_id', $user->pays_id ?? '') == $p->id) ? 'selected' : '' }}>
                    {{ $p->nom }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label>Téléphone</label>
        <input type="text" name="telephone" class="form-control" 
            value="{{ old('telephone', $user->telephone ?? '') }}">
    </div>

    <div class="col-md-4">
        <label>Whatsapp</label>
        <input type="text" name="whatsapp" class="form-control" 
            value="{{ old('whatsapp', $user->whatsapp ?? '') }}">
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label>Taux du tarif abonnement <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="taux_tarif_abonnement" class="form-control"
            value="{{ old('taux_tarif_abonnement', $user->taux_tarif_abonnement ?? '') }}" required>
    </div>

    <div class="col-md-4">
        <label>Taux de commission <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="taux_commission" class="form-control"
            value="{{ old('taux_commission', $user->taux_commission ?? '') }}" required>
    </div>

    <div class="col-md-4">
        <label>Taux de commission parrainage <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="taux_commission_sponsor" class="form-control"
            value="{{ old('taux_commission_sponsor', $user->taux_commission_sponsor ?? '') }}" required>
    </div>
</div>

<button type="submit" class="btn btn-success">
    <i class="bi bi-check-circle"></i> Enregistrer
</button>
