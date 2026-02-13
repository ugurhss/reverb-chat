<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;

class ChatController extends Controller
{
//bu kısım mesaj gönderildiğinde tetiklenecek olan fonksiyon yani api
public function send(Request $request)
    {
        $data = $request->validate([
            'user' => ['required', 'string', 'max:50'],
            'message' => ['required', 'string', 'max:500'],
        ]);

        broadcast(new MessageSent($data['user'], $data['message']))->toOthers();
        //broadcast fonksiyonu ile mesaj gönderilir ve toOthers() ile diğer kullanıcılara iletilir
        return response()->json(['ok' => true]);//ok
    } 
}
