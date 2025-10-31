<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\XenditService;

class PaymentController extends Controller
{
    public function createVA(XenditService $xendit)
    {
        $userName = 'Adrian Adiputra';
        $externalId = 'order-' . time();
        $bankCode = 'BCA';
        $amount = 100000;

        $va = $xendit->createFixedVA($userName, $externalId, $bankCode, $amount);

        return response()->json([
            'va_number' => $va['account_number'],
            'bank' => $va['bank_code'],
            'amount' => $va['expected_amount'],
        ]);
    }
}
