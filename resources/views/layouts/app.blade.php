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

    <!-- Waiting global -->
    <div id="global-waiting" class="global-waiting d-none">
        <div class="waiting-box">

            <div class="waiting-logo-container">
                <img
                    src="{{ asset('images/logo_fond_blanc.png') }}"
                    alt="RentalPark"
                    class="waiting-logo"
                >

                <div class="waiting-spinner-overlay">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            </div>

            <div class="waiting-message">
                Traitement en cours...
            </div>

        </div>
    </div>

    
    @include('partials.verification-modal')

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/js/intlTelInput.min.js"></script>
    
    {{-- Scripts --}}

    <script>
        (function () {

            'use strict';

            /*
            |--------------------------------------------------------------------------
            | WAITING GLOBAL RENTALPARK
            |--------------------------------------------------------------------------
            */

            const waiting = document.getElementById('global-waiting');

            if (!waiting) {
                console.error('❌ #global-waiting introuvable.');
                return;
            }

            const messageElement = waiting.querySelector('.waiting-message');

            let waitingVisible = false;


            /*
            |--------------------------------------------------------------------------
            | AFFICHER
            |--------------------------------------------------------------------------
            */

            window.showWaiting = function (message) {

                const defaultMessage = 'Patientez SVP...';

                if (messageElement) {
                    messageElement.textContent =
                        typeof message === 'string' && message.trim() !== ''
                            ? message
                            : defaultMessage;
                }

                waiting.classList.remove('d-none');
                waitingVisible = true;
            };


            /*
            |--------------------------------------------------------------------------
            | MASQUER
            |--------------------------------------------------------------------------
            */

            window.hideWaiting = function () {

                waiting.classList.add('d-none');

                waitingVisible = false;

                document.body.classList.remove('waiting-active');

                // Réinitialiser le message
                if (messageElement) {
                    messageElement.textContent = 'Patientez SVP...';
                }
            };


            /*
            |--------------------------------------------------------------------------
            | FORCER LA FERMETURE
            |--------------------------------------------------------------------------
            */

            window.forceHideWaiting = function () {

                waiting.classList.add('d-none');

                waitingVisible = false;

                document.body.classList.remove('waiting-active');
                
                // Réinitialiser le message
                if (messageElement) {
                    messageElement.textContent = 'Patientez SVP...';
                }
            };


            /*
            |--------------------------------------------------------------------------
            | TEST
            |--------------------------------------------------------------------------
            |
            | Dans la console :
            |
            | showWaiting('Test...');
            | hideWaiting();
            |
            */

            window.testWaiting = function () {

                showWaiting('Test du système...');

                setTimeout(function () {
                    hideWaiting();
                }, 2000);

            };


            /*
            |--------------------------------------------------------------------------
            | 1. SUBMIT NORMAL
            |--------------------------------------------------------------------------
            */

            document.addEventListener('submit', function (event) {

                const form = event.target;

                if (!(form instanceof HTMLFormElement)) {
                    return;
                }

                if (form.hasAttribute('data-no-waiting')) {
                    return;
                }

                showWaiting(
                    form.dataset.waitingMessage ||
                    'Enregistrement en cours...'
                );

                disableSubmitButtons(form);

            }, true);


            /*
            |--------------------------------------------------------------------------
            | 2. form.submit()
            |--------------------------------------------------------------------------
            |
            | IMPORTANT :
            | form.submit() ne déclenche PAS l'événement submit.
            |
            | On intercepte donc la méthode native.
            |--------------------------------------------------------------------------
            */

            const nativeSubmit =
                HTMLFormElement.prototype.submit;

            HTMLFormElement.prototype.submit = function () {

                if (!this.hasAttribute('data-no-waiting')) {

                    showWaiting(
                        this.dataset.waitingMessage ||
                        'Enregistrement en cours...'
                    );

                    disableSubmitButtons(this);
                }

                return nativeSubmit.call(this);
            };


            /*
            |--------------------------------------------------------------------------
            | 3. requestSubmit()
            |--------------------------------------------------------------------------
            */

            const nativeRequestSubmit =
                HTMLFormElement.prototype.requestSubmit;

            if (nativeRequestSubmit) {

                HTMLFormElement.prototype.requestSubmit =
                    function (...args) {

                        if (!this.hasAttribute('data-no-waiting')) {

                            showWaiting(
                                this.dataset.waitingMessage ||
                                'Enregistrement en cours...'
                            );

                            disableSubmitButtons(this);
                        }

                        return nativeRequestSubmit.apply(this, args);
                    };
            }


            /*
            |--------------------------------------------------------------------------
            | 4. DÉSACTIVER LES BOUTONS SUBMIT
            |--------------------------------------------------------------------------
            */

            function disableSubmitButtons(form) {

                form.querySelectorAll(
                    'button[type="submit"], input[type="submit"]'
                ).forEach(function (button) {

                    if (button.disabled) {
                        return;
                    }

                    button.disabled = true;

                    if (button.tagName === 'BUTTON') {

                        if (!button.dataset.originalHtml) {

                            button.dataset.originalHtml =
                                button.innerHTML;
                        }

                        button.innerHTML = `
                            <span
                                class="spinner-border spinner-border-sm me-1"
                                role="status"
                                aria-hidden="true">
                            </span>
                            Traitement...
                        `;
                    }

                });
            }


            /*
            |--------------------------------------------------------------------------
            | 5. LIENS
            |--------------------------------------------------------------------------
            |
            | On affiche automatiquement le waiting pour les liens
            | qui entraînent une navigation.
            |--------------------------------------------------------------------------
            */

            document.addEventListener('click', function (event) {

                const link = event.target.closest('a');

                if (!link) {
                    return;
                }

                if (link.hasAttribute('data-no-waiting')) {
                    return;
                }

                /*
                * Ignorer les liens sans destination
                */

                const href = link.getAttribute('href');

                if (
                    !href ||
                    href === '#' ||
                    href.startsWith('javascript:')
                ) {
                    return;
                }

                /*
                * Ignorer les ancres de la page
                */

                if (href.startsWith('#')) {
                    return;
                }

                /*
                * Ignorer nouvel onglet
                */

                if (
                    link.target === '_blank' ||
                    event.ctrlKey ||
                    event.metaKey ||
                    event.shiftKey ||
                    event.altKey
                ) {
                    return;
                }

                /*
                * Ignorer bouton désactivé
                */

                if (link.classList.contains('disabled')) {
                    return;
                }

                showWaiting(
                    link.dataset.waitingMessage ||
                    'Chargement en cours...'
                );

            }, true);


            /*
            |--------------------------------------------------------------------------
            | 6. BOUTONS AVEC data-waiting
            |--------------------------------------------------------------------------
            */

            document.addEventListener('click', function (event) {

                const button = event.target.closest(
                    'button[data-waiting]'
                );

                if (!button) {
                    return;
                }

                if (button.hasAttribute('data-no-waiting')) {
                    return;
                }

                if (button.disabled) {
                    return;
                }

                /*
                * Les boutons submit sont déjà gérés par submit.
                */

                if (
                    button.type &&
                    button.type.toLowerCase() === 'submit'
                ) {
                    return;
                }

                showWaiting(
                    button.dataset.waitingMessage ||
                    'Patientez SVP...'
                );

            }, true);


            /*
            |--------------------------------------------------------------------------
            | 7. AJAX JQUERY
            |--------------------------------------------------------------------------
            */

            if (window.jQuery) {

                $(document).ajaxStart(function () {

                    showWaiting(
                        'Patientez SVP...'
                    );

                });

                $(document).ajaxStop(function () {

                    hideWaiting();

                });

                $(document).ajaxError(function () {

                    hideWaiting();

                });
            }


            /*
            |--------------------------------------------------------------------------
            | 8. FETCH
            |--------------------------------------------------------------------------
            */

            if (window.fetch) {

                const nativeFetch = window.fetch;

                window.fetch = function (...args) {

                    showWaiting(
                        'Patientez SVP...'
                    );

                    return nativeFetch.apply(this, args)
                        .then(function (response) {

                            return response;

                        })
                        .catch(function (error) {

                            throw error;

                        })
                        .finally(function () {

                            hideWaiting();

                        });
                };
            }


            /*
            |--------------------------------------------------------------------------
            | 9. NAVIGATION DE PAGE
            |--------------------------------------------------------------------------
            */

            window.addEventListener('pageshow', function () {

                hideWaiting();

            });


            /*
            |--------------------------------------------------------------------------
            | 10. DEBUG
            |--------------------------------------------------------------------------
            */

            console.log(
                '✅ Waiting global RentalPark initialisé.'
            );

        })();

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
    
    <script src="{{ asset('js/laravel-form-handler.js') }}"></script>
    {{-- Custom JS --}}
    @stack('scripts')

</body>
</html>
