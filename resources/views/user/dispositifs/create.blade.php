@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('content')
<h1>Ajouter un matériel</h1>

@include('user.dispositifs.form')
@endsection
