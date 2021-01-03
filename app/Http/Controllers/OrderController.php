<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostPay;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $valid = $request->validate([
            'transaction_detail' => 'required|min:10:max:255',
            'gross_amount' => 'required|numeric|min:0',
        ]);

        $valid['status'] = 'created';
        $valid['user_id'] = auth()->id();
        $valid['expired_at'] = now()->addMinutes(60);
        $order = Order::create($valid);


        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    public function get(Order $order)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'order' => $order,
                'payment_methods' => PaymentMethod::all()
            ]
        ]);
    }

    public function pay(PostPay $request, Order $order, PaymentMethod $paymentMethod)
    {

        $valid = $request->validated();
        $payUsing = $valid['option'] ?? 'Airpay';

        switch ($order['status']) {
            case 'created':
                $user = User::find($order['user_id']);
                if (!$user->wallet()->exists())
                    $user->wallet()->save(new Wallet());

                if (now() > $order['expired_at'])
                    return response()->json([
                        'success' => false,
                        'messages' => 'Order Expired'
                    ]);

                DB::beginTransaction();
                try {
                    Transaction::create([
                        'user_id' => $user['id'],
                        'type' => 'credit',
                        'info' => "Approved by System",
                        'note' => "pay using {$paymentMethod['name']} - {$payUsing}",
                        'gross_amount' => $order['gross_amount'],
                        'payment_method_id' => $paymentMethod['id'],
                        'status' => 'success',
                    ]);

                    $user->wallet->credit($order['gross_amount']);
                    $order->update(['status' => 'success']);

                    DB::commit();
                    return response()->json([
                        'success' => true,
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'messages' => $e
                    ]);
                }

            case 'success':
                return response()->json([
                    'success' => false,
                    'messages' => 'Order Already Paid'
                ]);

            default:
                return response()->json([
                    'success' => false,
                    'messages' => 'Unknown Order Status'
                ]);
        }
    }

    public function buy()
    {

    }
}
