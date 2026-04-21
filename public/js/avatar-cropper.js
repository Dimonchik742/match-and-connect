document.addEventListener("DOMContentLoaded", function () {
    const photoInput = document.getElementById("photo-input");
    const imageToCrop = document.getElementById("image-to-crop");
    const previewAvatar = document.getElementById("preview-avatar");
    const cropModalElement = document.getElementById("cropModal");
    const cropBtn = document.getElementById("crop-btn");

    // Перевірка: якщо на сторінці немає інпута для фото, скрипт просто зупиняється
    if (
        !photoInput ||
        !imageToCrop ||
        !previewAvatar ||
        !cropModalElement ||
        !cropBtn
    ) {
        return;
    }

    // --- ДОДАЄМО CSS ДЛЯ ВІЗУАЛЬНОГО ЗАКРУГЛЕННЯ ОБЛАСТІ ОБРІЗАННЯ ---
    // Ми створюємо стилі програмно, щоб не змінювати Blade-файли
    const style = document.createElement("style");
    style.innerHTML = `
        /* Закруглюємо саму рамку виділення */
        .cropper-view-box,
        .cropper-face {
            border-radius: 50%;
            outline: none !important; /* Прибираємо стандартний квадратний аутлайн */
        }
        
        /* Додаємо білу круглу рамку для чіткості */
        .cropper-view-box {
            outline: 2px solid #fff !important;
            box-shadow: 0 0 0 5000px rgba(0, 0, 0, 0.6); /* Затемнення навколо кола */
        }

        /* Прибираємо квадратний фон за замовчуванням */
        .cropper-bg {
            background-image: none !important;
            background-color: #000 !important;
        }
    `;
    document.head.appendChild(style);
    // -----------------------------------------------------------------

    const cropModal = new bootstrap.Modal(cropModalElement);
    let cropper;

    // 1. Коли користувач вибрав файл
    photoInput.addEventListener("change", function (e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const file = files[0];
            if (!file.type.startsWith("image/")) return;

            const reader = new FileReader();
            reader.onload = function (event) {
                imageToCrop.src = event.target.result;
                cropModal.show();
            };
            reader.readAsDataURL(file);
        }
    });

    // 2. Ініціалізація кропера
    cropModalElement.addEventListener("shown.bs.modal", function () {
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 1, // Квадрат 1:1, CSS зробить його візуально колом
            viewMode: 1,
            dragMode: "move",
            autoCropArea: 0.8,
            background: false,
            guides: false, // Прибираємо сітку для чистішого вигляду
            center: false,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
        });
    });

    // 3. Знищуємо кропер при закритті
    cropModalElement.addEventListener("hidden.bs.modal", function () {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        if (!previewAvatar.dataset.updated) {
            photoInput.value = "";
        }
    });

    // 4. Збереження результату
    cropBtn.addEventListener("click", function () {
        if (!cropper) return;

        cropper
            .getCroppedCanvas({
                width: 400,
                height: 400,
            })
            .toBlob(
                function (blob) {
                    let file = new File([blob], "avatar.jpg", {
                        type: "image/jpeg",
                        lastModified: new Date().getTime(),
                    });
                    let container = new DataTransfer();
                    container.items.add(file);
                    photoInput.files = container.files;

                    let newAvatarUrl = URL.createObjectURL(blob);
                    previewAvatar.src = newAvatarUrl;
                    previewAvatar.dataset.updated = "true";

                    cropModal.hide();
                },
                "image/jpeg",
                0.9,
            );
    });
});
