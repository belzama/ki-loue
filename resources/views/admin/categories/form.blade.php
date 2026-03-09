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

<div class="mb-3">
    <label>Nom de la catégorie <span class="text-danger">*</span></label>
    <input type="text" name="nom" class="form-control" 
           value="{{ old('nom', $category->nom ?? '') }}" required>
</div>

<button type="submit" class="btn btn-success">Enregistrer</button>
