<?php

namespace Msh\PayMob;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

use PayMob;

class PayMobController extends Controller
{
    // Const(s)
    CONST DEFAULT_CURRENCY = 'EGP';              // the payment currency

    CONST DeFAULT_BILLING_DATA = [              // used in step 3 to get the payment key
        "apartment" => "803",
        "email" => "70m0s15@gamil.com",
        "floor" => "42",
        "first_name" => "Mohamed",
        "street" => "Ethan Land",
        "building" => "8028",
        "phone_number" => "+86(8)9135210487",
        "shipping_method" => "PKG",
        "postal_code" => "01898",
        "city" => "Jaskolskiburgh",
        "country" => "CR",
        "last_name" => "Hassan",
        "state" => "Utah"
    ];

    CONST RESPONSE_CODES = [
        '0' => 'Success',
        '1' => 'There was an error processing the transaction',
        '2' => 'Contact card issuing bank',
        '4' => 'Expired Card',
        '5' => 'Insufficient Funds',
        '6' => 'Payment is already being processed'
    ];

    // Amount to be paid
    private $amountInCents;

    // Data Obtained from Step 1 : API Authentication Request
    private $token;
    private $profile;
    private $merchantId;

    // Data Obtained from Step 2 : Order registration request
    private $order;
    private $orderId;

    // Data Obtained from Step 3 : Payment key request
    private $paymentKeyToken;


    public function pay(Request $request)
    {
        $data = $this->validate($request, [
            'amountInCents' => 'required|numeric',
        ]);

        $this->amountInCents = data_get($data, 'amountInCents');

        $this->apiAuthRequest();

        $this->orderRegistrationRequest();

        $this->paymentKeyRequest();

        $iframe = $this->prepareClientCode();

        // @TODO : install SDK for mobile !
        echo "<iframe src='{$iframe}' style='width: 100%; height: 100%'></iframe>";
    }

    /**
     * Step 5: Transaction Processed Callback "server-side"
     */
    public function transactionProcessedCallback(Request $request)
    {
        // LOG Transactions @TODO : Update Orders !
        Log::info(json_encode($request->all()));
    }

    /**
     * Step 6: Transaction Response Callback "server-side"
     */
    public function transactionResponseCallback(Request $request)
    {
        Log::info(json_encode($request->all()));
        //  @TODO : return the parsed native object so that your application can formulate the response page.
        echo data_get(self::RESPONSE_CODES, data_get($request, 'txn_response_code'));
    }

    /**
     * Step 1: API Authentication Request "server-side"
     */
    public function apiAuthRequest()
    {
        $response = PayMob::getAuthenticationToken();

        $this->token = data_get($response, 'token');
        $this->profile = data_get($response, 'profile');
        $this->merchantId = data_get($response, 'profile.id');
    }

    /**
     * Step 2: Order registration request "server-side"
     *
     */
    public function orderRegistrationRequest()
    {
        $this->order = PayMob::makeOrder($this->token,
            $this->merchantId,
            $this->amountInCents,
            $this->generateMerchantOrderId(), // unique alpha-numerice value, example: E6RR3
            self::DEFAULT_CURRENCY);

        $this->orderId = data_get($this->order, 'id');
    }

    /**
     * Step 3: Payment key request "server-side"
     *
     */
    public function paymentKeyRequest()
    {
        $this->paymentKeyToken = PayMob::createPaymentKeyToken($this->token,
            $this->amountInCents,
            $this->orderId,
            self::DeFAULT_BILLING_DATA,
            self::DEFAULT_CURRENCY);
    }

    /**
     *  Step 4: Prepare your client code "client-side"
     */
    public function prepareClientCode()
    {
        return sprintf("https://accept.paymobsolutions.com/api/acceptance/iframes/%s?payment_token=%s", config('paymob.iframe_id'), $this->paymentKeyToken);
    }

    private function generateMerchantOrderId()
    {
        return uniqid();
    }

    /**
     * Transaction processed callback: https://accept.paymobsolutions.com/api/acceptance/post_pay
     * Transaction response callback: https://accept.paymobsolutions.com/api/acceptance/post_pay
     */

}
