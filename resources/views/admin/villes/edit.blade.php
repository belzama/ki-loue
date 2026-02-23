@extends('layouts.admin')

@section('content')
<h1>Modifier le ville/préfecture</h1>
<form action="{{ route('admin.villes.update', $pays) }}" method="POST">
    @method('PUT')
    @include('admin.villes.form')
</form>
@endsection
