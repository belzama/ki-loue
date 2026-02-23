@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('content')
<h1>Modifier la publication</h1>

@include('user.publications.form')
@endsection
