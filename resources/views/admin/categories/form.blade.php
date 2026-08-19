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

<div class="mb-3">
    <label>Image de la catégorie
        @if(!isset($category))
            <span class="text-danger">*</span>
        @endif
    </label>
    <input type="file" name="image" class="form-control" accept="image/*"
           @if(!isset($category)) required @endif>

    @if(isset($category) && $category->image_link)
        <div class="mt-2">
            <img src="{{ asset('storage/' . $category->image_link) }}"
                 alt="{{ $category->nom }}" style="max-height: 100px;">
            <div class="form-text">Laisser vide pour conserver l'image actuelle.</div>
        </div>
    @endif
</div>

<button type="submit" class="btn btn-success">Enregistrer</button>