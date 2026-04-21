@extends('layouts.app')

@section('title', 'Мої діалоги')

@section('content')
    <style>
        /* Стилі для карток діалогів */
        .dialog-card {
            background-color: #1e1e1e;
            border: 1px solid #2a2a2a;
            transition: all 0.2s ease;
        }

        .dialog-card:hover {
            background-color: #252525;
            border-color: #bb86fc;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .dialog-arrow {
            color: #444;
            transition: color 0.2s ease;
        }

        .dialog-card:hover .dialog-arrow {
            color: #bb86fc;
        }
    </style>

    <div class="row justify-content-center mt-4 mb-5">
        <div class="col-md-8 col-lg-6">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="mb-0 fw-bold text-white">Повідомлення</h3>
                <span class="badge bg-dark border border-secondary text-muted px-3 py-2 rounded-pill">
                    {{ count($contacts) }}
                </span>
            </div>

            <div class="d-flex flex-column gap-3">
                @forelse($contacts as $contact)
                    <a href="/chat/{{ $contact->id }}"
                        class="dialog-card rounded-4 p-3 text-decoration-none d-flex align-items-center">

                        <div class="position-relative me-3 flex-shrink-0">
                            @if($contact->photo)
                                <img src="/storage/{{ $contact->photo }}" class="rounded-circle object-fit-cover shadow-sm"
                                    style="width: 55px; height: 55px; border: 2px solid #2d2d2d;" alt="Фото">
                            @else
                                <div class="bg-dark rounded-circle d-flex justify-content-center align-items-center text-muted shadow-sm"
                                    style="width: 55px; height: 55px; border: 2px solid #2d2d2d;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                        class="bi bi-person-fill" viewBox="0 0 16 16">
                                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="flex-grow-1 min-w-0">
                            <h6 class="mb-1 fw-bold text-white text-truncate">{{ $contact->name }}</h6>
                            <small class="text-muted text-truncate d-block">Натисніть, щоб відкрити листування</small>
                        </div>

                        <div class="ms-2 dialog-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                class="bi bi-chevron-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                            </svg>
                        </div>
                    </a>

                @empty
                    <div class="text-center p-5 rounded-4" style="background-color: #1e1e1e; border: 1px dashed #2a2a2a;">
                        <div class="mb-3" style="color: #444;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                class="bi bi-chat-square-dots" viewBox="0 0 16 16">
                                <path
                                    d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-2.5a2 2 0 0 0-1.6.8L8 14.333 6.1 11.8a2 2 0 0 0-1.6-.8H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2.5a1 1 0 0 1 .8.4l1.9 2.533a1 1 0 0 0 1.6 0l1.9-2.533a1 1 0 0 1 .8-.4H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                                <path
                                    d="M5 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                            </svg>
                        </div>
                        <h5 class="text-white fw-bold mb-2">Немає активних діалогів</h5>
                        <p class="text-muted small mb-4">Ви ще ні з ким не почали спілкування. Знайдіть нові знайомства!</p>
                        <a href="/search" class="btn btn-primary rounded-pill px-4 fw-bold">Перейти до пошуку</a>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
@endsection