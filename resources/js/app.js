import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
  broadcaster: 'reverb',
  key: import.meta.env.VITE_REVERB_APP_KEY,
  wsHost: import.meta.env.VITE_REVERB_HOST,
  wsPort: Number(import.meta.env.VITE_REVERB_PORT),
  wssPort: Number(import.meta.env.VITE_REVERB_PORT),
  forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'http') === 'https',
  enabledTransports: ['ws', 'wss'],
});

const log = document.getElementById('log');
const userInput = document.getElementById('user');
const msgInput = document.getElementById('message');
const sendBtn = document.getElementById('send');

function appendMessage(user, message) {
  const div = document.createElement('div');
  div.className = 'msg';
  const userSpan = document.createElement('span');
  userSpan.className = 'user';
  userSpan.textContent = `${user}:`;
  div.appendChild(userSpan);
  div.appendChild(document.createTextNode(` ${message}`));
  log.appendChild(div);
  log.scrollTop = log.scrollHeight;
}

// Reverb üzerinden public channel dinle
window.Echo.channel('chat')
  .listen('.message.sent', (e) => {
    appendMessage(e.user, e.message);
  });

// Mesaj gönder
sendBtn.addEventListener('click', async () => {
  const user = userInput.value.trim();
  const message = msgInput.value.trim();
  if (!user || !message) return;

  // kendi mesajını hemen ekle (toOthers kullandık, yayın bize dönmeyecek)
  appendMessage(user, message);

  const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute('content');

  try {
    const res = await fetch('/messages', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
      },
      body: JSON.stringify({ user, message }),
    });

    if (!res.ok) {
      const text = await res.text().catch(() => '');
      console.error('POST /messages failed', res.status, text);
      appendMessage('system', `Mesaj gönderilemedi (HTTP ${res.status}).`);
    }
  } catch (err) {
    console.error('POST /messages error', err);
    appendMessage('system', 'Mesaj gönderilemedi (network error).');
  }

  msgInput.value = '';
  msgInput.focus();
});
