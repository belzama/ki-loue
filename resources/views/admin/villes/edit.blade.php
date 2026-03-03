@extends('layouts.admin')

@section('content')
<h1>Modifier la ville/préfecture</h1>
<form action="{{ route('admin.villes.update', $region) }}" method="POST">
    @method('PUT')
    @include('admin.villes.form')
</form>
@endsection
