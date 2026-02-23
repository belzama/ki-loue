@extends('layouts.admin')

@section('content')
<h1>Ajouter un utilisateur</h1>
<form action="{{ route('admin.users.store') }}" method="POST">
    @include('admin.users.form')
</form>
@endsection
