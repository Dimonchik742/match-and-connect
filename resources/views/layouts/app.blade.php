<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Match & Connect')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #121212 !important;
            color: #e0e0e0 !important;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        /* Навігаційна панель */
        .navbar {
            background-color: #1e1e1e !important;
            border-bottom: 1px solid #2a2a2a !important;
            padding: 12px 0 !important;
        }

        .navbar-brand {
            color: #ffffff !important;
            letter-spacing: 0.5px;
        }

        .nav-link {
            color: #a0a0a0 !important;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-left: 15px;
        }

        .nav-link:hover {
            color: #ffffff !important;
        }

        .nav-link.nav-accent {
            color: #bb86fc !important;
        }

        .nav-link.nav-accent:hover {
            color: #d1b2ff !important;
            text-shadow: 0 0 10px rgba(187, 134, 252, 0.3);
        }

        .navbar-toggler {
            border-color: #444444 !important;
        }

        .navbar-toggler-icon {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        /* Картки, шапка, панелі */
        .card,
        .card-header,
        .card-footer,
        .offcanvas {
            background-color: #1e1e1e !important;
            border: 1px solid #2a2a2a !important;
            color: #ffffff !important;
        }

        .offcanvas-header {
            background-color: #1e1e1e !important;
            border-bottom: 1px solid #2a2a2a !important;
        }

        .border-bottom {
            border-bottom: 1px solid #2a2a2a !important;
        }

        .text-muted {
            color: #a0a0a0 !important;
        }

        /* Головні кнопки та акценти (Неоновий фіолетовий) */
        .btn-primary,
        .bg-primary {
            background-color: #bb86fc !important;
            border: none !important;
            color: #000000 !important;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-primary:hover {
            background-color: #d1b2ff !important;
            box-shadow: 0 0 15px rgba(187, 134, 252, 0.4) !important;
            color: #000000 !important;
        }

        .btn-outline-primary {
            border-color: #bb86fc !important;
            color: #bb86fc !important;
        }

        .btn-outline-primary:hover {
            background-color: #bb86fc !important;
            color: #000000 !important;
        }

        .btn-dark {
            background-color: #333333 !important;
            border: 1px solid #444444 !important;
        }

        .btn-outline-danger {
            border-color: #cf6679 !important;
            color: #cf6679 !important;
        }

        .btn-outline-danger:hover {
            background-color: #cf6679 !important;
            color: #000000 !important;
        }

        /* Форми вводу */
        .form-control,
        .form-select {
            background-color: #2d2d2d !important;
            border: 1px solid #444444 !important;
            color: #ffffff !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #bb86fc !important;
            box-shadow: 0 0 0 0.25rem rgba(187, 134, 252, 0.25) !important;
            background-color: #2d2d2d !important;
            color: #ffffff !important;
        }

        /* Чат: Ваші повідомлення */
        .my-message-bubble {
            background-color: #bb86fc !important;
            color: #000000 !important;
            box-shadow: none !important;
        }

        /* Чат: Повідомлення співрозмовника */
        .justify-content-start .bg-white.border {
            background-color: #2d2d2d !important;
            border: 1px solid #333333 !important;
            color: #e0e0e0 !important;
            box-shadow: none !important;
        }

        /* Інтереси (Теги) */
        .badge.bg-light {
            background-color: #2d2d2d !important;
            color: #bb86fc !important;
            border: 1px solid #444444 !important;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/search">Match & Connect</a>

            <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    @auth
                        <li class="nav-item"><a class="nav-link" href="/profile">Мій профіль</a></li>
                        <li class="nav-item"><a class="nav-link" href="/search">Пошук людей</a></li>

                        @if(Auth::user()->is_admin == 1)
                            <li class="nav-item">
                                <a class="nav-link text-danger fw-bold" href="/admin">Адмін панель</a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link fw-bold nav-accent" href="/messages">Повідомлення</a>
                        </li>

                        <li class="nav-item ms-lg-4 mt-3 mt-lg-0">
                            <a class="btn btn-sm btn-outline-danger w-100 rounded-pill px-4" href="/logout">Вийти</a>
                        </li>
                    @endauth

                    @guest
                        <li class="nav-item ms-lg-4 mt-3 mt-lg-0">
                            <a class="btn btn-sm btn-outline-primary w-100 rounded-pill px-4"
                                href="/register">Реєстрація</a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>