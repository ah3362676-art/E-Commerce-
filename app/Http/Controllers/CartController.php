<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
public function index()
{
    $cartItems = CartItem::with(['product.images', 'product.category'])
        ->where('user_id', auth()->id())
        ->latest()
        ->get()
        ->filter(function ($item) {
            return $item->product !== null;
        });

    $total = $cartItems->sum(function ($item) {
        return $item->product->price * $item->quantity;
    });

    return view('cart.index', compact('cartItems', 'total'));
}

    public function store(StoreCartItemRequest $request)
    {
        $data = $request->validated();

        $cartItem = CartItem::where('user_id', auth()->id())
            ->where('product_id', $data['product_id'])
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $data['quantity']);
        } else {
            CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $data['product_id'],
                'quantity' => $data['quantity'],
            ]);
        }

        return redirect()
            ->route('cart.index')
            ->with('success', 'Product added to cart successfully.');
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem)
    {
        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->update([
            'quantity' => $request->validated()['quantity'],
        ]);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Cart updated successfully.');
    }

    public function destroy(CartItem $cartItem)
    {
        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();

        return redirect()
            ->route('cart.index')
            ->with('success', 'Product removed from cart successfully.');
    }
}
