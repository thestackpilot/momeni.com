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

        return view('dashboard.quotation', [
            'customer' => $customer
        ]);
    }

    public function save_quote(Request $request){
        dd($request->all());
        $customer_id = $request->customer_id;
        $quotes_date = $request->quotes_date;
        $cancel_quote_date = $request->cancel_quote_date;
        $item_id = $request->item_id;
        $serging = $request->serging;
        $lengthF = $request->lengthF;
        $lengthI = $request->lengthI;
        $widthF = $request->widthF;
        $widthI = $request->widthI;

    }
}
