@extends('layouts.app')

@section('title', 'Рекомендації')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        <h3 class="fw-bold mb-0 text-warning">✨ Рекомендації для Вас</h3>
    </div>

    <!-- Фільтр -->
    <div class="glass-card mb-4">
        <div class="card-body p-4">
            <form action="/recommendations" method="GET" class="d-flex flex-column flex-md-row gap-3">
                <div class="flex-grow-1">
                    <select name="interests[]" id="interest-select" class="form-select border-0 shadow-sm px-4"
                        style="height: 50px; background-color: #2d2d2d; color: white;" multiple>
                        @foreach($allInterests as $interest)
                            <option value="{{ $interest->id }}" {{ (is_array(request('interests')) && in_array($interest->id, request('interests'))) ? 'selected' : '' }}>
                                {{ $interest->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary px-5 rounded-pill py-2 fw-bold" style="height: 50px;">Пошук</button>
            </form>
        </div>
    </div>

    <!-- Підключаємо Choices.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <style>
        .choices__inner {
            background-color: #2d2d2d !important;
            border-radius: 25px !important;
            border: 1px solid #444 !important;
            min-height: 50px !important;
            padding-top: 10px !important;
        }
        .choices__list--multiple .choices__item {
            background-color: #bb86fc;
            border: 1px solid #764ba2;
            border-radius: 12px;
            color: #000;
        }
        .choices[data-type*="select-multiple"] .choices__button {
            border-left: 1px solid rgba(0,0,0,0.2);
            filter: invert(1);
        }
        .choices__list--dropdown {
            background-color: #2d2d2d;
            border: 1px solid #444;
            color: #e0e0e0;
        }
        .choices__list--dropdown .choices__item--selectable.is-highlighted {
            background-color: #bb86fc;
            color: #000;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const element = document.getElementById('interest-select');
            const choices = new Choices(element, {
                removeItemButton: true,
                placeholderValue: 'Виберіть інтереси...',
                searchPlaceholderValue: 'Пошук...',
                noResultsText: 'Нічого не знайдено',
                itemSelectText: 'Натисніть щоб вибрати'
            });
        });
    </script>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 12px;">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row g-3">
        @forelse($recommendedUsers as $user)
            <div class="col-lg-6">
                <div class="glass-card h-100">
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
                                <span class="badge bg-danger rounded-pill px-2 py-1 flex-shrink-0">
                                    {{ $user->common_interests_count }} спільних тегів
                                </span>
                            </div>

                            <p class="text-muted small mb-2 text-truncate">
                                {{ $user->bio ?? 'Користувач ще не додав опис...' }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <a href="/user/{{ $user->id }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" style="font-size: 0.8rem;">Профіль</a>
                                
                                <form action="/like/{{ $user->id }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3" style="font-size: 0.8rem;">
                                        🔥 Лайк
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        @empty
            <div class="col-12">
                <div class="alert alert-light border text-center p-5 rounded-4 shadow-sm">
                    <h5 class="fw-bold mb-2">Немає рекомендацій 🤷‍♂️</h5>
                    <p class="text-muted mb-0 small">Спробуйте додати більше інтересів до свого профілю.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $recommendedUsers->links() }}
    </div>
@endsection
