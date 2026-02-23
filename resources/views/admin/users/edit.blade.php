@extends('layouts.admin')

@section('content')
<h1>Modifier l'utilisateur</h1>
<form action="{{ route('admin.users.update', $user) }}" method="POST">
    @method('PUT')
    @include('admin.users.form')
</form>
@endsection
