<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;    // لو قناة عادية مش برايفت للادمن بس

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;      // لو قناة برايفت  للادمن بس
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCreated implements ShouldBroadcastNow
{
    use Dispatchable,  SerializesModels;


    public $order;
    /**
     * Create a new event instance.
     */
    public function __construct(Order  $order)
    {
        $this->order = $order->load('user');  // عشان لما نبعت الحدث ده نقدر نوصل لبيانات المستخدم اللي عمل الطلب
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            // new Channel('orders'),  //  مفتوحة لكل الناس orders ابعت الحدث ده على قناة اسمها
            new PrivateChannel('orders'),  //  orders ابعت الحدث ده على قناة برايفت عشن تبقي للادمن بس اسمها

        ];
    }
  public function broadcastAs(): string
{
    return 'order.created';
}

       public function broadcastWith(): array
    {
        return [
            'id' => $this->order->id,
            'total' => $this->order->total,
            'status' => $this->order->status,
            'user_name' => $this->order->user?->name,
            'created_at' => $this->order->created_at?->toDateTimeString(),
        ];
    }
}
