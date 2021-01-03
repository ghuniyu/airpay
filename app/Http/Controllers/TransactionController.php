<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function all()
    {
        $user = auth()->user()->load('transactions');
        return response()->json([
            'success' => true,
            'data' => $user['transactions']
        ]);
    }
}
