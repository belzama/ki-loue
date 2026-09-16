@extends(auth()->user()->role == 'Admin'
    ? 'layouts.admin'
    : 'layouts.guest')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <i class="bi bi-truck page-header-icon"></i>
        <div>
            <h1 class="page-title">Ajouter un matériel</h1>
        </div>
    </div>
</div>

@include('user.dispositifs.form')
@endsection
