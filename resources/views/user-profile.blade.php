@extends('layouts.app')

@section('title', 'Профіль: ' . $user->name)

@section('content')
    <div class="row justify-content-center mt-4 mb-5">
        <div class="col-lg-8">

            <div class="card border-0 shadow-lg mb-4"
                style="border-radius: 20px; background: #1e1e1e; border: 1px solid #2a2a2a !important;">
                <div class="card-body p-4 p-md-5">

                    <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start mb-4 pb-4"
                        style="border-bottom: 1px solid #2a2a2a;">

                        <div class="flex-shrink-0 mb-3 mb-md-0 position-relative">
                            @if($user->photo)
                                <img src="/storage/{{ $user->photo }}" class="rounded-circle object-fit-cover shadow"
                                    style="width: 140px; height: 140px; border: 3px solid #2d2d2d;" alt="Фото">
                            @else
                                <div class="bg-dark rounded-circle d-flex justify-content-center align-items-center text-muted shadow"
                                    style="width: 140px; height: 140px; border: 3px solid #2d2d2d;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor"
                                        class="bi bi-person-fill" viewBox="0 0 16 16">
                                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                    </svg>
                                </div>
                            @endif

                            <span class="position-absolute bottom-0 end-0 p-2 bg-success border border-dark rounded-circle"
                                style="margin-bottom: 10px; margin-right: 10px;">
                                <span class="visually-hidden">Онлайн</span>
                            </span>
                        </div>

                        <div class="flex-grow-1 ms-md-4 text-center text-md-start w-100">
                            <h2 class="fw-bold mb-1 text-white">{{ $user->name }}</h2>
                            <h5 class="text-muted mb-3">{{ $user->age ?? 'Вік не вказано' }} років</h5>

                            <div
                                class="d-flex flex-column flex-sm-row gap-2 justify-content-center justify-content-md-start">
                                <a href="/chat/{{ $user->id }}"
                                    class="btn btn-primary rounded-pill px-4 fw-bold text-dark d-flex align-items-center justify-content-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                        class="bi bi-chat-text-fill me-2" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8c0 3.866-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7zM4.5 5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7zm0 2.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7zm0 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1h-4z" />
                                    </svg>
                                    Написати
                                </a>
                                <a href="/search"
                                    class="btn btn-dark rounded-pill px-4 d-flex align-items-center justify-content-center">Назад</a>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold text-uppercase text-muted mb-2" style="letter-spacing: 1px; font-size: 0.8rem;">
                            Про себе</h6>
                        <div class="p-3 rounded-4" style="background-color: #262626;">
                            <p class="mb-0" style="font-size: 1rem; color: #e0e0e0; line-height: 1.5;">
                                {{ $user->bio ?? 'Користувач ще не додав опис.' }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <h6 class="fw-bold text-uppercase text-muted mb-3" style="letter-spacing: 1px; font-size: 0.8rem;">
                            Інтереси</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @forelse($user->interests as $interest)
                                @if($currentUser->interests->contains('id', $interest->id))
                                    <span class="badge rounded-pill d-flex align-items-center px-3 py-2 shadow-sm"
                                        style="background-color: rgba(187, 134, 252, 0.15); border: 1px solid #bb86fc; color: #bb86fc; font-size: 0.85rem; font-weight: 600;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                                            class="bi bi-fire me-1" viewBox="0 0 16 16">
                                            <path
                                                d="M8 16c3.314 0 6-2 6-5.5 0-1.5-.5-4-2.5-6 .25 1.5-1.25 2-1.25 2C11 4 9 .5 6 0c.357 2 .5 4-2 6-1.25 1-2 2.729-2 4.5C2 14 4.686 16 8 16Zm0-1c-1.657 0-3-1-3-2.75 0-.75.25-2 1.25-3C6.125 10 7 10.5 7 10.5c-.375-1.25.5-3.25 2-3.5-.179 1-.25 2 1 3 .625.5 1 1.364 1 2.25C11 14 9.657 15 8 15Z" />
                                        </svg>
                                        {{ $interest->name }}
                                    </span>
                                @else
                                    <span class="badge rounded-pill px-3 py-2"
                                        style="background-color: #2d2d2d; border: 1px solid #444; color: #a0a0a0; font-size: 0.85rem; font-weight: normal;">
                                        {{ $interest->name }}
                                    </span>
                                @endif
                            @empty
                                <span class="text-muted small">Немає інтересів</span>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection