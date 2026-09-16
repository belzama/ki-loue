{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Rentalpark')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Icons Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/css/intlTelInput.css" rel="stylesheet">
    
    
    @stack('styles')

    {{-- Custom CSS --}}
   <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>
<body>
    {{-- Bande orange : icônes sociales + navbar en pilule --}}
    <header class="site-header">
        <div class="header">
            <div class="header-social">
                <a href="#" target="_blank"><i class="bi bi-linkedin"></i></a>
                <a href="#" target="_blank"><i class="bi bi-tiktok"></i></a>
                <a href="#" target="_blank"><i class="bi bi-instagram"></i></a>
                <a href="#" target="_blank"><i class="bi bi-facebook"></i></a>
            </div>

            <nav class="navbar navbar-expand-lg navbar-dark bg-blue-custom shadow">
                <div class="container-fluid px-3">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{ asset('images/logo_text_fond_bleu.png') }}" alt="Rentalpark">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            @yield('nav-bar')
                        </ul>
                    </div>
                    <div class="navbar-right">
                        {{-- Pays + thème : identique à la version précédente --}}
                        @include('partials.select-pays-theme')
                    </div>
                </div>
            </nav>
        </div>
    </header>

    {{-- Main Content --}}
    @yield('main-content')

    {{-- Footer --}}
    <footer class="mt-5 bg-body-tertiary">
        <div class="footer-card">
            <div class="container">
                <div class="row g-4">
                    {{-- Colonne 1 : À propos --}}
                    <div class="col-lg-4 col-md-6">
                        <img src="{{ asset('images/logo_text_fond_blanc.png') }}" alt="Rentalpark" height="40">
                        <p class="text-muted small">
                            La plateforme de référence pour la location de matériels et équipements.
                            Trouvez ce dont vous avez besoin, où que vous soyez.
                        </p>
                        <div class="d-flex gap-3 fs-5 mt-3 social-icons">
                            <a href="#"><i class="bi bi-facebook"></i></a>
                            <a href="#"><i class="bi bi-instagram"></i></a>
                            <a href="#"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>

                    {{-- Colonne 2 : Liens rapides --}}
                    <div class="col-lg-2 col-md-6">
                        <h6 class="fw-bold mb-3">Navigation</h6>
                        <ul class="list-unstyled small">
                            <li class="mb-2"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Accueil</a></li>
                            <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Parcourir</a></li>
                            <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Comment ça marche</a></li>
                        </ul>
                    </div>

                    {{-- Colonne 3 : Support --}}
                    <div class="col-lg-3 col-md-6">
                        <h6 class="fw-bold mb-3">Aide & Support</h6>
                        <ul class="list-unstyled small">
                            <li class="mb-2"><a href="#" class="text-decoration-none text-muted">FAQ</a></li>
                            <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Conditions Générales</a></li>
                            <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Politique de confidentialité</a></li>
                        </ul>
                    </div>

                    {{-- Colonne 4 : Contact --}}
                    <div class="col-lg-3 col-md-6">
                        <h6 class="fw-bold mb-3">Contact</h6>
                        <ul class="list-unstyled small text-muted">
                            <li class="mb-2"><i class="bi bi-geo-alt me-2"></i> Lomé, Togo</li>
                            <li class="mb-2"><i class="bi bi-envelope me-2"></i> contact@Rentalpark.com</li>
                            <li class="mb-2"><i class="bi bi-telephone me-2"></i> +228 00 00 00 00</li>
                        </ul>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 small text-muted">
                    <span>&copy; {{ date('Y') }} Rentalpark. Tous droits réservés.</span>
                    <div class="d-flex gap-3">
                        <span>Développé avec <i class="bi bi-heart-fill text-danger"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    
    @include('partials.verification-modal')

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/js/intlTelInput.min.js"></script>
    <script src="{{ asset('js/laravel-form-handler.js') }}"></script>
    {{-- Custom JS --}}
    @stack('scripts')

    {{-- Scripts --}}

    <script>

        function adjustLayout() {
            const navbar = document.querySelector('.bg-blue-custom');
            if (navbar) {
                const height = navbar.getBoundingClientRect().height;
                document.documentElement.style.setProperty('--navbar-height', height + 'px');
            }
        }

        adjustLayout();
        window.addEventListener('resize', adjustLayout);

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
            const html = document.documentElement;
            const icon = document.getElementById('themeIcon');

            if (html.getAttribute('data-bs-theme') === 'dark') {
                // Passer en mode clair
                html.setAttribute('data-bs-theme', 'light');
                icon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
                localStorage.setItem('theme', 'light');
            } else {
                // Passer en mode sombre
                html.setAttribute('data-bs-theme', 'dark');
                icon.classList.replace('bi-sun-fill', 'bi-moon-stars-fill');
                localStorage.setItem('theme', 'dark');
            }
        }

        // Appliquer le thème sauvegardé au chargement de la page
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
            const icon = document.getElementById('themeIcon');
            if (icon) {
                icon.className = savedTheme === 'dark' ? 'bi-moon-stars-fill' : 'bi-sun-fill';
            }
        })();
    </script>
</body>
</html>
