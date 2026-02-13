<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Reverb Chat</title>
    @vite(['resources/js/app.js'])
    <style>
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial; margin: 24px; }
        #log { border: 1px solid #ddd; padding: 12px; height: 320px; overflow: auto; border-radius: 8px; }
        .row { display: flex; gap: 8px; margin-top: 12px; }
        input { padding: 10px; border: 1px solid #ddd; border-radius: 8px; flex: 1; }
        button { padding: 10px 14px; border: 0; border-radius: 8px; cursor: pointer; }
        .msg { margin: 6px 0; }
        .user { font-weight: 600; }
    </style>
</head>
<body>
    <h1>Reverb Mini Chat</h1>

    <div id="log"></div>

    <div class="row">
        <input id="user" placeholder="Kullanıcı adı" value="guest-{{ rand(100,999) }}">
        <input id="message" placeholder="Mesaj yaz...">
        <button id="send">Gönder</button>
    </div>
</body>
</html>
