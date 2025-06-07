<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class StripeController extends Controller
{
    protected StripeClient $stripe;

    public function __construct()
    {
        // الطريقة الصحيحة لتهيئة StripeClient
        $this->stripe = new StripeClient([
            'api_key' => config('stripe.keys.secret'),
            'stripe_version' => config('stripe.settings.api_version')
        ]);
        
        // أو يمكنك استخدام هذه الطريقة البديلة:
        // $this->stripe = new StripeClient(config('stripe.keys.secret'));
    }

public function pay(Request $request)
{

    // dd(config('stripe.keys.secret'));
    // أضف هذا السطر مؤقتاً في الكونترولر لفحص المفتاح
    \Stripe\Stripe::setApiKey(config('stripe.keys.secret'));
    
    try {
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Invoice #' . $request->invoice_id,
                    ],
                    'unit_amount' => $request->amount * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success', ['invoice' => $request->invoice_id]),
            'cancel_url' => route('payment.cancel', ['invoice' => $request->invoice_id]),
        ]);

        return response()->json(['url' => $session->url]);
        
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}