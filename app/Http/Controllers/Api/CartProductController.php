<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ListController;
use App\Models\Cart;
use App\Models\CartProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CartProductController extends ListController
{
    protected $model = CartProduct::class;

    protected $orderByColumn = 'id';

    public function create(Request $request, $product_id)
    {
        $request->request->add(['product_id' => $product_id]);

        $request->validate([
            'product_id' => 'required|exists:product,id',
            'count' => 'required|numeric|min:1',
        ]);

        $cart = app(CartController::class)
            ->create($request)
            ->getData();

        $count = $request->get('count');
        $created_at = Carbon::now();

        $cart_product = CartProduct::create([
            'cart_id' => $cart->id,
            'product_id' => $product_id,
            'count' => $count,
            'created_at' => $created_at,
        ]);

        return response()->json($cart_product);
    }
}
