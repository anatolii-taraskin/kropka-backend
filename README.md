# Kropka Backend

Бэкенд сервиса студии «Кропка». Приложение написано на Laravel 12 и
предоставляет публичное API для описания студии (общая информация,
оборудование, прайс-листы, преподаватели и правила). Спецификация REST API
лежит в файле [`openapi.yaml`](openapi.yaml).

## Требования

- Docker и Docker Compose (используется [Laravel Sail](https://laravel.com/docs/sail)).
- Composer 2.6+ и PHP 8.2+ (нужны только для установки зависимостей на машине разработчика).
- Node.js 20+ и npm 10+ для сборки фронтенд-ассетов.

## Первый запуск

```bash
# 1. Клонируем репозиторий
git clone git@github.com:your-org/kropka-backend.git
cd kropka-backend

# 2. Ставим PHP-зависимости
composer install

# 3. Ставим фронтенд-зависимости
npm install

# 4. Создаём .env и настраиваем переменные при необходимости
cp .env.example .env
```

Далее все команды выполняем через Sail (Docker-контейнеры). Если вы
вносили изменения в Dockerfile или обновляли системные зависимости,
соберите контейнеры заново:

```bash
./vendor/bin/sail build
```

### Подъём инфраструктуры

```bash
# Запускаем контейнеры в фоне
./vendor/bin/sail up -d

# Генерируем APP_KEY (нужно один раз после создания .env)
./vendor/bin/sail artisan key:generate

# Применяем миграции и заполняем тестовыми данными
./vendor/bin/sail artisan migrate --seed
```

После этого API будет доступно на `http://localhost` (порт задаётся через
`APP_PORT` в `.env`, по умолчанию 80). Почтовый сэндбокс Mailpit — на
`http://localhost:8025`, база данных PostgreSQL доступна на 5432 порту.

### Сборка ассетов

Для production-сборки используйте:

```bash
# внутри контейнера
./vendor/bin/sail npm run build

# или на хосте, если установлен Node.js
npm run build
```

Для разработки удобно запускать Vite в watch-режиме:

```bash
./vendor/bin/sail npm run dev
```

### Полезные команды

```bash
# Остановить контейнеры
./vendor/bin/sail down

# Пересоздать БД с начальными данными
./vendor/bin/sail artisan migrate:fresh --seed

# Запустить тесты
./vendor/bin/sail artisan test
```

## Структура проекта

- `app/` — HTTP-контроллеры, сервисы и бизнес-логика.
  - `App\\Http\\Services` — сервисы, обслуживающие HTTP-слой (агрегация данных для контроллеров, подготовка ответов API).
  - `App\\Services\\Media` — сервисы для работы с медиа-хранилищами и файлами.
- `database/migrations/` — миграции БД.
- `database/seeders/` — сидеры с начальными данными студии.
- `resources/` — Blade-шаблоны и фронтенд-ассеты (Vite).
- `routes/` — маршруты API.
- `openapi.yaml` — спецификация публичного API.

## Обновление зависимостей

```bash
./vendor/bin/sail composer update
./vendor/bin/sail npm update
./vendor/bin/sail build       # при изменении системных пакетов
./vendor/bin/sail up -d --build
```

После обновления не забудьте прогнать тесты и убедиться, что миграции
проходят успешно.
