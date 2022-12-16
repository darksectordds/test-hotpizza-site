<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ListController;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class CartController extends ListController
{
    protected $model = Cart::class;

    protected $orderByColumn = 'id';

    /**
     * Получение ID-сессии из cookie-запроса,
     * который сохраняется в зависимости от конфига
     * в .env (1 год сейчас).
     *
     * @param Request $request
     * @return string
     */
    private function getSessionIdFromRequestCookie(Request $request)
    {
        // WARNING: не использовать session()->getId(),
        // потому что эта функция генерирует новую сессию
        // каждый раз!!!

        $key = strtolower(config('app.name')).'_session';
        $cookie_arr = $request->cookie();

        $crypt_session_id = $cookie_arr[$key];

        $hash = Crypt::decryptString($crypt_session_id);

        return substr($hash, strpos($hash, '|') + 1);
    }

    /**
     * Создание новой корзины или возврат сущ.
     * не оплаченной!
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $session_id = $this->getSessionIdFromRequestCookie($request);

        $cart = $this->model::firstOrCreate([
            'session_id' => $session_id,
            'is_payment' => 0,
        ]);

        return response()->json($cart);
    }

    public function unPaymentCount(Request $request)
    {
        /* SELECT m.id,m.is_payment,cp.count FROM `cart` as m
            LEFT JOIN cart_product AS cp ON m.id = cp.cart_id
            WHERE m.id = 3 AND m.is_payment = 0
         */

        $session_id = $this->getSessionIdFromRequestCookie($request);

        $count = $this->model::select([
                'cart.id as id',
                'cart.is_payment as payment',
            ])
            ->leftJoin('cart_product', function($join) {
                $join->on('cart.id', '=', 'cart_product.cart_id');
            })
            ->where('session_id', $session_id)
            ->where('is_payment', 0)
            ->sum('cart_product.count');

        return response()->json($count);
    }
}
