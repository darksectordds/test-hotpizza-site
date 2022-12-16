<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ListController;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends ListController
{
    protected $model = Cart::class;

    protected $orderByColumn = 'id';

    public function create(Request $request)
    {
        $session_id = session()->getId();

        $cart = $this->model::firstOrCreate([
            'session_id' => $session_id,
            'is_payment' => 0,
        ]);

        return response()->json($cart);
    }
}
