@extends('layouts.admin')

@section('content')
<h1>Ajouter un pays</h1>
<form action="{{ route('admin.pays.store') }}" method="POST">
    @include('admin.pays.form')
</form>
@endsection
