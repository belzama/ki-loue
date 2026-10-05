@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <i class="bi bi-journal-text page-header-icon"></i>
        <div>
            <h1 class="page-title">Nouvelle publication</h1>
        </div>
    </div>
</div>

@include('user.publications.form')
@endsection
