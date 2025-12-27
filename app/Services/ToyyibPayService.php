<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ToyyibPayService
{
    /**
     * ToyyibPay API Endpoint
     */
    private string $baseUrl;

    /**
     * ToyyibPay Merchant Secret Key
     */
    private string $secretKey;

    /**
     * ToyyibPay Category Code (Bill Code)
     */
    private string $categoryCode;

    /**
     * Create a new service instance
     */
    public function __construct()
    {
        $this->baseUrl = config('services.toyyibpay.env') === 'production'
            ? 'https://toyyibpay.com'
            : 'https://dev.toyyibpay.com';

        $this->secretKey = config('services.toyyibpay.secret_key', env('TOYYIBPAY_SECRET_KEY'));
        $this->categoryCode = config('services.toyyibpay.category_code', env('TOYYIBPAY_CATEGORY_CODE'));
    }

    /**
     * Create a payment bill
     *
     * @param array $paymentData
     * @return array
     */
    public function createBill(array $paymentData): array
    {
        $params = [
            'userSecretKey' => $this->secretKey,
            'categoryCode' => $this->categoryCode,
            'billName' => $paymentData['bill_name'] ?? 'Alamanda Houseboat Booking',
            'billDescription' => $paymentData['bill_description'] ?? 'Booking Payment',
            'billPriceSetting' => 1, // Fixed amount
            'billPayorInfo' => 1, // Require payer info
            'billAmount' => $paymentData['amount'] * 100, // ToyyibPay uses sen (cents)
            'billReturnUrl' => $paymentData['return_url'] ?? route('payment.toyyibpay.return'),
            'billCallbackUrl' => $paymentData['callback_url'] ?? route('payment.toyyibpay.callback'),
            'billExternalReferenceNo' => $paymentData['reference_no'] ?? uniqid('BOOKING-'),
            'billTo' => $paymentData['payer_name'] ?? 'Customer',
            'billEmail' => $paymentData['payer_email'] ?? '',
            'billPhone' => $paymentData['payer_phone'] ?? '',
            'billSplitPayment' => 0,
            'billSplitPaymentArgs' => '',
            'billPaymentChannel' => 0, // All channels
            'billContentEmail' => 'Thank you for your booking!',
            'billChargeToCustomer' => 0, // Merchant bears the fee
        ];

        // Optional: Set expiry date (24 hours from now)
        if (!isset($paymentData['billExpiryDate'])) {
            $params['billExpiryDate'] = now()->addHours(24)->format('d-m-Y H:i:s');
        }

        Log::info('ToyyibPay API Request', [
            'url' => $this->baseUrl . '/index.php/api/createBill',
            'params' => $params,
        ]);

        $response = Http::asForm()->post($this->baseUrl . '/index.php/api/createBill', $params);

        Log::info('ToyyibPay API Response', [
            'status' => $response->status(),
            'body' => $response->body(),
            'json' => $response->json(),
        ]);

        if ($response->successful()) {
            $body = $response->json();

            if (isset($body[0]) && isset($body[0]['BillCode'])) {
                return [
                    'success' => true,
                    'bill_code' => $body[0]['BillCode'],
                    'payment_url' => $this->baseUrl . '/' . $body[0]['BillCode'],
                ];
            }

            return [
                'success' => false,
                'message' => $body[0]['Msg'] ?? 'Failed to create bill',
            ];
        }

        Log::error('ToyyibPay API Error', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return [
            'success' => false,
            'message' => 'Failed to connect to payment gateway',
        ];
    }

    /**
     * Get payment URL for a bill code
     *
     * @param string $billCode
     * @return string
     */
    public function getPaymentUrl(string $billCode): string
    {
        return $this->baseUrl . '/' . $billCode;
    }

    /**
     * Verify payment callback
     *
     * @param array $data
     * @return bool
     */
    public function verifyCallback(array $data): bool
    {
        // Required fields from ToyyibPay callback
        $required = ['billcode', 'status', 'refno', 'amount'];

        foreach ($required as $field) {
            if (!isset($data[$field])) {
                Log::warning('ToyyibPay callback missing field: ' . $field, $data);
                return false;
            }
        }

        // Check if payment is successful (status = 1)
        return $data['status'] == '1';
    }

    /**
     * Check bill status
     *
     * @param string $billCode
     * @return array
     */
    public function checkBillStatus(string $billCode): array
    {
        $params = [
            'userSecretKey' => $this->secretKey,
            'billCode' => $billCode,
        ];

        $response = Http::asForm()->get($this->baseUrl . '/index.php/api/getBillTransactions', $params);

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data[0])) {
                return [
                    'success' => true,
                    'status' => $data[0]['billstatus'] ?? 'unknown',
                    'payment_status' => $data[0]['payment_status'] ?? 'unknown',
                    'amount' => $data[0]['billamount'] ?? 0,
                ];
            }
        }

        return [
            'success' => false,
            'message' => 'Failed to check bill status',
        ];
    }
}
