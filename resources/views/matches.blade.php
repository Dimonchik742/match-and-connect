@extends('layouts.app')

@section('title', 'Мої Матчі')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        <h3 class="fw-bold mb-0">💖 Ваші Матчі</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-3">
        @forelse($matches as $user)
            <div class="col-lg-6">
                <div class="glass-card h-100">
                    <div class="card-body p-3 d-flex align-items-center">

                        <div class="flex-shrink-0">
                            @if($user->photo)
                                <img src="/storage/{{ $user->photo }}" class="rounded-circle object-fit-cover shadow-sm"
                                    style="width: 80px; height: 80px; border: 2px solid #bb86fc;" alt="Фото">
                            @else
                                <div class="bg-dark rounded-circle d-flex justify-content-center align-items-center text-muted shadow-sm"
                                    style="width: 80px; height: 80px; border: 2px solid #bb86fc; font-size: 24px;">👤</div>
                            @endif
                        </div>

                        <div class="flex-grow-1 ms-3" style="min-width: 0;">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="fw-bold mb-0 text-truncate pe-2" style="font-size: 1.1rem; color: #bb86fc !important;">
                                    {{ $user->name }}, {{ $user->age ?? '?' }}
                                </h6>
                                <span class="badge bg-success rounded-pill px-2 py-1 flex-shrink-0">
                                    Match!
                                </span>
                            </div>

                            <p class="text-light-50 small mb-2 text-truncate" style="opacity: 0.8">
                                {{ $user->bio ?? 'Користувач ще не додав опис...' }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <a href="/user/{{ $user->id }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" style="font-size: 0.8rem;">Профіль</a>
                                <a href="/chat/{{ $user->id }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold text-dark" style="font-size: 0.8rem;">
                                    💬 Написати
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        @empty
            <div class="col-12">
                <div class="glass-card border text-center p-5 rounded-4 shadow-sm">
                    <h5 class="fw-bold mb-2 text-white">У вас ще немає матчів 😢</h5>
                    <p class="text-muted mb-0 small">Продовжуйте ставити лайки у розділі Рекомендацій!</p>
                </div>
            </div>
        @endforelse
    </div>
@endsection
