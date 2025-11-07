<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        if ($payload['type'] === 'checkout.session.completed') {
            $session = $payload['data']['object'];
            $userId = $session['client_reference_id']; // セッション作成時に渡す
            $productId = $session['metadata']['product_id']; // 同上
            $payMethod = $session['payment_method_types'][0] === 'card' ? 2 : 1;

            Purchase::firstOrCreate(
                ['user_id' => $userId, 'product_id' => $productId],
                ['pay_method' => $payMethod, 'stripe_payment_intent_id' => $session['id']]
            );

            // ここで DB に登録されれば sold 表示などが反映される
        }

        return response()->json(['status' => 'success']);
    }
}