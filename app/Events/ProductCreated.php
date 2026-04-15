<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ProductCreated implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public $product;

    public function __construct(Product $product)
    {
        $this->product = $product->load(['category', 'images']);
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('products'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'product.created';
    }

    public function broadcastWith(): array
    {
        $firstImage = $this->product->images->first();

        return [
            'id' => $this->product->id,
            'title' => $this->product->title,
            'price' => $this->product->price,
            'stock' => $this->product->stock,
            'is_active' => $this->product->is_active,
            'category' => $this->product->category?->name ?? 'No Category',
            'image' => $firstImage ? asset('storage/' . $firstImage->image) : null,
        ];
    }
}
