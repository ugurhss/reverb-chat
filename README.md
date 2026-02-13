# Reverb Mini Chat + Canlı Konum (Laravel + Reverb)

Bu repo, **Laravel Reverb** ile çalışan iki mini örnek içerir:
- **Mini Chat**: Sekmeler arasında anlık mesajlaşma
- **Canlı Konum**: Postman’dan gelen enlem/boylam verisini webde canlı gösterme

Genel mantık:
- Frontend, Reverb’e WebSocket ile bağlanıp ilgili kanalı dinler.
- Backend, HTTP request alınca bir event broadcast eder.
- Diğer istemciler anında güncellemeyi görür.

## Gereksinimler

- PHP
- Composer
- Node.js + npm
- (Opsiyonel) MySQL: Queue `database` ise migration gerekebilir.

## Hızlı Kurulum

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan optimize:clear
```

## Çalıştırma (3 Terminal)

Terminal 1:
```bash
php artisan serve
```

Terminal 2:
```bash
php artisan reverb:start --debug
```

Terminal 3:
```bash
npm run dev
```

## Sayfalar ve Endpoint’ler

### Chat

- Sayfa: `GET http://localhost:8000/`
- Mesaj gönderme: `POST http://localhost:8000/messages`

Frontend `chat` kanalını dinler, `.message.sent` event’ini yakalar.

### Canlı Konum

- Sayfa: `GET http://localhost:8000/location`
- Konum gönderme (Postman): `POST http://localhost:8000/api/location`

Frontend `locations` kanalını dinler, `.location.updated` event’ini yakalar.

## Postman Örneği (Konum)

URL:
- `POST http://localhost:8000/api/location`

Headers:
- `Accept: application/json`
- `Content-Type: application/json`

Body (raw JSON):
```json
{
  "device_id": "ugurcanKonum",
  "lat": 37.5858,
  "lng": 36.9371,
  "accuracy": 12.5,
  "recorded_at": "2026-02-13T12:34:56Z"
}

```

Birden fazla cihaz simülasyonu için `device_id`’yi değiştirmeniz yeterli.

## Proje Yapısı (Özet)

### Chat

- UI: `resources/views/chat.blade.php`
- Frontend: `resources/js/app.js`
- Route: `routes/web.php` (`/` ve `/messages`)
- Controller: `app/Http/Controllers/ChatController.php`
- Event: `app/Events/MessageSent.php`

### Konum

- UI: `resources/views/location.blade.php`
- Frontend: `resources/js/location.js`
- API Route: `routes/api.php` (`/api/location`)
- Controller: `app/Http/Controllers/LocationController.php`
- Event: `app/Events/LocationUpdated.php`

## Notlar

- Bu örnekte mesajlar DBye yazılmaz; amaç Reverb broadcast akışını göstermek.
- Frontend mesaj basarken `innerHTML` kullanılmadı; basit XSS riskini azaltmak için text olarak ekledim
