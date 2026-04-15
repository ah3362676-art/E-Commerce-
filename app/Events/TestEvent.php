<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestEvent implements ShouldBroadcastNow                  //ShouldBroadcastNow =  Real-time" "ابعت الحدث فورًا
{
    use Dispatchable, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('test-channel'),  //  test-channel" "ابعت الحدث ده على قناة اسمها
        ];
    }

    public function broadcastAs(): string
    {
        return 'test-event';
    }
}
