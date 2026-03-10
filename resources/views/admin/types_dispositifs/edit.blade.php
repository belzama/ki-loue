@extends('layouts.admin')

@section('content')
<h1>Modifier le type de matériel</h1>
<form action="{{ route('admin.types_dispositifs.update', $types_dispositif) }}" method="POST">
    @method('PUT')
    @include('admin.types_dispositifs.form')
</form>
@endsection
