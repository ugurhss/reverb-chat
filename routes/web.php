<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;



//yeni channels.php dosyası oluşturuldu ve burada chat kanalı tanımlandı private olursa oraya müdale
Route::view('/', 'chat');
Route::view('/location', 'location');
Route::post('/messages', [ChatController::class, 'send']);
