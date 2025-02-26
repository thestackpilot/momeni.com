@php
use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
@endphp

@extends('dashboard.layouts.app')
@section('title','Dashboard | Quotation')
@section('content')
<div class="wrapper admin-side">
    @include('dashboard.components.header')
    <main class="main-content">
        <div class="spinner-overlay quotes-spinner" style="display: none;">
            <div class="spinner"></div>
          </div>

        <section class="collection-section">
            <div class="container">
                <div class="d-flex flex-row">
                    <div class="col-lg-3 col-sm-6 col-6 sidebar-main">
                        @include('dashboard.components.sidebar')
                    </div>
                    <div class="col-lg-9 col-sm-12 col-12 py-3 quotes-container">
                        <div class="container-fluid bg-white">
                            <div class="quotes-btn-parent">
                                <div class="d-flex flex-row settings">
                                    <a href="javascript:void(0)" data-related-section="new-quotes" class="quotes-btn">New Quote</a>
                                    <a href="javascript:void(0)" data-related-section="view-active-quotes" class="quotes-btn active-btn">View Active Quotes</a>
                                </div>
                            </div>
                            <div>
                               <div style="" class="new-quotes mt-3">
                                <div class="container-fluid">
                                    <form action="" method="" >
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                    <div class="mb-3 sale_rep_box">
                                                        <label class="form-label">Customer ID</label>
                                                        <select class="form-control customer_id_sr" name="customer_id" id="customer_id">
                                                            <option disabled selected>Choose Customer</option>
                                                            @foreach ($customer as $single_customer)
                                                                <option value="{{ $single_customer['value'] }}">{{ $single_customer['label'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 customer_box">
                                                        <label class="form-label">Customer ID</label>
                                                        <input type="text" name="customer_id" id="customer_id" class="form-control customer_id_c" readonly disabled>
                                                    </div>
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Quotation Date</label>
                                                    <div class="input-group">
                                                        <input name="quotes_date" id="quotes_date" value="" class="form-control"  type="text" data-required="true">
                                                        <span class="input-group-addon">
                                                            <i class="bi bi-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Cancel Date</label>
                                                    <div class="input-group">
                                                        <input name="cancel_quote_date" id="cancel_quote_date" value="" class="form-control cancel_quote_date" type="text" data-required="true">
                                                        <span class="input-group-addon cancel-date-group">
                                                            <i class="bi bi-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-9 col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Style</label>
                                                    <select class="form-control js-example-basic-single" name="item_id" id="item_id">
                                                        <option disabled selected>Choose Style</option>
                                                        @foreach ($items as $single_item)
                                                            <option value="{{ $single_item['KeyID'] }}" data-rugcheck="{{ $single_item['IsRugPad']}}">{{ $single_item['Description'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Serging</label>
                                                    <select class="form-control" name="serging" id="serging">
                                                        @foreach ($sergingtypes as $serging_type)
                                                            <option value="{{ $serging_type['SergingTypeNo'] }}" data-sergingcharges="{{ $serging_type['Charges'] }}">{{ $serging_type['Description'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Cut Width</label>
                                                    <div class="mb-3 d-flex align-items-center justify-content-between">
                                                        <div class="input-group me-2">
                                                            <input type="number" class="form-control text-center small-input" name="widthF" id="widthF" placeholder="00" min="0" max="100">
                                                            <span class="input-group-text">Ft</span>
                                                        </div>
                                                        <div class="input-group ms-2">
                                                            <input type="number" class="form-control text-center small-input" name="widthI" id="widthI" placeholder="00" min="0" max="11" value="0">
                                                            <span class="input-group-text">In</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Cut Length</label>
                                                    <div class="mb-3 d-flex align-items-center justify-content-between">
                                                        <div class="input-group me-2">
                                                            <input type="number" class="form-control text-center small-input" name="lengthF" id="lengthF" placeholder="00" min="0" max="100">
                                                            <span class="input-group-text">Ft</span>
                                                        </div>
                                                        <div class="input-group ms-2">
                                                            <input type="number" class="form-control text-center small-input" name="lengthI" id="lengthI" placeholder="00" min="0" max="11" value="0">
                                                            <span class="input-group-text">In</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-2 offset-md-1 col-3">
                                                <div class="form-check mt-5">
                                                    <input class="form-check-input" type="checkbox" value="" name="reserve-stock" id="reserve-stock">
                                                    <label class="form-check-label" >
                                                        Reserve Stock
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-3">
                                                <div class="form-check mt-5">
                                                    <input class="form-check-input" type="checkbox" value="" name="add-rugpad" id="add-rugpad" disabled>
                                                    <label class="form-check-label" >
                                                    Add a Rug Pad
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="my-2 total-amount"><b>Total :</b> <span class="quote-total-price">$0.00</span></p>
                                        <a href="javascript:void(0)" class="btn btn-primary submit-btn-other generate-quote-btn mx-5">Create or Generate Quote</a>
                                        <a href="javascript:void(0)" class="btn btn-primary submit-btn save-quote-btn submit-disabled-link mx-5">Save Quote</a>
                                    </form>
                                </div>
                               </div>
                               <div style="display: none" class="view-active-quotes mt-3">
                                <div class="container-fluid">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class="p-3" style="background-color: #596460; color: #fff;">
                                              <tr>
                                                <th scope="col">Quotation ID</th>
                                                <th scope="col">Customer ID</th>
                                                <th scope="col">Quotation Date</th>
                                                <th scope="col">Cancel Date</th>
                                                <th scope="col">Reservation</th>
                                                <th scope="col">Status</th>
                                                <th></th>
                                              </tr>
                                            </thead>
                                            <tbody class="table-rows-custom">
                                                @foreach ($quotationsLists as $list)
                                                    @php
                                                        $qoDate = \DateTime::createFromFormat('m/d/Y h:i:s A', $list['QODate']);
                                                        $formattedqoDate = $qoDate ? $qoDate->format('Y-m-d') : null;
                                                        $cancelDate = \DateTime::createFromFormat('m/d/Y h:i:s A', $list['CancelDate']);
                                                        $formattedcancelDate = $cancelDate ? $cancelDate->format('Y-m-d') : null;
                                                    @endphp
                                                    <tr class="">
                                                        <td class="text-start">{{ $list['QuotationNo'] }}</td>
                                                        <td class="text-start">{{ $list['CustomerID'] }}</td>
                                                        <td class="text-start">{{ $formattedqoDate }}</td>
                                                        <td class="text-start">{{ $formattedcancelDate}}</td>
                                                        <td class="text-start">{{ $list['Reservation'] }}</td>
                                                        <td class="text-start">{{ $list['StatusDescription'] }}</td>
                                                        <td class="d-flex flex-row">
                                                            <form id="quoteForm" action="/dashboard/order-quote" method="POST">
                                                                @csrf
                                                                <input type="hidden" id="hiddenQuoteNo" name="QuotationNo" value="{{$list['QuotationNo']}}">
                                                                <button type="submit" class="btn btn-primary quotes-order-btn place-order-call text-center mx-1">Place Order</button>
                                                            </form>
                                                            <a class="btn btn-primary view-quote-btn text-center mx-1" data-quoteno="{{ $list['QuotationNo'] }}">View Quote</a>
                                                            <a class="void-quote-btn text-center mx-1" data-quoteno="{{ $list['QuotationNo'] }}">
                                                                <i class="bi bi-x" style="color: red; font-size: 30px;"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                          </table>
                                    </div>
                                </div>
                               </div>
                            </div>

                            {{-- order modal --}}
                            <div class="modal fade" id="quoteorder" tabindex="-1" aria-labelledby="quoteorderLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                    <div class="modal-body text-center" id="quoteorderbody">
                                        <h1 class="my-2">BL QUOTES ORDER</h1>
                                        <p class="my-2"></p>
                                        <a href="{{ route('dashboard') }}" class="btn btn-dark p-2">Back To Dashboard</a>
                                    </div>
                                    </div>
                                </div>
                            </div>

                            {{-- report modal --}}
                            <div class="modal fade" id="quoteReportModal" tabindex="-1" aria-labelledby="quoteReportModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class="modal-content" style="border-radius: 20px !important;">
                                        <div class="modal-header" style="border-bottom: none !important;">
                                            <h5 class="modal-title sample-selext-title" id="quoteReportModalLabel">Quote Report</h5>
                                            <button type="button" class="btn-close btn-refresh" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" id="purchase-order-modal-container">
                                            <div id="report_details"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- void modal --}}
                            <div class="modal fade" id="voidConfirmation" tabindex="-1" aria-labelledby="voidConfirmationLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content text-center">
                                    <div class="modal-body py-4">
                                        <h4 class="mb-3 text-danger"><strong>Confirmation Required</strong></h4>
                                        <hr style="border: 1px solid #333;">
                                        <b><p class="mb-4 text-start" style="font-size:17px !important">Are you sure ? <br>You want to void Quotation No: <span id="voidConfirmationQuoteNo" class="text-danger fw-bold"></span></b>
                                        </p>
                                        <div class="text-end">
                                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal" id="closeVoidQuote">No</button>
                                            <button type="button" class="btn btn-primary" id="confirmVoidQuote">Yes</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    @include('dashboard.components.footer')
</div>
@endsection
@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

<style>
    body{
        overflow-x: hidden !important;
    }
    .quotes-container {
        background-color: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        padding: 10px 4px;
        margin: 10px 0;
    }
    .quotes-btn-parent{
        background-color: #F1F1F1;
    }
    .quotes-btn-parent a{
        text-decoration: none;
    }
    .quotes-btn{
        background-color: #F1F1F1;
        color: #660000 !important;
        padding: 15px 30px;
        border: none;
        border-radius: 2px;
        font-size: 16px;
        cursor: pointer;
    }
    .quotes-btn.active-btn{
        background-color: #660000;
        color: white !important;
    }
    .new-quotes label{
        font-size: 15px !important;
        color: #333333 !important;
    }
    .small-input {
        max-width: 80px !important
    }
    .total-amount{
        font-size: 1.3rem;
        position: absolute;
        bottom: 65px;
        right: 84px;
    }
    .submit-btn {
        position: absolute;
        bottom: 20px;
        right: 20px;
        background-color: #660000;
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 8px 50px;
        border-radius: 5px;
        border: none;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        margin: 0px 50px !important;
    }
    .submit-btn-other {
        position: absolute;
        bottom: 20px;
        right: 205px;
        background-color: #660000;
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 8px 50px;
        border-radius: 5px;
        border: none;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        margin: 0px 50px !important;
    }
    .submit-btn:hover {
        background-color: #880000;
        box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.2);
    }
    .submit-disabled-link {
        pointer-events: none !important;
        color: gray !important;
        text-decoration: none !important;;
    }
    .quotes-order-btn, .view-quote-btn{
        display: inline-block;
        padding: 10px 20px;
        font-size: 16px;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        color: #ffffff;
        background-color: #660000;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .table-rows-custom td{
        padding: 10px !important
    }
    .select2-container .select2-selection--single {
        font-size: 14px !important;
        color: #333333 !important;
        height: 40px !important;
        border: 1px solid #e4e4e4 !important;
        border-radius: 0 !important;
        background: transparent !important;
    }
    .select2-container .select2-selection--single .select2-selection__rendered{
        padding: 10px !important
    }
    .spinner-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5); /* Semi-transparent dull background */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999; /* Ensure it appears above other elements */
    }
    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3; /* Light gray */
        border-top: 5px solid #880000; /* Blue */
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    .admin-side .btn.btn-primary{
        line-height: inherit !important;
    }
    .void-quote-btn{
        cursor: pointer;
    }
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        top: 75% !important;
    }
    .quotes-btns-tag {
        pointer-events: none !important;
        cursor: not-allowed !important;
        color: gray !important;
    }
    .place-order-call{
        min-height: 45px !important
    }
    @media only screen and (min-width: 1700px) and (max-width: 2000px){
        .submit-btn {
            bottom: 450px;
        }
        .submit-btn-other {
            bottom: 450px;
        }
        .total-amount {
            bottom: 510px;
        }
    }
    @media only screen and (min-width: 750px) and (max-width: 1194px) {
        .submit-btn {
            bottom: -55px;
        }
        .submit-btn-other {
            bottom: -55px;
        }
    }
    @media (max-width: 768px) {
        .quotes-order-btn, .view-quote-btn{
            max-height: auto !important;
            font-size: 11px !important
        }
        .submit-btn{
            bottom: -116px !important;
        }
        .submit-btn-other {
            bottom: -67px !important;
            right: -21px !important;
        }
        .total-amount {
            bottom: 18px;
            right: 37px;
        }
    }
</style>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        // Hide show on the base customer or sales rep
        var is_sales_rep = '{{ Auth::user()->is_sale_rep }}';
        var is_customer = '{{ Auth::user()->is_customer }}';
        var is_customer_id = '{{ Auth::user()->customer_id }}';
        var sergingcharges = 0;
        if(is_sales_rep == 1 && is_customer == 0){
            $('.customer_box').addClass('d-none');
            $('.sale_rep_box').removeClass('d-none');
        }else{
            $('.sale_rep_box').addClass('d-none');
            $('.customer_box').removeClass('d-none');
            $('.customer_id_c').val(is_customer_id);
        }

        // Serging charges on firrst screen load
        sergingcharges = $('#serging option:selected').data('sergingcharges') || 0;

        // Dates Handle
        var currentDate = new Date();
        var currentDateFormatted = currentDate.toISOString().split('T')[0];
        $('#quotes_date').val(currentDateFormatted).prop('readonly', true);
        $('.cancel_quote_date').val(new Date(new Date().setDate(new Date().getDate() + 7)).toISOString().split('T')[0]);
        $('.cancel_quote_date').datepicker({
            format: 'yyyy-mm-dd',
            startDate: new Date(),
            endDate: new Date(new Date().setDate(new Date().getDate() + 13)),
            maxViewMode: 3,
            todayBtn: "linked",
            clearBtn: false,
            keyboardNavigation: false,
            autoclose: true,
            todayHighlight: true,
            toggleActive: true
        });
        $('.cancel-date-group').on('click', function() {
            $('#cancel_quote_date').focus();
        });

        // Tabs handle
        $('.settings a[data-related-sect="view-active-quotes"]').addClass('active-btn');
        $('.view-active-quotes').show();
        $('.new-quotes').hide();
        $('.settings a').click(function() {
            $('.quotes-btn').removeClass('active-btn');
            $(this).addClass('active-btn');
            $('.new-quotes, .view-active-quotes').hide();
            $('.' + $(this).attr('data-related-section')).show();
        });

        $('#item_id, .customer_id_sr').select2({});

        // Validation
        function checkForm() {
            if(is_sales_rep == 1 && is_customer == 0){
                var customer_id = $('#customer_id').val();
            }else{
                var customer_id = $('.customer_id_c').val();
            }
            var quotes_date = $('#quotes_date').val();
            var cancel_quote_date = $('#cancel_quote_date').val();
            var item_id = $('#item_id').val();
            var serging = $('#serging').val();
            var lengthF = $('#lengthF').val();
            var lengthI = $('#lengthI').val();
            var widthF = $('#widthF').val();
            var widthI = $('#widthI').val();

            console.log(`Customer ID: ${customer_id} quotes_date: ${quotes_date} cancel_quote_date: ${cancel_quote_date} item_id: ${item_id}  serging: ${serging} lengthF: ${lengthF} lengthI: ${lengthI} widthF: ${widthF} widthI: ${widthI}`);


            if (customer_id && quotes_date && cancel_quote_date && item_id && serging && lengthF && lengthI && widthF && widthI) {
                $('.submit-btn').removeClass('submit-disabled-link');
            } else {
                $('.submit-btn').addClass('submit-disabled-link');
            }
        }
        $('#customer_id, #quotes_date, #cancel_quote_date, #item_id, #serging, #lengthF, #lengthI, #widthF, #widthI').on('change', function() {
            checkForm();
        });
        checkForm();


        // Auto do inch is 1 if feet for width/lenght is 0
        $('#lengthF, #lengthI').on('change', function () {
            validateFeetinchInput('#lengthF', '#lengthI');
        });
        $('#widthF, #widthI').on('change', function () {
            validateFeetinchInput('#widthF', '#widthI');
        });
        function validateFeetinchInput(feetField, inchField) {
            var feetValue = $(feetField).val();
            var inchValue = $(inchField).val();
            if (feetValue == 0 && inchValue == 0) {
                $(inchField).val(1);
            }
        }

        // Get serging charges
        $('#serging').val(2).trigger('change');
        $('#serging').change(function() {
            sergingcharges = $('#serging option:selected').data('sergingcharges');

            if($(this).val() == 0){
                $('#widthF').prop('disabled', true);
                $('#widthI').prop('disabled', true);
            }else{
                $('#widthF').prop('disabled', false);
                $('#widthI').prop('disabled', false);
            }
        });

        // GENRATE PRICE
        $('.generate-quote-btn').on('click', function(e) {
            e.preventDefault();

            var customer_id = (is_sales_rep == 1 && is_customer == 0) ? $('#customer_id').val() : '{{ Auth::user()->customer_id }}';
            var item_id = $('#item_id').val();
            var serging = $('#serging').val();
            var lengthF = parseInt($('#lengthF').val());
            var lengthI = $('#lengthI').val();
            var widthF = parseInt($('#widthF').val());
            var widthI = $('#widthI').val();
            var addRugpad = $('#add-rugpad').is(':checked') ? "Y" : "N";

            var missingFields = [];
            if (!customer_id) missingFields.push("Customer ID");
            if (!item_id) missingFields.push("Item ID");
            if (!serging) missingFields.push("Serging");
            if (!lengthF) missingFields.push("Length (Feet)");
            if (!lengthI) missingFields.push("Length (Inches)");
            if (!widthF) missingFields.push("Width (Feet)");
            if (!widthI) missingFields.push("Width (Inches)");

            if (missingFields.length > 0) {
                toastr.error("The following fields are required: " + missingFields.join(", "), "Validation Error");
                return;
            }

            $.ajax({
                url: '/dashboard/quote-price',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    CustomerID: customer_id,
                    ItemID: item_id,
                    SergingType: serging,
                    CutLengthFeet: lengthF,
                    CutLengthInches: lengthI,
                    CutWidthFeet: widthF,
                    CutWidthInches: widthI,
                    RugPad: addRugpad,
                },
                beforeSend: function(){
                    $('.quotes-spinner').show();
                },
                success: function(response) {
                    if(response.success){
                        $('.quotes-spinner').hide();
                        $('.quote-total-price').text(`$${response.price}`)
                    }else{
                        $('.quotes-spinner').hide();
                        toastr.error(toastr.message);
                    }
                }
            });
        });

        // Save quote
        $('.save-quote-btn').on('click', function(e) {
            e.preventDefault();
            var customer_id = (is_sales_rep == 1 && is_customer == 0) ? $('#customer_id').val() : '{{ Auth::user()->customer_id }}';
            var quotes_date = $('#quotes_date').val();
            var cancel_quote_date = $('#cancel_quote_date').val();
            var item_id = $('#item_id').val();
            var serging = $('#serging').val();
            var lengthF = parseInt($('#lengthF').val());
            var lengthI = parseInt($('#lengthI').val());
            var widthF = parseInt($('#widthF').val());
            var widthI = parseInt($('#widthI').val());
            var addRugpad = $('#add-rugpad').is(':checked') ? true : false;
            var reserveStock = $('#reserve-stock').is(':checked') ? "Y" : "N";
            var length = lengthF * 12 + lengthI;
            var width  = widthF * 12 + widthI;


            console.log(`Customer ID: ${customer_id} quotes_date: ${quotes_date} cancel_quote_date: ${cancel_quote_date} item_id: ${item_id}  serging: ${serging} lengthF: ${lengthF} lengthI: ${lengthI} widthF: ${widthF} widthI: ${widthI}`);

            $.ajax({
                url: '/dashboard/save-quote',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    customer_id: customer_id,
                    quotes_date: quotes_date,
                    cancel_quote_date: cancel_quote_date,
                    item_id: item_id,
                    serging: serging,
                    sergingcharges: sergingcharges,
                    length: length,
                    width: width,
                    len_f: lengthF,
                    len_i: lengthI,
                    wid_f: widthF,
                    wid_i: widthI,
                    addRugpad: addRugpad,
                    reserveStock: reserveStock,
                },
                beforeSend: function(){
                    $('.quotes-spinner').show();
                },
                success: function(response) {
                    if(response.success){
                        toastr.success(response.message, {
                            hideDuration: 300,
                            closeButton: true,
                        });

                        $('#purchase-order-modal-container').empty();
                        var report_div = $('<div id="report_details"></div>');
                        $('#purchase-order-modal-container').append(report_div);

                        // Embed
                        if (window.innerWidth <= 1024){
                            $('#report_details').html('<canvas id="pdf-canvas" style="width: 100%;"></canvas>');
                            var binary = atob(response.reportdata);
                            var len = binary.length;
                            var buffer = new Uint8Array(len);
                            for (var i = 0; i < len; i++) {
                                buffer[i] = binary.charCodeAt(i);
                            }
                            var pdfData = buffer.buffer;
                            var loadingTask = pdfjsLib.getDocument({ data: pdfData });
                            loadingTask.promise.then(function (pdf) {
                                pdf.getPage(1).then(function (page) {
                                    var scale = 1.5;
                                    var viewport = page.getViewport({ scale: scale });

                                    var canvas = document.getElementById('pdf-canvas');
                                    var context = canvas.getContext('2d');
                                    canvas.width = viewport.width;
                                    canvas.height = viewport.height;

                                    var renderContext = {
                                        canvasContext: context,
                                        viewport: viewport
                                    };

                                    page.render(renderContext);
                                });
                            });
                        }else{
                            var obj = document.createElement('object');
                            obj.style.width = '100%';
                            obj.style.height = '842pt';
                            obj.type = 'application/pdf';
                            obj.data = 'data:application/pdf;base64,' + response.reportdata;
                            $(obj).insertAfter('#report_details');
                        }

                        // Download
                        var link = document.createElement('a');
                        link.innerHTML = 'Download Report';
                        link.className = 'btn btn-primary my-3 py-3';
                        link.download = 'Report.pdf';
                        link.href = 'data:application/octet-stream;base64,' + response.reportdata;
                        $(link).insertAfter('#report_details');

                        $('.btn-close').addClass('btn-refresh').removeClass('btn-close-without-refresh');

                        ReportExcelDownloadProcess(response.reportTitle, response.previewID, response.reportdata);
                    }else{
                        $('.quotes-spinner').hide();
                        toastr.error(response.message, {
                            hideDuration: 10000,
                            closeButton: true,
                        });
                    }
                }
            });
        });

        function ReportExcelDownloadProcess(reportTitle, previewID, b64){
            $.post('/dashboard/quote-report-excel', {
                _token: '{{ csrf_token() }}',
                reportTitle: reportTitle,
                previewID: previewID,
            }).done(function(response) {
                if (response.success) {
                        // Excel
                        var link = document.createElement('a');
                        link.innerHTML = 'Download Excel';
                        link.className = 'btn btn-primary my-3 py-3 mr-1';
                        link.download = 'Report.xls';
                        link.href = 'data:application/octet-stream;base64,' + response.data;
                        $(link).insertAfter('#report_details');

                        $('.quotes-spinner').hide();
                        $('#quoteReportModal').modal({ backdrop: 'static', keyboard: false });
                        $('#quoteReportModal').modal('show');
                }
            }).fail(function() {
                console.log('Error: Failed to get the excel data.');
            });
        }

        // Place order
        $('.quotes-order-btn1').on('click', function(e){
            e.preventDefault();
            var quote_id = $(this).data('quoteno');
            $.ajax({
                url: '/dashboard/order-quote',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    QuotationNo: quote_id,
                    UserNo: '{{  Auth::user()->spars_logged_user_no }}',
                },
                beforeSend: function(){
                    $('.quotes-spinner').show();
                },
                success: function(response) {
                    console.log('response.success', response);

                    if(response.success) {
                        $('#quoteorderbody p').text(`The order has been successfully placed based on the provided quotation. The order number associated with this transaction is ${response.order_no}.`);
                        $('#quoteorder').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                        $('.quotes-spinner').hide();
                        $('#quoteorder').modal('show');
                    }else{
                        $('.quotes-spinner').hide();
                        toastr.error(response.msg, {
                            hideDuration: 10000,
                            closeButton: true,
                        });
                    }
                },
            });
        });

        // View quote
        $('.view-quote-btn').on('click', function(e){
            e.preventDefault();
            var quote_id = $(this).data('quoteno');
            $.ajax({
                url: '/dashboard/view-quote',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    QuotationNo: quote_id,
                },
                beforeSend: function(){
                    $('.quotes-spinner').show();
                },
                success: function(response) {
                    if(response.success){

                        $('#purchase-order-modal-container').empty();
                        var report_div = $('<div id="report_details"></div>');
                        $('#purchase-order-modal-container').append(report_div);

                        // Embed
                        if (window.innerWidth <= 1024){
                            $('#report_details').html('<canvas id="pdf-canvas" style="width: 100%;"></canvas>');
                            var binary = atob(response.reportdata);
                            var len = binary.length;
                            var buffer = new Uint8Array(len);
                            for (var i = 0; i < len; i++) {
                                buffer[i] = binary.charCodeAt(i);
                            }
                            var pdfData = buffer.buffer;
                            var loadingTask = pdfjsLib.getDocument({ data: pdfData });
                            loadingTask.promise.then(function (pdf) {
                                pdf.getPage(1).then(function (page) {
                                    var scale = 1.5;
                                    var viewport = page.getViewport({ scale: scale });

                                    var canvas = document.getElementById('pdf-canvas');
                                    var context = canvas.getContext('2d');
                                    canvas.width = viewport.width;
                                    canvas.height = viewport.height;

                                    var renderContext = {
                                        canvasContext: context,
                                        viewport: viewport
                                    };

                                    page.render(renderContext);
                                });
                            });
                        }else{
                            var obj = document.createElement('object');
                            obj.style.width = '100%';
                            obj.style.height = '842pt';
                            obj.type = 'application/pdf';
                            obj.data = 'data:application/pdf;base64,' + response.reportdata;
                            $(obj).insertAfter('#report_details');
                        }

                        // Download
                        var link = document.createElement('a');
                        link.innerHTML = 'Download Report';
                        link.className = 'btn btn-primary my-3 py-3';
                        link.download = 'Report.pdf';
                        link.href = 'data:application/octet-stream;base64,' + response.reportdata;
                        $(link).insertAfter('#report_details');

                        $('.btn-close').removeClass('btn-refresh').addClass('btn-close-without-refresh');

                        ReportExcelDownloadProcess(response.reportTitle, response.previewID, response.reportdata);
                    }else{
                        $('.quotes-spinner').hide();
                        toastr.error(response.message, {
                            hideDuration: 10000,
                            closeButton: true,
                        });
                    }
                },
            });
        });

        // Void quote
        let quoteId = null;
        $('.void-quote-btn').on('click', function(e) {
            e.preventDefault();
            quoteId = $(this).data('quoteno');
            $('#voidConfirmationQuoteNo').text(`"${quoteId}"`);
            $('#voidConfirmation').modal({ backdrop: 'static',  keyboard: false  });
            $('#voidConfirmation').modal('show');
        });

        $('#closeVoidQuote').on('click', function(e) {
            e.preventDefault();
            $('#voidConfirmation').modal('hide');
        });

        // When "Yes" is clicked in the modal
        $('#confirmVoidQuote').on('click', function() {
            if (quoteId) {
                $.ajax({
                    url: '/dashboard/void-quote',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        QuotationNo: quoteId,
                        UserNo: '{{ Auth::user()->spars_logged_user_no }}',
                    },
                    beforeSend: function() {
                        $('.quotes-spinner').show();
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message, {
                                hideDuration: 10000,
                                closeButton: true,
                            });
                            window.location.reload();
                        } else {
                            $('.quotes-spinner').hide();
                            toastr.error(response.message, {
                                hideDuration: 10000,
                                closeButton: true,
                            });
                        }
                    },
                });
                $('#voidConfirmation').modal('hide');
            }
        });


        $(document).on('click', '.btn-refresh', function () {
            $('.quotes-btn').addClass('quotes-btns-tag');
            $('#quoteReportModal').modal('hide');
            window.location.reload();
            $('.quotes-btn').removeClass('quotes-btns-tag');
        });
        $(document).on('click', '.btn-close-without-refresh', function () {
            $('.quotes-btn').addClass('quotes-btns-tag');
            $('#quoteReportModal').modal('hide');
            $('.quotes-btn').removeClass('quotes-btns-tag');
        });

        $('#item_id').change(function() {
            var rugCheck = $(this).find('option:selected').data('rugcheck');
            rugCheck == 'N' ? $('#add-rugpad').prop('disabled', true) : $('#add-rugpad').prop('disabled', false);
        });

});
</script>
@endsection
