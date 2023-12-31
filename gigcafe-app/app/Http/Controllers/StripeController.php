<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function session(Request $request)
    {
        //$user         = auth()->user();
        $menuItems = [];
 
        \Stripe\Stripe::setApiKey(config('stripe.sk'));
 
        foreach (session('cart') as $id => $details) {
 
            $name = $details['name'];
            $total = $details['price'];
            $quantity = $details['quantity'];
 
            $two0 = "00";
            $unit_amount = "$total$two0";
 
            $menuItems[] = [
                'price_data' => [
                    'product_data' => [
                        'name' => $name,
                    ],
                    'currency'     => 'USD',
                    'unit_amount'  => $unit_amount,
                ],
                'quantity' => $quantity
            ];
        }
 
        $checkoutSession = \Stripe\Checkout\Session::create([
            'line_items'            => [$menuItems],
            'mode'                  => 'payment',
            'allow_promotion_codes' => true,
            'metadata'              => [
                'user_id' => "0001"
            ],
            'customer_email' => "customer@gmail.com", //$user->email,
            'success_url' => route('success'),
            'cancel_url'  => route('cancel'),
        ]);
     
        return redirect()->away($checkoutSession->url);
    }
 
    public function success()
    {
        return "Thanks for you order You have just completed your payment. The seeler will reach out to you as soon as possible";
    }
 
    public function cancel()
    {
        return view('cart.cart');
    }
}
