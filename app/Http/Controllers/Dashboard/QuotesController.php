<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendMail;

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
            "ShipToCode" => '',
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
        ];

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

    public function order_excel(Request $request){
        $title = isset($request->reportTitle) ? $request->reportTitle : '';
        $id = isset($request->previewID) ? $request->previewID : 0;

        $excel = $this->ApiObj->DownloadExcelReports($title, $id);
        return $excel['Success'] ?
            response()->json(['success' => 1, 'data' => $excel['ReportData']]) :
            response()->json(['success' => 0]);
    }
}
