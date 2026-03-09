{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ki-loue')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Icons Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/css/intlTelInput.css" rel="stylesheet">

    {{-- Custom CSS --}}
    <style>
        body {
            padding-top: 40px;
            background-color: #f4f6f9;
        }
        .card img {
            height: 200px;
            object-fit: cover;
        }

        /* Image gauche */
        .bg-login-image {
            background: linear-gradient(
                rgba(0,0,0,0.5),
                rgba(0,0,0,0.5)
            ),
            url('/images/login-btp.jpg') center/cover no-repeat;
        }

        /* Dark mode */
        .dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }

        .dark-mode .card {
            background-color: #1e1e1e;
        }

        .dark-mode .form-control,
        .dark-mode .input-group-text {
            background-color: #2a2a2a;
            color: #fff;
            border-color: #444;
        }

        .dark-mode .btn-outline-secondary {
            color: #fff;
            border-color: #666;
        }


        .sidebar {
            min-height: 100vh;
            border-right: 1px solid #dee2e6;
        }
        .sidebar .nav-link {
            color: #495057;
            border-radius: .375rem;
            padding: .6rem .75rem;
        }
        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background-color: #e9ecef;
            color: #000;
            font-weight: 500;
        }
        .content-wrapper {
            background: #fff;
            border-radius: .5rem;
            padding: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
        }
        .navbar-brand {
            font-weight: 600;
            letter-spacing: .5px;
        }
        .readonly-field {
            background-color: #e9ecef;
            cursor: not-allowed;
        }

        .photo-box {
            position: relative;
            width: 100%;
            padding-top: 100%; /* carré */
            overflow: hidden;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .photo-box img.photo-preview {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover; /* remplit tout le cadre */
            z-index: 1;
        }

        .photo-empty {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #e9ecef; /* gris clair */
            display: flex;
            justify-content: center;
            align-items: center;
            color: #adb5bd; /* couleur icône */
            font-size: 2rem;
            z-index: 0;
            border: 2px dashed #ced4da; /* effet cadre photo vide */
            border-radius: 5px;
        }

        .photo-empty i {
            pointer-events: none;
        }

        .photo-buttons {
            position: absolute;
            bottom: 5px;
            right: 5px;
            z-index: 2; /* devant l'image */
            display: flex;
            gap: 5px;
        }
        
        .publication-card{
            transition: all .25s ease;
            border-radius:12px;
        }

        .publication-card:hover{
            transform: translateY(-5px);
            box-shadow:0 10px 25px rgba(0,0,0,0.15);
        }

        .carousel img{
            border-top-left-radius:12px;
            border-top-right-radius:12px;
        }
    </style>
</head>
<body>

{{-- Navbar --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow">
    <div class="container">        
        <span class="navbar-brand">
            <i class="bi bi-box-seam"></i>
            <a class="navbar-brand" href="{{ url('/') }}">Ki-Loue</a>
        </span>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Dark mode toggle --}}
        <div class="text-end mb-2">
            <button class="btn btn-sm btn-outline-secondary"
                    onclick="toggleTheme()">
                🌙 / ☀️
            </button>
        </div>
        
        @yield('nav-bar')       
        
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">

                {{-- Pays actif (session ou défaut) --}}
                @php
                    $currentPays = session('pays') ?? $paysList->first();
                @endphp

                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                href="#" role="button" data-bs-toggle="dropdown">

                    <img src="https://flagcdn.com/w20/{{ strtolower($currentPays->code) }}.png"
                        class="rounded" alt="{{ $currentPays->nom }}">

                    <span>{{ $currentPays->nom }}</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                    @foreach($paysList as $pays)
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2"
                            href="{{ route('change.pays', $pays->id) }}">

                                <img src="https://flagcdn.com/w20/{{ strtolower($pays->code) }}.png"
                                    alt="{{ $pays->nom }}">

                                <div>
                                    <div class="fw-semibold">{{ $pays->nom }}</div>
                                    <small class="text-muted">{{ $pays->langue_officielle }}</small>
                                </div>
                            </a>
                        </li>
                    @endforeach

                </ul>
            </li>
        </ul>

    </div>
</nav>

{{-- Main Content --}}
<div class="container mt-4">
    @yield('main-content')
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/js/intlTelInput.min.js"></script>  
{{-- Custom JS --}}
@stack('scripts')

{{-- Scripts --}}

<script>
function togglePassword() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');

    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        pwd.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}

function toggleTheme() {
    document.body.classList.toggle('dark-mode');
}
</script>
</body>
</html>
