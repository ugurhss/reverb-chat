# Reverb Mini Chat (Laravel 12 + Reverb)

Bu repo, **Laravel Reverb** ile çalışan çok basit bir “anlık mesajlaşma” (real‑time chat) örneğidir.

Kısaca mantık:
- Tarayıcı **Reverb WebSocket**’ine bağlanır ve `chat` kanalını dinler.
- Sen “Gönder”e basınca tarayıcı `/messages` endpoint’ine **HTTP POST** atar.
- Backend `MessageSent` event’ini **broadcast** eder.
- Diğer açık sekmeler/browsers bu event’i alır ve ekrana basar.

## Gereksinimler

- PHP (bu projede `php -v` ile 8.3 çalışıyor)
- Composer
- Node.js + npm
- (Opsiyonel) MySQL: Projede queue `database` olarak ayarlı. Chat’in çalışması için şart değil, ama bazı şeylerde lazım olabilir.

## Kurulum (ilk kez)

1) Paketleri yükle
```bash
composer install
npm install
```

2) `.env` ayarla
```bash
cp .env.example .env
php artisan key:generate
```

3) `.env` içinde Reverb ayarları

Bu projede örnek olarak şunlar var (zaten `.env` içinde bulunuyor):
- `BROADCAST_CONNECTION=reverb`
- `REVERB_HOST=localhost`
- `REVERB_PORT=8080`
- `REVERB_SCHEME=http`


## Çalıştırma (3 terminal)

Terminal 1 (Laravel HTTP server):
```bash
php artisan serve
```

Terminal 2 (Reverb WebSocket server):
```bash
php artisan reverb:start --debug
```

Terminal 3 (Vite - frontend):
```bash
npm run dev
```

Sonra tarayıcıdan aç:
- `http://localhost:8000`

Test için 2 pencere aç:
- Aynı sayfayı 2 farklı sekmede aç
- Birinden mesaj at, diğerinde anında gör

## Projede “neresi ne yapıyor?”

- UI: `resources/views/chat.blade.php`
- Frontend (Echo + dinleme + POST): `resources/js/app.js`
  - Dinlediği kanal: `chat`
  - Dinlediği event adı: `.message.sent`
- Backend endpoint: `routes/web.php` içindeki `POST /messages`
- Controller: `app/Http/Controllers/ChatController.php`
- Broadcast event: `app/Events/MessageSent.php`

## Sık görülen hatalar (ve çözüm)

- “Mesaj gidiyor ama diğer sekmede görünmüyor”
  - Reverb çalışıyor mu? (`php artisan reverb:start --debug`)
  - Event broadcast oluyor mu? `app/Events/MessageSent.php` event’i broadcast interface’ini implement etmeli.

- `419 (CSRF token mismatch)`
  - Sayfada `<meta name="csrf-token" ...>` var mı? (Var: `resources/views/chat.blade.php`)
  - Frontend request header’da `X-CSRF-TOKEN` gidiyor mu? (`resources/js/app.js`)

- `.env` değiştirdim ama etkilenmedi
  - `php artisan optimize:clear` çalıştır.

- Veritabanı hatası alıyorum
  - `.env` içinde `DB_*` satırlarının başında boşluk kalmadığından emin ol.
  - Session’ı DB’de tutmak istiyorsan `SESSION_DRIVER=database` için `sessions` tablosu gerekir.

## Notlar

- Bu örnekte mesajlar DB’ye yazılmaz; amaç Reverb broadcast akışını göstermek.
- Frontend’de mesaj basarken `innerHTML` kullanılmadı; basit XSS riskini azaltmak için text olarak eklenir.

