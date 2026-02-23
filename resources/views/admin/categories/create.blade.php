@extends('layouts.admin')

@section('content')
    <h1>Ajouter une catégorie</h1>
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @include('admin.categories.form')
    </form>
@endsection
