@extends('layouts.app')

@section('title', 'Реєстрація')

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">

    <div class="row justify-content-center mt-4 mb-5">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-lg"
                style="border-radius: 20px; background: #1e1e1e; border: 1px solid #2a2a2a !important;">

                <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0 px-4 px-md-5">
                    <h4 class="mb-0 fw-bold text-white text-center" id="form-title">Крок 1: Базові дані</h4>
                </div>

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

                    <form action="/register" method="POST" enctype="multipart/form-data" id="registerForm">
                        @csrf

                        <div id="step-1">
                            <div class="mb-4">
                                <label for="email" class="form-label text-muted small fw-bold text-uppercase"
                                    style="letter-spacing: 1px;">Email адреса</label>
                                <input type="email" class="form-control form-control-lg border-0 shadow-none text-white"
                                    id="email" name="email" value="{{ old('email') }}"
                                    style="background: #2d2d2d; border-radius: 12px;" required autofocus>
                            </div>

                            <div class="mb-5">
                                <label for="password" class="form-label text-muted small fw-bold text-uppercase"
                                    style="letter-spacing: 1px;">Пароль</label>
                                <input type="password" class="form-control form-control-lg border-0 shadow-none text-white"
                                    id="password" name="password" style="background: #2d2d2d; border-radius: 12px;"
                                    required>
                            </div>

                            <button type="button" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold text-dark"
                                onclick="nextStep()">Далі ➔</button>

                            <div class="text-center mt-4">
                                <span class="text-muted small">Вже є акаунт?</span>
                                <a href="/login" class="fw-bold text-decoration-none ms-1"
                                    style="color: #bb86fc;">Увійти</a>
                            </div>
                        </div>

                        <div id="step-2" class="d-none">
                            <div class="row g-3 mb-4">
                                <div class="col-md-8">
                                    <label for="name" class="form-label text-muted small fw-bold text-uppercase"
                                        style="letter-spacing: 1px;">Ваше ім'я</label>
                                    <input type="text" class="form-control form-control-lg border-0 shadow-none text-white"
                                        id="name" name="name" value="{{ old('name') }}"
                                        style="background: #2d2d2d; border-radius: 12px;">
                                </div>
                                <div class="col-md-4">
                                    <label for="age" class="form-label text-muted small fw-bold text-uppercase"
                                        style="letter-spacing: 1px;">Вік</label>
                                    <input type="number"
                                        class="form-control form-control-lg border-0 shadow-none text-white" id="age"
                                        name="age" value="{{ old('age') }}"
                                        style="background: #2d2d2d; border-radius: 12px;">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted small fw-bold text-uppercase"
                                    style="letter-spacing: 1px;">Фото профілю</label>
                                <div class="d-flex align-items-center bg-dark p-3"
                                    style="border-radius: 12px; border: 1px dashed #444;">
                                    <img id="preview-avatar" src="https://via.placeholder.com/150"
                                        class="rounded-circle object-fit-cover me-3 shadow-sm"
                                        style="width: 60px; height: 60px; border: 2px solid #bb86fc;" alt="Фото">
                                    <div class="flex-grow-1">
                                        <input type="file" id="photo-input" name="photo"
                                            class="form-control form-control-sm border-0 text-white shadow-none"
                                            style="background: transparent;" accept="image/png, image/jpeg, image/jpg">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="bio" class="form-label text-muted small fw-bold text-uppercase"
                                    style="letter-spacing: 1px;">Коротко про себе</label>
                                <textarea class="form-control border-0 shadow-none text-white" id="bio" name="bio" rows="3"
                                    style="background: #2d2d2d; border-radius: 12px; resize: none;"
                                    placeholder="Напишіть пару слів..."></textarea>
                            </div>

                            <h6 class="text-muted small fw-bold text-uppercase mb-3" style="letter-spacing: 1px;">Оберіть
                                ваші інтереси</h6>

                            <div class="d-flex flex-wrap gap-2 mb-5">
                                @php
                                    $defaultInterests = ['Python', 'Java', 'The Witcher 3', 'Збірка ПК'];
                                @endphp

                                @foreach($defaultInterests as $index => $interest)
                                    <div>
                                        <input type="checkbox" class="btn-check" name="interests[]" value="{{ $interest }}"
                                            id="int{{ $index }}">
                                        <label class="btn btn-outline-secondary rounded-pill px-3 py-2" for="int{{ $index }}"
                                            style="font-size: 0.85rem; border-color: #444; color: #ccc;">
                                            {{ $interest }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <style>
                                .btn-check:checked+.btn-outline-secondary {
                                    background-color: rgba(187, 134, 252, 0.15) !important;
                                    border-color: #bb86fc !important;
                                    color: #bb86fc !important;
                                    font-weight: 600;
                                }
                            </style>

                            <div class="d-flex justify-content-between pt-3" style="border-top: 1px solid #2a2a2a;">
                                <button type="button" class="btn btn-dark rounded-pill px-4"
                                    onclick="prevStep()">Назад</button>
                                <button type="submit"
                                    class="btn btn-primary rounded-pill px-4 fw-bold text-dark">Завершити</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="background-color: #1e1e1e; border-radius: 16px;">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold text-white">Обрізати фото</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 bg-dark d-flex justify-content-center" style="height: 350px;">
                    <img id="image-to-crop" style="max-width: 100%; display: block;" src="">
                </div>
                <div class="modal-footer border-top-0 d-flex justify-content-between">
                    <button type="button" class="btn btn-dark rounded-pill px-4" data-bs-dismiss="modal">Скасувати</button>
                    <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold text-dark"
                        id="crop-btn">Застосувати</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="{{ asset('js/avatar-cropper.js') }}"></script>

    <script>
        function nextStep() {
            let email = document.getElementById('email').value;
            let password = document.getElementById('password').value;

            if (email === '' || password === '') {
                alert('Будь ласка, заповніть email та пароль!');
                return;
            }

            document.getElementById('step-1').classList.add('d-none');
            document.getElementById('step-2').classList.remove('d-none');
            document.getElementById('form-title').innerText = 'Крок 2: Деталі профілю';
        }

        function prevStep() {
            document.getElementById('step-2').classList.add('d-none');
            document.getElementById('step-1').classList.remove('d-none');
            document.getElementById('form-title').innerText = 'Крок 1: Базові дані';
        }
    </script>
@endsection