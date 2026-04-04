<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\adminRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();


        $cartItems = CartItem::with('product')
            ->where('user_id', $request->user()->id)
            ->get()
            ->filter(function ($item) {
                return $item->product !== null;
            });

        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'Your cart is empty.',
            ], 422);
        }

        $order = DB::transaction(function () use ($request, $data, $cartItems) {
            $total = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            $order = Order::create([
                'user_id' => $request->user()->id,
                'total' => $total,
                'status' => 'pending',
                'phone' => $data['phone'],
                'address' => $data['address'],
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            CartItem::where('user_id', $request->user()->id)->delete();

            return $order->load(['items.product.images', 'items.product.category']);
        });

        return response()->json([
            'message' => 'Order placed successfully.',
            'data' => $order,
        ], 201);
    }

    public function index(Request $request)
    {
        $orders = Order::with(['items.product.images', 'items.product.category'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Orders retrieved successfully.',
            'data' => $orders,
        ]);
    }

    public function show(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized');
        }

        $order->load(['items.product.images', 'items.product.category']);

        return response()->json([
            'message' => 'Order retrieved successfully.',
            'data' => $order,
        ]);
    }

public function adminIndex()
{
    $orders = Order::with(['user', 'items.product.images', 'items.product.category'])
        ->latest()
        ->get();

    return response()->json([
        'message' => 'All orders retrieved successfully.',
        'data' => $orders,
    ]);
}

public function updateStatus(adminRequest $request, Order $order)
{
    $data = $request->validated();

    $order->update([
        'status' => $data['status'],
    ]);

    $order->load(['user', 'items.product.images', 'items.product.category']);

    return response()->json([
        'message' => 'Order status updated successfully.',
        'data' => $order,
    ]);
}


}
