@extends('layouts.app')

@section('title', 'Панель Адміністратора')

@section('content')
    <style>
        /* Стилізація таблиці під Dark Mode */
        .admin-table {
            color: #e0e0e0;
            margin-bottom: 0;
        }

        .admin-table thead th {
            background-color: #2d2d2d !important;
            color: #a0a0a0;
            border-bottom: 2px solid #444 !important;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
            font-weight: 600;
            padding: 15px;
        }

        .admin-table tbody td {
            background-color: #1e1e1e !important;
            border-bottom: 1px solid #2a2a2a !important;
            color: #e0e0e0;
            vertical-align: middle;
            padding: 15px;
        }

        .admin-table.table-hover tbody tr:hover td {
            background-color: #252525 !important;
        }
    </style>

    <div class="row justify-content-center mt-4 mb-5">
        <div class="col-12 col-lg-10">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <h3 class="fw-bold text-white mb-0">Панель Адміністратора</h3>
                <div class="badge border border-secondary px-3 py-2 rounded-pill"
                    style="background-color: #2d2d2d; color: #a0a0a0; font-size: 0.9rem;">
                    Всього користувачів: <span class="text-white fw-bold ms-1">{{ $users->count() }}</span>
                </div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger shadow-sm mb-4"
                    style="background-color: rgba(207, 102, 121, 0.1); border: 1px solid #cf6679; color: #cf6679; border-radius: 12px;">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success shadow-sm mb-4"
                    style="background-color: rgba(3, 218, 198, 0.1); border: 1px solid #03dac6; color: #03dac6; border-radius: 12px;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card border-0 shadow-lg"
                style="border-radius: 20px; background: #1e1e1e; border: 1px solid #2a2a2a !important; overflow: hidden;">
                <div class="table-responsive">
                    <table class="table admin-table table-hover border-0">
                        <thead>
                            <tr>
                                <th class="border-0">ID</th>
                                <th class="border-0">Фото</th>
                                <th class="border-0">Ім'я</th>
                                <th class="border-0">Email</th>
                                <th class="border-0">Дата реєстрації</th>
                                <th class="border-0 text-end">Дія</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td class="text-muted fw-bold" style="font-size: 0.9rem;">#{{ $user->id }}</td>
                                    <td>
                                        @if($user->photo)
                                            <img src="{{ asset('storage/' . $user->photo) }}"
                                                class="rounded-circle object-fit-cover"
                                                style="width: 40px; height: 40px; border: 2px solid #2d2d2d;">
                                        @else
                                            <div class="rounded-circle d-flex justify-content-center align-items-center text-muted"
                                                style="width: 40px; height: 40px; background-color: #2d2d2d; border: 1px solid #444;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                    class="bi bi-person" viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold text-white">{{ $user->name }}</div>
                                        @if($user->is_admin)
                                            <span class="badge bg-primary text-dark mt-1"
                                                style="font-size: 0.7rem; font-weight: 700;">ADMIN</span>
                                        @endif
                                    </td>
                                    <td style="color: #bbb;">{{ $user->email }}</td>
                                    <td class="text-muted small">{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                    <td class="text-end">
                                        @if($user->id !== Auth::id())
                                            <form action="/admin/user/{{ $user->id }}" method="POST"
                                                onsubmit="return confirm('Ви впевнені, що хочете назавжди видалити цього користувача?');"
                                                class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                    style="font-size: 0.8rem; font-weight: 600;">Видалити</button>
                                            </form>
                                        @else
                                            <span class="text-muted small">Це ви</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection