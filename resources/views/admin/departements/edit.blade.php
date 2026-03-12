@extends('layouts.admin')

@section('content')
<h1>Modifier la préfecture/département</h1>
<form action="{{ route('admin.departements.update', $region) }}" method="POST">
    @method('PUT')
    @include('admin.departements.form')
</form>
@endsection
