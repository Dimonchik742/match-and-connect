@extends('layouts.app')

@section('title', 'Чат з ' . $receiver->name)

@section('content')
    <style>
        /* Стилі для захисту від виділення на мобільних пристроях */
        .my-message-bubble {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-touch-callout: none;

            /* Дизайн бульбашки (Мої повідомлення) */
            border-radius: 18px 18px 4px 18px !important;
        }

        /* Дизайн бульбашки (Повідомлення співрозмовника) */
        .their-message-bubble {
            background-color: #2d2d2d !important;
            border: none !important;
            color: #e0e0e0 !important;
            border-radius: 18px 18px 18px 4px !important;
        }

        /* Стилізація скролбару (опціонально, але красиво) */
        #chat-box::-webkit-scrollbar {
            width: 6px;
        }

        #chat-box::-webkit-scrollbar-track {
            background: transparent;
        }

        #chat-box::-webkit-scrollbar-thumb {
            background-color: #444;
            border-radius: 10px;
        }
    </style>

    <div class="row justify-content-center mt-4 mb-5">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg position-relative" style="border-radius: 20px; overflow: hidden;">

                <div class="card-header border-bottom-0 d-flex align-items-center py-3 px-4"
                    style="background-color: #1e1e1e;">
                    <div class="position-relative me-3">
                        @if($receiver->photo)
                            <img src="/storage/{{ $receiver->photo }}" class="rounded-circle object-fit-cover shadow-sm"
                                style="width: 45px; height: 45px; border: 2px solid #2d2d2d;" alt="Фото">
                        @else
                            <div class="bg-dark rounded-circle d-flex justify-content-center align-items-center text-muted shadow-sm"
                                style="width: 45px; height: 45px; border: 2px solid #2d2d2d; font-size: 18px;">
                                👤
                            </div>
                        @endif
                    </div>

                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold text-white">{{ $receiver->name }}</h6>
                        <a href="/user/{{ $receiver->id }}" class="text-decoration-none small"
                            style="color: #bb86fc; font-size: 0.8rem;">Профіль користувача</a>
                    </div>

                    <a href="/search"
                        class="btn btn-sm btn-dark rounded-circle d-flex justify-content-center align-items-center"
                        style="width: 35px; height: 35px; border: none;">
                        ✕
                    </a>
                </div>

                <div class="card-body p-4" style="height: 60vh; overflow-y: auto; background-color: #121212;" id="chat-box">

                    @forelse($messages as $message)
                        @if($message->sender_id == Auth::id())
                            <div class="d-flex justify-content-end mb-3 pe-1 pt-1">
                                <div class="my-message-bubble bg-primary text-dark p-3 shadow-sm"
                                    style="max-width: 80%; cursor: pointer;" data-message-id="{{ $message->id }}">

                                    <div style="font-weight: 500; font-size: 0.95rem;">
                                        {{ $message->content }}
                                    </div>

                                    <div class="text-end mt-1" style="font-size: 0.65rem; opacity: 0.7;">
                                        {{ $message->created_at->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="d-flex justify-content-start mb-3 pe-1 pt-1">
                                <div class="their-message-bubble p-3 shadow-sm" style="max-width: 80%;">

                                    <div style="font-weight: 400; font-size: 0.95rem;">
                                        {{ $message->content }}
                                    </div>

                                    <div class="mt-1 text-muted" style="font-size: 0.65rem;">
                                        {{ $message->created_at->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="d-flex h-100 justify-content-center align-items-center text-center">
                            <div class="text-muted p-4 rounded-4" style="background-color: rgba(255,255,255,0.02);">
                                <div class="mb-2" style="font-size: 2rem;">👋</div>
                                <h6 class="fw-bold mb-1">Почніть спілкування</h6>
                                <p class="small mb-0 opacity-75">Напишіть перше повідомлення</p>
                            </div>
                        </div>
                    @endforelse

                </div>

                <div class="card-footer border-top-0 p-3" style="background-color: #1e1e1e;">
                    <form action="/chat/{{ $receiver->id }}" method="POST" class="d-flex align-items-center gap-2"
                        id="chat-form">
                        @csrf
                        <input type="text" id="message-input" name="content"
                            class="form-control form-control-lg border-0 shadow-none text-white rounded-pill px-4"
                            style="background-color: #2d2d2d; font-size: 0.95rem;" placeholder="Напишіть повідомлення..."
                            required autocomplete="off" autofocus>

                        <button type="submit"
                            class="btn btn-primary rounded-circle d-flex justify-content-center align-items-center flex-shrink-0"
                            style="width: 48px; height: 48px; border: none; padding-left: 4px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                class="bi bi-send-fill" viewBox="0 0 16 16">
                                <path
                                    d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083l6-15Zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471-.47 1.178Z" />
                            </svg>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div id="customContextMenu" class="dropdown-menu shadow-lg position-fixed"
        style="display: none; z-index: 1050; border-radius: 12px; overflow: hidden; background-color: #2d2d2d; border: 1px solid #444;">
        <form id="deleteMessageForm" action="" method="POST" class="m-0 p-0">
            @csrf
            @method('DELETE')
            <button type="submit" class="dropdown-item text-danger py-2 px-4 d-flex align-items-center fw-bold"
                style="font-size: 0.9rem; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#3a3a3a'"
                onmouseout="this.style.backgroundColor='transparent'"
                onclick="return confirm('Видалити це повідомлення?');">
                Видалити повідомлення
            </button>
        </form>
    </div>

    <script>
        window.chatConfig = {
            receiverId: {{ $receiver->id }},
            messageCount: {{ $messages->count() }}
        };
    </script>

    <script src="{{ asset('js/chat.js') }}"></script>

@endsection