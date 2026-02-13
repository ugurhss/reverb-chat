<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Live Location</title>

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""
    />
    <style>
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial; margin: 24px; }
        .grid { display: grid; grid-template-columns: 1fr 360px; gap: 16px; align-items: start; }
        #map { height: 520px; border: 1px solid #ddd; border-radius: 10px; }
        .card { border: 1px solid #ddd; border-radius: 10px; padding: 12px; }
        .muted { color: #666; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { padding: 8px; border-bottom: 1px solid #eee; vertical-align: top; }
        th { text-align: left; font-size: 12px; color: #666; }
        .mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
        .row { display: flex; gap: 8px; }
        input { flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 8px; }
        button { padding: 10px 12px; border: 0; border-radius: 8px; cursor: pointer; }
    </style>

    @vite(['resources/js/location.js'])
</head>
<body>
    <h1>Live Location</h1>
    <p class="muted">
        Postman ile <span class="mono">POST /api/location</span> atınca burası canlı güncellenir.
        Sayfa Reverb üzerinden <span class="mono">locations</span> kanalını dinler.
    </p>

    <div class="grid">
        <div id="map"></div>

        <div class="card">
            <h3 style="margin: 0 0 8px;">Cihazlar</h3>
            <div class="muted" id="status">Bağlanıyor...</div>

            <div style="height: 12px;"></div>

            <table>
                <thead>
                    <tr>
                        <th>Cihaz</th>
                        <th>Enlem/Boylam</th>
                        <th>Doğruluk</th>
                        <th>Zaman</th>
                    </tr>
                </thead>
                <tbody id="devices"></tbody>
            </table>

            <div style="height: 12px;"></div>

            <div class="muted">
                Örnek body:
                <div class="mono" style="margin-top: 6px; white-space: pre-wrap;">
{ "device_id":"device-1", "lat":41.015, "lng":28.979, "accuracy":12.5, "recorded_at":"2026-02-13T12:34:56Z" }
                </div>
            </div>
        </div>
    </div>

    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""
    ></script>
</body>
</html>

