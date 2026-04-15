<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Repositories\Interfaces\CartRepositoryInterface;

class CartController extends Controller
{
    protected CartRepositoryInterface $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function index()
    {
        $cartItems = $this->cartRepository->getUserCartItems(auth()->id());

        $total = $this->cartRepository->calculateTotal($cartItems);

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function store(StoreCartItemRequest $request)
    {
        $data = $request->validated();

        $cartItem = $this->cartRepository->findUserCartItemByProductId(
            auth()->id(),
            $data['product_id']
        );

        if ($cartItem) {
            $this->cartRepository->incrementQuantity($cartItem, $data['quantity']);
        } else {
            $this->cartRepository->create([
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

        $this->cartRepository->updateQuantity(
            $cartItem,
            $request->validated()['quantity']
        );

        return redirect()
            ->route('cart.index')
            ->with('success', 'Cart updated successfully.');
    }

    public function destroy(CartItem $cartItem)
    {
        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        $this->cartRepository->delete($cartItem);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Product removed from cart successfully.');
    }
}
