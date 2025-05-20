<?php

namespace App\Http\Controllers\Dashboard;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use View;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\CommonController;

class GenericReportsController extends DashboardController
{
    public function __construct()
    {
        parent::__construct();
    }

    // TODO : The dashboard for the LR needs to have the icons.
    public function company_credit( Request $request )
    {
        $active_customer = $request->has( 'customer' ) ? $request->customer : ( new Cart() )->get_active_cart_customer();
        $customers       = $this->get_customers_dropdown_options( false );

        if ( ! $active_customer && count( $customers ) > 1 )
        {
            $active_customer = $customers[0]['value'];
        }

        if ( $active_customer )
        {
            $company_credit = $this->ApiObj->Get_CompanyCredit( $active_customer );

            if ( $company_credit )
            {
                $company_credit = $company_credit['OutPut'];
            }

        }
        else
        {
            $company_credit = [
                'PaymentTerms'       => 0,
                'CreditLimit'        => 0,
                'OutstandingBalance' => 0,
                'AvailableCredit'    => 0
            ];
        }

        View::share( 'active_customer', $active_customer );
        View::share( 'customers', $customers );
        View::share( 'company_credit', $company_credit );

        $report_type = isset( $request->report_type ) && $request->report_type ? $request->report_type : 'credit-memos';
        // die($report_type);
        switch ( $report_type )
        {
            case 'credit-memos':
                $data = $this->get_credit_memos( $request );
                View::share( 'additional_data', ['title' => 'Credit Memos'] );
                View::share( 'memos', $data['memos'] );
                break;
            case 'debit-memos':
                $data = $this->get_debit_memos( $request );
                View::share( 'additional_data', ['title' => 'Debit Memos'] );
                View::share( 'memos', $data['memos'] );
                break;
            case 'invoices':
                $data = $this->get_invoices( $request );
                View::share( 'additional_data', ['title' => 'Invoices'] );
                View::share( 'invoices', $data['invoices'] );
                break;
        }

        array_unshift( $data['filters'], [
            'title'        => 'Report Type',
            'type'         => 'radio',
            'placeholder'  => '',
            'filter_width' => 'col-md-12',
            'value'        => $report_type,
            'options'      => [
                'credit-memos' => 'Credit Memos',
                'debit-memos'  => 'Debit Memos',
                'invoices'     => 'Invoices'
            ]
        ] );
        View::share( 'table', $data['table'] );
        View::share( 'filters', $data['filters'] );
        View::share( 'paginated', 'yes' );

        return view( 'dashboard.company-credit' );
    }

    public function credit_memos( Request $request )
    {
        $data = $this->get_credit_memos( $request );
        View::share( 'memos', $data['memos'] );
        View::share( 'table', $data['table'] );
        View::share( 'filters', $data['filters'] );
        View::share( 'title', 'Credit Memos' );
        View::share( 'paginated', 'yes' );

        return view( 'dashboard.generic-report' );
    }

    public function download_print_orders( Request $request )
    {

        if ( isset( $request->report_data ) && is_array( json_decode( $request->report_data, 1 ) ) )
        {
            $report_data = $request->report_data;
            View::share( 'report_data', $report_data );

            return view( 'dashboard.order-report-pdf' );
        }
        else
        {
            return redirect()->back();
        }

    }

    public function download_sample_files( $type = '' )
    {
        $data = $columns = [];

        switch ( $type )
        {
            case 'order':
                $columns = ['Item ID', 'Quantity'];
                $data    = [
                    ['Item ID' => 'COVT12331GYIV2222', 'Quantity' => 10, 'SideMark' => 'SideMark 1'],
                    ['Item ID' => 'COVT12331GYIV2223', 'Quantity' => 20, 'SideMark' => 'SideMark 2'],
                    ['Item ID' => 'COVT12331GYIV2224', 'Quantity' => 3, 'SideMark' => 'SideMark 3'],
                    ['Item ID' => 'COVT12331GYIV2225', 'Quantity' => 9, 'SideMark' => 'SideMark 4']
                ];
                break;
            case 'hangtag':
                $columns = ['Design ID'];
                $data    = [
                    ['Design ID' => 'A05101'],
                    ['Design ID' => 'A05102'],
                    ['Design ID' => 'A05103']
                ];
                break;
            default:
                return redirect()->route( 'dashboard' );
                break;
        }

        return response()->stream( function () use ( $data, $columns )
        {
            $file = fopen( 'php://output', 'w' );
            fputcsv( $file, $columns );

            foreach ( $data as $row )
            {
                fputcsv( $file, $row );
            }

            fclose( $file );
        }, 200, [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$type}-sample.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ] );

    }

    public function financial_transactions( Request $request )
    {
        $return = ['transactions' => [], 'table' => [], 'filters' => []];

        if ( count( $request->all() ) > 0 )
        {

// TODO - Needs to be improvised
            if ( $request->has( 'draw' ) && $request->draw )
            {
                $page      = $request->start == 0 ? 1 : ( $request->start / $request->length ) + 1;
                $page_size = $request->length;
            }
            else
            {
                $page      = 1;
                $page_size = 25;
            }

            $transactions = $this->ApiObj->Get_FinancialTransactions( $request->customer, $request->sales_rep, $request->from_date, $request->to_date, $request->po_number, $request->invoice_number, $request->cash_receipt_number, $page, $page_size );
            $table        = array( 'thead' => [
                'transaction_number' => 'Transaction Number',
                'transaction_date'   => 'Transaction Date',
                'total_quantity'     => 'Total Quantity',
                'customer_id'        => 'Customer Id',
                'status'             => 'Status',
                'total_amount'       => 'Total Amount',
                'transaction_type'   => 'Transaction Type',
                'actions'            => 'Actions'

            ], 'tbody' => [] );

            if ( isset( $transactions['FinancialTransactions'] ) )
            {

                foreach ( $transactions['FinancialTransactions'] as $transaction )
                {
                    $table['tbody'][] = [
                        'transaction_number' => $transaction['SalesInvoiceNo'] ? $transaction['SalesInvoiceNo'] : ( $transaction['CashReceiptNo'] ? $transaction['CashReceiptNo'] : 'N/A' ),
                        'transaction_date'   => isset( $transaction['TransactionDate'] ) ? CommonController::get_date_format( $transaction['TransactionDate'] ) : 'N/A',
                        'total_quantity'     => isset( $transaction['TotalQty'] ) ? $transaction['TotalQty'] : 'N/A',
                        'total_amount'       => ConstantsController::CURRENCY.number_format( $transaction['TotalAmount'], ConstantsController::ALLOWED_DECIMALS ),
                        'transaction_type'   => $transaction['TransactionType'],
                        'customer_id'        => isset( $transaction['CustomerID'] ) ? $transaction['CustomerID'] : 'N/A',
                        'status'             => isset( $transaction['Status'] ) ? $transaction['Status'] : 'N/A',
                        'actions'            => [['type' => 'modal', 'label' => 'View Details']],
                        'details'            => [
                            'heading' => $transaction['SalesInvoiceNo'] ? $transaction['SalesInvoiceNo'] : ( $transaction['CashReceiptNo'] ? $transaction['CashReceiptNo'] : 'N/A' ),
                            'body'    => [
                                'sections' => [
                                    [
                                        'title'   => 'General',
                                        'content' => [
                                            'transaction_number' => $transaction['SalesInvoiceNo'] ? $transaction['SalesInvoiceNo'] : ( $transaction['CashReceiptNo'] ? $transaction['CashReceiptNo'] : 'N/A' ),
                                            'transaction_date'   => isset( $transaction['TransactionDate'] ) ? CommonController::get_date_format( $transaction['TransactionDate'] ) : 'N/A',
                                            'total_quantity'     => isset( $transaction['TotalQty'] ) ? $transaction['TotalQty'] : 'N/A',
                                            'total_amount'       => ConstantsController::CURRENCY.number_format( $transaction['TotalAmount'], ConstantsController::ALLOWED_DECIMALS ),
                                            'transaction_type'   => $transaction['TransactionType'],
                                            'customer_id'        => isset( $transaction['CustomerID'] ) ? $transaction['CustomerID'] : 'N/A',
                                            'status'             => isset( $transaction['Status'] ) ? $transaction['Status'] : 'N/A'
                                        ]
                                    ],
                                    [
                                        'title'   => 'Billing Details',
                                        'content' => $transaction['BillToAddress']
                                    ],
                                    [
                                        'title'   => 'Shipping Details',
                                        'content' => $transaction['ShipToAddress']
                                    ],
                                    [
                                        'title'   => 'Details',
                                        'content' => $transaction['Details']
                                    ]

                                ]
                            ]
                        ]

                    ];
                }

                if ( $request->has( 'draw' ) && $request->draw )
                {
                    die( json_encode(
                        [
                            'recordsFiltered' => $transactions['TotalRows'],
                            'recordsTotal'    => $transactions['TotalRows'],
                            'draw'            => $request->draw + 1,
                            'data'            => $table['tbody']
                        ]
                    ) );
                }

            }

            View::share( 'transactions', $transactions );
            View::share( 'table', $table );
        }

        $filters = [
            [
                'title'       => 'Sales Rep',
                'type'        => 'hidden',
                'placeholder' => '',
                'value'       => Auth::user()->is_sale_rep ? Auth::user()->customer_id : ''
            ],
            [
                'title'       => 'From Date',
                'type'        => 'date',
                'attribues'   => ' data-required="true" ',
                'placeholder' => '',
                'value'       => $request->from_date ? $request->from_date : CommonController::get_date_format( '-1 month' )
            ],
            [
                'title'       => 'To Date',
                'type'        => 'date',
                'attribues'   => ' data-required="true" ',
                'placeholder' => '',
                'value'       => $request->to_date ? $request->to_date : CommonController::get_date_format( date( 'Y-m-d' ) )
            ],
            [
                'title'       => 'Customer',
                'type'        => Auth::user()->is_customer ? 'hidden' : 'select',
                'options'     => $this->get_customers_dropdown_options(),
                'placeholder' => '',
                'value'       => $request->has( 'customer' ) ? $request->customer : Auth::user()->customer_id
            ],
            [
                'title'       => 'PO Number',
                'type'        => 'text',
                'placeholder' => '',
                'attribues'   => ' maxlength="255" ',
                'value'       => $request->po_number
            ],
            [
                'title'       => 'Invoice Number',
                'type'        => 'number',
                'attribues'   => ' maxlength="255" ',
                'placeholder' => '',
                'value'       => $request->invoice_number
            ],

            [
                'title'       => 'Cash Receipt Number',
                'type'        => 'number',
                'attribues'   => ' maxlength="255" ',
                'placeholder' => '',
                'value'       => $request->cash_receipt_number
            ]
        ];

        View::share( 'filters', $filters );
        View::share( 'title', 'Financial Transactions' );
        View::share( 'paginated', 'yes' );

        return view( 'dashboard.generic-report' );
    }

    // TODO : The dashboard for the LR needs to have the icons.
    public function get_credit_memos( Request $request )
    {

        $return = ['memos' => [], 'table' => [], 'filters' => []];

        if ( count( $request->all() ) > 0 && isset( $request->submit ) )
        {

// TODO - Needs to be improvised
            if ( $request->has( 'draw' ) && $request->draw )
            {
                $page      = $request->start == 0 ? 1 : ( $request->start / $request->length ) + 1;
                $page_size = $request->length;
            }
            else
            {
                $page      = 1;
                $page_size = 25;
            }

            $memos = $this->ApiObj->Get_CreditMemos( $request->customer, $request->sales_rep, $request->from_date, $request->to_date, $request->invoice_number, $request->po_number, $page, $page_size );
            $table = array( 'thead' => [
                'memo_number'    => 'Credit Number',
                'customer_id'    => 'Customer ID',
                'customer_po'    => 'Customer PO',
                'total_quantity' => 'Total Quantity',
                'total_amount'   => 'Total Amount',
                'status'         => 'Status',
                'actions'        => 'Actions'
            ], 'tbody' => [] );

            if ( isset( $memos['CreditMemos'] ) )
            {

                foreach ( $memos['CreditMemos'] as $memo )
                {
                    $table['tbody'][] = [
                        'memo_number'    => isset( $memo['SalesInvoiceNo'] ) ? $memo['SalesInvoiceNo'] : 'N/A',
                        'customer_id'    => $memo['CustomerID'],
                        'customer_po'    => $memo['CustomerPO'],
                        'total_quantity' => isset( $memo['TotalQty'] ) ? $memo['TotalQty'] : 'N/A',
                        'total_amount'   => ConstantsController::CURRENCY.number_format( $memo['TotalAmount'], ConstantsController::ALLOWED_DECIMALS ),
                        'status'         => isset( $memo['Status'] ) ? $memo['Status'] : 'N/A',
                        'actions'        => [['type' => 'modal', 'label' => 'View Details']],
                        'details'        => [
                            'heading' => $memo['SalesInvoiceNo'].' : '.$memo['CustomerID'],
                            'body'    => [
                                'sections' => [
                                    [
                                        'title'   => 'General',
                                        'content' => [
                                            'Invoice Number'   => $memo['SalesInvoiceNo'],
                                            'Customer ID'      => $memo['CustomerID'],
                                            'Customer PO'      => $memo['CustomerPO'],
                                            'Sales Order #'    => $memo['SalesOrderNo'],
                                            'Total Amount'     => ConstantsController::CURRENCY.number_format( $memo['TotalAmount'], ConstantsController::ALLOWED_DECIMALS ),
                                            'Payment Due Date' => CommonController::get_date_format( $memo['PaymentDueDate'] )
                                        ]
                                    ],
                                    [
                                        'title'   => 'Billing Details',
                                        'content' => $memo['BillToAddress']
                                    ],
                                    [
                                        'title'   => 'Shipping Details',
                                        'content' => $memo['ShipToAddress']
                                    ],
                                    [
                                        'title'   => 'Details',
                                        'content' => $memo['Details']
                                    ]
                                ]
                            ]
                        ]
                    ];
                }

                if ( $request->has( 'draw' ) && $request->draw )
                {
                    die( json_encode(
                        [
                            'recordsFiltered' => $memos['TotalRows'],
                            'recordsTotal'    => $memos['TotalRows'],
                            'draw'            => $request->draw + 1,
                            'data'            => $table['tbody']
                        ]
                    ) );
                }

            }

            $return['memos'] = $memos;
            $return['table'] = $table;
        }

        $filters = [
            [
                'title'       => 'Sales Rep',
                'type'        => 'hidden',
                'placeholder' => '',
                'value'       => Auth::user()->is_sale_rep ? Auth::user()->customer_id : ''
            ],
            [
                'title'       => 'From Date',
                'type'        => 'date',
                'attribues'   => ' data-required="true" ',
                'placeholder' => '',
                'value'       => $request->from_date ? $request->from_date : CommonController::get_date_format( '-1 month' )
            ],
            [
                'title'       => 'To Date',
                'type'        => 'date',
                'attribues'   => ' data-required="true" ',
                'placeholder' => '',
                'value'       => $request->to_date ? $request->to_date : CommonController::get_date_format( date( 'Y-m-d' ) )
            ],
            [
                'title'       => 'Customer',
                'type'        => Auth::user()->is_customer ? 'hidden' : 'select',
                'options'     => $this->get_customers_dropdown_options(),
                'placeholder' => '',
                'value'       => $request->has( 'customer' ) ? $request->customer : Auth::user()->customer_id
            ],
            [
                'title'       => 'PO Number',
                'type'        => 'text',
                'placeholder' => '',
                'attribues'   => ' maxlength="255" ',
                'value'       => $request->po_number
            ],
            [
                'title'       => 'Invoice Number',
                'type'        => 'number',
                'placeholder' => '',
                'attribues'   => ' maxlength="255" ',
                'value'       => $request->invoice_number
            ]
        ];

        $return['filters'] = $filters;

        return $return;
    }

    // TODO : The dashboard for the LR needs to have the icons.
    public function get_debit_memos( Request $request )
    {

        $return = ['memos' => [], 'table' => [], 'filters' => []];

        if ( count( $request->all() ) > 0 && isset( $request->submit ) )
        {
            if ( $request->has( 'draw' ) && $request->draw )
            {
                $page      = $request->start == 0 ? 1 : ( $request->start / $request->length ) + 1;
                $page_size = $request->length;
            }
            else
            {
                $page      = 1;
                $page_size = 25;
            }
            // echo "<pre>" . print_r($request->all(), 1). "</pre>";
            $memos = $this->ApiObj->Get_DebitMemos( $request->customer, $request->from_date, $request->to_date, $request->invoice_number, $request->vendor, $page, $page_size );
            $table = array( 'thead' => [
                'memo_number'    => 'Memo Number',
                // 'customer_id'    => 'Customer ID',
                'vendor'         => 'Vendor ID',
                'total_quantity' => 'Total Quantity',
                'total_amount'   => 'Total Amount',
                'status'         => 'Status',
                'actions'        => 'Actions'
            ], 'tbody' => [] );

            if ( isset( $memos['DebitMemos'] ) )
            {

                foreach ( $memos['DebitMemos'] as $memo )
                {
                    $table['tbody'][] = [
                        'memo_number'    => isset( $memo['PayableInvoiceNo'] ) ? $memo['PayableInvoiceNo'] : 'N/A',
                        // 'customer_id'    => $memo['CustomerID'],
                        'vendor'         => $memo['VendorID'],
                        'total_quantity' => isset( $memo['TotalQty'] ) ? $memo['TotalQty'] : 'N/A',
                        'total_amount'   => ConstantsController::CURRENCY.number_format( $memo['TotalAmount'], ConstantsController::ALLOWED_DECIMALS ),
                        'status'         => isset( $memo['Status'] ) ? $memo['Status'] : 'N/A',
                        'actions'        => [['type' => 'modal', 'label' => 'View Details']],
                        'details'        => [
                            // 'heading' => $memo['PayableInvoiceNo'].' : '.$memo['CustomerID'],
                            'heading' => $memo['PayableInvoiceNo'],
                            'body'    => [
                                'sections' => [
                                    [
                                        'title'   => 'General',
                                        'content' => [
                                            'Invoice Number'   => $memo['PayableInvoiceNo'],
                                            // 'Customer ID'      => $memo['CustomerID'],
                                            'Vendor ID'        => $memo['VendorID'],
                                            'Sales Order #'    => $memo['SalesOrderNo'],
                                            'Total Amount'     => $memo['TotalAmount'],
                                            'Payment Due Date' => CommonController::get_date_format( $memo['PaymentDueDate'] )
                                        ]
                                    ],
                                    [
                                        'title'   => 'Billing Details',
                                        'content' => $memo['BillToAddress']
                                    ],
                                    [
                                        'title'   => 'Details',
                                        'content' => $memo['Details']
                                    ]
                                ]
                            ]
                        ]
                    ];
                }

                if ( $request->has( 'draw' ) && $request->draw )
                {
                    die( json_encode(
                        [
                            'recordsFiltered' => $memos['TotalRows'],
                            'recordsTotal'    => $memos['TotalRows'],
                            'draw'            => $request->draw + 1,
                            'data'            => $table['tbody']
                        ]
                    ) );
                }

            }

            $return['memos'] = $memos;
            $return['table'] = $table;
        }

        $filters = [
            [
                'title'       => 'Sales Rep',
                'type'        => 'hidden',
                'placeholder' => '',
                'value'       => Auth::user()->is_sale_rep ? Auth::user()->customer_id : ''
            ],
            [
                'title'       => 'From Date',
                'type'        => 'date',
                'attribues'   => ' data-required="true" ',
                'placeholder' => '',
                'value'       => $request->from_date ? $request->from_date : CommonController::get_date_format( '-1 month' )
            ],
            [
                'title'       => 'To Date',
                'type'        => 'date',
                'attribues'   => ' data-required="true" ',
                'placeholder' => '',
                'value'       => $request->to_date ? $request->to_date : CommonController::get_date_format( date( 'Y-m-d' ) )
            ],
            [
                'title'       => 'Customer',
                'type'        => Auth::user()->is_customer ? 'hidden' : 'select',
                'options'     => $this->get_customers_dropdown_options(),
                'placeholder' => '',
                'value'       => $request->has( 'customer' ) ? $request->customer : Auth::user()->customer_id
            ],
            [
                'title'       => 'Vendor',
                'type'        => 'text',
                'placeholder' => '',
                'value'       => $request->vendor
            ],
            [
                'title'       => 'Invoice Number',
                'type'        => 'number',
                'placeholder' => '',
                'attribues'   => ' maxlength="255" ',
                'value'       => $request->invoice_number
            ]
        ];

        $return['filters'] = $filters;

        return $return;
    }

    public function get_invoices( Request $request )
    {

        $return = ['invoices' => [], 'table' => [], 'filters' => []];

        if ( count( $request->all() ) > 0 )
        {

// TODO - Needs to be improvised
            if ( $request->has( 'draw' ) && $request->draw )
            {
                $page      = $request->start == 0 ? 1 : ( $request->start / $request->length ) + 1;
                $page_size = $request->length;
            }
            else
            {
                $page      = 1;
                $page_size = 25;
            }

            $invoices = $this->ApiObj->Get_Invoices( $request->customer, $request->sales_rep, $request->invoice_number, $request->po_number, $request->from_date, $request->to_date, $page, $page_size );
            $table    = array( 'thead' => [
                'invoice_no'     => 'Sale Invoice Number',
                'invoice_date'   => 'Sale Invoice Date',
                'customer_id'    => 'Customer ID',
                'total_quantity' => 'Total Quantity',
                'total_amount'   => 'Total Amount',
                'status'         => 'Status',
                'actions'        => 'Actions'
            ], 'tbody' => [] );

            if ( isset( $invoices['SalesInvoices'] ) )
            {

                foreach ( $invoices['SalesInvoices'] as $invoice )
                {
                    $table['tbody'][] = [
                        'invoice_no'     => $invoice['SalesInvoiceNo'],
                        'invoice_date'   => CommonController::get_date_format( $invoice['InvoiceDate'] ),
                        'customer_id'    => $invoice['CustomerID'],
                        'total_quantity' => isset( $invoice['TotalQty'] ) ? $invoice['TotalQty'] : 'N/A',
                        'total_amount'   => ConstantsController::CURRENCY.number_format( $invoice['TotalAmount'], ConstantsController::ALLOWED_DECIMALS ),
                        'status'         => isset( $invoice['Status'] ) ? $invoice['Status'] : 'N/A',
                        'actions'        => [['type' => 'modal', 'label' => 'View Details']],
                        'details'        => [
                            'heading' => $invoice['SalesInvoiceNo'].' : '.$invoice['CustomerID'],
                            'body'    => [
                                'sections' => [
                                    [
                                        'title'   => 'General',
                                        'content' => [
                                            'Invoice Number'   => $invoice['SalesInvoiceNo'],
                                            'Customer ID'      => $invoice['CustomerID'],
                                            'Customer PO'      => $invoice['CustomerPO'],
                                            'Sales Order #'    => $invoice['SalesOrderNo'],
                                            'Total Amount'     => ConstantsController::CURRENCY.number_format( $invoice['TotalAmount'], ConstantsController::ALLOWED_DECIMALS ),
                                            'Invoice Date'     => CommonController::get_date_format( $invoice['InvoiceDate'] ),
                                            'Payment Due Date' => CommonController::get_date_format( $invoice['PaymentDueDate'] )
                                        ]
                                    ],
                                    [
                                        'title'   => 'Billing Details',
                                        'content' => $invoice['BillToAddress']
                                    ],
                                    [
                                        'title'   => 'Shipping Details',
                                        'content' => $invoice['ShipToAddress']
                                    ],
                                    [
                                        'title'   => 'Details',
                                        'content' => isset( $invoice['OrderTrackingDetail'] ) ? [
                                            'tabs' => [
                                                'products' => $invoice['Details'],
                                                'tracks'   => isset( $invoice['OrderTrackingDetail'] ) ? $invoice['OrderTrackingDetail'] : []
                                            ]
                                        ] : $invoice['Details']
                                    ]
                                ]
                            ]
                        ]
                    ];
                }

                if ( $request->has( 'draw' ) && $request->draw )
                {
                    die( json_encode(
                        [
                            'recordsFiltered' => $invoices['TotalRows'],
                            'recordsTotal'    => $invoices['TotalRows'],
                            'draw'            => $request->draw + 1,
                            'data'            => $table['tbody']
                        ]
                    ) );
                }

            }

            $return['invoices'] = $invoices;
            $return['table']    = $table;
        }

        $filters = [
            [
                'title'       => 'Sales Rep',
                'type'        => 'hidden',
                'placeholder' => '',
                'value'       => Auth::user()->is_sale_rep ? Auth::user()->customer_id : ''
            ],
            [
                'title'       => 'From Date',
                'type'        => 'date',
                'attribues'   => ' data-required="true" ',
                'placeholder' => '',
                'value'       => $request->from_date ? $request->from_date : CommonController::get_date_format( '-1 month' )
            ],
            [
                'title'       => 'To Date',
                'type'        => 'date',
                'attribues'   => ' data-required="true" ',
                'placeholder' => '',
                'value'       => $request->to_date ? $request->to_date : CommonController::get_date_format( date( 'Y-m-d' ) )
            ],
            [
                'title'       => 'Customer',
                'type'        => Auth::user()->is_customer ? 'hidden' : 'select',
                'options'     => $this->get_customers_dropdown_options(),
                'placeholder' => '',
                'value'       => $request->has( 'customer' ) ? $request->customer : Auth::user()->customer_id
            ],
            [
                'title'       => 'PO Number',
                'type'        => 'text',
                'placeholder' => '',
                'attribues'   => ' maxlength="255" ',
                'value'       => $request->po_number
            ],
            [
                'title'       => 'Invoice Number',
                'type'        => 'number',
                'placeholder' => '',
                'attribues'   => ' maxlength="255" ',
                'value'       => $request->invoice_number
            ]
        ];

        $return['filters'] = $filters;

        return $return;
    }

    public function invoice( Request $request )
    {
        $data = $this->get_invoices( $request );
        View::share( 'invoices', $data['invoices'] );
        View::share( 'table', $data['table'] );
        View::share( 'filters', $data['filters'] );
        View::share( 'paginated', 'yes' );

        View::share( 'title', 'Invoices' );

        return view( 'dashboard.generic-report' );
    }

    public function payment_options()
    {
        return view( 'dashboard.payment-options' );
    }

    public function view_order_bl(Request $request){
        if ( count( $request->all() ) > 0 )
        {
            if ( $request->has( 'draw' ) && $request->draw )
            {
                $page      = $request->start == 0 ? 1 : ( $request->start / $request->length ) + 1;
                $page_size = $request->length;
            }
            else
            {
                $page      = 1;
                $page_size = 25;
            }

            $view_orders = $this->ApiObj->View_BL_Order( $request->customer, $request->external_number, $request->from_date, $request->to_date, $request->sales_rep, $page, $page_size, $request->customer_po, $request->order_number );
            $table       = array( 'thead' => [
                'order_no'     => 'Order Number',
                'customer_id'  => 'Customer ID',
                'customer_po'  => 'Customer PO',
                'total_Amount' => 'Total Amount',
                'total_qty'    => 'Total Quantity',
                'status'       => 'Status',
                'order_date'   => 'Order Date',
                'actions'      => 'Actions',
                'other_actions' => 'Reports'
            ], 'tbody' => [] );

            if ( isset( $view_orders['Orders'] ) )
            {

                foreach ( $view_orders['Orders'] as $view_order )
                {
                    $table['tbody'][] = [
                        'order_no'     => $view_order['Header']['OrderNo'],
                        'customer_id'  => $view_order['Header']['CustomerID'],
                        'customer_po'  => $view_order['Header']['CustomerPO'],
                        'total_Amount' => ConstantsController::CURRENCY.number_format( $view_order['Header']['TotalAmount'], ConstantsController::ALLOWED_DECIMALS ),
                        'total_qty'    => $view_order['Header']['TotalQty'],
                        'status'       => $view_order['Header']['Status'],
                        'tab'          => isset( $view_order['Header']['TabStatusDescription'] ) ? $view_order['Header']['TabStatusDescription'] : '',
                        'order_date'   => isset( $view_order['Header']['OrderDate'] ) ? CommonController::get_date_format( $view_order['Header']['OrderDate'] ) : 'N/A',
                        'actions'      => [['type' => 'modal', 'label' => 'View Details']],
                        'details'      => [
                            'heading' => $view_order['Header']['OrderNo'].' : '.$view_order['Header']['CustomerID'],
                            'body'    => [
                                'sections' => [
                                    [
                                        'title'   => 'General',
                                        'content' => [
                                            'OrderNo'       => $view_order['Header']['OrderNo'],
                                            'Customer ID'   => $view_order['Header']['CustomerID'],
                                            'Customer PO'   => $view_order['Header']['CustomerPO'],
                                            'Status'        => $view_order['Header']['Status'],
                                            'PaymentTerm'   => $view_order['Header']['PaymentTerm'],
                                            'OrderDate'     => CommonController::get_date_format( $view_order['Header']['OrderDate'] ),
                                            'TotalAmount'   => ConstantsController::CURRENCY.number_format( $view_order['Header']['TotalAmount'], ConstantsController::ALLOWED_DECIMALS ),
                                            'TotalQuantity' => $view_order['Header']['TotalQty']
                                        ]
                                    ],
                                    [
                                        'title'   => 'Billing Details',
                                        'content' => [
                                            'FirstName' => $view_order['Header']['BillingFirstName'],
                                            'LastName'  => $view_order['Header']['BillingLastName'],
                                            'Phone'     => $view_order['Header']['BillingPhone1'],
                                            'Email'     => $view_order['Header']['BillingEmail'],
                                            'Address 1' => $view_order['Header']['BillingAddress1'],
                                            'Address 2' => $view_order['Header']['BillingAddress2'],
                                            'City'      => $view_order['Header']['BillingCity'],
                                            'State'     => $view_order['Header']['BillingState'],
                                            'ZipCode'   => $view_order['Header']['BillingZipCode'],
                                            'Country'   => $view_order['Header']['BillingCountry']
                                        ]
                                    ],
                                    [
                                        'title'   => 'Shipping Details',
                                        'content' => [
                                            'FirstName'    => $view_order['Header']['ShippingFirstName'],
                                            'LastName'     => $view_order['Header']['ShippingLastName'],
                                            'Address 1'    => $view_order['Header']['ShippingAddress1'],
                                            'Address 2'    => $view_order['Header']['ShippingAddress2'],
                                            'State'        => $view_order['Header']['ShippingState'],
                                            'ZipCode'      => $view_order['Header']['ShippingZipCode'],
                                            'ShipViaCode'  => $view_order['Header']['ShipViaCode'],
                                            'ShippingCost' => $view_order['Header']['ShippingCost'],
                                            'ShippingDate' => CommonController::get_date_format( $view_order['Header']['ShippingDate'] )
                                        ]
                                    ],
                                    [
                                        'title'   => 'Detail',
                                        'content' => isset( $view_order['Header']['TabStatusDescription'] ) ? [
                                            'tabs' => [
                                                'products' => $view_order['Detail'],
                                                'tracks'   => isset( $view_order['OrderTrackingDetail'] ) ? $view_order['OrderTrackingDetail'] : [],
                                                'invoices' => isset( $view_order['OrderInvoiceDetail'] ) ? $view_order['OrderInvoiceDetail'] : []
                                            ]
                                        ] : $view_order['Detail']
                                    ]
                                ]
                            ]
                        ],
                        'other_actions' => [['type' => 'modal', 'label' => 'View Report', 'module' => 'Bl Order']],
                        'other_actions_details' => [
                            'OrderNo'   => $view_order['Header']['OrderNo'],
                        ],
                    ];
                }

                if ( $request->has( 'draw' ) && $request->draw )
                {
                    return response()->json(
                        [
                            'recordsFiltered' => $view_orders['TotalRows'],
                            'recordsTotal'    => $view_orders['TotalRows'],
                            'draw'            => $request->draw + 1,
                            'data'            => $table['tbody']
                        ]
                    );
                }

            }

            View::share( 'view_orders', $view_orders );
            View::share( 'table', $table );
        }

        $filters = [
            [
                'title'       => 'Sales Rep',
                'type'        => 'hidden',
                'placeholder' => '',
                'value'       => Auth::user()->is_sale_rep ? Auth::user()->customer_id : ''
            ],
            [
                'title'       => 'From Date',
                'type'        => 'date',
                'attribues'   => ' data-required="true" ',
                'placeholder' => '',
                'value'       => $request->from_date ? $request->from_date : CommonController::get_date_format( '-1 month' )
            ],
            [
                'title'       => 'To Date',
                'type'        => 'date',
                'attribues'   => ' data-required="true" ',
                'placeholder' => '',
                'value'       => $request->to_date ? $request->to_date : CommonController::get_date_format( date( 'Y-m-d' ) )
            ],
            [
                'title'       => 'Customer',
                'type'        => Auth::user()->is_customer ? 'hidden' : 'select',
                'options'     => $this->get_customers_dropdown_options(),
                'placeholder' => '',
                'value'       => $request->has( 'customer' ) ? $request->customer : Auth::user()->customer_id
            ],
            [
                'title'       => 'External Number',
                'type'        => 'hidden',
                'placeholder' => '',
                'value'       => $request->external_number
            ],
            [
                'title'       => 'Customer PO',
                'type'        => 'text',
                'attribues'   => ' data-required="false" ',
                'placeholder' => '',
                'value'       => $request->customer_po ? $request->customer_po : ''
            ],
            [
                'title'       => 'Order Number',
                'type'        => 'text',
                'attribues'   => ' data-required="false" ',
                'placeholder' => '',
                'value'       => $request->order_number ? $request->order_number : ''
            ],
        ];

        View::share( 'filters', $filters );
        View::share( 'title', 'Orders' );
        View::share( 'paginated', 'yes' );
        View::share( 'tabular', isset( $this->active_theme_json->general->tabular_orders ) && $this->active_theme_json->general->tabular_orders ? 'yes' : 'no' );

        return view( 'dashboard.genaric-bl-report' );
    }

    public function view_order( Request $request )
    {
        if ( count( $request->all() ) > 0 )
        {

// TODO - Needs to be improvised
            if ( $request->has( 'draw' ) && $request->draw )
            {
                $page      = $request->start == 0 ? 1 : ( $request->start / $request->length ) + 1;
                $page_size = $request->length;
            }
            else
            {
                $page      = 1;
                $page_size = 25;
            }

            $view_orders = $this->ApiObj->View_Order( $request->customer, $request->external_number, $request->from_date, $request->to_date, $request->sales_rep, $page, $page_size, $request->customer_po, $request->order_number );
            $table       = array( 'thead' => [
                'order_no'     => 'Order Number',
                'customer_id'  => 'Customer ID',
                'customer_po'  => 'Customer PO',
                'total_Amount' => 'Total Amount',
                'total_qty'    => 'Total Quantity',
                'status'       => 'Status',
                'order_date'   => 'Order Date',
                'actions'      => 'Actions',
                'other_actions' => 'Reports',
            ], 'tbody' => [] );

            if ( isset( $view_orders['Orders'] ) )
            {

                foreach ( $view_orders['Orders'] as $view_order )
                {
                    $table['tbody'][] = [
                        'order_no'     => $view_order['Header']['OrderNo'],
                        'customer_id'  => $view_order['Header']['CustomerID'],
                        'customer_po'  => $view_order['Header']['CustomerPO'],
                        'total_Amount' => ConstantsController::CURRENCY.number_format( $view_order['Header']['TotalAmount'], ConstantsController::ALLOWED_DECIMALS ),
                        'total_qty'    => $view_order['Header']['TotalQty'],
                        'status'       => $view_order['Header']['Status'],
                        'tab'          => isset( $view_order['Header']['TabStatusDescription'] ) ? $view_order['Header']['TabStatusDescription'] : '',
                        'order_date'   => isset( $view_order['Header']['OrderDate'] ) ? CommonController::get_date_format( $view_order['Header']['OrderDate'] ) : 'N/A',
                        'actions'      => [['type' => 'modal', 'label' => 'View Details']],
                        'other_actions' => [['type' => 'modal', 'label' => 'View Report', 'module' => 'Other']],
                        'other_actions_details' => [
                            'OrderNo'   => $view_order['Header']['OrderNo'],
                        ],
                        'details'      => [
                            'heading' => $view_order['Header']['OrderNo'].' : '.$view_order['Header']['CustomerID'],
                            'body'    => [
                                'sections' => [
                                    [
                                        'title'   => 'General',
                                        'content' => [
                                            'OrderNo'       => $view_order['Header']['OrderNo'],
                                            'Customer ID'   => $view_order['Header']['CustomerID'],
                                            'Customer PO'   => $view_order['Header']['CustomerPO'],
                                            'Status'        => $view_order['Header']['Status'],
                                            'PaymentTerm'   => $view_order['Header']['PaymentTerm'],
                                            'OrderDate'     => CommonController::get_date_format( $view_order['Header']['OrderDate'] ),
                                            'TotalAmount'   => ConstantsController::CURRENCY.number_format( $view_order['Header']['TotalAmount'], ConstantsController::ALLOWED_DECIMALS ),
                                            'TotalQuantity' => $view_order['Header']['TotalQty']
                                        ]
                                    ],
                                    [
                                        'title'   => 'Billing Details',
                                        'content' => [
                                            'FirstName' => $view_order['Header']['BillingFirstName'],
                                            'LastName'  => $view_order['Header']['BillingLastName'],
                                            'Phone'     => $view_order['Header']['BillingPhone1'],
                                            'Email'     => $view_order['Header']['BillingEmail'],
                                            'Address 1' => $view_order['Header']['BillingAddress1'],
                                            'Address 2' => $view_order['Header']['BillingAddress2'],
                                            'City'      => $view_order['Header']['BillingCity'],
                                            'State'     => $view_order['Header']['BillingState'],
                                            'ZipCode'   => $view_order['Header']['BillingZipCode'],
                                            'Country'   => $view_order['Header']['BillingCountry']
                                        ]
                                    ],
                                    [
                                        'title'   => 'Shipping Details',
                                        'content' => [
                                            'FirstName'    => $view_order['Header']['ShippingFirstName'],
                                            'LastName'     => $view_order['Header']['ShippingLastName'],
                                            'Address 1'    => $view_order['Header']['ShippingAddress1'],
                                            'Address 2'    => $view_order['Header']['ShippingAddress2'],
                                            'State'        => $view_order['Header']['ShippingState'],
                                            'ZipCode'      => $view_order['Header']['ShippingZipCode'],
                                            'ShipViaCode'  => $view_order['Header']['ShipViaCode'],
                                            'ShippingCost' => $view_order['Header']['ShippingCost'],
                                            'ShippingDate' => CommonController::get_date_format( $view_order['Header']['ShippingDate'] )
                                        ]
                                    ],
                                    [
                                        'title'   => 'Detail',
                                        'content' => isset( $view_order['Header']['TabStatusDescription'] ) ? [
                                            'tabs' => [
                                                'products' => $view_order['Detail'],
                                                'tracks'   => isset( $view_order['OrderTrackingDetail'] ) ? $view_order['OrderTrackingDetail'] : [],
                                                'invoices' => isset( $view_order['OrderInvoiceDetail'] ) ? $view_order['OrderInvoiceDetail'] : []
                                            ]
                                        ] : $view_order['Detail']
                                    ]
                                ]
                            ]
                        ]
                    ];
                }

                if ( $request->has( 'draw' ) && $request->draw )
                {
                    return response()->json(
                        [
                            'recordsFiltered' => $view_orders['TotalRows'],
                            'recordsTotal'    => $view_orders['TotalRows'],
                            'draw'            => $request->draw + 1,
                            'data'            => $table['tbody']
                        ]
                    );
                }

            }

            View::share( 'view_orders', $view_orders );
            View::share( 'table', $table );
        }

        $filters = [
            [
                'title'       => 'Sales Rep',
                'type'        => 'hidden',
                'placeholder' => '',
                'value'       => Auth::user()->is_sale_rep ? Auth::user()->customer_id : ''
            ],
            [
                'title'       => 'From Date',
                'type'        => 'date',
                'attribues'   => ' data-required="true" ',
                'placeholder' => '',
                'value'       => $request->from_date ? $request->from_date : CommonController::get_date_format( '-1 month' )
            ],
            [
                'title'       => 'To Date',
                'type'        => 'date',
                'attribues'   => ' data-required="true" ',
                'placeholder' => '',
                'value'       => $request->to_date ? $request->to_date : CommonController::get_date_format( date( 'Y-m-d' ) )
            ],
            [
                'title'       => 'Customer',
                'type'        => Auth::user()->is_customer ? 'hidden' : 'select',
                'options'     => $this->get_customers_dropdown_options(),
                'placeholder' => '',
                'value'       => $request->has( 'customer' ) ? $request->customer : Auth::user()->customer_id
            ],
            [
                'title'       => 'External Number',
                'type'        => 'hidden',
                'placeholder' => '',
                'value'       => $request->external_number
            ],
            [
                'title'       => 'Customer PO',
                'type'        => 'text',
                'attribues'   => ' data-required="false" ',
                'placeholder' => '',
                'value'       => $request->customer_po ? $request->customer_po : ''
            ],
            [
                'title'       => 'Order Number',
                'type'        => 'text',
                'attribues'   => ' data-required="false" ',
                'placeholder' => '',
                'value'       => $request->order_number ? $request->order_number : ''
            ],
        ];

        View::share( 'filters', $filters );
        View::share( 'title', 'Orders' );
        View::share( 'paginated', 'yes' );
        View::share( 'tabular', isset( $this->active_theme_json->general->tabular_orders ) && $this->active_theme_json->general->tabular_orders ? 'yes' : 'no' );

        return view( 'dashboard.generic-report' );
    }

    public function view_return( Request $request )
    {

        if ( count( $request->all() ) > 0 )
        {

// TODO - Needs to be improvised
            if ( $request->has( 'draw' ) && $request->draw )
            {
                $page      = $request->start == 0 ? 1 : ( $request->start / $request->length ) + 1;
                $page_size = $request->length;
            }
            else
            {
                $page      = 1;
                $page_size = 25;
            }

            $rmas = $this->ApiObj->Get_View_Return( $request->customer, $request->sales_rep, $request->from_date, $request->to_date, $request->rma_number, $request->invoice_number, $request->packing_slip_number, $request->order_number, $page, $page_size );

            $table = array( 'thead' => [
                'rma_no'                 => 'RMA Number',
                'customer_return_number' => 'Customer Return #',
                'credit_memo_number'     => 'Credit Memo #',
                'return_date'            => 'Return Date',
                'quantity'               => 'Quantity',
                'amount'                 => 'Amount',
                'status'                 => 'Status',
                'actions'                => 'Actions'
            ], 'tbody' => [] );

            if ( isset( $rmas['RMAs'] ) )
            {

                foreach ( $rmas['RMAs'] as $rma )
                {
                    $table['tbody'][] = [
                        'rma_no'                 => $rma['RMANo'],
                        'customer_return_number' => isset( $rma['CustomerReturnNo'] ) ? $rma['CustomerReturnNo'] : 'N/A',
                        'credit_memo_number'     => isset( $rma['CreditMemoNo'] ) ? $rma['CreditMemoNo'] : 'N/A',
                        'return_date'            => isset( $rma['RMADate'] ) ? CommonController::get_date_format( $rma['RMADate'] ) : 'N/A',
                        'quantity'               => $rma['TotalQuantity'],
                        'amount'                 => ConstantsController::CURRENCY.number_format( $rma['TotalAmount'], ConstantsController::ALLOWED_DECIMALS ),
                        'status'                 => isset( $rma['Status'] ) ? $rma['Status'] : 'N/A',
                        'actions'                => [['type' => 'modal', 'label' => 'View Details']],
                        'details'                => [
                            'heading' => $rma['RMANo'].' : '.$rma['CustomerID'],
                            'body'    => [
                                'sections' => [
                                    [
                                        'title'   => 'General',
                                        'content' => [
                                            'Rma Number'    => $rma['RMANo'],
                                            'Customer ID'   => $rma['CustomerID'],
                                            'Customer Name' => $rma['CustomerName'],
                                            'Sales Order #' => $rma['SalesOrderNo'],
                                            'Total Amount'  => ConstantsController::CURRENCY.number_format( $rma['TotalAmount'], ConstantsController::ALLOWED_DECIMALS )
                                        ]
                                    ],

                                    [
                                        'title'   => 'Details',
                                        'content' => $rma['Details']
                                    ]

                                ]
                            ]
                        ]
                    ];
                }

                if ( $request->has( 'draw' ) && $request->draw )
                {
                    return response()->json(
                        [
                            'recordsFiltered' => $rmas['TotalRows'],
                            'recordsTotal'    => $rmas['TotalRows'],
                            'draw'            => $request->draw + 1,
                            'data'            => $table['tbody']
                        ]
                    );
                }

            }

            View::share( 'rmas', $rmas );
            View::share( 'table', $table );
        }

        $filters = [
            [
                'title'       => 'Sales Rep',
                'type'        => 'hidden',
                'placeholder' => '',
                'value'       => Auth::user()->is_sale_rep ? Auth::user()->customer_id : ''
            ],
            [
                'title'       => 'RMA Number',
                'type'        => 'number',
                'placeholder' => '',
                'attribues'   => ' maxlength="255" ',
                'value'       => $request->rma_number
            ],

            [
                'title'       => 'Invoice Number',
                'type'        => 'number',
                'placeholder' => '',
                'attribues'   => ' maxlength="255" ',
                'value'       => $request->invoice_number
            ],
            [
                'title'       => 'Packing Slip Number',
                'type'        => 'number',
                'placeholder' => '',
                'attribues'   => ' maxlength="255" ',
                'value'       => $request->packing_slip_number
            ],

            [
                'title'       => 'Order Number',
                'type'        => 'number',
                'placeholder' => '',
                'attribues'   => ' maxlength="255" ',
                'value'       => $request->order_number
            ],
            [
                'title'       => 'Customer',
                'type'        => Auth::user()->is_customer ? 'hidden' : 'select',
                'options'     => $this->get_customers_dropdown_options(),
                'placeholder' => '',
                'value'       => $request->has( 'customer' ) ? $request->customer : Auth::user()->customer_id
            ],
            [
                'title'       => 'From Date',
                'type'        => 'date',
                'attribues'   => ' data-required="true" ',
                'placeholder' => '',
                'value'       => $request->from_date ? $request->from_date : CommonController::get_date_format( '-1 month' )
            ],

            [
                'title'       => 'To Date',
                'type'        => 'date',
                'attribues'   => ' data-required="true" ',
                'placeholder' => '',
                'value'       => $request->to_date ? $request->to_date : CommonController::get_date_format( date( 'Y-m-d' ) )
            ]

        ];

        View::share( 'filters', $filters );
        View::share( 'title', 'Returns' );
        View::share( 'paginated', 'yes' );

        return view( 'dashboard.generic-report' );
    }

    //Sales history
    public function sales_history(Request $request){
        if ( count( $request->all() ) > 0 )
        {
            $from_date = $request->has('from_date') ? $request->from_date : Carbon::now()->format('Y-m-d');
            $to_date = $request->has('to_date') ? $request->to_date : Carbon::now()->format('Y-m-d');
            $report = $this->ApiObj->Get_SalesReport( $request->sales_rep, $request->customer, $request->report_title, $from_date, $to_date, $request->quality, $request->item_id, $request->collection, $request->design);

            if( $report['Success'] )
            {
                View::share( 'ReportData', $report['ReportData'] );
                View::share( 'ReportTitle', $report['ReportTitle'] );
                View::share( 'PreviewID', $report['PreviewID'] );
            }

        }

        $reports_title  = array();
        $reports        = $this->ApiObj->Get_AllReports();

        if ( $reports['Success'] )
        {
            foreach ( $reports['ReportList'] as $report )
            {
                $reports_title[] =
                    [
                    'value' => $report['KeyID'],
                    'label' => $report['Description'],
                    'fields' => [
                        'customer_show' => $report['CustomerField'],
                        'date_field' => $report['DateField'],
                        'item_id_show' => $report['ItemIDField'],
                        'quality_show' => $report['QualityField'],
                        'collection_show' => $report['CollectionField'],
                        'design_show' => $report['DesignField']
                    ]
                ];

            }

        }

        $filters = [
            [
                'title'       => 'Report Title',
                'type'        => 'select',
                'id'          => 'report_title',
                'options'     => $reports_title ? $reports_title : '',
                'placeholder' => '',
                'value'       => $request->report_title ? $request->report_title : ''
            ],
            [
                'title'       => 'From Date',
                'type'        => 'date',
                'id'          => 'date_field',
                'attribues'   => ' data-required="true" ',
                'placeholder' => '',
                'value'       => $request->from_date ? CommonController::get_date_format( $request->from_date ) : CommonController::get_date_format( '-1 month' )
            ],
            [
                'title'       => 'To Date',
                'type'        => 'date',
                'id'          => 'date_field',
                'attribues'   => ' data-required="true" ',
                'placeholder' => '',
                'value'       => $request->to_date ? CommonController::get_date_format( $request->to_date ) : CommonController::get_date_format( date( 'Y-m-d' ) )
            ],
            [
                'title'       => 'Customer',
                'type'        => Auth::user()->is_customer ? 'hidden' : 'select',
                'id'          => 'customer_show',
                'options'     => $this->get_customers_dropdown_options(),
                'placeholder' => '',
                'value'       => $request->has( 'customer' ) ? $request->customer : ''
            ],
            [
                'title'       => 'Item Id',
                'type'        => 'text',
                'id'          => 'item_id_show',
                'attribues'   => '',
                'placeholder' => 'Enter Item ID',
                'value'       => $request->has( 'item_id' ) ? $request->item_id : ''
            ],
            [
                'title'       => 'Quality',
                'type'        => 'text',
                'id'          => 'quality_show',
                'attribues'   => '',
                'placeholder' => 'Enter Quality',
                'value'       => $request->has( 'quality' ) ? $request->quality : ''
            ],
            [
                'title'       => 'Collection',
                'type'        => 'text',
                'id'          => 'collection_show',
                'attribues'   => '',
                'placeholder' => 'Enter Collection ',
                'value'       => $request->has( 'collection' ) ? $request->collection : ''
            ],
            [
                'title'       => 'Design',
                'type'        => 'text',
                'id'          => 'design_show',
                'attribues'   => '',
                'placeholder' => 'Enter Design',
                'value'       => $request->has( 'design' ) ? $request->design : ''
            ],
        ];
        View::share( 'filters', $filters );
        View::share( 'reports_title', $reports_title );

        return view( 'dashboard.sales-history' );
    }

    public function order_report(Request $request)
    {
        try {
            $SalesRepId = $request->has('SalesRepId') ? $request->SalesRepId : '';
            $CustomerId = $request->has('CustomerId') ? $request->CustomerId : '';
            $MenuTag = $request->has('MenuTag') ? $request->MenuTag : 'View Order';
            $DocumentNo = $request->has('DocumentNo') ? $request->DocumentNo : 0000;
           // dd($SalesRepId, $CustomerId, $MenuTag, $DocumentNo);
            $report = $this->ApiObj->Get_ViewDocumentsReport($SalesRepId, $CustomerId, $MenuTag, $DocumentNo);
            dd($report['document']['ReportData']);
            if( $report['document']['Success'] )
            {
                View::share( 'ReportData', $report['document']['ReportData'] );
                return $report['document']['ReportData'];
            }
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['error' => 'An error occurred. Please try again later.']);
        }
    }

    public function download_excel(Request $request)
    {
        if ( count( $request->all() ) > 0 )
        {

            $report = $this->ApiObj->DownloadExcelReports( $request->report_title, $request->preview_id );

            if( $report['Success'] )
            {
                return response()->json(['success' => 1, 'data' => $report]);
            }
            else
            {
                return response()->json(['success' => 0]);
            }

        }
    }

    public function sales_history_bl(Request $request){
        if ( count( $request->all() ) > 0 )
        {
            $from_date = $request->has('from_date') ? $request->from_date : Carbon::now()->format('Y-m-d');
            $to_date = $request->has('to_date') ? $request->to_date : Carbon::now()->format('Y-m-d');
            $report = $this->ApiObj->Get_BLSalesReport( $request->sales_rep, $request->customer, $request->report_title, $from_date, $to_date, $request->quality, $request->item_id, $request->collection, $request->design);

            if( $report['Success'] )
            {
                View::share( 'ReportData', $report['ReportData'] );
                View::share( 'ReportTitle', $report['ReportTitle'] );
                View::share( 'PreviewID', $report['PreviewID'] );
            }

        }

        $reports_title  = array();
        $reports        = $this->ApiObj->Get_AllBLReports();

        if ( $reports['OutPut']['Success'] )
        {
            foreach ( $reports['OutPut']['ReportList'] as $report )
            {
                $reports_title[] =
                    [
                    'value' => $report['KeyID'],
                    'label' => $report['Description'],
                    'fields' => [
                        'customer_show' => $report['CustomerField'],
                        'date_field' => $report['DateField'],
                        'item_id_show' => $report['ItemIDField'],
                        'quality_show' => $report['QualityField'],
                        'collection_show' => $report['CollectionField'],
                        'design_show' => $report['DesignField']
                    ]
                ];

            }

        }
        $filters = [
            [
                'title'       => 'Report Title',
                'type'        => 'select',
                'id'          => 'report_title',
                'options'     => $reports_title ? $reports_title : '',
                'placeholder' => '',
                'value'       => $request->report_title ? $request->report_title : ''
            ],
            [
                'title'       => 'From Date',
                'type'        => 'date',
                'id'          => 'date_field',
                'attribues'   => ' data-required="true" ',
                'placeholder' => '',
                'value'       => $request->from_date ? CommonController::get_date_format( $request->from_date ) : CommonController::get_date_format( '-1 month' )
            ],
            [
                'title'       => 'To Date',
                'type'        => 'date',
                'id'          => 'date_field',
                'attribues'   => ' data-required="true" ',
                'placeholder' => '',
                'value'       => $request->to_date ? CommonController::get_date_format( $request->to_date ) : CommonController::get_date_format( date( 'Y-m-d' ) )
            ],
            [
                'title'       => 'Customer',
                'type'        => Auth::user()->is_customer ? 'hidden' : 'select',
                'id'          => 'customer_show',
                'options'     => $this->get_customers_dropdown_options(),
                'placeholder' => '',
                'value'       => $request->has( 'customer' ) ? $request->customer : ''
            ],
            [
                'title'       => 'Item Id',
                'type'        => 'text',
                'id'          => 'item_id_show',
                'attribues'   => '',
                'placeholder' => 'Enter Item ID',
                'value'       => $request->has( 'item_id' ) ? $request->item_id : ''
            ],
            [
                'title'       => 'Quality',
                'type'        => 'text',
                'id'          => 'quality_show',
                'attribues'   => '',
                'placeholder' => 'Enter Quality',
                'value'       => $request->has( 'quality' ) ? $request->quality : ''
            ],
            [
                'title'       => 'Collection',
                'type'        => 'text',
                'id'          => 'collection_show',
                'attribues'   => '',
                'placeholder' => 'Enter Collection ',
                'value'       => $request->has( 'collection' ) ? $request->collection : ''
            ],
            [
                'title'       => 'Design',
                'type'        => 'text',
                'id'          => 'design_show',
                'attribues'   => '',
                'placeholder' => 'Enter Design',
                'value'       => $request->has( 'design' ) ? $request->design : ''
            ],
        ];
        View::share( 'filters', $filters );
        View::share( 'reports_title', $reports_title );

        return view( 'dashboard.bl-sales-history' );
    }
}
