<?php

namespace App\Http\Controllers;

use App\Events\OrderCreated;
use App\Events\OrderStatusUpdated;
use App\Http\Requests\adminRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Repositories\Interfaces\CartRepositoryInterface;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected CartRepositoryInterface $cartRepository;
    protected OrderRepositoryInterface $orderRepository;

    public function __construct(
        CartRepositoryInterface $cartRepository,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->cartRepository = $cartRepository;
        $this->orderRepository = $orderRepository;
    }

    public function create()
    {
        $cartItems = $this->cartRepository->getUserCartItems(auth()->id());

        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('success', 'Your cart is empty.');
        }

        $total = $this->cartRepository->calculateTotal($cartItems);

        return view('checkout.create', compact('cartItems', 'total'));
    }

    public function store(StoreOrderRequest $request)
    {
        $cartItems = $this->cartRepository->getUserCartItems(auth()->id());

        if ($cartItems->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $order = DB::transaction(function () use ($request, $cartItems) {
            $total = $this->cartRepository->calculateTotal($cartItems);

            $order = $this->orderRepository->create([
                'user_id' => auth()->id(),
                'total' => $total,
                'status' => 'pending',
                'phone' => $request->phone,
                'address' => $request->address,
                'notes' => $request->notes,
            ]);

            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            foreach ($cartItems as $item) {
                $this->cartRepository->delete($item);
            }

            return $order;
        });

        event(new OrderCreated($order));

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order placed successfully.');
    }

    public function index()
    {
        $orders = $this->orderRepository->getUserOrdersPaginated(auth()->id(), 10);

        return view('orders.index', compact('orders'));
    }

    public function adminIndex()
    {
        $orders = $this->orderRepository->getAdminOrdersPaginated(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(adminRequest $request, \App\Models\Order $order)
    {
        $this->orderRepository->update($order, [
            'status' => $request->status,
        ]);

        event(new OrderStatusUpdated($order));

        return back()->with('success', 'Order status updated.');
    }
}
