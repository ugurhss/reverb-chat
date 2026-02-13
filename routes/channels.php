<?php

use Illuminate\Support\Facades\Broadcast;

// models klasöründe User modeli var ise onun id'si ile kanal oluşturulur ve sadece o kullanıcıya izin verilir
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
