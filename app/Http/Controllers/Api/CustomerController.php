<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Customers;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|max:256',
            'phone' => 'required|max:256',
            'email' => 'required|max:256|email',
            'address' => 'required|max:512',
            'paid' => 'required|numeric',
            'comment' => 'required|max:2048',
        ]);

        $name = $request->get('name');
        $phone = $request->get('phone');
        $email = $request->get('email');
        $address = $request->get('address');
        $paid = $request->get('paid');
        $comment = $request->get('comment');

        $customer = Customers::create([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'paid' => $paid,
            'comment' => $comment,
        ]);

        $session_id = app(CartController::class)->getSessionIdFromRequestCookie($request);

        $res = Cart::where('session_id', $session_id)
            ->where('is_payment', false)
            ->update([
                'customer_id' => $customer->id,
                'is_payment' => true,
            ]);

        // если не было изменений в БД, то это значит, что
        // скорее всего мы не нашли "корзину" с такой сессией
        // и при этом не оплаченную
        return response()->json($res, (!$res) ? 403 : 200);
    }
}
