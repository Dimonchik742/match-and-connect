@extends('layouts.app')

@section('title', 'Налаштування профілю')

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">

    <div class="row justify-content-center mt-4 mb-5">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm" style="border-radius: 20px; background: #1e1e1e;">

                <div class="card-header border-bottom-0 pt-4 pb-0" style="background: transparent;">
                    <h4 class="mb-0 fw-bold text-white text-center">Налаштування профілю</h4>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="/profile/edit" method="POST" enctype="multipart/form-data" id="profileForm">
                        @csrf

                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label class="form-label text-muted small fw-bold text-uppercase"
                                    style="letter-spacing: 1px;">Ваше ім'я</label>
                                <input type="text" name="name"
                                    class="form-control form-control-lg border-0 shadow-none text-white"
                                    style="background: #2d2d2d; border-radius: 12px;" value="{{ $user->name }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-bold text-uppercase"
                                    style="letter-spacing: 1px;">Вік</label>
                                <input type="number" name="age"
                                    class="form-control form-control-lg border-0 shadow-none text-white"
                                    style="background: #2d2d2d; border-radius: 12px;" value="{{ $user->age }}" min="16"
                                    max="100" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold text-uppercase"
                                style="letter-spacing: 1px;">Про себе</label>
                            <textarea name="bio" class="form-control border-0 shadow-none text-white" rows="4"
                                style="background: #2d2d2d; border-radius: 12px; resize: none;"
                                placeholder="Розкажіть трохи про себе...">{{ $user->bio }}</textarea>
                        </div>

                        <div class="mb-5">
                            <label class="form-label text-muted small fw-bold text-uppercase"
                                style="letter-spacing: 1px;">Фото профілю</label>
                            <div class="d-flex align-items-center bg-dark p-3"
                                style="border-radius: 12px; border: 1px dashed #444;">

                                <img id="preview-avatar"
                                    src="{{ $user->photo ? '/storage/' . $user->photo : 'https://via.placeholder.com/150' }}"
                                    class="rounded-circle object-fit-cover me-3 shadow-sm"
                                    style="width: 60px; height: 60px; border: 2px solid #bb86fc;" alt="Фото">

                                <div class="flex-grow-1">
                                    <input type="file" id="photo-input" name="photo"
                                        class="form-control form-control-sm border-0 text-white shadow-none"
                                        style="background: transparent;" accept="image/png, image/jpeg, image/jpg">
                                </div>
                            </div>
                            <div class="form-text text-muted small mt-1">Виберіть файл, щоб обрізати та оновити фото.</div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label text-muted small fw-bold text-uppercase mb-3"
                                style="letter-spacing: 1px;">Ваші інтереси</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($allInterests as $interest)
                                    <div>
                                        <input type="checkbox" class="btn-check" name="interests[]" value="{{ $interest->id }}"
                                            id="interest_{{ $interest->id }}" {{ $user->interests->contains($interest->id) ? 'checked' : '' }}>
                                        <label class="btn btn-outline-secondary rounded-pill px-3 py-2"
                                            for="interest_{{ $interest->id }}"
                                            style="font-size: 0.85rem; border-color: #444; color: #ccc;">
                                            {{ $interest->name }}
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
                        </div>

                        <div class="d-flex justify-content-between pt-3 border-top"
                            style="border-color: #2a2a2a !important;">
                            <a href="/profile" class="btn btn-dark rounded-pill px-4">Скасувати</a>
                            <button type="submit"
                                class="btn btn-primary rounded-pill px-5 fw-bold text-dark">Зберегти</button>
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
@endsection