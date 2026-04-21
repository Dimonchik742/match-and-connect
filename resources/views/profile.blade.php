@extends('layouts.app')

@section('title', 'Профіль користувача')

@section('content')
    <div class="row justify-content-center mt-4">
        <div class="col-lg-10 col-xl-8">

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px; background: #1e1e1e;">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start">

                        <div class="flex-shrink-0 mb-3 mb-md-0">
                            @if($user->photo)
                                <img src="/storage/{{ $user->photo }}" class="rounded-circle object-fit-cover shadow"
                                    style="width: 160px; height: 160px; border: 3px solid #2d2d2d;" alt="Фото">
                            @else
                                <div class="bg-dark rounded-circle d-flex justify-content-center align-items-center text-muted shadow"
                                    style="width: 160px; height: 160px; border: 3px solid #2d2d2d; font-size: 3rem;">
                                    👤
                                </div>
                            @endif
                        </div>

                        <div class="flex-grow-1 ms-md-4 w-100">
                            <div
                                class="d-flex flex-column flex-sm-row justify-content-between align-items-center align-items-sm-start mb-2">
                                <div class="text-center text-sm-start">
                                    <h3 class="fw-bold mb-0 text-white">{{ $user->name }}, {{ $user->age ?? '?' }}</h3>
                                    <p class="text-muted small mb-0">Учасник з {{ $user->created_at->format('d.m.Y') }}</p>
                                </div>

                                <div class="mt-3 mt-sm-0">
                                    @if(Auth::id() == $user->id)
                                        <a href="/profile/edit"
                                            class="btn btn-sm btn-outline-primary rounded-pill px-4">Редагувати</a>
                                    @else
                                        <div class="d-flex gap-2">
                                            <a href="/chat/{{ $user->id }}"
                                                class="btn btn-sm btn-primary rounded-pill px-4 fw-bold">Написати</a>
                                            <button class="btn btn-sm btn-dark rounded-circle"
                                                style="width: 32px; height: 32px; padding: 0;">⋮</button>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="bg-dark p-3 rounded-3 mt-2" style="background-color: #262626 !important;">
                                <h6 class="text-uppercase fw-bold text-muted small mb-2" style="letter-spacing: 1px;">Про
                                    мене</h6>
                                <p class="mb-0 text-light-50 small">
                                    {{ $user->bio ?? 'Користувач ще не додав інформацію про себе.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 20px; background: #1e1e1e;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="text-uppercase fw-bold text-muted mb-0 small" style="letter-spacing: 1px;">Інтереси та
                            вподобання</h6>
                        <span
                            class="badge bg-dark border border-secondary text-muted fw-normal">{{ $user->interests->count() }}</span>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        @forelse($user->interests as $interest)
                            <span class="badge rounded-pill bg-dark border border-secondary px-3 py-2 fw-normal"
                                style="color: #bb86fc; font-size: 0.8rem;">
                                {{ $interest->name }}
                            </span>
                        @empty
                            <p class="text-muted small mb-0">Інтереси ще не вибрані.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection