<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark px-3">
    <span class="navbar-brand">ADMIN PANEL</span>
    <form action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button class="btn btn-link text-white p-0" type="submit">Se déconnecter</button>
    </form>
</nav>

<div class="container-fluid">
    <div class="row">
        <aside class="col-2 bg-light vh-100 p-3">
            <ul class="nav flex-column">
            <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link">Tableau de bord</a></li>
            
            <li class="nav-item"><a href="{{ route('admin.users.index') }}" class="nav-link">Utilisateurs</a></li>
            <li class="nav-item"><a href="{{ route('admin.categories.index') }}" class="nav-link">Categories</a></li>
            <li class="nav-item"><a href="{{ route('admin.types_dispositifs.index') }}" class="nav-link">Types de matériels</a></li>
            </ul>
        </aside>

        <main class="col-10 p-4">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
