@csrf

<div class="mb-3">
    <label>Code Parrainage <span class="text-danger">*</span></label>
    <input type="text" name="code" class="form-control" 
        value="{{ old('code', $user->code ?? '') }}" >
</div>

<div class="mb-3">
    <label>Nom & prénom(s)/Raison sociale <span class="text-danger">*</span></label>
    <input type="text" name="nom" class="form-control" 
           value="{{ old('nom', $user->nom ?? '') }}" required>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-6">
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

    <div class="col-md-6">
        <label>Contact whatsapp</label>
        <input type="text" name="contact" class="form-control" 
            value="{{ old('contact', $user->contact ?? '') }}">
    </div>
</div>

<div class="mb-3">
    <label>Email <span class="text-danger">*</span></label>
    <input type="text" name="email" class="form-control" 
        value="{{ old('email', $user->email ?? '') }}" required>
</div>


<div class="mb-3">
    <label>Rôle <span class="text-danger">*</span></label>
    <select name="role" class="form-select" required>
        <option value="User" {{ (old('role', $user->role ?? '') === 'User') ? 'selected' : '' }}>Utilisateur</option>
        <option value="Admin" {{ (old('role', $user->role ?? '') === 'Admin') ? 'selected' : '' }}>Administrateur</option>
    </select>
</div>

<div class="mb-3">
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

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label>Taux de commission <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="taux_commission" class="form-control"
            value="{{ old('taux_commission', $user->taux_commission ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label>Taux de commission de parrainage <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="taux_commission_sponsor" class="form-control"
            value="{{ old('taux_commission_sponsor', $user->taux_commission_sponsor ?? '') }}" required>
    </div>
</div>

<button type="submit" class="btn btn-success">
    <i class="bi bi-check-circle"></i> Enregistrer
</button>
