@extends('layouts.admin')

@section('content')
<h1>Ajouter une ville/Préfecture</h1>
<form action="{{ route('admin.villes.store') }}" method="POST">
    @include('admin.villes.form')
</form>
@endsection
