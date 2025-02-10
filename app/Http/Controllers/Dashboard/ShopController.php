<?php

namespace App\Http\Controllers\Dashboard;

use View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\Frontend\ItemController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Models\Cart;
use App\Models\OrderPayments;
use App\Http\Controllers\PaytraceController;
use App\Http\Controllers\ConstantsController;
use App\Jobs\SendMail;

class ShopController extends DashboardController
{
    public function __construct()
    {
        parent::__construct();
        $this->cart_model = new Cart();
        $this->order_payment_model = new OrderPayments();
        $this->paytrace = $this->active_theme_json->general->payment_method->enabled ? new PaytraceController() : '';
    }

    public function express_order( Request $request )
    {
        if ( count( $request->all() ) > 0 )
        {
            $meta_array   = $this->convert_form_data_into_array( array( 'ItemID' => $request->ItemID, 'OrderQty' => $request->OrderQty, 'UnitPrice' => $request->UnitPrice ) );
            $place_orders = $this->ApiObj->Place_Order( $request->customer_id, $request->customer_po, $request->order_date, $request->shipment_id, $request->first_name, $request->last_name, $request->email, $request->address1, $request->address2, $request->city, $request->state, $request->postal_code, '', $meta_array );

            $table = array( 'thead' => [
                'success_response' => 'Success',
                'Message'          => 'Message'
            ], 'tbody' => [] );

            if ( isset( $place_orders['Message'] ) )
            {
                $table['tbody'][] = [
                    'success_response' => $place_orders['Success'],
                    'Message'          => $place_orders['Message']

                ];
            }

            View::share( 'place_orders', $place_orders );
            View::share( 'table', $table );
        }

        return view( 'dashboard.ExpressOrder' );
    }

    public function get_additional_filters( Request $request )
    {

        if ( $request->all() )
        {
        /*
        if ( $request->FilterType && $request->Customer && $request->ProductType && $request->Collection && $request->Design && $request->Color && $request->Size )
        {
        $response = $this->ApiObj->Get_ItemsDetail( $request->Customer, $request->ProductType, $request->Collection, $request->Design, $request->Color, $request->Size );
        }
        else
        {
        $response = $this->ApiObj->Get_OrderInquiryData( $request->FilterType, $request->ProductType, $request->Collection, $request->Design, $request->Color, $request->Size );
        }
        */
            if ( isset($request->FilterType) && isset($request->Customer) && isset($request->Category) && isset($request->Collection) && isset($request->Design) && isset($request->Color) && isset($request->Size))
            {
                $response = $this->ApiObj->Get_ItemsPlaceOrderDetail( $request->Customer, $request->Category, $request->SubCategory, $request->Collection, $request->Design, $request->Color, $request->Size );
            }
            else
            {
                $response = $this->ApiObj->Get_B2BOrderInquiryData( $request->FilterType, $request->Category, $request->SubCategory, $request->Collection, $request->Design, $request->Color, $request->Size );
            }

            return response()->json(
                array(
                    'success'  => 1,
                    'response' => $response['OutPut']
                ), 200 );
        }

    }

    public function get_customer_addresses( Request $request )
    {

        if ( isset( $request->customer ) && $request->customer )
        {
            $shipping_addresses = $this->ApiObj->Get_CustomerAddresses( $request->customer );

            if ( $shipping_addresses && $shipping_addresses['Success'] == true )
            {
                $shipping_addresses = $shipping_addresses['CustomerAddress'];

                return response()->json(
                    array(
                        'success'   => 1,
                        'addresses' => $shipping_addresses
                    ), 200 );
            }

        }

        return response()->json(
            array(
                'success'   => 0,
                'addresses' => []
            ), 200 );

    }

    public function init_return( Request $request )
    {

        if ( count( $request->all() ) > 0 )
        {
            $meta_array    = $this->convert_form_data_into_array( array( 'SalesInvoiceNo' => $request->SalesInvoiceNo, 'ItemID' => $request->ItemID, 'Reason' => $request->Reason, 'ReturnQuantity' => $request->ReturnQuantity, 'LineNo' => $request->LineNo ) );
            $rmas_generate = $this->ApiObj->Get_RMA_Generated( $request->customer_id, $request->requested_by, $meta_array );
            prr( $rmas_generate );

            if ( isset( $rmas_generate['Success'] ) && $rmas_generate['Success'] )
            {
                return redirect()->back()->withInput()->with( 'message', ['type' => 'success', 'body' => isset( $rmas_generate['Message'] ) && $rmas_generate['Message'] ? $rmas_generate['Message'] : 'Operation Successfull!'] );
            }
            else
            {
                return redirect()->back()->withInput()->with( 'message', ['type' => 'danger', 'body' => isset( $rmas_generate['Message'] ) && $rmas_generate['Message'] ? $rmas_generate['Message'] : 'Something went wrong, please try again.'] );
            }

            $table = array( 'thead' => [
                'rma_number'       => 'Rma Number',
                'success_response' => 'Success',
                'Message'          => 'Message'
            ], 'tbody' => [] );

            if ( isset( $rmas_generate['Message'] ) )
            {
                $table['tbody'][] = [
                    'rma_number'       => $rmas_generate['RMANo'],
                    'success_response' => $rmas_generate['Success'],
                    'Message'          => $rmas_generate['Message']
                ];
            }

            View::share( 'rmas_generate', $rmas_generate );
            View::share( 'table', $table );
        }

        $reasons = $this->ApiObj->Get_ReturnReasonList();
        View::share( 'reasons', $reasons ? $reasons['ReturnReasons'] : [] );
        View::share( 'customers', Auth::user()->is_customer ? [[
            'value' => Auth::user()->customer_id, 'label' => Auth::user()->customer_id
        ]] : $this->get_customers_dropdown_options( 0 ) );

        return view( 'dashboard.init-return' );
    }

    public function place_order( Request $request )
    {
        if ( count( $request->all() ) > 0 )
        {
            $meta_array = $this->convert_form_data_into_array(
                array( 'ItemID' => $request->ItemID, 'OrderQty' => $request->OrderQty, 'MarkFor' => $request->MarkFor ) // , 'UnitPrice' => $request->UnitPrice )
            );

            $shipping_cost = 0;
            $details       = [];

            $item_prices = $this->ApiObj->Get_GetMultipleItemsPrices( $request->customer_id, join( ',', $request->ItemID ) );

            $hasBroadloom = false;
            $hasOther = false;
            $isError = false;
            $isBDitem = false;
            foreach ($item_prices['ItemPrices'] as $key => $item) {
                if ($item['ItemType'] == 'R') {
                    $hasBroadloom = true;
                } else {
                    $hasOther = true;
                }

                if ($hasBroadloom && $hasOther) {
                    $isError = true;
                    break;
                }

                if($hasBroadloom){
                    $isBDitem = true;
                    break;
                }
            }
            if($isError){
                return redirect()->back()->withInput()->with( 'message', ['type' => 'danger', 'body' => '<b>Broadloom item and Rug item</b> orders are not placed at same time'] );
            }
            if($isBDitem){
                return redirect()->back()->withInput()->with( 'message', ['type' => 'danger', 'body' => '<b>Broadloom item</b> orders are not placed from dashboard'] );
            }

            if ( $item_prices['Success'] )
            {

                if ( count( $item_prices['ItemPrices'] ) != count( array_unique( $request->ItemID ) ) )
                {
                    $item_ids = [];

                    foreach ( $meta_array as $index )
                    {
                        $item_found = false;

                        foreach ( $item_prices['ItemPrices'] as $item )
                        {

                            if ( $index['ItemID'] == $item['ItemID'] )
                            {
                                $item_found = true;
                                break;
                            }

                        }

                        if ( ! $item_found )
                        {
                            $item_ids[] = $index['ItemID'];
                        }

                    }

                    if ( ! $item_ids )
                    {
                        $duplicate_items = [];

                        foreach ( $request->ItemID as $k => $item_id )
                        {
                            $key = md5( json_encode( [$item_id, $request->MarkFor[$k]] ) );

                            foreach ( $item_prices['ItemPrices'] as $item )
                            {

                                if ( $item_id == $item['ItemID'] )
                                {

                                    if ( array_key_exists( $key, $duplicate_items ) )
                                    {
                                        $duplicate_items[$key]++;
                                    }
                                    else
                                    {
                                        $duplicate_items[$key] = 1;
                                    }

                                    if ( $duplicate_items[$key] > 1 && ! in_array( $item_id, $item_ids ) )
                                    {
                                        $item_ids[] = $item_id;
                                    }

                                    break;
                                }

                            }

                        }

                        if ( $item_ids )
                        {
                            return redirect()->back()->withInput()->with( 'message', ['type' => 'danger', 'body' => 'Following item ids are repeated/duplicate : <br> <br> Item ID : '.join( '<br> Item ID : ', $item_ids ).' <br/> <br/> Please if you can cross check these and try again.'] );
                        }
                        else
                        {
                            return redirect()->back()->withInput()->with( 'message', ['type' => 'danger', 'body' => 'There seems to be an issue with the data, please if you can cross check these and try again.'] );
                        }

                    }
                    else
                    {
                        return redirect()->back()->withInput()->with( 'message', ['type' => 'danger', 'body' => 'Following item ids seems to be invalid since we were unable to fetch their prices : <br> <br> Item ID : '.join( '<br> Item ID : ', $item_ids ).' <br/> <br/> Please if you can cross check these and try again.'] );
                    }

                }
                else
                {
                    $allowed_item_quantities = [];
                    $non_web_rug_items       = [];
                    $item_quantities         = [];

                    foreach ( $request->ItemID as $k => $item_id )
                    {
                        $item_quantities[$item_id] = $request->OrderQty[$k];
                    }

                    foreach ( $item_prices['ItemPrices'] as $item )
                    {
                        $item_ASTQs[] = $item['ATSQ'];

                        foreach ( $meta_array as &$index )
                        {

                            if ( $index['ItemID'] == $item['ItemID'] )
                            {
                                $index['UnitPrice'] = $item['ItemPrice'];

                                if (
                                    (
                                        CommonController::check_bit_field( $item, 'Discontinued' ) ||
                                        CommonController::check_bit_field( $item, 'SpecialBuy' ) ||
                                        CommonController::check_bit_field( $item, 'Reviewed' )
                                    ) && ( isset( $item['ATSQ'] ) && $item['ATSQ'] < $item_quantities[$item['ItemID']] ) )
                                {
                                    $allowed_item_quantities[] = "{$item['ItemID']} has only {$item['ATSQ']} units available";
                                }

                                // break;
                            }

                        }

                        if ( ! CommonController::check_bit_field( $item, 'WEBRug' ) )
                        {
                            $non_web_rug_items[] = $item['ItemID'];
                        }

                        array_push( $details, [
                            'ItemID' => $item['ItemID'],
                            'Qty'    => $item_quantities[$item['ItemID']],
                            'Price'  => $item['ItemPrice']
                        ] );

                    }

// when WEBRug is False then show message.
                    if ( $non_web_rug_items )
                    {
                        return redirect()->back()->withInput()->with( 'message', ['type' => 'danger', 'body' => 'Following Items: <br><br> Item ID : '.join( '<br> Item ID : ', $non_web_rug_items ).' <br> <br> are not marked as webrugs please make the correction before proceeding ahead.'] );
                    }

                    if ( $allowed_item_quantities )
                    {
                        return redirect()->back()->withInput()->with( 'message', ['type' => 'danger', 'body' => join( '<br>', $allowed_item_quantities ).' <br> <br> Please order the correct quantities.'] );
                    }

                    $shipping_rate_response = $this->ApiObj->Get_Shipping_Rates( $request->customer_id, $request->ship_via_id, $details );
                    if ( $shipping_rate_response['Success'] == 1 )
                    {
                        $shipping_cost = $shipping_rate_response['ShippingRates'][0]['Rate'];
                    }

                }

            }
            else
            {
                return redirect()->back()->withInput()->with( 'message', ['type' => 'danger', 'body' => $item_prices['Message'].'<br>'.join( '<br>', array_unique( $request->ItemID ) )] );
            }

            $headers = [
                'CustomerID'   => $request->customer_id,
                'CustomerPO'   => $request->customer_po,
                'OrderDate'    => date( "Y-m-d", strtotime( $request->order_date ) ),
                'FirstName'    => $request->first_name,
                'LastName'     => $request->last_name,
                'Email'        => $request->email,
                'Address1'     => $request->address1,
                'Address2'     => $request->address2,
                'City'         => $request->city,
                'Country'      => $request->country,
                'State'        => $request->state,
                'Zip'          => $request->postal_code,
                'ShipViaCode'  => $request->ship_via_id,
                'ShippingCost' => $shipping_cost,
                'AddressType'  => 'RESIDENTIAL' // if dropship
            ];

            if ( isset( $request->address_id ) && $request->address_id )
            {
                $headers['ShipToCode']  = $request->address_id;
                $headers['AddressType'] = 'COMMERCIAL'; // if selected a given address
            }

            if ( isset( $request->req_ship_date ) && $request->req_ship_date )
            {
                $headers['ShipDate']     = date( 'Y-m-d', strtotime( $request->req_ship_date ) );
                $headers['DeliveryTime'] = date( 'H:i:s', strtotime( $request->req_ship_date ) );
            }

            $place_orders = $this->ApiObj->Place_Order(
                $headers,
                $meta_array
            );

            $table = array( 'thead' => [
                'Message' => 'Message'
            ], 'tbody' => [] );

            if ( isset( $place_orders['Message'] ) )
            {
                $message = $place_orders['Message'];

                if ( isset( $place_orders['ErrorDetail'] ) && $place_orders['ErrorDetail'] )
                {
                    $message .= ", following are the details:";

                    foreach ( $place_orders['ErrorDetail'] as $i => $error )
                    {
                        $message .= "<br/>".( $i + 1 ).": {$error['ErrorDescription']}<br/>";
                    }

                }

                $table['tbody'][] = [
                    'Message' => $message
                ];
            }

            if ( isset( $place_orders['Success'] ) && $place_orders['Success'] )
            {
                return redirect()->back()->with( 'message', ['type' => 'success', 'body' => $message] );
            }
            else
            {
                return redirect()->back()->withInput()->with( 'message', ['type' => 'danger', 'body' => $message] );
            }

            View::share( 'place_orders', $place_orders );
            View::share( 'table', $table );

        }

        $shippings = $this->ApiObj->Get_ShipViaList();

        if ( $shippings )
        {
            $temp = [];

            foreach ( $shippings['ShipVias'] as $shipping )
            {
                $temp[$shipping['Description']] = $shipping;
            }

            ksort( $temp );
            $shippings['ShipVias'] = $temp;
        }

        $ship_vias = [];

        if ( $shippings && isset( $shippings['ShipVias'] ) )
        {

            foreach ( $shippings['ShipVias'] as $ship_via )
            {
                $ship_vias[] = [
                    'value' => $ship_via['ShipViaID'],
                    // 'label' => $ship_via['ShipViaID']." - ".$ship_via['Description']
                    'label' => $ship_via['Description']
                ];
            }

        }

        $default_ship_via_id = '';

        if ( $request->ship_via_id || old( 'ship_via_id' ) )
        {
            $default_ship_via_id = $request->ship_via_id || old( 'ship_via_id' );
        }
        else
        {
            $shipping_options = $this->ApiObj->Get_Shipping_Options( Auth::user()->customer_id );

            if ( $shipping_options && $shipping_options['Success'] == true )
            {
                $default_ship_via_id = $shipping_options['CustomerShipVias'][0]['ShipViaID'];
            }

        }

        $countries = $states = [];
        /*
        $countries  = $this->ApiObj->Get_CountriesList();
        $country_id = 0;

        foreach ( $countries['Countries'] as $country )
        {

        if ( $country['OriginCode'] == 'US' )
        {
        $country_id = $country['CountryNo'];
        break;
        }

        }
        $states = $this->ApiObj->Get_CountryStates( $country_id );
         */
        View::share( 'countries', $countries );
        View::share( 'states', $states );

        $filters = [
            [
                'title'       => 'Customer ID',
                'type'        => Auth::user()->is_customer ? 'hidden' : 'select',
                'options'     => $this->get_customers_dropdown_options( 0 ),
                'placeholder' => '',
                'value'       => $request->customer_id ? $request->customer_id : Auth::user()->customer_id
            ],
            [
                'title'       => 'Customer PO',
                'type'        => 'text',
                'placeholder' => '',
                'attribues'   => ' data-required="true" maxlength="250" ',
                'value'       => $request->customer_po
            ],
            [
                'title'       => 'Order Date',
                'type'        => 'text',
                'placeholder' => '',
                'attribues'   => ' data-required="true" readonly="readonly"',
                'value'       => $request->order_date ? CommonController::get_date_format( $request->order_date ) : CommonController::get_date_format( date( 'Y-m-d' ) )
            ],
            [
                'title'       => 'Req Ship Date',
                'type'        => 'date',
                'class'       => 'datepicker',
                'placeholder' => '',
                'attribues'   => ' data-required="true" ',
                'value'       => $request->req_ship_date ? CommonController::get_date_format( $request->req_ship_date ) : CommonController::get_date_format( date( 'Y-m-d' ) )
            ],
            [
                'title'       => 'Ship Via ID',
                'type'        => 'select',
                'placeholder' => 'Ship Via',
                'attribues'   => ' data-required="true" ',
                'options'     => $ship_vias,
                'value'       => $default_ship_via_id
            ]
        ];

        View::share( 'datepicker_dates', ['min' => 'today', 'max' => '+1m'] );
        View::share( 'filters', $filters );

        return view( 'dashboard.place-order' );

    }

    public function view_order( Request $request )
    {

        if ( count( $request->all() ) > 0 )
        {
            $view_orders = $this->ApiObj->View_Order( $request->customer_id, $request->external_id, $request->date_from, $request->date_to, $request->sales_rep );
            prr( $view_orders );

            $table = array( 'thead' => [
                'order_no'     => 'RMA Number',
                'customer_id'  => 'Customer ID',
                'customer_po'  => 'Customer PO',
                'total_Amount' => 'Amount',
                'status'       => 'Status',
                'order_date'   => 'Order Date',
                'actions'      => 'Actions'
            ], 'tbody' => [] );

            if ( isset( $view_orders['Orders'] ) )
            {

                foreach ( $view_orders['Orders'] as $view_order )
                {
                    $table['tbody'][] = [
                        'order_no'     => $view_order['Header']['OrderNo'],
                        'customer_id'  => $view_order['Header']['CustomerID'],
                        'customer_po'  => $view_order['Header']['CustomerPO'],
                        'total_Amount' => $view_order['Header']['TotalAmount'],
                        'status'       => $view_order['Header']['Status'],
                        'order_date'   => isset( $view_order['Header']['OrderDate'] ) ? $view_order['Header']['OrderDate'] : 'N/A',
                        'actions'      => [['type' => 'modal', 'label' => 'View Details']],
                        'details'      => [
                            'heading' => $view_order['Header']['OrderNo'].' : '.$view_order['Header']['CustomerID'],
                            'body'    => [
                                'sections' => [
                                    [
                                        'title'   => 'Detail',
                                        'content' => $view_order['Detail']
                                    ]
                                ]
                            ]
                        ]
                    ];
                }

            }

            View::share( 'view_orders', $view_orders );
            View::share( 'table', $table );

        }

        $filters = [
            [
                'title'       => 'From Date',
                'type'        => 'date',
                'attribues'   => ' data-required="true" ',
                'placeholder' => '',
                'value'       => ''
            ],
            [
                'title'       => 'To Date',
                'type'        => 'date',
                'attribues'   => ' data-required="true" ',
                'placeholder' => '',
                'value'       => ''
            ],
            [
                'title'       => 'Customer',
                'type'        => Auth::user()->is_customer ? 'hidden' : 'select',
                'options'     => $this->get_customers_dropdown_options(),
                'placeholder' => '',
                'value'       => Auth::user()->customer_id
            ],
            [
                'title'       => 'External Number',
                'type'        => 'text',
                'placeholder' => '',
                'value'       => ''
            ]
        ];

        View::share( 'filters', $filters );

        return view( 'dashboard.generic-report' );

    }

    public function place_bl_order( Request $request ){
        $shippings = $this->ApiObj->Get_ShipViaList();
        if ( $shippings )
        {
            $temp = [];
            foreach ( $shippings['ShipVias'] as $shipping )
            {
                $temp[$shipping['Description']] = $shipping;
            }
            ksort( $temp );
            $shippings['ShipVias'] = $temp;
        }

        $ship_vias = [];
        if ( $shippings && isset( $shippings['ShipVias'] ) )
        {
            foreach ( $shippings['ShipVias'] as $ship_via )
            {
                $ship_vias[] = [
                    'value' => $ship_via['ShipViaID'],
                    'label' => $ship_via['Description']
                ];
            }
        }

        $default_ship_via_id = '';
        if ( $request->ship_via_id || old( 'ship_via_id' ) )
        {
            $default_ship_via_id = $request->ship_via_id || old( 'ship_via_id' );
        }
        else
        {
            $shipping_options = $this->ApiObj->Get_Shipping_Options( Auth::user()->customer_id );
            if ( $shipping_options && $shipping_options['Success'] == true )
            {
                $default_ship_via_id = $shipping_options['CustomerShipVias'][0]['ShipViaID'];
            }

        }

        $countries = $states = [];
        $itemIds =  $this->ApiObj->Get_AllBLItemsForOrderPlace();
        $surging_types = $this->ApiObj->Get_SurgingTypes();

        $filters = [
            [
                'title'       => 'Customer ID',
                'type'        => Auth::user()->is_customer ? 'hidden' : 'select',
                'options'     => $this->get_broadloom_customers_dropdown_options(0),
                'placeholder' => '',
                'value'       => $request->customer_id ? $request->customer_id : Auth::user()->customer_id
            ],
            [
                'title'       => 'Customer PO',
                'type'        => 'text',
                'placeholder' => '',
                'attribues'   => ' data-required="true" maxlength="250" ',
                'value'       => $request->customer_po
            ],
            [
                'title'       => 'Order Date',
                'type'        => 'text',
                'placeholder' => '',
                'attribues'   => ' data-required="true" readonly="readonly"',
                'value'       => $request->order_date ? CommonController::get_date_format( $request->order_date ) : CommonController::get_date_format( date( 'Y-m-d' ) )
            ],
            [
                'title'       => 'Req Ship Date',
                'type'        => 'date',
                'class'       => 'datepicker',
                'placeholder' => '',
                'attribues'   => ' data-required="true" ',
                'value'       => $request->req_ship_date ? CommonController::get_date_format( $request->req_ship_date ) : CommonController::get_date_format( date( 'Y-m-d' ) )
            ],
            [
                'title'       => 'Ship Via ID',
                'type'        => 'select',
                'placeholder' => 'Ship Via',
                'attribues'   => ' data-required="true" ',
                'options'     => $ship_vias,
                'value'       => $default_ship_via_id
            ]
        ];

        View::share( 'datepicker_dates', ['min' => 'today', 'max' => '+1m'] );
        View::share( 'filters', $filters );
        View::share( 'itemIds', $itemIds['OutPut']['BLItemsList']);
        View::share( 'surging_types', $surging_types['OutPut']['SurgingTypesList']);
        View::share( 'countries', $countries );
        View::share( 'states', $states );

        return view( 'dashboard.place-bl-order' );

    }

    public function fetch_item_roll_data(Request $request){
        $roll_pieces = $this->ApiObj->Get_ItemsRollAndCutPieceList($request->item_id);
        return response()->json([
            'data' => $roll_pieces['OutPut']['RollsAndCutPieces']
        ]);
    }

    public function fetch_item_id_data(Request $request){
        $itemController = new ItemController();
        $items = $itemController->generate_color_name($itemController->generate_image_urls($this->ApiObj->Get_Items('', '', $request->item_id, '', '', '', '', '', '', '', '', '', '', '' )));
        return response()->json([
            'item_json' => json_encode($items['Items'][0]),
            'rug_pad' => $items['Items'][0]['ULTPad']
        ]);
    }


    public function place_bl_order_post(Request $request)
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
            $total_amount = 0;
            $requestDataArray = $request->all();
            $quoteCartData =  json_decode($requestDataArray['quoteCartData'], true );

            $headers = [
                'CustomerID' => !empty($quoteCartData) ? $quoteCartData[0]['item_customer_id'] : (new Cart())->get_active_cart_customer(),
                'FirstName' => $requestDataArray['first_name'],
                'LastName' => $requestDataArray['last_name'],
                'Email' => $requestDataArray['email'],
                'Address1' => $requestDataArray['address1'],
                'Address2' => $requestDataArray['address2'],
                'City' => $requestDataArray['city'],
                'State' => $requestDataArray['state'],
                'Zip' => $requestDataArray['postal_code'],
                'ShipViaCode' => $requestDataArray['ship_via_id'],
                'OrderDate' => date("Y-m-d"),
                'AddressType' => 'RESIDENTIAL'
            ];

            if (isset($requestDataArray['country'])) {
                $headers['Country'] = $requestDataArray['country'];
            }

            if (isset($requestDataArray['country'])) {
                $headers['Country'] = $requestDataArray['country'] = 0 ? '' : $requestDataArray['country'];
            }

            if (isset($requestDataArray['address_id'])) {
                $headers['ShipToCode'] = $requestDataArray['address_id'];
                $headers['AddressType'] = 'COMMERCIAL';
            }

            if (isset($requestDataArray['shipping_cost'])) {
                $headers['ShippingCost'] = $requestDataArray['shipping_cost'];
                $total_amount += $headers['ShippingCost'];
            }

            if (isset($requestDataArray['payment_term'])) {
                $headers['PaymentTerm'] = $requestDataArray['payment_term'];
            }

            if (isset($requestDataArray['customer_po'])) {
                $headers['CustomerPO'] = $requestDataArray['customer_po'];
            }

            if (isset($requestDataArray['shipping_instructions'])) {
                $headers['Instructions'] = $requestDataArray['shipping_instructions'];
            }

            if (isset($requestDataArray['req_ship_date'])) {
                $headers['ShipDate'] = date('Y-m-d', strtotime($requestDataArray['req_ship_date']));
                $headers['DeliveryTime'] = date('H:i:s', strtotime($requestDataArray['req_ship_date']));
            }

            $TempSalesOrderNo = "";
            $count = 0;
            foreach ($this->cart_model->get_cart_for_front($this->ApiObj)['items'] as $item) {
                $count++;
                $item_data = json_decode(unserialize($item['item_data']));
                $cut_pieces = [];
                $order_length = 0;
                $line = 0;
                $totalSQFT = 0;
                $total_serging_charges = 0;

                foreach ($item_data->CutPieces as $key => $cut_piece) {
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
                        $temp['ActualSQFT'] = $sqft;
                        $temp['CutType'] = $item_data->cut_type;
                        $temp['LocationID'] = $item_data->location_id;
                        $temp['Serging'] = !empty($cut_piece->SergingType) ? "Y" : "N";
                        $temp['SergingCharges'] = (!empty($cut_piece->SergingCharges) && $temp['Serging'] == "Y") ? $cut_piece->SergingCharges : 0;
                        $temp['SergingType'] = empty($cut_piece->SergingType) ? "0" : $cut_piece->SergingType;
                        $temp['LineNo'] = ++$line;
                        $temp['UserRemarks'] = $cut_piece->UserRemarks;

                        $order_length += $cut_piece->ATSLength;
                        $total_serging_charges += $temp['SergingCharges'] * ((round($cut_piece->ATSWidth / 12, 2) + round($cut_piece->ATSLength / 12, 2)) * 2);
                        $cut_pieces[] = $temp;
                    }
                }

                $itemDetail[] = [
                    'ItemID' => $item['item_id'],
                    'OrderQty' => $item['item_quantity'],
                    'UnitPrice' => $item['item_price'],
                    'SQFTPrice' => $item_data->SQFTPrice,
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
                return redirect()->back()->withInput()->with( 'message', ['type' => 'danger', 'body' => $payment_response] );
                //return response()->json($payment_response);
            }
            prr(" :: Place Order API CALL DATA :: ");
            prr($headers, $itemDetail);
            $result = $this->ApiObj->Place_BLOrder(
                $headers,
                $itemDetail
            );
            prr(" :: Place Order API CALL RESULT :: ");
            prr($result);

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

                if(empty($quoteCartData)){
                    $this->cart_model->remove_cart_item(Auth::user()->id, (new Cart())->get_active_cart_customer(), 0, 0, '', '', true);
                }
                $successMsg = $result['Message'];
                preg_match("/\[[^\]]*\]/", $successMsg, $matches);
                $matched_string = $matches[0];
                $updatedString = '<span>' . $matched_string . '</span>';
                $successMsg = str_replace($matched_string, $updatedString, $successMsg);
                // $response['success'] = 1;
                // $response['webhook'] = 0;
                // $response['msg'] = $successMsg;

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
                       return redirect()->back()->with( 'message', ['type' => 'success', 'body' => 'Your order is genearted, Order Number is ' .  $matched_string] );

                    prr(" :: Order Acknowledgment Email Sent :: ");
                } catch (\Exception $e) {
                    prr("Order Acknowledgment Email Exception :: " . $e->getMessage());
                }
            } else {
                $order_payment = $this->order_payment_model->updateOrCreate(
                    ['user_id' => Auth::user()->id, 'hash' => $order_payment_hash],
                    [
                        'order_status' => ConstantsController::ORDER_STATUS['failed']
                    ]
                );

                if ((isset($result['Exception']) && $result['Exception']) || (isset($result['ObjectID']) && $result['ObjectID'] >= 900)) {
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
                        $this->cart_model->remove_cart_item(Auth::user()->id, (new Cart())->get_active_cart_customer(), 0, 0, '', '', true);
                        // $response['success'] = 1;
                        // $response['webhook'] = 1;
                        // $response['msg'] = 'You order is processed and you will get the confirmation soon. <br> Your order reference is: ' . $order_payment_hash . '</br>';
                        return redirect()->back()->with( 'message', ['type' => 'success', 'body' => 'You order is processed and you will get the confirmation soon. <br> Your order reference is: ' . $order_payment_hash] );

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

                    // $response['success'] = 0;
                    // $response['msg'] = $errorMsg;
                    return redirect()->back()->withInput()->with( 'message', ['type' => 'danger', 'body' => $errorMsg] );
                }

            }

            //  return response()->json($response);

        } catch (\Exception$e) {
            prr(['CheckoutController::EXCEPTION' => $e->getMessage()]);
            //return response()->json(['success' => 0, 'msg' => 'Something went wrong, please try again.', 'error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with( 'message', ['type' => 'danger', 'body' => $e->getMessage()] );
        } catch (\Error$e) {
            prr(['CheckoutController::ERROR' => $e->getMessage()]);
            return redirect()->back()->withInput()->with( 'message', ['type' => 'danger', 'body' => $e->getMessage()] );
            // return response()->json(['success' => 0, 'msg' => 'Something went wrong, please try again.', 'error' => $e->getMessage()]);
        }

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
}
