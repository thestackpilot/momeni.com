<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendMail;
use App\Models\Cart;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\ConstantsController;

class QuotesController extends DashboardController
{
    public function index(){
        $is_salse_rep = Auth::user()->is_sale_rep;
        $is_customer = Auth::user()->is_customer;
        $customer = ($is_salse_rep && !$is_customer) ? $this->get_customers_dropdown_value(false) : [];
        $sergingtypes = $this->ApiObj->Get_QuotationSergingTypes();
        $quotationsLists = $this->ApiObj->Get_QuotationList();
        $items = $this->ApiObj->Get_AllBLItems();

        $blList = isset($items['OutPut']['BLItemsList']) ? $items['OutPut']['BLItemsList'] : [];
        $sergList = isset($sergingtypes['SurgingTypesList']) ? $sergingtypes['SurgingTypesList'] : [];
        $quoteList = isset($quotationsLists['QuotationList']) ? $quotationsLists['QuotationList'] : [];

        return view('dashboard.quotation', [
            'customer' => $customer,
            'items' => $blList,
            'sergingtypes' => $sergList,
            'quotationsLists' => $quoteList,
        ]);
    }

    public function save_quote(Request $request){
        $customer_id = $request->customer_id;
        $quotes_date = $request->quotes_date;
        $cancel_quote_date = $request->cancel_quote_date;
        $item_id = $request->item_id;
        $serging = $request->serging;
        $length = $request->length;
        $width = $request->width;

        $select_customer = $this->ApiObj->Get_CustomerDetail($customer_id);
        $email = isset($select_customer['CustomerDetail']['Email']) ? $select_customer['CustomerDetail']['Email'] : '';

        $data = $this->save_payload($request, $select_customer, $cancel_quote_date);
        $quote = $this->ApiObj->Place_BLQuotation($data);

        if($quote['OutPut']['Success']){
            $reportGet = $this->ApiObj->Get_ViewDocumentsReport('', '', 'ViewBLQuotation', $quote['OutPut']['ObjectID']);
            $maildata = [];
            $maildata['pdf'] = $reportGet['document']['ReportData'];

            if(isset($email) && $email){
                try {
                    SendMail::dispatch( [
                        'data'  => $maildata,
                        'slug'  => 'New Quotes Data',
                        'email' => ['sheikhammar568@gmail.com'],
                        'template' => 'email.quotes_submit'
                    ] );
                }
                catch ( \Exception$e )
                {
                    prr( "Quote Mail Exception: ".$e->getMessage() );
                }
            }

            return response()->json([
               'success' => true,
               'reportTitle' => $reportGet['document']['ReportTitle'],
               'previewID' => $reportGet['document']['PreviewID'],
               'reportdata' => $reportGet['document']['ReportData'],
               'message' => 'Quote has been save successfully'
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => $quote['OutPut']['Message']
             ]);
        }
    }

    public function save_payload($request, $formCustomer, $cancel_quote_date) {
        $sergCharges = ((  ($request->len_f * 12 + $request->len_i) + ($request->wid_f * 12 + $request->wid_i) ) * 2 / 12) * $request->sergingcharges;
        $cutpiece = [
            "TempSalesOrderNo" => "",
            "ItemID" => $request->item_id,
            "RollID" => "",
            "CutPieceID" => "",
            "ActualLength" => $request->length,
            "ActualWidth" => $request->width,
            "ActualSQFT" => "",
            "CutType" => "",
            "LocationID" => "",
            "Serging" => $request->serging,
            "SergingCharges" => $sergCharges,
            "SergingType" => $request->serging,
            "LineNo" => "1",
            "UserRemarks" => "",
        ];

        $details = [
            "ItemID" => $request->item_id,
            "OrderQty" => 1,
            "UnitPrice" => "",
            "SQFTPrice" => "",
            "SQFTArea" => "",
            "Discount" => "",
            "WHSID" => "",
            "Remarks" => "",
            "MarkFor" => "",
            "Line_No" => "1",
            "CutPieceID" => "",
            "RollID" => "",
            "SergingCharges" => $sergCharges,
            "SergingType" => $request->serging,
            "UserRemarks" => "",
            "OrderLength" => "",
            "CFA" => "",
            "IsRemnantShipable" => "",
            "ETA_Date" => "",
            "CutPieces" => [$cutpiece], // Nest CutPieces array properly
        ];

        $data = [
            "Detail" => [$details],
            "CustomerID" => $formCustomer['CustomerDetail']['CustomerID'],
            "CustomerPO" => $cancel_quote_date . rand(1,100),
            "FirstName" => $formCustomer['CustomerDetail']['CustomerAddressDetail']['ShipToAddresses'][0]['FirstName'],
            "LastName" => $formCustomer['CustomerDetail']['CustomerAddressDetail']['ShipToAddresses'][0]['LastName'],
            "Email" => $formCustomer['CustomerDetail']['CustomerAddressDetail']['ShipToAddresses'][0]['Email'],
            "CompanyName" => $formCustomer['CustomerDetail']['Company'],
            "ShipToCode" =>  $formCustomer['CustomerDetail']['CustomerAddressDetail']['ShipToAddresses'][0]['AddressID'],
            "Address1" => $formCustomer['CustomerDetail']['CustomerAddressDetail']['ShipToAddresses'][0]['Address1'],
            "Address2" => $formCustomer['CustomerDetail']['CustomerAddressDetail']['ShipToAddresses'][0]['Address2'],
            "City" => $formCustomer['CustomerDetail']['CustomerAddressDetail']['ShipToAddresses'][0]['City'],
            "State" => $formCustomer['CustomerDetail']['CustomerAddressDetail']['ShipToAddresses'][0]['State'],
            "Zip" => $formCustomer['CustomerDetail']['CustomerAddressDetail']['ShipToAddresses'][0]['Zip'],
            "Country" => $formCustomer['CustomerDetail']['CustomerAddressDetail']['ShipToAddresses'][0]['Country'],
            "Phone" => $formCustomer['CustomerDetail']['CustomerAddressDetail']['ShipToAddresses'][0]['Phone1'],
            "ShipViaCode" => $formCustomer['CustomerDetail']['CustomerAddressDetail']['CustomerShipVias'][0]['ShipViaID'],
            "CancelDate" => $cancel_quote_date,
            "ShippingCost" => 23.0,
            "IsRugPad" => $request->addRugpad,
            "IsReserveStock" =>  $request->reserveStock,
        ];
        return $data;
    }

    public function order_quote(Request $request){
        $QuotationNo = $request->QuotationNo;
        $UserNo = $request->UserNo;

        $customer_details = $shipping_options = $shippings = $shipping_addresses = $payment_terms_list = $customer_payment_options = $countries = $states = [];
        $data = $this->ApiObj->GetQuotationOrderDetailForOrderPlace($QuotationNo, $UserNo);

        $customer_details = $this->ApiObj->Get_CustomerDetail($data['OutPut']['CustomerID']);
        if ($customer_details && $customer_details['Success'] == true) {
            $shipping_addresses = $customer_details['CustomerDetail']['CustomerAddressDetail'];
            $default_ship_via_id = isset($customer_details['CustomerDetail']['CustomerAddressDetail']['CustomerShipVias'][0]) ? $customer_details['CustomerDetail']['CustomerAddressDetail']['CustomerShipVias'][0]['ShipViaID'] : '';
        }

        $countries = $this->ApiObj->Get_CountriesList();

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


        $payment_terms_list = $this->ApiObj->Get_PaymentTermsList();
        if ($payment_terms_list && $payment_terms_list['Success']) {
            $payment_terms_list = $payment_terms_list['PaymentTerms'];
            foreach ($payment_terms_list as $payment_term) {
                if (!in_array(md5($payment_term['Description']), $payment_terms_list)) {
                    $payment_terms_list[md5($payment_term['Description'])] = $payment_term;
                }

            }

        }


        $price = 0;
        $quote_cart_data = [];
        $max_len_size  = "";
        $totalSqftArea = "";
        $item_price = 0;
        $unit_price =  "";
        $bd_child = 0;
        $rugpad_price = 0.00;
        $cut_piece_data = [];
        $cut_charges =  $this->ApiObj->GetCutingService();
        foreach($data['OutPut']['QuotationDetailList'] as $rowDetail){
            $item = $this->ApiObj->Get_Items('', '', $rowDetail['ItemID'], '', '', '', '', '', '', '', '', '', '', '' );
            $price = $this->update_ats_prices( $this->ApiObj->Get_ATS($rowDetail['ItemID'], $data['OutPut']['CustomerID'] )['ATSInfo'], $rowDetail['ItemID'], $data['OutPut']['CustomerID'] );
            foreach($data['OutPut']['QuotationDetailCutList'] as $rowCutList){
                if($rowCutList['ItemID'] == $rowDetail['ItemID'] && $rowCutList['RollID'] == $rowDetail['RollID']){
                    $bd_child = $rowCutList['RugPad'] == "1" ? 0 : 1;

                    if($rowCutList['Line_No'] == $rowDetail['ParentLine_NO']){
                        $rugpad_price_get = $this->update_ats_prices( $this->ApiObj->Get_ATS($rowDetail['ItemID'], $data['OutPut']['CustomerID'] )['ATSInfo'], $rowDetail['ItemID'], $data['OutPut']['CustomerID'] );
                    }

                    $max_len_size  = $this->calculateMaxLenSize($rowCutList);
                    $totalSqftArea = $this->calculateTotalSqftArea($rowCutList);
                    $item_price = $price['Price'] * $totalSqftArea;
                    $unit_price =  $this->calculateUnitPrice($rowCutList, $cut_charges['OutPut']['UnitPrice']);
                    $cut_piece_data[] = $this->cut_pieces_payload($rowCutList);

                    if(isset($rugpad_price_get['Price'])){
                        $rugpad_price = $rugpad_price_get['Price'] * $totalSqftArea;
                    }
                }
            }

            $item_json = [
                "ItemID" => $item['Items'][0]['ItemID'] ?? "",
                "ItemName" => $item['Items'][0]['ItemName'] ?? "",
                "Dimensions" => $item['Items'][0]['Dimensions'] ?? "",
                "BasePrice" => $item['Items'][0]['BasePrice'] ?? 0,
                "ATSQ" => $item['Items'][0]['ATSQ'] ?? "",
                "QualityDescription" => $item['Items'][0]['QualityDescription'] ?? "",
                "IntroDate" => $item['Items'][0]['IntroDate'] ?? "",
                "TimeStamp" => $item['Items'][0]['TimeStamp'] ?? "",
                "UPC" => $item['Items'][0]['UPC'] ?? "",
                "PriceLevel1" => $item['Items'][0]['PriceLevel1'] ?? 0,
                "PriceLevel2" => $item['Items'][0]['PriceLevel2'] ?? 0,
                "PriceLevel3" => $item['Items'][0]['PriceLevel3'] ?? 0,
                "PriceLevel4" => $item['Items'][0]['PriceLevel4'] ?? 0,
                "PriceLevel5" => $item['Items'][0]['PriceLevel5'] ?? 0,
                "UDF1" => $item['Items'][0]['UDF1'] ?? "",
                "UDF2" => $item['Items'][0]['UDF2'] ?? "",
                "UDF3" => $item['Items'][0]['UDF3'] ?? "",
                "UDF4" => $item['Items'][0]['UDF4'] ?? "",
                "UDF5" => $item['Items'][0]['UDF5'] ?? "",
                "UDF6" => $item['Items'][0]['UDF6'] ?? "",
                "UDF7" => $item['Items'][0]['UDF7'] ?? "",
                "UDF8" => $item['Items'][0]['UDF8'] ?? "",
                "UDF9" => $item['Items'][0]['UDF9'] ?? "",
                "UDF10" => $item['Items'][0]['UDF10'] ?? "",
                "UDF11" => $item['Items'][0]['UDF11'] ?? "",
                "UDF12" => $item['Items'][0]['UDF12'] ?? "",
                "UDF13" => $item['Items'][0]['UDF13'] ?? "",
                "UDF14" => $item['Items'][0]['UDF14'] ?? "",
                "UDF15" => $item['Items'][0]['UDF15'] ?? "",
                "UDF16" => $item['Items'][0]['UDF16'] ?? "",
                "UDF17" => $item['Items'][0]['UDF17'] ?? "",
                "UDF18" => $item['Items'][0]['UDF18'] ?? "",
                "UDF19" => $item['Items'][0]['UDF19'] ?? "",
                "UDF20" => $item['Items'][0]['UDF20'] ?? "",
                "PhotoName" => $item['Items'][0]['PhotoName'] ?? "",
                "Discontinued" => $item['Items'][0]['Discontinued'] ?? "",
                "Source" => $item['Items'][0]['Source'] ?? "",
                "IsDeleted" => $item['Items'][0]['IsDeleted'] ?? "",
                "Weight" => $item['Items'][0]['Weight'] ?? "",
                "QualityID" => $item['Items'][0]['QualityID'] ?? "",
                "DesignID" => $item['Items'][0]['DesignID'] ?? "",
                "ColorID" => $item['Items'][0]['ColorID'] ?? "",
                "SizeID" => $item['Items'][0]['SizeID'] ?? "",
                "ShapeID" => $item['Items'][0]['ShapeID'] ?? "",
                "DesignDescription" => $item['Items'][0]['DesignDescription'] ?? "",
                "ExternalID" => $item['Items'][0]['ExternalID'] ?? "",
                "ProductType" => $item['Items'][0]['ProductType'] ?? "",
                "DimentionalWeight" => $item['Items'][0]['DimentionalWeight'] ?? "",
                "ImageName" => $item['Items'][0]['ImageName'] ?? "",
                "Country" => $item['Items'][0]['Country'] ?? "",
                "ProductDescription" => $item['Items'][0]['ProductDescription'] ?? "",
                "CareInstructions" => $item['Items'][0]['CareInstructions'] ?? "",
                "UDFFields" => $item['Items'][0]['UDFFields'] ?? [],
                "NewArrivalExpiry" => $item['Items'][0]['NewArrivalExpiry'] ?? "",
                "Clearence" => $item['Items'][0]['Clearence'] ?? "",
                "TopSeller" => $item['Items'][0]['TopSeller'] ?? "",
                "SpecialBuy" => $item['Items'][0]['SpecialBuy'] ?? "",
                "Thickness" => $item['Items'][0]['Thickness'] ?? "",
                "PileHeight" => $item['Items'][0]['PileHeight'] ?? "",
                "HotBuy" => $item['Items'][0]['HotBuy'] ?? "",
                "RugPad" => $item['Items'][0]['RugPad'] ?? "",
                "MarketSpecial" => $item['Items'][0]['MarketSpecial'] ?? "",
                "PrePad" => $item['Items'][0]['PrePad'] ?? "",
                "ULTPad" => $item['Items'][0]['ULTPad'] ?? "",
                "GroupPricing" => $item['Items'][0]['GroupPricing'] ?? "",
                "VideoURL" => $item['Items'][0]['VideoURL'] ?? "",
                "Length" => $item['Items'][0]['Length'] ?? "",
                "Width" => $item['Items'][0]['Width'] ?? "",
                "RegularDeliveryTime" => $item['Items'][0]['RegularDeliveryTime'] ?? "",
                "ExpressDeliveryTime" => $item['Items'][0]['ExpressDeliveryTime'] ?? "",
                "ImageNameArray" => ['https://media.momeni.com/Full_Img/'],
                "ItemColor" => $item['Colors'][0]['Description'] ?? "",
                "ItemColorImage" => "https://media.momeni.com/Full_Img/" ?? "",
                "CutPieces" => $cut_piece_data ?? [],
                "SQFTPrice" => (string)$totalSqftArea ?? "",
                "SQFTArea" => "",
                "CutPieceID" => "",
                "RollID" => $rowDetail['RollID'] ?? "",
                "SergingCharges" => "",
                "SergingType" => "",
                "location_id" => "",
                "cut_type" => "",
                "Serging" => ""
            ];

            $quote_cart = [
                'id' => $rowDetail['Line_NO'],
                'item_id' => $rowDetail['ItemID'],
                'item_customer_id' => $data['OutPut']['CustomerID'],
                'item_name' => $item['Items'][0]['ItemName'],
                'item_quantity' => 1,
                'item_price' => $item_price, //(float) number_format((float)$item_price, ConstantsController::ALLOWED_DECIMALS, '.', ''),
                'item_total' =>  $item_price, //(float) number_format((float)$item_price, ConstantsController::ALLOWED_DECIMALS, '.', ''),
                'item_color' => $item['Colors'][0]['Description'],
                'item_size' => $max_len_size,
                'item_currency' => '$',
                'item_image' => 'https://media.momeni.com/Full_Img/',
                'item_eta' => null,
                'item_data' => serialize(json_encode($item_json)),
                'item_atsq' => 1,
                'item_only_max_quantity' => false,
                'oak_item' => 0,
                'broadloom_item' => 1,
                'oak_sku' => null,
                'bd_roll_id' => $rowDetail['RollID'],
                'user_remarks' => null,
                'cfa' => 0,
                'remnant_shipable' => 0,
                'unit_price' => (float) number_format((float)$unit_price, ConstantsController::ALLOWED_DECIMALS, '.', ''),
                'sqft_area' => (float) number_format((float)$totalSqftArea, ConstantsController::ALLOWED_DECIMALS, '.', ''),
                'rand_str' => '',
                'is_bd_child' => $bd_child,
                'rugpad_price' =>  0.00,
                'order_length' => $rowDetail['OrderLength'],
                'quotation_no' => $QuotationNo,
            ];
            $quote_cart_data[] = $quote_cart;

            foreach($quote_cart_data as $index => $quote){
                if($quote['id'] == $rowDetail['Line_NO'] && $index > 0){
                    $quote_cart_data[$index - 1]['rugpad_price'] = (float)$rugpad_price;
                }
            }
        }
//dd(isset($customer_details['CustomerDetail']['PaymentTerm']) ? $customer_details['CustomerDetail']['PaymentTerm'] : '');
        return view( 'frontend.'.$this->active_theme->theme_abrv.'.broadloom-shopping-cart', [
            'quote_cart_data'     => json_decode(json_encode($quote_cart_data)),
            'countries'           => $countries,
            'cust_country'        => $data['OutPut']['Country'],
            'cust_state'          => $data['OutPut']['State'],
            'shipping_options'    => $shipping_options,
            'shippings'           => $shippings,
            'default_ship_via_id' => $default_ship_via_id,
            'shipping_addresses' => isset($shipping_addresses) ? $shipping_addresses : [],
            'payment_terms_list' => $payment_terms_list,
            'customer_comment' => '',
            'payment_term' =>  isset($customer_details['CustomerDetail']['PaymentTerm']) ? $customer_details['CustomerDetail']['PaymentTerm'] : '',
            'payment_options' => []
        ]);
    }
    public function calculateMaxLenSize($cutpieces) {
        $maxLength = 0;
        $maxWidth = 0;

        if (isset($cutpieces[0])) {
            foreach ($cutpieces as $cut) {
                $length = (float)$cut['ActualLength'];
                $width = (float)$cut['ActualWidth'];

                if ($length > $maxLength) {
                    $maxLength = $length;
                }

                if ($width > $maxWidth) {
                    $maxWidth = $width;
                }
            }
        } else {
            $length = (float)$cutpieces['ActualLength'];
            $width = (float)$cutpieces['ActualWidth'];

            if ($length > $maxLength) {
                $maxLength = $length;
            }

            if ($width > $maxWidth) {
                $maxWidth = $width;
            }
        }
        $maxLengthFeet = floor($maxLength / 12);
        $maxLengthInches = $maxLength % 12;
        $maxWidthFeet = floor($maxWidth / 12);
        $maxWidthInches = $maxWidth % 12;
        return $max_len_size = "{$maxWidthFeet}'{$maxWidthInches}\" x {$maxLengthFeet}'{$maxLengthInches}\"";
    }

    public function calculateTotalSqftArea($cutpieces)
    {
        $totalSqftArea = 0;
        if (isset($cutpieces[0])) {
            foreach ($cutpieces as $piece) {
                $length = (float)$piece['ActualLength'];
                $width = (float)$piece['ActualWidth'];
                $area = ($length / 12) * ($width / 12);
                $totalSqftArea += $area;
            }
        } else {
            $length = (float)$cutpieces['ActualLength'];
            $width = (float)$cutpieces['ActualWidth'];
            $area = ($length / 12) * ($width / 12);
            $totalSqftArea += $area;
        }
        return $totalSqftArea = round($totalSqftArea, 2);
    }

    public function calculateUnitPrice($cutpieces, $cut_charges)
    {
        $unit_price = 0;
        if (isset($cutpieces[0])) {
            foreach ($cutpieces as $piece) {
                if($piece['SergingType'] != "0"){
                    $unit_price += (float)$cut_charges;
                };
            }
        } else {
            if($cutpieces['SergingType'] != "0"){
                $unit_price += (float)$cut_charges;
            };
        }
        return $unit_price;
    }

    public function cut_pieces_payload($cutpieces) {
        $cutpiece_data = [];
        if (isset($cutpieces[0]) && !isset($cutpieces[1])) {
            foreach ($cutpieces as $cut) {
                $cutpiece_data = [
                    'ItemID' => $cut['ItemID'],
                    'RollID' => $cut['RollID'],
                    'CutPieceID' => $cut['CutPieceID'],
                    'ATSLength' => $cut['ActualLength'],
                    'ATSWidth' => $cut['ActualWidth'],
                    'TotalUsedLength' => $cut['ActualLength'],
                    'LengthStatus' => $cut['Remnant'] == "Y" ? "R" : "F",
                    'TempSalesOrderNo' => "",
                    'CPTempLine_No' => "",
                    'LengthID' => "",
                    'SergingType' => $cut['SergingType'],
                    'SergingCharges' => $cut['SergingCharges'],
                    'UserRemarks' => ""
                ];
            }
        } else {
            $cutpiece_data = [
                'ItemID' => $cutpieces['ItemID'],
                'RollID' => $cutpieces['RollID'],
                'CutPieceID' => $cutpieces['CutPieceID'],
                'ATSLength' => $cutpieces['ActualLength'],
                'ATSWidth' => $cutpieces['ActualWidth'],
                'TotalUsedLength' => $cutpieces['ActualLength'],
                'LengthStatus' => $cutpieces['Remnant'] == "Y" ? "R" : "F",
                'TempSalesOrderNo' => "",
                'CPTempLine_No' => "",
                'LengthID' => "",
                'SergingType' => $cutpieces['SergingType'],
                'SergingCharges' => $cutpieces['SergingCharges'],
                'UserRemarks' => ""
            ];
        }
        return $cutpiece_data;
    }


    public function view_quote(Request $request){
        $reportGet = $this->ApiObj->Get_ViewDocumentsReport('', '', 'ViewBLQuotation', $request->QuotationNo);

        return response()->json([
           'success' => $reportGet['document']['Success'],
           'reportTitle' => $reportGet['document']['ReportTitle'],
           'previewID' => $reportGet['document']['PreviewID'],
           'reportdata' => $reportGet['document']['ReportData'],
           'message' =>  $reportGet['document']['Message'],
        ]);
    }

    public function void_quote(Request $request){
        $voidQuote = $this->ApiObj->VoidQuotation($request->QuotationNo, $request->UserNo);

        if($voidQuote['OutPut']['Success']){
            return response()->json([
                'success' => $voidQuote['OutPut']['Success'],
                'message' =>  $voidQuote['OutPut']['Message'],
            ]);
        }else{
            return response()->json([
               'success' => $voidQuote['OutPut']['Success'],
               'message' =>  $voidQuote['OutPut']['Message'],
            ]);
        }
    }

    public function order_excel(Request $request){
        $title = isset($request->reportTitle) ? $request->reportTitle : '';
        $id = isset($request->previewID) ? $request->previewID : 0;

        $excel = $this->ApiObj->DownloadExcelReports($title, $id);
        return $excel['Success'] ?
            response()->json(['success' => 1, 'data' => $excel['ReportData']]) :
            response()->json(['success' => 0]);
    }

    public function get_customers_dropdown_value( $include_all = 1 )
    {
        $options = [];
        if ( $include_all )
        {
            $options[] = [
                'value' => '',
                'label' => 'All',
            ];
        }

        if ( Auth::user() && Auth::user()->sales_rep_customers )
        {
            $sales_rep_customers = json_decode( Auth::user()->sales_rep_customers, true );
            foreach ( $sales_rep_customers['Customers'] as $customer )
            {
                if ($customer['BroadloomCustomer'] === 'Y') {
                    $options[] = [
                        'value' => $customer['CustomerID'],
                        'label' => "{$customer['CompanyName']} ({$customer['CustomerID']})",
                    ];
                }
            }

        }
        return $options;
    }

    public function update_ats_prices($data, $item_id, $customer_id = 0)
    {
        $multiplier = 1;

        if (
            Auth::user() &&
            strcmp(Auth::user()->getDataAttribute('cost-type', 'my-cost'), 'msrp') === 0 &&
            Auth::user()->getDataAttribute('msrp-multiplier', 1)
        ) {
            $multiplier = Auth::user()->getDataAttribute('msrp-multiplier', 1);
            $data['Price'] = number_format($data['Price'] * $multiplier, ConstantsController::ALLOWED_DECIMALS, '.', ',');
        }

        $data['OnlyMaxQuantity'] = (
            CommonController::check_bit_field($data, 'Discontinued') ||
            CommonController::check_bit_field($data, 'SpecialBuy') ||
            CommonController::check_bit_field($data, 'Reviewed')
        );

        $data['ATSQtyOrig'] = $data['ATSQty'];
        $data['ATSQty'] = $data['ATSQty'] - (new Cart())->get_item_quantity($item_id);
        $data['ETADate'] = CommonController::get_date_format($data['ETADate']);
        $cart_item = (new Cart())->get_item($item_id);

        $data['ItemExistInCart'] = $cart_item ? ($cart_item->customer_id == $customer_id ? 1 : -1) : 0;

        return $data;

    }

    public function quote_price(Request $request){
        $price = $this->ApiObj->CheckBLQuotePrice($request->CustomerID, $request->ItemID, $request->SergingType, $request->CutLengthFeet, $request->CutLengthInches, $request->CutWidthFeet, $request->CutWidthInches, $request->RugPad);
        if($price['OutPut']['Success']){
            return response()->json([
                'success' => $price['OutPut']['Success'],
                'price' =>  $price['OutPut']['QuotePrice'],
            ]);
        }else{
            return response()->json([
                'success' => $price['OutPut']['Success'],
                'message' =>  $price['OutPut']['Message'],
            ]);
        }
    }
}
