<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow

//mesaj gönderildiğinde tetiklenecek kısım dinleyici
  { 
      use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
       public function __construct(
        public string $user, //kullanıcı adı
        public string $message //gönderilen mesaj
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    //gizli kanal üzerinden yayınlanacak şekilde ayarlanır bu kısım ben public yapacagım
    // public function broadcastOn(): array 
    // { 
    //     return [
    //         new PrivateChannel('channel-name'),
    //     ];
    // }

      public function broadcastOn(): Channel
    {
        return new Channel('chat');
    }
    //broadcastON fonksiyonu  hangi kanal olacagını  belirleriz 
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    //broadcastAs fonksiyonu ise hangi event ismi ile yayınlanacağını belirleriz
}
