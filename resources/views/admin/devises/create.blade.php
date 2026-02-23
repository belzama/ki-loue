@extends('layouts.admin')

@section('content')
    <h1>Ajouter une devise</h1>
    <form action="{{ route('admin.devises.store') }}" method="POST">
        @include('admin.devises.form')
    </form>
@endsection
