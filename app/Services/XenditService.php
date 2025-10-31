<?php

namespace App\Services;

use Xendit\Xendit;

class XenditService
{
    public function __construct()
    {
        Xendit::setApiKey(env('XENDIT_SECRET_KEY'));
    }

    public function createFixedVA($userName, $externalId, $bankCode, $amount)
    {
        $params = [
            'external_id' => $externalId,
            'bank_code' => $bankCode, // 'BCA', 'BNI', 'BRI', 'MANDIRI'
            'name' => $userName,
            'expected_amount' => $amount,
        ];

        return \Xendit\VirtualAccounts::create($params);
    }
}
