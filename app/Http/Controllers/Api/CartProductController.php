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

    /**
     * Удаление товара из корзины.
     *
     * @param Request $request
     * @param $product_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $product_id)
    {
        $session_id = app(CartController::class)->getSessionIdFromRequestCookie($request);

        // запрос должен в себе содержать проерку сессии
        $cart_product = CartProduct::whereHas('cart', function ($query) use($session_id){
                $query->where('session_id', $session_id)
                    ->where('is_payment', 0);
            })
            ->where('product_id', $product_id)
            ->first();

        $count = $cart_product->delete();

        return response()->json($cart_product, ($count > 0) ? 200 : 400);
    }
}
