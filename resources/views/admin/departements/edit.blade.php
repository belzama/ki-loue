@extends('layouts.admin')

@section('content')
<h1>Modifier la sous-région</h1>
<form action="{{ route('admin.departements.update', $departement) }}" method="POST">
    @method('PUT')
    @include('admin.departements.form')
</form>
@endsection
