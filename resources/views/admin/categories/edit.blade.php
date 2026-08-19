@extends('layouts.admin')

@section('content')
    <h1>Modifier la catégorie</h1>

    <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.categories.form')
    </form>
@endsection
