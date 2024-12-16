<?php

namespace App\Http\Controllers\Frontend;

use App\Jobs\SendMail;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\OrderPayments;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PaytraceController;
use App\Http\Controllers\ConstantsController;

class CheckoutController extends FrontendController
{
    private $jsonResponseArray = [];

    public function __construct()
    {
        parent::__construct();
        $this->cart_model = new Cart();
        $this->order_payment_model = new OrderPayments();
        $this->paytrace = $this->active_theme_json->general->payment_method->enabled ? new PaytraceController() : '';
    }

    public function check_for_payments(&$order_payment, $order_payment_hash, $total_amount, $requestDataArray, &$header)
    {
        $response['success'] = 1;

        if ($this->active_theme_json->general->payment_method->enabled && isset($requestDataArray['card']) && $requestDataArray['card']) {
            $card_details = $requestDataArray['card'];

            if (strcmp($order_payment->payment_status, ConstantsController::PAYMENT_STATUS['done']) !== 0) {

                switch ($this->active_theme_json->general->payment_method->gateway) {
                    case 'paytrace':
                        $order_payment = $this->order_payment_model->updateOrCreate(
                            ['user_id' => Auth::user()->id, 'hash' => $order_payment_hash],
                            [
                                'payment_status' => ConstantsController::PAYMENT_STATUS['initiated']
                            ]
                        );

                        $payment_response = $this->paytrace->process_payment($card_details, $total_amount, (new Cart())->get_active_cart_customer(), $order_payment_hash);

                        if (
                            $payment_response &&
                            (
                                $payment_response['response_code'] != 101 ||
                                $payment_response['success'] == '' ||
                                !$payment_response['success']
                            )
                        ) {
                            $response['success'] = 0;
                            $response['msg'] = '<b style="color: red">Order cannot be placed due to the following reasons:</b> <br/><br/>' . $payment_response['status_message'];
                            $errorDetails = '';

                            if (
                                isset($payment_response['errors'])) {

                                foreach ($payment_response['errors'] as $error) {
                                    $errorDetails = "{$error[0]}<br/>";
                                }

                                $response['msg'] = '<b style="color: red">Order cannot be placed due to the following reasons:</b> <br/><br/><br/>' . $payment_response['status_message'] . ' <br/> <br/>' . $errorDetails;
                            }

                            $order_payment = $this->order_payment_model->updateOrCreate(
                                ['user_id' => Auth::user()->id, 'hash' => $order_payment_hash],
                                [
                                    'payment_response' => json_encode(['payment' => $payment_response]),
                                    'payment_status' => ConstantsController::PAYMENT_STATUS['failed']
                                ]
                            );
                        } else {
                            $client_response = ['msg' => 'Customer Already Exists'];

                            if (isset($card_details['save-update-card']) && $card_details['save-update-card'] != 'false') {

                                if (!isset($card_details['type']) && $card_details['type'] == 'new') {
                                    $client_response = $this->paytrace->create_customer_from_transaction($payment_response, (new Cart())->get_active_cart_customer(), $card_details);
                                } else {
                                    $client_response = $this->paytrace->update_customer((new Cart())->get_active_cart_customer(), $card_details);
                                }

                            }

                            $header['IsAdvancePayment'] = true;
                            $header['AdvancePaymentAmout'] = $total_amount;
                            $header['TransactionCode'] = $payment_response['transaction_id'];
                            $order_payment = $this->order_payment_model->updateOrCreate(
                                ['user_id' => Auth::user()->id, 'hash' => $order_payment_hash],
                                [
                                    'payment_response' => json_encode([
                                        'payment' => $payment_response,
                                        'client' => $client_response
                                    ]),
                                    'payment_status' => ConstantsController::PAYMENT_STATUS['done']
                                ]
                            );
                        }

                        // prr( ['payment_response' => $payment_response, 'customer_response' => $client_response] );
                        break;
                }

            }

        }

        return $response;

    }

    public function finalize_payment(&$order_payment, $order_payment_hash, $requestDataArray, $action)
    {

        if ($this->active_theme_json->general->payment_method->enabled && isset($requestDataArray['card']) && $requestDataArray['card']) {

            if (strcmp($order_payment->payment_status, ConstantsController::PAYMENT_STATUS['done']) === 0) {

                switch ($this->active_theme_json->general->payment_method->gateway) {
                    case 'paytrace':
                        $payment_response = json_decode($order_payment->payment_response, 1);
                        prr(['finalize_payment::payment_response' => $payment_response]);

                        if (isset($payment_response['payment']) && isset($payment_response['payment']['transaction_id'])) {
                            $payment_response['settlement'] = $this->paytrace->capture_or_void_transaction($payment_response['payment']['transaction_id'], $action);
                            $payment_data = [
                                'payment_response' => json_encode($payment_response)
                            ];

                            if ($payment_response['settlement']['success']) {
                                $payment_data['payment_status'] = ConstantsController::PAYMENT_STATUS['done'];
                            }

                            $order_payment = $this->order_payment_model->updateOrCreate(
                                ['user_id' => Auth::user()->id, 'hash' => $order_payment_hash],
                                $payment_data
                            );
                        }

                        break;
                }

            }

        }

    }

    public function get_country_states(Request $request)
    {
        return response()->json($this->ApiObj->Get_CountryStates($request->country));
    }

    public function index()
    {
        $customer_details = $shipping_options = $shippings = $shipping_addresses = $payment_terms_list = $customer_payment_options = $countries = $states = [];
        $default_ship_via_id = '';
        $countries = $this->ApiObj->Get_CountriesList();
        // $states = $this->ApiObj->Get_CountryStates( $country_id );
        if (Auth::user() && $this->cart_model->get_cart_for_front($this->ApiObj)['items'] && $this->cart_model->get_cart_for_front($this->ApiObj)['items'][0]) {
            $customer_details = $this->ApiObj->Get_CustomerDetail((new Cart())->get_active_cart_customer());
            $payment_terms_list = $this->ApiObj->Get_PaymentTermsList();
            $shippings = $this->ApiObj->Get_ShipViaList();
            if ($shippings && $shippings['Success'] == true) {
                $temp = [];
                foreach ($shippings['ShipVias'] as $shipping) {
                    $temp[$shipping['Description']] = $shipping;
                }
                ksort($temp);
                $shippings['ShipVias'] = $temp;
                $shippings = $shipping_options = $shippings['ShipVias'];
            }
            if (
                $this->active_theme_json->general->payment_method->enabled &&
                $this->active_theme_json->general->payment_method->gateway == 'paytrace'
            ) {
                $customer_payment_options = $this->paytrace->get_customer((new Cart())->get_active_cart_customer());
                prr(['customer_payment_options' => $customer_payment_options]);
            }
            if ($payment_terms_list && $payment_terms_list['Success']) {
                $payment_terms_list = $payment_terms_list['PaymentTerms'];
                foreach ($payment_terms_list as $payment_term) {

// Update keys to minimize working in blade
                    if (!in_array(md5($payment_term['Description']), $payment_terms_list)) {
                        $payment_terms_list[md5($payment_term['Description'])] = $payment_term;
                    }

                }

            }

            if ($customer_details && $customer_details['Success'] == true) {
                $shipping_addresses = $customer_details['CustomerDetail']['CustomerAddressDetail'];
                $default_ship_via_id = isset($customer_details['CustomerDetail']['CustomerAddressDetail']['CustomerShipVias'][0]) ? $customer_details['CustomerDetail']['CustomerAddressDetail']['CustomerShipVias'][0]['ShipViaID'] : '';
            }

        }

        // dd($countries);
        // dd($customer_details['CustomerDetail']['Country'],$customer_details['CustomerDetail']['State']);

        if (!isset($customer_details['CustomerDetail'])) {
            return redirect('/');
        }

        $this->append_breadcrumbs( 'Checkout', route( 'frontend.checkout' ) );
        return view( 'frontend.'.$this->active_theme->theme_abrv.'.checkout', [
            'countries'           => $countries,
            // 'states'              => $states,
            'cust_country'        => $customer_details['CustomerDetail']['Country'],
            'cust_state'          => $customer_details['CustomerDetail']['State'],
            'shipping_options'    => $shipping_options,
            'shippings'           => $shippings,
            'default_ship_via_id' => $default_ship_via_id,
            'shipping_addresses' => $shipping_addresses,
            'payment_terms_list' => $payment_terms_list,
            'customer_comment' => isset($customer_details['CustomerDetail']['Comment']) ? $customer_details['CustomerDetail']['Comment'] : '',
            'payment_term' => isset($customer_details['CustomerDetail']['PaymentTerm']) ? $customer_details['CustomerDetail']['PaymentTerm'] : '',
            'payment_options' => isset($customer_payment_options['customers']) && count($customer_payment_options['customers']) > 0 ? $customer_payment_options['customers'][0] : []
        ]);
    }

    public function place_order(Request $request)
    {
        try {
            $response = [
                'data' => [],
                'success' => 0,
                'msg' => 'Sorry something went wrong...'
            ];

            $payment_success = false;
            $itemDetail = [];
            $cartItems = [];
            $requestDataArray = $request->all();
            // dd($requestDataArray);
            $total_amount = 0;

            $headers = [
                'CustomerID' => (new Cart())->get_active_cart_customer(),
                'FirstName' => $requestDataArray['FirstName'],
                'LastName' => $requestDataArray['LastName'],
                'Email' => $requestDataArray['Email'],
                'Address1' => $requestDataArray['Address1'],
                'Address2' => $requestDataArray['Address2'],
                'City' => $requestDataArray['City'],
                'State' => $requestDataArray['State'],
                'Zip' => $requestDataArray['Zip'],
                'ShipViaCode' => $requestDataArray['shipping_method'],
                'OrderDate' => date("Y-m-d"),
                'AddressType' => 'RESIDENTIAL' // if dropship
            ];

            if (isset($requestDataArray['country'])) {
                $headers['Country'] = $requestDataArray['country'];
            }

            if (isset($requestDataArray['Country'])) {
                $headers['Country'] = $requestDataArray['Country'];
            }

            if (isset($requestDataArray['AddressID'])) {
                $headers['ShipToCode'] = $requestDataArray['AddressID'];
                $headers['AddressType'] = 'COMMERCIAL'; // if selected a given address
            }

            if (isset($requestDataArray['shipping_cost'])) {
                $headers['ShippingCost'] = $requestDataArray['shipping_cost'];
                $total_amount += $headers['ShippingCost'];
            }

            if (isset($requestDataArray['payment_term'])) {
                $headers['PaymentTerm'] = $requestDataArray['payment_term'];
            }

            if (isset($requestDataArray['reference_number'])) {
                $headers['CustomerPO'] = $requestDataArray['reference_number'];
            }

            if (isset($requestDataArray['shipping_instructions'])) {
                $headers['Instructions'] = $requestDataArray['shipping_instructions'];
            }

            if (isset($requestDataArray['ship_date'])) {
                $headers['ShipDate'] = date('Y-m-d', strtotime($requestDataArray['ship_date']));
                $headers['DeliveryTime'] = date('H:i:s', strtotime($requestDataArray['ship_date']));
            }

            $TempSalesOrderNo = "";
            if (isset($requestDataArray['item_broadloom']) && $requestDataArray['item_broadloom']) {
                $count = 0;
                foreach ($this->cart_model->get_cart_for_front($this->ApiObj)['items'] as $item) {
                    // dd($item);
                    $count++;
                    $item_data = json_decode(unserialize($item['item_data']));
//                    $item_data = json_decode($item['item_data']);
                    $cut_pieces = [];
                    $order_length = 0;
                    $line = 0;
                    $totalSQFT = 0;
                    $total_serging_charges = 0;

                    foreach ($item_data->CutPieces as $key => $cut_piece) {
                        // if ($cut_piece->LengthStatus !== "R") {
                        if ($cut_piece->LengthStatus == "F") {

                            $sqft =  round(($cut_piece->ATSWidth / 12) * ($cut_piece->ATSLength / 12), 2);
                            $totalSQFT += $sqft;

                            $temp = [];
                            $temp['TempSalesOrderNo'] = $cut_piece->TempSalesOrderNo;
                            $temp['ItemID'] = $cut_piece->ItemID;
                            $temp['RollID'] = $cut_piece->RollID;
                            $temp['CutPieceID'] = $cut_piece->CutPieceID;
                            $temp['ActualLength'] = $cut_piece->ATSLength;
                            $temp['ActualWidth'] = $cut_piece->ATSWidth;
                            $temp['ActualSQFT'] = $sqft;  // $cut_piece->ATSWidth * $cut_piece->ATSLength;
                            $temp['CutType'] = $item_data->cut_type;
                            $temp['LocationID'] = $item_data->location_id;
                            $temp['Serging'] = !empty($cut_piece->SergingType) ? "Y" : "N";
                          //  $temp['SergingCharges'] = !empty($cut_piece->SergingCharges) ? $cut_piece->SergingCharges : 0;
                            $temp['SergingCharges'] = (!empty($cut_piece->SergingCharges) && $temp['Serging'] == "Y") ? $cut_piece->SergingCharges : 0;
                            $temp['SergingType'] = empty($cut_piece->SergingType) ? "0" : $cut_piece->SergingType;
                            $temp['LineNo'] = ++$line;
                            $temp['UserRemarks'] = $cut_piece->UserRemarks;

                            $order_length += $cut_piece->ATSLength;
                            $total_serging_charges += $temp['SergingCharges'] * ((round($cut_piece->ATSWidth / 12, 2) + round($cut_piece->ATSLength / 12, 2)) * 2);
                            $cut_pieces[] = $temp;
                        }
                    }
                    // dump($item);
                    // dd('end');

                    $itemDetail[] = [
                        'ItemID' => $item['item_id'],
                        'OrderQty' => $item['item_quantity'],
                        'UnitPrice' => $item['item_price'],
                        'SQFTPrice' => $item_data->SQFTPrice,
                        // 'SQFTArea' => $totalSQFT,
                        'SQFTArea'   => $item['sqft_area'],
                        'CutPieceID' => $item_data->CutPieceID,
                        'RollID' => $item['bd_roll_id'],//$item_data->RollID,
                        'SergingCharges' => $total_serging_charges,
                        'SergingType' => null,
                        'Line_No' => $count,
                        "Discount" => 0,
                        "WHSID" => null,
                        "Remarks" => null,
                        "UserRemarks" => $item['user_remarks'],
                        "ETA_Date" => "\/Date(-62135596800000)\/",
                        'OrderLength' => $order_length,
                        "CFA" => $item['cfa'] == 1 ? "Y" : "N",
                        'ParentLine_NO' => $item['is_bd_child'] == 1 ?  ($count - 1) : '',
                        "IsRemnantShipable" => $item['remnant_shipable'] == 1 ? "Y" : "N",
                        'CutPieces' => $cut_pieces,
                        'MarkFor' => isset($requestDataArray['sidemark']) && isset($requestDataArray['sidemark'][$item['item_id']]) ? $requestDataArray['sidemark'][$item['item_id']] : '',
                        'SKU'       => $item['oak_sku']];

                    $cartItems[] = [
                        'Image' => str_replace(' ', '%20', $item['item_image']),
                        'ItemID' => $item['item_id'],
                        'Color' => $item['item_color'],
                        'Size' => $item['item_size'],
                        'OrderQty' => $item['item_quantity'],
                        'UnitPrice' => $item['item_price'],
                        'SubTotal' => $item['item_total'],
                        'MarkFor' => isset($requestDataArray['sidemark']) && isset($requestDataArray['sidemark'][$item['item_id']]) ? $requestDataArray['sidemark'][$item['item_id']] : ''

                    ];

                    $total_amount += $item['item_price'];
                }
                // $headers['Line_No'] = $count;
            } else {
                foreach ($this->cart_model->get_cart_for_front($this->ApiObj)['items'] as $item) {
                    array_push($itemDetail, [
                        'ItemID' => $item['item_id'],
                        'OrderQty' => $item['item_quantity'],
                        'UnitPrice' => $item['item_price'],
                        'MarkFor' => isset($requestDataArray['sidemark']) && isset($requestDataArray['sidemark'][$item['item_id']]) ? $requestDataArray['sidemark'][$item['item_id']] : '',
                        'SKU'       => $item['oak_sku']
                    ]);
                    $total_amount += $item['item_price'];
                }
            }


            $order_payment_hash = md5(json_encode(['general' => $headers, 'items' => $itemDetail]));
            $headers['IsAdvancePayment'] = false;
            $headers['AdvancePaymentAmout'] = 0;
            $headers['TransactionCode'] = '';

            $order_payment = $this->order_payment_model->updateOrCreate(
                ['user_id' => Auth::user()->id, 'hash' => $order_payment_hash],
                [
                    'user_id' => Auth::user()->id,
                    'hash' => $order_payment_hash,
                    'order_data' => serialize([$headers, $itemDetail]),
                    'order_status' => ConstantsController::ORDER_STATUS['not-applicable']
                ]
            );

            $payment_response = $this->check_for_payments($order_payment, $order_payment_hash, $total_amount, $requestDataArray, $headers);

            if (!$payment_response['success']) {
                return response()->json($payment_response);
            }
          //  dd($headers, $itemDetail);
            prr(" :: Place Order API CALL DATA :: ");
            prr($headers, $itemDetail);
            //dd($headers, $itemDetail);
            if (isset($requestDataArray['item_broadloom']) && $requestDataArray['item_broadloom'] == 1) {
                $result = $this->ApiObj->Place_BLOrder(
                    $headers,
                    $itemDetail
                );
            } else {
                $result = $this->ApiObj->Place_Order(
                    $headers,
                    $itemDetail
                );
            }
            prr(" :: Place Order API CALL RESULT :: ");
            prr($result);
            // dd($result);

            $order_payment = $this->order_payment_model->updateOrCreate(
                ['user_id' => Auth::user()->id, 'hash' => $order_payment_hash],
                [
                    'order_response' => json_encode($result),
                    'order_status' => ConstantsController::ORDER_STATUS['processed']
                ]
            );

            if (isset($result['Success']) && $result['Success']) {
                $this->finalize_payment($order_payment, $order_payment_hash, $requestDataArray, 'capture');
                $order_payment = $this->order_payment_model->updateOrCreate(
                    ['user_id' => Auth::user()->id, 'hash' => $order_payment_hash],
                    [
                        'hash' => md5(json_encode(['general' => $headers, 'items' => $itemDetail, 'status' => ConstantsController::ORDER_STATUS['done']])), // update this hash to avoid any repeating order to be caught by this
                        'order_status' => ConstantsController::ORDER_STATUS['done']
                    ]
                );

                $this->cart_model->remove_cart_item(Auth::user()->id, (new Cart())->get_active_cart_customer(), 0, 0, '', '', true);
                $successMsg = $result['Message'];
                preg_match("/\[[^\]]*\]/", $successMsg, $matches);
                $matched_string = $matches[0];
                $updatedString = '<span>' . $matched_string . '</span>';
                $successMsg = str_replace($matched_string, $updatedString, $successMsg);
                $response['success'] = 1;
                $response['webhook'] = 0;
                $response['msg'] = $successMsg;

                // if ( isset($this->active_theme_json->general->order_ack) && $this->active_theme_json->general->order_ack ) {
                // $headers['ShippingCost'] = number_format( $requestDataArray['shipping_cost'], ConstantsController::ALLOWED_DECIMALS, '.', ',' );
                $cart_data = [
                    'shipping' => $headers,
                    'items' => $cartItems,
                    'total' => number_format($total_amount, ConstantsController::ALLOWED_DECIMALS, '.', ',')
                ];

                if (array_key_exists('Instructions', $cart_data['shipping']) == false) {
                    $cart_data['shipping']['Instructions'] = $request->shipping_instructions;
                }

                $cart_data['shipping']['SO_Number'] = $result['ObjectID'];

                try {

                    if (!empty($cart_data['shipping']['Email'])) {
                           $cart_data_email = $cart_data['shipping']['Email'];
                           SendMail::dispatch( [
                               'data'     => $cart_data,
                               'slug'     => "Order Confirmed",
                               'email'    => [$cart_data_email, 'techbugs06@gmail.com'],
                               'template' => 'email.order-confirmation',
                                //'cc_email' => Auth::user()->is_sale_rep ? (isset(Auth::user()->email) ? Auth::user()->email : '') : ''
                           ] );
                           prr($cart_data['shipping']['Email']);
                           prr(" :: Shipping Email Sent :: ");
                    }

                       SendMail::dispatch( [
                           'data'     => $cart_data,
                           'slug'     => "Order Confirmed Official",
                           'email'    => ConstantsController::ORDER_NOTIFICATION,
                           'template' => 'email.order-confirmation',
                           'cc_email' => Auth::user()->is_sale_rep ? (isset(Auth::user()->email) ? Auth::user()->email : '') : ''
                       ] );

                    prr(" :: Order Acknowledgment Email Sent :: ");
                } catch (\Exception $e) {
                    prr("Order Acknowledgment Email Exception :: " . $e->getMessage());
                }
                // }
            } else {
                $order_payment = $this->order_payment_model->updateOrCreate(
                    ['user_id' => Auth::user()->id, 'hash' => $order_payment_hash],
                    [
                        'order_status' => ConstantsController::ORDER_STATUS['failed']
                    ]
                );

                if ((isset($result['Exception']) && $result['Exception']) || (isset($result['ObjectID']) && $result['ObjectID'] >= 900)) {
                    // $this->cart_model->remove_cart_item(Auth::user()->id, (new Cart())->get_active_cart_customer(), 0, 0, '', true);
                    // $response['success'] = 1;
                    // $response['msg'] = 'You order is processed and you will get the confirmation soon. <br> Your order reference is: ' . $order_payment_hash;

                    try {
                        $order_data = [
                            'hash' => $order_payment_hash,
                            'order-detail' => serialize([$headers, $itemDetail])
                        ];

                       SendMail::dispatch( [
                           'data'     => $order_data,
                           'slug'     => 'Web Hook Order',
                           'email'    => ConstantsController::WEB_HOOK_EMAIL,
                           'template' => 'email.web_hook_email'
                       ] );
                        prr(" :: WEB_HOOK Email Sent :: ");
                        $this->cart_model->remove_cart_item(Auth::user()->id, (new Cart())->get_active_cart_customer(), 0, 0, '', true);
                        $response['success'] = 1;
                        $response['webhook'] = 1;
                        $response['msg'] = 'You order is processed and you will get the confirmation soon. <br> Your order reference is: ' . $order_payment_hash . '</br>';

                    } catch (\Exception $e) {
                        prr("Mail Exception: " . $e->getMessage());
                    }
                } else {
                    $this->finalize_payment($order_payment, $order_payment_hash, $requestDataArray, 'void');
                    $errorMsg = $result['Message'];

                    if ($result['ErrorDetail']) {

                        if (is_array($result['ErrorDetail'])) {
                            $errorDetails = '';

                            foreach ($result['ErrorDetail'] as $errorDetail) {
                                $errorDetails .= $errorDetail['ErrorDescription'] . '<br>';
                            }

                            if ($errorDetails) {
                                $errorMsg = '<b style="color: red">' . $errorMsg . '</b> <br/><br/>Following are the details: <br/>' . $errorDetails;
                            }

                        } else {
                            $errorMsg = $errorMsg . ' [' . $result['ErrorDetail']['ErrorDescription'] . ']';
                        }

                    }

                    $response['success'] = 0;
                    $response['msg'] = $errorMsg;
                }

            }

            // TODO : we have a btter way of doing this - Adil needs to look into this

            return response()->json($response);

        } catch (\Exception$e) {
            prr(['CheckoutController::EXCEPTION' => $e->getMessage()]);

            return response()->json(['success' => 0, 'msg' => 'Something went wrong, please try again.', 'error' => $e->getMessage()]);
        } catch (\Error$e) {
            prr(['CheckoutController::ERROR' => $e->getMessage()]);

            return response()->json(['success' => 0, 'msg' => 'Something went wrong, please try again.', 'error' => $e->getMessage()]);
        }

    }

    public function pt_security()
    {
        die(file_exists(storage_path('app/storage/pem_file/security.pem')) ? file_get_contents(storage_path('app/storage/pem_file/security.pem')) : '');
    }

    public function shipping_rate(Request $request)
    {
        $request = $request->all();
        $shipping_method = $request['shipping_method'];
        $details = [];
        $customer_id = '';

        foreach ($this->cart_model->get_cart_for_front($this->ApiObj)['items'] as $item) {
            $customer_id = $item['item_customer_id'];
            array_push($details, [
                'ItemID' => $item['item_id'],
                'Qty' => $item['item_quantity'],
                'Price' => $item['item_price']
            ]);
        }

        $shipping_rate_response = $this->ApiObj->Get_Shipping_Rates($customer_id, $shipping_method, $details);
        prr(['$shipping_rate_response' => $shipping_rate_response]);

        if ($shipping_rate_response['Success'] == 1) {
            $this->jsonResponseArray['data'] = $shipping_rate_response['ShippingRates'][0]['Rate'];
            $this->jsonResponseArray['success'] = 1;
        } else {
            $this->jsonResponseArray['msg'] = $shipping_rate_response['Message'];
            $this->jsonResponseArray['success'] = 0;
        }

        // TODO : we have a btter way of doing this - Adil needs to look into this

        return response()->json($this->jsonResponseArray);
    }
}
