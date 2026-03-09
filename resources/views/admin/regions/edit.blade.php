@extends('layouts.admin')

@section('content')
<h1>Modifier la région</h1>
<form action="{{ route('admin.regions.update', $region) }}" method="POST">
    @method('PUT')
    @include('admin.regions.form')
</form>
@endsection
