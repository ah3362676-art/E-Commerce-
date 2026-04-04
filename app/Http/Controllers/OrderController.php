<?php

namespace App\Http\Controllers;

use App\Http\Requests\adminRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
   public function create()
{
    $cartItems = CartItem::with(['product.images'])
        ->where('user_id', auth()->id())
        ->latest()
        ->get()
        ->filter(function ($item) {
            return $item->product !== null;
        });

    if ($cartItems->isEmpty()) {
        return redirect()
            ->route('cart.index')
            ->with('success', 'Your cart is empty.');
    }

    $total = $cartItems->sum(function ($item) {
        return $item->product->price * $item->quantity;
    });

    return view('checkout.create', compact('cartItems', 'total'));
}

  public function store(StoreOrderRequest $request)
{
    $cartItems = CartItem::with('product')
        ->where('user_id', auth()->id())
        ->get()
        ->filter(function ($item) {
            return $item->product !== null;
        });

    if ($cartItems->isEmpty()) {
        return redirect()
            ->route('cart.index')
            ->with('error', 'Your cart is empty.');
    }

    DB::transaction(function () use ($request, $cartItems) {    // يعني كل اللي جواها يا يحصل كله يا ما يحصلش أي حاجة

        // 🟢 حساب التوتال
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // 🟢 إنشاء الأوردر
        $order = Order::create([
            'user_id' => auth()->id(),
            'total' => $total,
            'status' => 'pending',
            'phone' => $request->phone,
            'address' => $request->address,
            'notes' => $request->notes,
        ]);

        foreach ($cartItems as $item) {



            // 🟢 إنشاء order item
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);

            // 🟢 تقليل المخزون
            $item->product->decrement('stock', $item->quantity);
        }

        // 🟢 مسح الكارت
        CartItem::where('user_id', auth()->id())->delete();
    });

    return redirect()
        ->route('orders.index')
        ->with('success', 'Order placed successfully.');
}

    public function index()
    {
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }


    public function adminIndex()
{
    $orders = Order::with(['user', 'items.product'])
        ->latest()
        ->paginate(10);

    return view('admin.orders.index', compact('orders'));
}


public function updateStatus(adminRequest $request, Order $order)
{


    $order->update([
        'status' => $request->status,
    ]);

    return back()
    ->with('success', 'Order status updated.');
}
}
