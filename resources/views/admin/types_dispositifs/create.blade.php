@extends('layouts.admin')

@section('content')
<h1>Ajouter un type de matériel</h1>
<form action="{{ route('admin.types_dispositifs.store') }}" method="POST" enctype="multipart/form-data">
    @include('admin.types_dispositifs.form')
</form>
@endsection
