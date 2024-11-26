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
                                                        <input name="cancel_quote_date" id="cancel_quote_date" value="" class="form-control datepicker" type="text" data-required="true">
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
                                                        <option value="1">One no</option>
                                                        <option value="2">Two no</option>
                                                        <option value="3">Three no</option>
                                                        <option value="1">One no</option>
                                                        <option value="1">five no</option>
                                                        <option value="1">five no</option>
                                                        <option value="1">five no</option>
                                                        <option value="1">six no</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Serging</label>
                                                    <select class="form-control" name="serging" id="serging">
                                                        <option disabled selected>Choose Item Id</option>
                                                        <option value="1">One</option>
                                                        <option value="2">Two</option>
                                                        <option value="3">Three</option>
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
                                                    <input class="form-check-input" type="checkbox" value="">
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
                                                <th scope="col" class="invisble">place order btn</th>
                                              </tr>
                                            </thead>
                                            <tbody class="table-rows-custom">
                                              <tr class="">
                                                <td class="text-start">158</td>
                                                <td class="text-start">123467</td>
                                                <td class="text-start">02/04/2022</td>
                                                <td class="text-start">02/04/2022</td>
                                                <td class="text-start">Yes</td>
                                                <td class="text-start">New</td>
                                                <td><button type="button" class="btn btn-primary quotes-order-btn">Place Order</button></td>
                                              </tr>
                                            </tbody>
                                          </table>
                                    </div>
                                </div>
                               </div>
                            </div>
                        </div>
                        {{-- start from now --}}
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
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

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
        padding: 1px 20px;
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
</style>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


<script type="text/javascript">
    $(document).ready(function () {
        var formValues = {};

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

        var currentDate = new Date();
        var currentDateFormatted = currentDate.toISOString().split('T')[0];
        $('#quotes_date').val(currentDateFormatted);
        $('#quotes_date').prop('readonly', true);
        currentDate.setDate(currentDate.getDate() + 7);
        var dateAfter7DaysFormatted = currentDate;
        var maxCancelDate = new Date();
        maxCancelDate.setDate(currentDate.getDate() + 14);
        $('#cancel_quote_date').datepicker({
            dateFormat: "mm/dd/yy", // Date format in the datepicker
            defaultDate: dateAfter7DaysFormatted, // Set default date (7 days from today)
            minDate: currentDate, // Set minimum selectable date (today + 7 days)
            maxDate: maxCancelDate, // Set maximum selectable date (today + 21 days)
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

        // Item id dropdown
        $('#item_id, .customer_id_sr').select2({
            theme: 'bootstrap-4',
            width: '100%'
        });

        // Required fields validation
        function checkForm() {
            var customer_id = $('#customer_id').val();
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

        // Save quote
        $('.submit-btn').on('click', function(e) {
            e.preventDefault();
            var customer_id = $('#customer_id').val();
            var quotes_date = $('#quotes_date').val();
            var cancel_quote_date = $('#cancel_quote_date').val();
            var item_id = $('#item_id').val();
            var serging = $('#serging').val();
            var lengthF = $('#lengthF').val();
            var lengthI = $('#lengthI').val();
            var widthF = $('#widthF').val();
            var widthI = $('#widthI').val();

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
                    lengthF: lengthF,
                    lengthI: lengthI,
                    widthF: widthF,
                    widthI: widthI,
                },
                success: function(response) {
                    console.log('Request successful', response);
                },
                error: function(xhr, status, error) {
                    console.log('Request failed', error);
                }
            });
        });
});
</script>
@endsection
