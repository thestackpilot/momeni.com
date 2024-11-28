<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuotesController extends DashboardController
{
    public function index(){
        $is_salse_rep = Auth::user()->is_sale_rep;
        $is_customer = Auth::user()->is_customer;
        $customer = ($is_salse_rep && !$is_customer) ? $this->get_customers_dropdown_options(false) : [];
        $sergingtypes = $this->ApiObj->Get_QuotationSergingTypes();
        $quotationsLists = $this->ApiObj->Get_QuotationList();
        $items = $this->ApiObj->Get_AllBLItems();

        return view('dashboard.quotation', [
            'customer' => $customer,
            'items' => $items['OutPut']['BLItemsList'],
            'sergingtypes' => $sergingtypes['SurgingTypesList'],
            'quotationsLists' => $quotationsLists['QuotationList'],
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
            $maildata = [
                'QuoteNo' => $quote['OutPut']['ObjectID']
            ];
            $maildata['attachment'] = $reportGet['document']['ReportData'];
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
                prr( "Mail Exception User: ".$e->getMessage() );
            }

        }else{

        }

    }

    public function save_payload($request, $formCustomer, $cancel_quote_date) {
        $data = [];
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
            "SergingCharges" => $request->sergingcharges,
            "SergingType" => $request->serging,
            "UserRemarks" => "",
            "OrderLength" => "",
            "CFA" => "",
            "IsRemnantShipable" => "",
            "ETA_Date" => "",
        ];
        $cutpiece = [
            "TempSalesOrderNo" => "",
            "TempUserNo" => "",
            "CutPieceID" => "",
            "RollID" => "",
            "ItemID" => $request->item_id,
            "ActualLength" => $request->length,
            "ActualWidth" => $request->width,
            "ActualSQFT" => "",
            "CutType" => "",
            "Description" => "",
            "LocationID" => "",
            "Serging" => $request->serging,
            "Waste" => "",
            "Remnant" => "",
            "AvailableForSale" => "",
            "SergingCharges" => $request->sergingcharges,
            "IsRemnantShipable" => "",
            "SergingType" => $request->serging,
            "LineNo" => "1",
            "UserRemarks" => "1",
            "LoggedUserNo" => Auth::user()->spars_logged_user_no,
        ];

        $data['Detail'] = $details;
        $data['Detail']['CutPieces'] = $cutpiece;
        $data = array_merge($data, [
            "CustomerID" => $formCustomer['CustomerDetail']['CustomerID'],
            "CustomerPO" => '',
            "AddressName" =>'',
            "FirstName" =>$formCustomer['CustomerDetail']['CustomerAddressDetail']['ShipToAddresses'][0]['FirstName'],
            "LastName" =>$formCustomer['CustomerDetail']['CustomerAddressDetail']['ShipToAddresses'][0]['LastName'],
            "Email" =>$formCustomer['CustomerDetail']['CustomerAddressDetail']['ShipToAddresses'][0]['Email'],
            "CompanyName" =>$formCustomer['CustomerDetail']['Company'],
            "ShipToCode" =>'',
            "Address1" =>$formCustomer['CustomerDetail']['CustomerAddressDetail']['ShipToAddresses'][0]['Address1'],
            "Address2" =>$formCustomer['CustomerDetail']['CustomerAddressDetail']['ShipToAddresses'][0]['Address2'],
            "City" =>$formCustomer['CustomerDetail']['CustomerAddressDetail']['ShipToAddresses'][0]['City'],
            "State" =>$formCustomer['CustomerDetail']['CustomerAddressDetail']['ShipToAddresses'][0]['State'],
            "Zip" =>$formCustomer['CustomerDetail']['CustomerAddressDetail']['ShipToAddresses'][0]['Zip'],
            "Country" =>$formCustomer['CustomerDetail']['CustomerAddressDetail']['ShipToAddresses'][0]['Country'],
            "Phone" =>$formCustomer['CustomerDetail']['CustomerAddressDetail']['ShipToAddresses'][0]['Phone1'],
            "OrderTakenBy" =>'',
            "EventID" =>'',
            "Instructions" =>'',
            "ShipViaCode" =>$formCustomer['CustomerDetail']['CustomerAddressDetail']['CustomerShipVias'][0]['ShipViaID'],
            "OrderDate" =>'',
            "ShipDate" =>'',
            "CancelDate" =>$cancel_quote_date,
            "ShippingCost" => 23.0,
            "DeliveryTime" =>'',
            "AddressType" =>'',
            "SignatureRequired" =>'',
            "IncludeDeclareValue" =>'',
            "ShipComplete" =>'',
            "OrderSource" =>'',
            "ExternalID" =>'',
            "PaymentTerm" =>'',
            "IsAdvancePayment" =>'',
            "AdvancePaymentAmout" =>'',
            "TransactionCode" =>'',
            "CashReceiptNo" =>'',
            "TempQuotationNo" =>'',
            "IsRugPad" =>$request->addRugpad,
        ]);

        return $data;

    }

    public function order_quote(Request $request){
        $QuotationNo = $request->QuotationNo;
        $UserNo = $request->UserNo;
        $order = $this->ApiObj->Place_QuotationOrder($QuotationNo, $UserNo);
        if($order['OutPut']['Success']){
            return response()->json(['success' => true, 'msg' => $order['OutPut']['Message'], 'order_no' => $order['OutPut']['ObjectID']]);
        }else{
            return response()->json(['success' => false, 'msg' => $order['OutPut']['Message']]);
        }
    }
}
