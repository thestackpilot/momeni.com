<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\ItemController;
use App\Http\Controllers\PaytraceController;
use App\Models\Cart;
use App\Models\OrderPayments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BroadloomController extends FrontendController
{
    public function __construct()
    {
        parent::__construct();
        $this->cart_model = new Cart();
        $this->order_payment_model = new OrderPayments();
        $this->paytrace = $this->active_theme_json->general->payment_method->enabled ? new PaytraceController() : '';
    }


    public function index($id, $cust_id, $color_id, $item_name = '', $item_id = '')
    {
        
        $itemController = new ItemController();

        $items = $itemController->generate_color_name($itemController->generate_image_urls($this->ApiObj->Get_Items("", $id, "", "", "", "", $color_id)));
        $item = [];
        // dd($items);

        // $images = $obj->generate_image_urls($items);
        // dd($images);

        foreach ($items['Items'] as $row) {
            if ($row['ProductType'] == 'Broadloom' && $row['ItemID'] == $item_id) {
                $item = $row;
            }
        }

        $roll_pieces =  $this->ApiObj->Get_ItemsRollAndCutPieceList($item_id);
        $surging_types = $this->ApiObj->Get_SurgingTypes();
        // dd($roll_pieces);
        // dd( 'frontend.' . $this->active_theme->theme_abrv . '.broadloom');
        return view('frontend.' . $this->active_theme->theme_abrv . '.broadloom', [
            'surging_types' => $surging_types,
            'roll_pieces' => $roll_pieces,
            'customer_id' => $cust_id,
            'item' => $item,
            'item_json' => json_encode($item),
            'correct_item_name' =>  $item_name
        ]);
    }

    public function shopping_cart()
    {
        $customer_details = $shipping_options = $shippings = $shipping_addresses = $payment_terms_list = $customer_payment_options = $countries = $states = [];
        $default_ship_via_id = '';
        $countries = $this->ApiObj->Get_CountriesList();

        if (Auth::user() && $this->cart_model->get_cart_for_front($this->ApiObj)['items'] && $this->cart_model->get_cart_for_front($this->ApiObj)['items'][0]) {
            $customer_details = $this->ApiObj->Get_CustomerDetail((new Cart())->get_active_cart_customer());
            $payment_terms_list = $this->ApiObj->Get_PaymentTermsList();
            $shippings = $this->ApiObj->Get_ShipViaList();
           // dd($shippings);

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


//        $payload = [
//            'items' => [
//                [
//                    'item_id' => 'CHEVOCHE-BBGED400',
//                    'item_customer_id' => '081370',
//                    'item_name' => ' ',
//                    'item_quantity' => 1,
//                    'item_price' => 169.00,
//                    'item_total' => 169.00,
//                    'item_color' => 'BEIGE',
//                    'item_size' => '9\'6" x 5\'5"',
//                    'item_currency' => '$',
//                    'item_image' => 'https://media.momeni.com/Full_Img/CHEVOCHE-BBGE.jpg',
//                    'item_eta' => '2024-09-23',
//                    'item_data' => 's:1813:"{"ItemID":"CHEVOCHE-BBGED400","ItemName":"DESIGN: CHE-B","Dimensions":"WOOL & POLYESTER","BasePrice":0,"ATSQ":13,"QualityDescription":"BROADLOOM CHEVRON","IntroDate":"2018-12-06T00:00:00","TimeStamp":"1544094557","UPC":"039425443673","PriceLevel1":0,"PriceLevel2":0,"PriceLevel3":0,"PriceLevel4":0,"PriceLevel5":0,"UDF1":"","UDF2":"","UDF3":"","UDF4":"","UDF5":"","UDF6":"","UDF7":"","UDF8":"","UDF9":"SHADES OF BEIGE","UDF10":"","UDF11":"","UDF12":"","UDF13":"","UDF14":"","UDF15":"","UDF16":"","UDF17":"","UDF18":"","UDF19":"","UDF20":"","PhotoName":"","Discontinued":"False","Source":"CHE-B","IsDeleted":"0","Weight":12.3,"QualityID":"","DesignID":"CHE-B","ColorID":"BGE000","SizeID":"D400","ShapeID":null,"DesignDescription":null,"ExternalID":"","ProductType":"Broadloom","DimentionalWeight":0,"ImageName":"Full_Img/CHEVOCHE-BBGE.jpg","Country":"INDIA","ProductDescription":"","CareInstructions":"","UDFFields":[{"FieldName":"Shades of Color","Value":"SHADES OF BEIGE"}],"NewArrivalExpiry":"False","Clearence":"False","TopSeller":"False","SpecialBuy":"False","Thickness":"","PileHeight":"","HotBuy":"False","RugPad":"False","MarketSpecial":"False","PrePad":"","ULTPad":"","GroupPricing":"","VideoURL":"","ImageNameArray":["https://media.momeni.com/Full_Img/CHEVOCHE-BBGE.jpg"],"ItemColor":"BEIGE","ItemColorImage":"https://media.momeni.com/Full_Img/CHEVOCHE-BBGE.jpg","CutPieces":[{"ItemID":"CHEVOCHE-BBGED400","RollID":"CHE-BBGED406057","CutPieceID":"","ATSLength":"114","ATSWidth":"65","TotalUsedLength":"114","LengthStatus":"F","TempSalesOrderNo":"104","CPTempLine_No":"1","LengthID":"359"},{"ItemID":"CHEVOCHE-BBGED400","RollID":"CHE-BBGED406057","CutPieceID":"","ATSLength":"114","ATSWidth":"115","TotalUsedLength":"114","LengthStatus":"R","TempSalesOrderNo":"104","CPTempLine_No":"1","LengthID":"360"}]}"',
//                    'item_atsq' => 1,
//                    'item_only_max_quantity' => null,
//                ],
//            ],
//            'cart_currency' => '$',
//            'cart_count' => 1,
//            'cart_total' => 169.00,
//        ];
        // dd($payload);
    //     dd( 
    //          $countries,
    //          isset($customer_details['CustomerDetail']['Country']) ? $customer_details['CustomerDetail']['Country'] : null,
    //          isset($customer_details['CustomerDetail']['State']) ? $customer_details['CustomerDetail']['State'] : null,
    //          $states,
    //         $shipping_options,
    //         $shippings,
    //          $default_ship_via_id,
    //         isset($shipping_addresses) ? $shipping_addresses : [],
    //          $payment_terms_list,
    //         isset($customer_details['CustomerDetail']['Comment']) ? $customer_details['CustomerDetail']['Comment'] : '',
    //         isset($customer_details['CustomerDetail']['PaymentTerm']) ? $customer_details['CustomerDetail']['PaymentTerm'] : '',
    //      isset($customer_payment_options['customers']) && count($customer_payment_options['customers']) > 0 ? $customer_payment_options['customers'][0] : []
        
    // );
        return view('frontend.' . $this->active_theme->theme_abrv . '.broadloom-shopping-cart', [
            'countries' => $countries,
            'cust_country' => isset($customer_details['CustomerDetail']['Country']) ? $customer_details['CustomerDetail']['Country'] : null,
            'cust_state' => isset($customer_details['CustomerDetail']['State']) ? $customer_details['CustomerDetail']['State'] : null,
            'states' => $states,
            'shipping_options' => $shipping_options,
            'shippings' => $shippings,
            'default_ship_via_id' => $default_ship_via_id,
            'shipping_addresses' => isset($shipping_addresses) ? $shipping_addresses : [],
            'payment_terms_list' => $payment_terms_list,
            'customer_comment' => isset($customer_details['CustomerDetail']['Comment']) ? $customer_details['CustomerDetail']['Comment'] : '',
            'payment_term' => isset($customer_details['CustomerDetail']['PaymentTerm']) ? $customer_details['CustomerDetail']['PaymentTerm'] : '',
            'payment_options' => isset($customer_payment_options['customers']) && count($customer_payment_options['customers']) > 0 ? $customer_payment_options['customers'][0] : []
        ]);
    }

    public function order_complete()
    {
        return view('frontend.' . $this->active_theme->theme_abrv . '.broadloom-order-complete', [
        ]);
    }

    public function AddCutPiece(Request $request)
    {
        $rollId = $request->input('roll_id');
        $itemId = $request->input('item_id');
        $cutpieceId = $request->input('cutpiece_id');
        $atslength = $request->input('atslength');
        $totalWidth = $request->input('totalwidth');
        $totalSqft = $request->input('totalsqft');
        $cutType = $request->input('cuttype');
        $locationId = $request->input('locationid');
        $charges = $request->input('charges');
        $description = $request->input('desc');
        $sergingTypeNo = $request->input('sergingtypeno');
        $tempsalesorderno = $request->input('tempsalesorderno');
        $waste = $request->input('waste');
        $remnant = $request->input('Remnant');
        $available = $request->input('AvailableForSale');
        $isremship = $request->input('IsremnantShipable');
        $serging = 'N';
        $line = $request->input('LineNo');
        $userremarks = $request->input('UserRemarks');
        $logged_user_no = $request->logged_user_no;

        if (!empty($sergingTypeNo)) {
            $serging = 'Y';
            // $width = number_format($totalWidth % 12, ConstantsController::ALLOWED_DECIMALS);
            // $length = number_format($atslength, ConstantsController::ALLOWED_DECIMALS);
            // $area = ($width + $length) * 2;

            $width = $totalWidth % 12;
            $length = $atslength;
            $area = ($width + $length) * 2;
            $formattedArea = number_format($area, ConstantsController::ALLOWED_DECIMALS);
            //  $charges = $area * $charges;
        }


        $res = $this->ApiObj->Get_AddCutPiece($tempsalesorderno, $cutpieceId, $rollId, $itemId, $atslength, $totalWidth, $totalSqft, $cutType, $description, $charges, $sergingTypeNo, $locationId, $waste, $remnant, $available, $isremship, $serging, $line, $userremarks, $logged_user_no);
        return [
            'cut_piece' => $res,
        ];
    }

    public function get_cut_pieces(Request $request)
    {
        $id = $request->temp_sales_order_no;
        $logged_user_no = $request->logged_user_no;
        return $this->ApiObj->Get_ShowCut($id, $logged_user_no);
    }

    public function RemoveCutPiece(Request $request)
    {
        $logged_user_no = $request->logged_user_no ? $request->logged_user_no : '';
        return $this->ApiObj->RemoveCutPiece($request->TempSalesOrderNo, $request->CutPieceID ? $request->CutPieceID : '', $request->RollID ? $request->RollID : '', $request->line_no, $logged_user_no);
    }

    public function RemoveAllCutPiece(Request $request){
        $TempSalesOrderNo = $request->temp_sales_order_no ?  $request->temp_sales_order_no : '';
        $LoggedUserNo = $request->logged_user_no ? $request->logged_user_no : '';
        return $this->ApiObj->RemoveAllCutPiece($TempSalesOrderNo, $LoggedUserNo);
    }

    public function GetCutingService(){
        $result =  $this->ApiObj->GetCutingService();
        return $result;
    }
}
