@extends('layouts.admin')

@section('content')
<h1>Modifier le pays</h1>
<form action="{{ route('admin.pays.update', $pays) }}" method="POST">
    @method('PUT')
    @include('admin.pays.form')
</form>
@endsection
