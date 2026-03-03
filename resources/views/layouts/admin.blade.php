@extends('layouts.app')
@section('nav-bar')
    @include('partials.user-connected-navbar')
@endsection

@section('main-content')
<div class="container-fluid">
    <div class="row">

        {{-- SIDEBAR --}}
        <aside class="col-3 bg-light sidebar p-3">
            <ul class="nav flex-column gap-1">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Tableau de bord
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.devises.index') }}" 
                        class="nav-link">
                        <i class="bi bi-currency-exchange me-2"></i> Devises
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.pays.index') }}" 
                        class="nav-link">
                        <i class="bi bi-globe-americas me-2"></i> Pays
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.regions.index') }}" 
                        class="nav-link">
                        <i class="bi bi-map me-2"></i> Régions
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.villes.index') }}" 
                        class="nav-link">
                        <i class="bi bi-building me-2"></i> Villes/Préfectures
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" 
                        class="nav-link">
                        <i class="bi bi-people me-2"></i> Utilisateurs
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.categories.index') }}" 
                        class="nav-link">
                        <i class="bi bi-tags me-2"></i> Categories
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.types_dispositifs.index') }}" 
                        class="nav-link">
                        <i class="bi bi-cpu me-2"></i> Types de dispositifs
                    </a>
                </li>
            
                <li class="nav-item">
                    <a href="{{ route('user.dispositifs.index') }}"
                       class="nav-link {{ request()->routeIs('user.dispositifs.*') ? 'active' : '' }}">
                        <i class="bi bi-truck me-2"></i> Dispositifs
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('user.publications.index') }}"
                       class="nav-link {{ request()->routeIs('user.publications.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-text me-2"></i> Publications
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('user.reservations.index') }}"
                       class="nav-link {{ request()->routeIs('user.reservations.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check me-2"></i> Réservations
                    </a>
                </li>      

                <li class="nav-item">
                    <a href="{{ route('user.transactions.index') }}"
                       class="nav-link {{ request()->routeIs('user.transactions.*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-left-right me-2"></i> Transactions
                    </a>
                </li>            

                <li class="nav-item">
                    <a href="{{ route('user.notifications.index') }}"
                       class="nav-link {{ request()->routeIs('user.notifications.*') ? 'active' : '' }}">
                        <i class="bi bi-bell me-2"></i> Notifications
                    </a>
                </li>
            </ul>
        </aside>

        {{-- CONTENT --}}
        <main class="col-9 p-4">
            <div class="content-wrapper">
                {{-- PAGE CONTENT --}}
                @yield('content')

            </div>
        </main>

    </div>
</div>

@yield('scripts')

@endsection
