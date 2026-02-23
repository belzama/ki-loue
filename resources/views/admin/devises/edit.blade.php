@extends('layouts.admin')

@section('content')
    <h1>Modifier la devise</h1>

    <form method="POST" action="{{ route('admin.devises.update', $devise) }}">
        @method('PUT')
        @include('admin.devises.form')
    </form>
@endsection
