# Match & Connect 🤝

## Короткий опис проєкту
**Match & Connect** — це сучасна вебплатформа для знайомств та соціалізації, яка допомагає користувачам знаходити однодумців на основі спільних інтересів та захоплень. Система включає функціонал створення профілю, гнучкого пошуку та фільтрації анкет за тегами, а також інтерактивний чат для обміну повідомленнями у режимі реального часу.

---

# Технологічний стек 🛠

- **Backend:** PHP, Laravel Framework, Eloquent ORM  
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap  
- **База даних:** MySQL / MariaDB  
- **Real-time комунікація:** WebSockets (Laravel Echo, Pusher / Reverb)  
- **Інструментарій:** Git, Composer, NPM, Vite  

---

# Системні вимоги ⚙️

Для успішного запуску проєкту необхідно:

- **PHP:** 8.2 або вище  
  (розширення: PDO, OpenSSL, mbstring, cURL)
- **MySQL:** 8.0+ або **MariaDB:** 10.4+
- **Node.js:** 18+
- **Composer**
- **NPM**
- Локальний сервер: XAMPP / Laragon / OpenServer

---

# Встановлення та запуск 

## 1. Клонування репозиторію

Відкрийте термінал у папці сервера (`htdocs` для XAMPP) та виконайте:

```bash
git clone https://github.com/Dimonchik742/match-and-connect.git
cd match-and-connect
```

---

## 2. Встановлення Backend-залежностей

```bash
composer install
```

---

## 3. Встановлення Frontend-залежностей

```bash
npm install
npm run build
```

---

## 4. Налаштування середовища

Створіть `.env` файл:

```bash
cp .env.example .env
```

Згенеруйте ключ застосунку:

```bash
php artisan key:generate
```

---

## 5. Налаштування бази даних

Відкрийте файл `.env` та змініть параметри:

```env
DB_DATABASE=match_connect
DB_USERNAME=root
DB_PASSWORD=
```

Для роботи WebSockets переконайтеся, що встановлено:

```env
BROADCAST_DRIVER=reverb
```

---

## 6. Ініціалізація бази даних

Виконайте міграції та сідери:

```bash
php artisan migrate --seed
```

---

## 7. Налаштування файлового сховища

Створіть символічне посилання для коректного відображення фото профілів:

```bash
php artisan storage:link
```

---

# Запуск застосунку 

Для повноцінної роботи потрібно відкрити **два термінали**.

## Термінал №1 — запуск Laravel сервера

```bash
php artisan serve
```

---

## Термінал №2 — запуск WebSocket сервера

```bash
php artisan reverb:start
```

---

# Готово 

Після запуску застосунок буде доступний за адресою:

```txt
http://localhost:8000
```

