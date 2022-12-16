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
}
