# Copilot / AI agent instructions for this repo

Purpose: Help an AI coding agent become productive quickly in this Laravel-based Telegram message management service.

- **Big picture:** This is a Laravel application that receives Telegram Web App submissions, validates and stores messages, schedules delivery to channels, and handles moderation. Main flows: incoming webhook -> `app/Jobs/ProcessMessage.php` (job) -> `app/Actions/*` (business logic) -> `app/Services` / `app/Facades` (integrations) -> `app/Models` (persisted entities).

- **Key files & directories to inspect first:**
  - `routes/web.php`, `routes/api.php` — entry points and webhooks
  - `app/Jobs/ProcessMessage.php` — message processing and queueing
  - `app/Actions/` — domain actions (business logic lives here)
  - `app/DTO/MessageDTO.php` — canonical message DTO used across actions
  - `app/Services/` and `app/Facades/` — external integrations (Telegram, VK, image compressor)
  - `app/Listeners/SetUserBot.php` — example of event/listener usage
  - `app/Models/` — domain models (Message, Place, Channel, Author, TelegramBot)
  - `config/queue.php`, `config/services.php` — queue and external service settings

- **Dataflow example (concrete):**
  - User opens Telegram Web App -> frontend submits form to the app webhook (see `routes/*`) -> `ProcessMessage` job is dispatched -> job constructs `MessageDTO` -> `app/Actions/*` render templates, persist `Message` and create `MessageSchedule` entries for later sending -> queue worker picks up sender jobs and uses facades/services to deliver messages.

- **Dev / run commands:**
  - Install deps: `composer install` and `npm install` (frontend assets in `resources/js`/`resources/sass`)
  - Copy env: `.env.example` -> `.env` (composer post-create may already do this)
  - Generate key: `php artisan key:generate`
  - Migrate DB: `php artisan migrate --seed`
  - Run queue worker: `php artisan queue:work --tries=3` (set `QUEUE_CONNECTION` in `.env`)
  - Run tests: `./vendor/bin/phpunit` (or `php artisan test`)
  - Docker: `docker-compose up -d` (project includes `docker-compose.yml`) — adapt env for containerized DB/queue.

- **Patterns & conventions used in this project:**
  - Business logic is organized as `app/Actions/*` rather than fat controllers — prefer adding or modifying actions there.
  - Use `MessageDTO` to move structured data between layers; follow its shape when creating/updating message flows.
  - External APIs are wrapped behind facades in `app/Facades/` (e.g., `ImageCompressorFacade.php`, `TechBotFacade.php`) — change implementation there and keep callers stable.
  - Queues are central: scheduled sending is implemented by creating schedule entries and dispatching jobs; do not perform long-running sends in HTTP request handlers.

- **Testing & debugging tips:**
  - Unit/feature tests under `tests/Unit` and `tests/Feature` rely on Laravel testing utilities (`Tests/TestCase.php`). Run focused tests by path.
  - Use `barryvdh/laravel-debugbar` and `spatie/laravel-ignition` in dev for better stack traces.
  - To reproduce sending behavior locally, seed data (`php artisan db:seed`) and run `php artisan queue:work` while triggering the webhook locally (use ngrok or `artisan serve`).

- **External integrations to be aware of:**
  - Telegram (primary): see uses of `telegram-bot/api` and code in `app/Services`/`app/Facades`.
  - VK and Odnoklassniki: third-party clients are present in `composer.json` and `app/Actions/VK` / `app/Actions/OK`.

- **When changing behavior, check these places together:**
  - If modifying message creation: update `app/Actions/*`, `app/DTO/MessageDTO.php`, `app/Models/Message.php`, and tests in `tests/Feature/messages*`.
  - If changing sending pipeline: check `MessageSchedule`, `ProcessMessage` job, queue workers, and code under `app/Services`.

- **Do not assume:**
  - There is no single monolithic sender; multiple channel-specific senders exist. Search for `send*` and `MessageSchedule` when adding channels.

If any of these areas are unclear or you want examples expanded (e.g., a walkthrough editing an existing action or adding a new channel), tell me which area and I will add a short how-to with file-level edits.
