document.addEventListener("DOMContentLoaded", function () {
    const receiverId = window.chatConfig.receiverId;
    // Нам знадобиться ID поточного користувача, щоб слухати правильний канал
    const currentUserId = window.chatConfig.currentUserId;
    let currentMessageCount = window.chatConfig.messageCount;
    const chatBox = document.getElementById("chat-box");

    // Прокрутка вниз при завантаженні
    chatBox.scrollTop = chatBox.scrollHeight;

    // --- ЛОГІКА КОНТЕКСТНОГО МЕНЮ (Залишаємо без змін) ---
    const contextMenu = document.getElementById("customContextMenu");
    const deleteForm = document.getElementById("deleteMessageForm");
    let pressTimer;
    let touchStartX = 0;
    let touchStartY = 0;

    document.addEventListener("click", function (e) {
        if (e.target.closest("#customContextMenu") === null) {
            contextMenu.style.display = "none";
        }
    });

    chatBox.addEventListener("contextmenu", function (e) {
        const bubble = e.target.closest(".my-message-bubble");
        if (bubble) {
            e.preventDefault();
            showMenu(e.clientX, e.clientY, bubble.dataset.messageId);
        }
    });

    chatBox.addEventListener("touchstart", function (e) {
        const bubble = e.target.closest(".my-message-bubble");
        if (bubble) {
            touchStartX = e.touches[0].clientX;
            touchStartY = e.touches[0].clientY;

            pressTimer = setTimeout(() => {
                showMenu(
                    e.touches[0].clientX,
                    e.touches[0].clientY,
                    bubble.dataset.messageId,
                );
                if (navigator.vibrate) navigator.vibrate(50);
            }, 500);
        }
    });

    chatBox.addEventListener("touchmove", function (e) {
        let moveX = Math.abs(e.touches[0].clientX - touchStartX);
        let moveY = Math.abs(e.touches[0].clientY - touchStartY);
        if (moveX > 10 || moveY > 10) {
            clearTimeout(pressTimer);
        }
    });

    chatBox.addEventListener("touchend", function () {
        clearTimeout(pressTimer);
    });
    chatBox.addEventListener("touchcancel", function () {
        clearTimeout(pressTimer);
    });

    function showMenu(x, y, messageId) {
        deleteForm.action = "/message/" + messageId;
        contextMenu.style.left = x + "px";
        contextMenu.style.top = y + "px";
        contextMenu.style.display = "block";
    }

    // --- WEBSOCKET ОНОВЛЕННЯ ЧАТУ (ЗАМІСТЬ AJAX) ---
    // Перевіряємо, чи доступний Echo (він має завантажитись з app.js)
    if (window.Echo) {
        console.log("Слухаємо канал: chat." + currentUserId);

        window.Echo.private(`chat.${currentUserId}`).listen(
            "MessageSent",
            (event) => {
                const msg = event.message;

                // ВАЖЛИВО: Перевіряємо, чи повідомлення прийшло від того,
                // з ким ми ЗАРАЗ відкрили чат. Щоб повідомлення від Колі
                // не з'явилося, поки ми переписуємось з Васею.
                if (msg.sender_id == receiverId) {
                    currentMessageCount++;

                    let date = new Date(msg.created_at);
                    let time =
                        date.getHours().toString().padStart(2, "0") +
                        ":" +
                        date.getMinutes().toString().padStart(2, "0");

                    // Малюємо бульбашку ТІЛЬКИ для вхідного повідомлення
                    // (Свої повідомлення малюються самі при перезавантаженні сторінки після відправки форми)
                    let html = `
                        <div class="d-flex justify-content-start mb-3 pe-1 pt-1">
                            <div class="their-message-bubble p-3 shadow-sm" style="max-width: 80%;">
                                <div style="font-weight: 400; font-size: 0.95rem;">
                                    ${msg.content}
                                </div>
                                <div class="mt-1 text-muted" style="font-size: 0.65rem;">
                                    ${time}
                                </div>
                            </div>
                        </div>`;

                    chatBox.insertAdjacentHTML("beforeend", html);
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            },
        );
    } else {
        console.error(
            "Laravel Echo не знайдено. Переконайся, що app.js підключено через Vite.",
        );
    }

    // --- ВІДПРАВКА ПОВІДОМЛЕННЯ БЕЗ ПЕРЕЗАВАНТАЖЕННЯ ---
    const chatForm = document.getElementById("chat-form");
    const messageInput = document.getElementById("message-input");

    if (chatForm) {
        chatForm.addEventListener("submit", function (e) {
            e.preventDefault(); // 🛑 МАГІЯ: Зупиняємо стандартне перезавантаження сторінки!

            let content = messageInput.value.trim();
            if (content === "") return;

            // 1. Одразу малюємо СВОЮ бульбашку на екрані (щоб користувачу здавалося, що все працює миттєво)
            let now = new Date();
            let time =
                now.getHours().toString().padStart(2, "0") +
                ":" +
                now.getMinutes().toString().padStart(2, "0");

            let myHtml = `
                <div class="d-flex justify-content-end mb-3 pe-1 pt-1">
                    <div class="my-message-bubble bg-primary text-dark p-3 shadow-sm" style="max-width: 80%;">
                        <div style="font-weight: 500; font-size: 0.95rem;">
                            ${content}
                        </div>
                        <div class="text-end mt-1" style="font-size: 0.65rem; opacity: 0.7;">
                            ${time}
                        </div>
                    </div>
                </div>`;

            chatBox.insertAdjacentHTML("beforeend", myHtml);
            chatBox.scrollTop = chatBox.scrollHeight;

            // 3. Фоново відправляємо дані на наш бекенд
            let formData = new FormData(chatForm);

            // 2. Очищаємо поле вводу
            messageInput.value = "";

            fetch(chatForm.action, {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest", // Кажемо Laravel, що це фоновий AJAX-запит
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    console.log("Повідомлення успішно відправлено на сервер!");
                })
                .catch((error) => console.error("Помилка відправки:", error));
        });
    }
});
