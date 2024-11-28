@php
use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
@endphp

@extends('dashboard.layouts.app')
@section('title','Dashboard | Account Information')
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
                                    <a href="javascript:void(0)" data-related-section="new-quotes" class="quotes-btn">New Quotes</a>
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
                                                    <label class="form-label">Quaotation Date</label>
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
                                                        <span class="input-group-addon">
                                                            <i class="bi bi-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-9 col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Item ID</label>
                                                    <select class="form-control" name="item_id" id="item_id">
                                                        <option disabled selected>Choose Item Id</option>
                                                        @foreach ($items as $single_item)
                                                            <option value="{{ $single_item['KeyID'] }}">{{ $single_item['Description'] }}</option>
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
                                                    <label class="form-label">Cut Length</label>
                                                    <div class="mb-3 d-flex align-items-center justify-content-between">
                                                        <div class="input-group me-2">
                                                            <input type="number" class="form-control text-center small-input" name="lengthF" id="lengthF" placeholder="00" min="0" max="100">
                                                            <span class="input-group-text">Ft</span>
                                                        </div>
                                                        <div class="input-group ms-2">
                                                            <input type="number" class="form-control text-center small-input" name="lengthI" id="lengthI" placeholder="00" min="0" max="11">
                                                            <span class="input-group-text">In</span>
                                                        </div>
                                                    </div>
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
                                                            <input type="number" class="form-control text-center small-input" name="widthI" id="widthI" placeholder="00" min="0" max="11">
                                                            <span class="input-group-text">In</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 offset-md-3 col-3">
                                                <div class="form-check mt-5">
                                                    <input class="form-check-input" type="checkbox" value="" name="add-rugpad" id="add-rugpad">
                                                    <label class="form-check-label" >
                                                    Add a Rugpad
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary submit-btn" disabled>Save Quotes</button>
                                    </form>
                                </div>
                               </div>
                               <div style="display: none" class="view-active-quotes mt-3">
                                <div class="container-fluid">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class="p-3" style="background-color: #596460; color: #fff;">
                                              <tr>
                                                <th scope="col">Quotaion ID</th>
                                                <th scope="col">Customer ID</th>
                                                <th scope="col">Quotation Date</th>
                                                <th scope="col">Cancel Date</th>
                                                <th scope="col">Reservation</th>
                                                <th scope="col">Status</th>
                                                <th scope="col" class="">&nbsp;&nbsp;</th>
                                              </tr>
                                            </thead>
                                            <tbody class="table-rows-custom">
                                                @foreach ($quotationsLists as $list)
                                                    <tr class="">
                                                        <td class="text-start">{{ $list['QuotationNo'] }}</td>
                                                        <td class="text-start">{{ $list['CustomerID'] }}</td>
                                                        <td class="text-start">{{ $list['QODate'] }}</td>
                                                        <td class="text-start">{{ $list['CancelDate'] }}</td>
                                                        <td class="text-start">{{ $list['Reservation'] }}</td>
                                                        <td class="text-start">{{ $list['Status'] }}</td>
                                                        <td><a class="btn btn-primary quotes-order-btn text-center" data-quoteno={{ $list['QuotationNo'] }}>Place Order</a></td>
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
                                        <a href="{{ route('dashboard') }}" class="btn btn-dark p-2">Back To Dashbord</a>
                                    </div>
                                    </div>
                                </div>
                            </div>

                            {{-- report modal --}}
                            <div class="modal fade" id="quoteReportModal" tabindex="-1" aria-labelledby="quoteReportModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class="modal-content" style="border-radius: 20px !important;">
                                        <div class="modal-header" style="border-bottom: none !important;">
                                            <h5 class="modal-title sample-selext-title" id="quoteReportModalLabel">Quoate Report</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" id="purchase-order-modal-container">
                                            <div id="report_details"></div>
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
    }
    .submit-btn:hover {
        background-color: #880000;
        box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.2);
    }
    .quotes-order-btn{
        background-color: #660000;
        color: white;
        font-size: 12px;
        font-weight: bold;
        border-radius: 5px;
        border: none;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        min-height: 30px !important;
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
    @keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
    }
</style>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        // Hide show on the base customer or sales rep
        var is_sales_rep = '{{ Auth::user()->is_sale_rep }}';
        var is_customer = '{{ Auth::user()->is_customer }}';
        var is_customer_id = '{{ Auth::user()->customer_id }}';
        if(is_sales_rep == 1 && is_customer == 0){
            $('.customer_box').addClass('d-none');
            $('.sale_rep_box').removeClass('d-none');
        }else{
            $('.sale_rep_box').addClass('d-none');
            $('.customer_box').removeClass('d-none');
            $('.customer_id_c').val(is_customer_id);
        }

        // Dates Handle
        var currentDate = new Date();
        var currentDateFormatted = currentDate.toISOString().split('T')[0];
        $('#quotes_date').val(currentDateFormatted).prop('readonly', true);
        $('.cancel_quote_date').val(new Date(new Date().setDate(new Date().getDate() + 7)).toISOString().split('T')[0]);
        $('.cancel_quote_date').datepicker({
            format: 'yyyy-mm-dd',
            startDate: new Date(),
            endDate: new Date(new Date().setDate(new Date().getDate() + 13))
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

        // Item id and customer id searchable dropdown
        $('#item_id, .customer_id_sr').select2({
            theme: 'bootstrap-4',
            width: '100%'
        });

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
                $('.submit-btn').prop('disabled', false);
            } else {
                $('.submit-btn').prop('disabled', true);
            }
        }
        $('#customer_id, #quotes_date, #cancel_quote_date, #item_id, #serging, #lengthF, #lengthI, #widthF, #widthI').on('change', function() {
            checkForm();
        });
        checkForm();

        // Get serging charges
        var sergingcharges = ''
        $('#serging').change(function() {
            sergingcharges = $('#serging option:selected').data('sergingcharges');
        });

        // Save quote
        $('.submit-btn').on('click', function(e) {
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
                    addRugpad: addRugpad
                },
                beforeSend: function(){
                    $('.quotes-spinner').show();
                },
                success: function(response) {
                    if(response.success){
                        // Embed
                        var obj = document.createElement('object');
                        obj.style.width = '100%';
                        obj.style.height = '842pt';
                        obj.type = 'application/pdf';
                        obj.data = 'data:application/pdf;base64,' + response.b64;
                        $(obj).insertAfter('#report_details');

                        // Download
                        var link = document.createElement('a');
                        link.innerHTML = 'Download Report';
                        link.className = 'btn btn-primary mx-2';
                        link.download = 'Report.pdf';
                        link.href = 'data:application/octet-stream;base64,' + response.b64;
                        $(link).insertAfter('#report_details');

                        ReportExcelDownloadProcess(response.reportTitle, response.previewID, response.reportdata)
                    }else{
                        $('.quotes-spinner').hide();
                        toastr.error(response.msg, {
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
                        link.className = 'btn btn-primary mx-2';
                        link.download = 'Report.xls';
                        link.href = 'data:application/octet-stream;base64,' + response.data;
                        $(link).insertAfter('#report_details');

                        $('.quotes-spinner').hide();
                        $('#quoteReportModal').modal('show');
                }
            }).fail(function() {
                console.log('Error: Failed to get the excel data.');
            });
        }

        // Place order
        $('.quotes-order-btn').on('click', function(e){
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
});
</script>
@endsection
