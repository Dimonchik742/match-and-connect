@extends('layouts.app')

@section('title', 'Ласкаво просимо до Match & Connect')

@section('content')
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-lg-8 text-center">

            <h1 class="display-3 fw-bold text-white mb-3" style="letter-spacing: -1px;">
                Знайди свою <span style="color: #bb86fc;">ідеальну</span> пару за інтересами
            </h1>

            <p class="lead text-muted mb-5 mx-auto" style="max-width: 600px; font-size: 1.25rem;">
                Match & Connect — це більше ніж дейтинг. Це платформа, де спільні захоплення стають початком великих
                історій.
            </p>

            <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                <a href="/register" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-bold text-dark shadow-lg">
                    Створити профіль безкоштовно
                </a>
                <a href="/login" class="btn btn-outline-light btn-lg rounded-pill px-5 py-3 fw-bold"
                    style="border-color: #444;">
                    Увійти до акаунту
                </a>
            </div>

            <div class="row mt-5 pt-5 justify-content-center opacity-75">
                <div class="col-4 col-md-3">
                    <h4 class="text-white fw-bold mb-0">10k+</h4>
                    <small class="text-muted text-uppercase"
                        style="font-size: 0.7rem; letter-spacing: 1px;">Користувачів</small>
                </div>
                <div class="col-4 col-md-3">
                    <h4 class="text-white fw-bold mb-0">500+</h4>
                    <small class="text-muted text-uppercase"
                        style="font-size: 0.7rem; letter-spacing: 1px;">Інтересів</small>
                </div>
                <div class="col-4 col-md-3">
                    <h4 class="text-white fw-bold mb-0">2k+</h4>
                    <small class="text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Метчів
                        щодня</small>
                </div>
            </div>

        </div>
    </div>

    <style>
        /* Плавна анімація появи контенту */
        body {
            background: radial-gradient(circle at top right, #1a1025 0%, #121212 40%) !important;
        }

        h1 {
            animation: fadeInUp 0.8s ease-out;
        }

        p {
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Ефект для кнопок */
        .btn-primary:hover {
            transform: scale(1.05);
            background-color: #d1b2ff !important;
        }
    </style>
@endsection