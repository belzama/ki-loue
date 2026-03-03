@extends('layouts.admin')

@section('content')
<h1>Ajouter une région</h1>
<form action="{{ route('admin.regions.store') }}" method="POST">
    @include('admin.regions.form')
</form>
@endsection
