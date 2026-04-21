document.addEventListener("DOMContentLoaded", function () {
    const receiverId = window.chatConfig.receiverId;
    let currentMessageCount = window.chatConfig.messageCount;
    const chatBox = document.getElementById("chat-box");

    // Прокрутка вниз при завантаженні
    chatBox.scrollTop = chatBox.scrollHeight;

    // --- ЛОГІКА КОНТЕКСТНОГО МЕНЮ ---
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

    // --- AJAX ОНОВЛЕННЯ ЧАТУ (З НОВИМ ДИЗАЙНОМ) ---
    setInterval(function () {
        fetch(`/api/chat/${receiverId}/messages`)
            .then((response) => response.json())
            .then((data) => {
                if (data.messages.length > currentMessageCount) {
                    currentMessageCount = data.messages.length;
                    chatBox.innerHTML = "";

                    data.messages.forEach((msg) => {
                        let isMe = msg.sender_id === data.current_user_id;
                        let date = new Date(msg.created_at);
                        let time =
                            date.getHours().toString().padStart(2, "0") +
                            ":" +
                            date.getMinutes().toString().padStart(2, "0");

                        let html = "";
                        if (isMe) {
                            html = `
                                <div class="d-flex justify-content-end mb-3 pe-1 pt-1">
                                    <div class="my-message-bubble bg-primary text-dark p-3 shadow-sm"
                                        style="max-width: 80%; cursor: pointer;" data-message-id="${msg.id}">
                                        <div style="font-weight: 500; font-size: 0.95rem;">
                                            ${msg.content}
                                        </div>
                                        <div class="text-end mt-1" style="font-size: 0.65rem; opacity: 0.7;">
                                            ${time}
                                        </div>
                                    </div>
                                </div>`;
                        } else {
                            html = `
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
                        }
                        chatBox.insertAdjacentHTML("beforeend", html);
                    });

                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            })
            .catch((error) => console.error("Помилка AJAX:", error));
    }, 3000);
});
