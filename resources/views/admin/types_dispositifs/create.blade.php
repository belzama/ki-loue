@extends('layouts.admin')

@section('content')
<h1>Ajouter un type de dispositif</h1>
<form action="{{ route('admin.types_dispositifs.store') }}" method="POST">
    @include('admin.types_dispositifs.form')
</form>
@endsection
