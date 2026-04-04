<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

use function Pest\Laravel\call;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cartitems= CartItem::with([ "product.images", "product.category" ])
        ->where("user_id",$request->user()->id)
        ->latest()
        ->get();

        $total= $cartitems->sum(function($item){
            return $item->product->price * $item->quantity ;
        });

        return response()->json([
            "message" => "Cart items retrieved successfully",
            "data" => $cartitems,
            "total" => $total
        ]);
    }

    public function store(StoreCartItemRequest $request)
    {
        $data = $request->validated();
        $cartitems = CartItem::where("user_id",$request->user()->id)
        ->where("Product_id",$data["product_id"])
        ->first();

        if($cartitems){
            $cartitems->increment("quantity",$data["quantity"]);
    }else{
        $cartitems = CartItem::create([
            "user_id" => $request->user()->id,
            "product_id" => $data["product_id"],
            "quantity" => $data["quantity"],
        ]);
    };

        return response()->json([
            "message" => "Product added to cart successfully",
            "data" => $cartitems
        ],201);
    }
     public function update(Request $request, CartItem $cartItem)
    {
        if ($cartItem->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized');
        }

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cartItem->update([
            'quantity' => $data['quantity'],
        ]);

        return response()->json([
            'message' => 'Cart updated successfully.',
            'data' => $cartItem->load(['product.category', 'product.images']),
        ]);
    }

    public function destroy(Request $request, CartItem $cartItem)
    {
        if ($cartItem->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized');
        }

        $cartItem->delete();

        return response()->json([
            'message' => 'Product removed from cart successfully.',
        ]);
    }
}
