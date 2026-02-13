import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const statusEl = document.getElementById('status');
const devicesTbody = document.getElementById('devices');
const mapEl = document.getElementById('map');

function setStatus(text) {
  if (statusEl) statusEl.textContent = text;
}

function formatNumber(value) {
  if (typeof value !== 'number' || Number.isNaN(value)) return '-';
  return value.toFixed(6);
}

function formatAccuracy(value) {
  if (value === null || value === undefined) return '-';
  const num = Number(value);
  if (Number.isNaN(num)) return '-';
  return `${num.toFixed(1)} m`;
}

function formatTime(isoString) {
  if (!isoString) return '-';
  const date = new Date(isoString);
  if (Number.isNaN(date.getTime())) return String(isoString);
  return date.toLocaleString();
}

function escapeHtml(value) {
  return String(value)
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');
}

// Map
const map = (mapEl && window.L) ? window.L.map(mapEl).setView([41.015, 28.979], 12) : null;
if (map) {
  window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors',
  }).addTo(map);
}

const markersByDevice = new Map();
const rowsByDevice = new Map();

function upsertRow(payload) {
  const deviceId = payload.device_id ?? 'unknown';
  const lat = Number(payload.lat);
  const lng = Number(payload.lng);
  const accuracy = payload.accuracy ?? null;
  const recordedAt = payload.recorded_at ?? null;

  let row = rowsByDevice.get(deviceId);
  if (!row) {
    row = document.createElement('tr');
    row.style.cursor = 'pointer';
    row.addEventListener('click', () => {
      const marker = markersByDevice.get(deviceId);
      if (marker && map) {
        map.setView(marker.getLatLng(), Math.max(map.getZoom(), 14));
        marker.openPopup();
      }
    });
    rowsByDevice.set(deviceId, row);
    devicesTbody?.prepend(row);
  }

  row.replaceChildren();
  const cellDevice = document.createElement('td');
  cellDevice.className = 'mono';
  cellDevice.textContent = deviceId;

  const cellCoords = document.createElement('td');
  cellCoords.className = 'mono';
  cellCoords.textContent = `${formatNumber(lat)}, ${formatNumber(lng)}`;

  const cellAcc = document.createElement('td');
  cellAcc.textContent = formatAccuracy(accuracy);

  const cellTime = document.createElement('td');
  cellTime.textContent = formatTime(recordedAt);

  row.append(cellDevice, cellCoords, cellAcc, cellTime);
}

function upsertMarker(payload) {
  if (!map) return;

  const deviceId = payload.device_id ?? 'unknown';
  const lat = Number(payload.lat);
  const lng = Number(payload.lng);
  if (Number.isNaN(lat) || Number.isNaN(lng)) return;

  const accuracy = payload.accuracy ?? null;
  const recordedAt = payload.recorded_at ?? null;

  let marker = markersByDevice.get(deviceId);
  if (!marker) {
    marker = window.L.marker([lat, lng]).addTo(map);
    markersByDevice.set(deviceId, marker);
  } else {
    marker.setLatLng([lat, lng]);
  }

  marker.bindPopup(
    `<div class="mono">${escapeHtml(deviceId)}</div>
     <div>${escapeHtml(formatNumber(lat))}, ${escapeHtml(formatNumber(lng))}</div>
     <div>Doğruluk: ${escapeHtml(formatAccuracy(accuracy))}</div>
     <div>Zaman: ${escapeHtml(formatTime(recordedAt))}</div>`
  );
}

// Echo / Reverb
const echo = new Echo({
  broadcaster: 'reverb',
  key: import.meta.env.VITE_REVERB_APP_KEY,
  wsHost: import.meta.env.VITE_REVERB_HOST,
  wsPort: Number(import.meta.env.VITE_REVERB_PORT),
  wssPort: Number(import.meta.env.VITE_REVERB_PORT),
  forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'http') === 'https',
  enabledTransports: ['ws', 'wss'],
});

setStatus('Reverb bağlantısı bekleniyor...');

try {
  const connection = echo.connector?.pusher?.connection;
  connection?.bind('connected', () => setStatus('Bağlandı. Postman isteği bekleniyor...'));
  connection?.bind('disconnected', () => setStatus('Bağlantı koptu. Yeniden bağlanıyor...'));
  connection?.bind('error', () => setStatus('Bağlantı hatası. Reverb çalışıyor mu?'));
} catch {
  // ignore
}

echo.channel('locations').listen('.location.updated', (e) => {
  upsertRow(e);
  upsertMarker(e);
});
