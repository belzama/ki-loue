@extends('layouts.admin')

@section('content')
<h1>Ajouter une préfecture/département</h1>
<form action="{{ route('admin.departements.store') }}" method="POST">
    @include('admin.departements.form')
</form>
@endsection