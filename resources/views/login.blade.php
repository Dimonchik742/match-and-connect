@extends('layouts.app')

@section('title', 'Вхід на платформу')

@section('content')
    <div class="row justify-content-center align-items-center" style="min-height: 75vh;">
        <div class="col-md-5 col-lg-4">

            <div class="text-center mb-4">
                <h2 class="fw-bold text-white mb-1" style="letter-spacing: 0.5px;">Match & Connect</h2>
                <p class="text-muted small">Знайди людей зі спільними інтересами</p>
            </div>

            <div class="card border-0 shadow-lg"
                style="border-radius: 20px; background: #1e1e1e; border: 1px solid #2a2a2a !important;">
                <div class="card-body p-4 p-md-5">

                    @if ($errors->any())
                        <div class="alert alert-danger"
                            style="background-color: rgba(207, 102, 121, 0.1); border-color: #cf6679; color: #cf6679; border-radius: 12px;">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="/login" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label text-muted small fw-bold text-uppercase"
                                style="letter-spacing: 1px;">Email адреса</label>
                            <input type="email" class="form-control form-control-lg border-0 shadow-none text-white"
                                id="email" name="email" value="{{ old('email') }}"
                                style="background: #2d2d2d; border-radius: 12px;" required autofocus>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="password" class="form-label text-muted small fw-bold text-uppercase mb-0"
                                    style="letter-spacing: 1px;">Пароль</label>
                                <a href="#" class="text-decoration-none small" style="color: #bb86fc;">Забули пароль?</a>
                            </div>
                            <input type="password" class="form-control form-control-lg border-0 shadow-none text-white"
                                id="password" name="password" style="background: #2d2d2d; border-radius: 12px;" required>
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input shadow-none" id="remember" name="remember"
                                style="background-color: #2d2d2d; border-color: #444;">
                            <label class="form-check-label text-muted small" for="remember">Запам'ятати мене</label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold text-dark mb-4"
                            style="font-size: 1rem;">Увійти</button>
                    </form>

                    <div class="text-center pt-3" style="border-top: 1px solid #2a2a2a;">
                        <span class="text-muted small">Ще не з нами?</span>
                        <a href="/register" class="fw-bold text-decoration-none ms-1"
                            style="color: #bb86fc;">Зареєструватись</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection