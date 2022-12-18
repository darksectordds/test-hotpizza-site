<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ListController;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class CartController extends ListController
{
    protected $model = Cart::class;

    protected $orderByColumn = 'cart_product.created_at';

    /**
     * Возвращает query builder'а модели
     *
     * @param Request $request
     * @param $nested_relationships
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function query(Request $request, $nested_relationships = [])
    {
        if (count($nested_relationships) === 0) {
            $nested_relationships = $this->withRelationships();
        }

        $session_id = $this->getSessionIdFromRequestCookie($request);

        /*
        SELECT
            `cart`.`id` AS `cart`,
            `product`.`name` AS `name`,
            `product`.`price` AS `price`,
            SUM(cart_product.count) AS COUNT,
            `cart_product`.`product_id` AS `id`,
            `cart_product`.`created_at` AS `date`
        FROM `cart`
        LEFT JOIN
            ( `cart_product`
                LEFT JOIN `product` ON `cart_product`.`product_id` = `product`.`id`
            )
            ON `cart`.`id` = `cart_product`.`cart_id`
        WHERE `session_id` = '0JD2dOitzAajcZoGNaZs2wXyh3Muf565P5XF78Zg'
        AND  `is_payment` = 0
        GROUP BY `cart_product`.`product_id`
        LIMIT 20
        OFFSET 0;
         */

        // WARNING:
        // чтобы использовать такого рода запрос нужно либо отключить ONLY_FULL_GROUP_BY в SQL,
        // либо обернуть все выбранные столбцы агрегатными функциями MIN | MAX() | AVG(),
        // но тогда провадет целесообразность в столбце `created_at`, которая может быть
        // не так важна самому пользователю, НО важна для внутреннего потребления сотрудниками
        // для определения времени заказа!!!
        // Поэтому, чтобы не было проблем, которые возникнуть при определения ВСЕГО rows
        // при таком query-запросе, особенно при SUM-группировке по `cart_product.count`,
        // то решено сделать тупо в лоб без группировки.

        $q = $this->model::select([
                'product.name as name',
                'product.price as price',
                'cart_product.count as count',
                'cart_product.created_at as date'
            ])
            ->leftJoin('cart_product', function($join) {
                $join->on('cart.id', '=', 'cart_product.cart_id');
                $join->leftJoin('product', function ($join) {
                    $join->on('cart_product.product_id', '=', 'product.id');
                });
            })
            ->where('session_id', $session_id)
            ->where('is_payment', 0);

        return $q;
    }

    // TODO: перенеси меня!!!
    /**
     * Получение ID-сессии из cookie-запроса,
     * который сохраняется в зависимости от конфига
     * в .env (1 год сейчас).
     *
     * @param Request $request
     * @return string
     */
    public function getSessionIdFromRequestCookie(Request $request)
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

    /*
     |-------------------------------------------------------------------------------------
     | Routes
     |------------------------------------------------------------------------------
     */

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
