@extends('layouts.app')

@section('title', 'Пошук людей')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        <h3 class="fw-bold mb-0">Знайомства</h3>

        <div>
            @if($selectedInterestId)
                <a href="/search" class="btn btn-sm btn-outline-danger ms-2 rounded-pill px-3">✖ Скинути</a>
            @endif
            <button class="btn btn-sm btn-dark rounded-pill px-3" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#filterOffcanvas">
                Фільтри
            </button>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="filterOffcanvas">
        <div class="offcanvas-header bg-light border-bottom">
            <h5 class="offcanvas-title fw-bold">Фільтрація пошуку</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="/search" method="GET">
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small">ЗНАЙТИ ЗА ІНТЕРЕСОМ:</label>
                    <select name="interest" class="form-select">
                        <option value="">Всі спільні (Smart Match)</option>
                        @foreach($allInterests as $interest)
                            <option value="{{ $interest->id }}" {{ $selectedInterestId == $interest->id ? 'selected' : '' }}>
                                {{ $interest->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-dark w-100 rounded-pill py-2 fw-bold">Застосувати</button>
            </form>
        </div>
    </div>

    <div class="row g-3">
        @forelse($users as $user)
            <div class="col-lg-6">
                <div class="card h-100 shadow-sm border-0" style="background: #ffffff; border-radius: 16px;">
                    <div class="card-body p-3 d-flex align-items-center">

                        <div class="flex-shrink-0">
                            @if($user->photo)
                                <img src="/storage/{{ $user->photo }}" class="rounded-circle object-fit-cover shadow-sm"
                                    style="width: 80px; height: 80px;" alt="Фото">
                            @else
                                <div class="bg-light rounded-circle d-flex justify-content-center align-items-center text-muted shadow-sm"
                                    style="width: 80px; height: 80px; font-size: 24px;">👤</div>
                            @endif
                        </div>

                        <div class="flex-grow-1 ms-3" style="min-width: 0;">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="fw-bold mb-0 text-truncate pe-2" style="font-size: 1.1rem;">
                                    {{ $user->name }}, {{ $user->age ?? '?' }}
                                </h6>
                                <a href="/user/{{ $user->id }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3 flex-shrink-0"
                                    style="font-size: 0.8rem;">Профіль</a>
                            </div>

                            <p class="text-muted small mb-2 text-truncate">
                                {{ $user->bio ?? 'Користувач ще не додав опис...' }}
                            </p>

                            <div class="d-flex flex-wrap gap-1">
                                @php $displayCount = 0; @endphp

                                @foreach($user->interests as $interest)
                                    @if($selectedInterestId == $interest->id || (!$selectedInterestId && $currentUser->interests->contains($interest->id)))
                                        @if($displayCount < 3)
                                            <a href="/search?interest={{ $interest->id }}"
                                                class="badge bg-light text-dark border text-decoration-none fw-normal"
                                                style="font-size: 0.7rem; padding: 5px 8px;">
                                                {{ $interest->name }}
                                            </a>
                                            @php $displayCount++; @endphp
                                        @endif
                                    @endif
                                @endforeach

                                @if(count($user->interests) > 3)
                                    <span class="badge bg-light text-muted border fw-normal"
                                        style="font-size: 0.7rem; padding: 5px 8px;">
                                        +{{ count($user->interests) - 3 }}
                                    </span>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        @empty
            <div class="col-12">
                <div class="alert alert-light border text-center p-5 rounded-4 shadow-sm">
                    <h5 class="fw-bold mb-2">Нікого не знайдено 🕵️‍♂️</h5>
                    <p class="text-muted mb-0 small">Спробуйте змінити критерії фільтрації.</p>
                </div>
            </div>
        @endforelse
    </div>
@endsection