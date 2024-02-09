@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp

@extends('dashboard.layouts.app')
@section('title','Dashboard | Place Order')
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
               <div class="col-lg-9 col-sm-12 col-12 py-0">
                  @if (Session::has('message'))
                  <div class="alert alert-{{Session::get('message')['type']}}">
                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     {!!Session::get('message')['body']!!}
                  </div>
                  @endif
                  
                  <div class="account-content p-5">
                     <h1 class="section-title text-center mb-3 mt-3 font-ropa">Place Order</h1>
                     <form method="POST" class="place-order-form" action="{{ route('dashboard.placeorder') }}" class="pt-3">
                        @csrf
                        <div class="row">
                           @if($filters)
                           @foreach($filters as $filter)
                           @if($filter['type'] == 'hidden')
                           <input name="{{str_replace(' ', '_', strtolower($filter['title']))}}" value="{{$filter['value']}}" class="form-control" {!!isset($filter['attributes']) ? $filter['attributes'] : '' !!} type="{{$filter['type']}}" />
                           @else
                           <div class="mb-3 col-md-3 col-sm-12 pe-1 pe-lg-3">
                              <label for="{{$filter['placeholder'] ? str_replace(' ', '_', strtolower($filter['placeholder'])) : str_replace(' ', '_', strtolower($filter['title']))}}" class="form-label">{{$filter['placeholder'] ? $filter['placeholder'] : $filter['title']}}</label>
                              @if($filter['type'] == 'select')
                              <select name="{{str_replace(' ', '_', strtolower($filter['title']))}}" class="form-control">
                                 @foreach($filter['options'] as $option)
                                 <option {{old(str_replace(' ', '_', strtolower($filter['title']))) && old(str_replace(' ', '_', strtolower($filter['title']))) == $option['value'] ? 'selected' : ($filter['value'] == $option['value'] ? 'selected' : '' ) }} value="{{$option['value']}}">{{$option['label']}}</option>
                                 @endforeach
                              </select>
                              @elseif($filter['type'] == 'date')
                              <div class="input-group">
                                    <input name="{{str_replace(' ', '_', strtolower($filter['title']))}}" value="{{$filter['value']}}" class="form-control datepicker {{isset($filter['class']) ? $filter['class'] : ''}}" type="text" {!! isset($filter["attribues"]) ? $filter["attribues"] : "" !!} />
                                    <span class="input-group-addon">
                                       <i class="bi bi-calendar"></i>
                                    </span>
                              </div>
                              @else
                              <input name="{{str_replace(' ', '_', strtolower($filter['title']))}}" {!!$filter['attribues']!!} value="{{old(str_replace(' ', '_', strtolower($filter['title']))) ? : $filter['value']}}" class="form-control" type="{{$filter['type']}}" />
                              @endif
                           </div>
                           @endif
                           @endforeach
                           @endif
                        </div>
                        <div class="row addresses-section">
                           <div class="d-flex justify-content-center m-5">
                              <div class="spinner-border" role="status">
                                 <span class="sr-only">Loading...</span>
                              </div>
                           </div>
                        </div>
                        <div class="other-address d-none" style="">
                           <input type="hidden" name="address_id" />
                           <div class="d-flex flex-row justify-content-between column-gap-20 mb-3">
                              <input type="text" data-required="true" class="form-control bg-white " name="first_name" value="{{old('first_name')}}" aria-describedby="FirstName" maxlength="35" placeholder="First Name*">
                              <input type="text" data-required="true" class="form-control bg-white" name="last_name" value="{{old('last_name')}}" aria-describedby="LastName" maxlength="35" placeholder="Last Name*">
                           </div>
                           <div class="d-flex flex-row justify-content-between column-gap-20 mb-3">
                              <input type="email" data-required="true" class="form-control bg-white" name="email" value="{{old('email')}}" aria-describedby="Email" maxlength="60" placeholder="Email*">
                           </div>
                           <div class="d-flex flex-column">
                              <!--                                            <input type="text" class="form-control bg-white mb-3" name="Company" aria-describedby="Company" placeholder="Company (optional)">-->
                              <input type="text" data-required="true" class="form-control bg-white mb-3" value="{{old('address1')}}" name="address1" aria-describedby="Address" maxlength="35" placeholder="Address*">
                              <input type="text" class="form-control bg-white mb-3" value="{{old('address2')}}" name="address2" aria-describedby="Apartment" maxlength="35" placeholder="Apartment, suite, etc. (optional)">
                              <input type="text" data-required="true" class="form-control bg-white mb-3" value="{{old('state')}}" name="state" maxlength="50" aria-describedby="State" placeholder="State*">
                              <input type="text" data-required="true" class="form-control bg-white mb-3" value="{{old('city')}}" name="city" maxlength="35" aria-describedby="City" placeholder="City*">
                              <input type="text" data-required="true" class="form-control bg-white mb-3" value="{{old('country')}}" name="country" maxlength="35" aria-describedby="Country" placeholder="Country*">
                           </div>
                           <div class="d-flex flex-row justify-content-between column-gap-20 mb-3">
                              <input type="text" data-required="true" class="form-control bg-white" value="{{old('postal_code')}}" name="postal_code" maxlength="10" aria-describedby="PostalCode" placeholder="Postal Code*">
                           </div>
                        </div>
                        <hr />
                        @if(isset($active_theme_json->general->advance_filters_for_orders) && $active_theme_json->general->advance_filters_for_orders)
                        <div class="row col-md-12 additional-filters">
                           <div class="p-3 col-md-12">
                              <div class="d-flex">
                                 <div class="input-group w-auto d-none">
                                    <input {{!old('FilterType') || old('FilterType') == 'A' ? 'checked' : ''}} name="FilterType" id="filter-all" value="A" class="" type="radio" />
                                    <label style="border: none;" for="filter-all">All</label>
                                 </div>
                                 <div class="input-group w-auto d-none">
                                    <input {{old('FilterType') == 'P' ? 'checked' : ''}} name="FilterType" id="filter-program" value="P" class="" type="radio" />
                                    <label style="border: none;" for="filter-program">Program Only</label>
                                 </div>
                                 <div class="input-group w-auto d-none">
                                    <input {{old('FilterType') == 'C' ? 'checked' : ''}} name="FilterType" id="filter-clearance" value="C" class="" type="radio" />
                                    <label style="border: none;" for="filter-clearance">Clearance Only</label>
                                 </div>
                              </div>
                           </div>
                           <div class="d-flex flex-wrap p-1">
                              <!-- <div class="mb-3 pe-1 pe-lg-3 col-md-3">
                                 <label for="ProductType" class="form-label w-100">Product Type</label>
                                 <select name="ProductType" id="ProductType" class="form-control">
                                    <option>Choose type</option>
                                 </select>
                              </div> -->
                              <div class="mb-3 pe-1 pe-lg-3 col-md-3">
                                 <label for="Category" class="form-label w-100">Category</label>
                                 <select name="Category" id="Category" class="form-control">
                                    <option>Choose category</option>
                                 </select>
                              </div>
                              <!-- <div class="mb-3 pe-1 pe-lg-3 col-md-3">
                                 <label for="SubCategory" class="form-label w-100">Sub-Category</label>
                                 <select name="SubCategory" id="SubCategory" class="form-control">
                                    <option>Choose sub-category</option>
                                 </select>
                              </div> -->
                              <div class="mb-3 pe-1 pe-lg-3 col-md-3">
                                 <label for="Collection" class="form-label w-100">Collection</label>
                                 <select name="Collection" id="Collection" class="form-control">
                                    <option>Choose collection</option>
                                 </select>
                              </div>
                              <div class="mb-3 pe-1 pe-lg-3 col-md-3">
                                 <label for="Design" class="form-label w-100">Design</label>
                                 <select name="Design" id="Design" class="form-control">
                                    <option>Choose design</option>
                                 </select>
                              </div>
                              <div class="mb-3 pe-1 pe-lg-3 col-md-3">
                                 <label for="Color" class="form-label w-100">Color</label>
                                 <select name="Color" id="Color" class="form-control">
                                    <option>Choose color</option>
                                 </select>
                              </div>
                              <div class="mb-3 pe-1 pe-lg-3 col-md-3">
                                 <label for="Size" class="form-label w-100">Size</label>
                                 <select name="Size" id="Size" class="form-control">
                                    <option>Choose size</option>
                                 </select>
                              </div>
                              <div class="mb-3 pe-1 pe-lg-3 col-md-3 d-none">
                                 <label for="FillType" class="form-label w-100">Fill Type</label>
                                 <select name="FillType" id="FillType" class="form-control">
                                    <option>Choose FillType</option>
                                 </select>
                              </div>
                              <div class="mb-3 pe-1 pe-lg-3 col-md-3">
                                 <label for="availability" class="form-label w-100">Available</label>
                                 <input type="text" id="availability" disabled readonly class="form-control availability" value="" />
                              </div>
                              <div class="mb-3 pe-1 pe-lg-3 col-md-3">
                                 <label for="qty" class="form-label w-100">Qty</label>
                                 <input type="number" disabled readonly max="9999" maxlength="4" min="1" step="1" class="form-control qty" value="" />
                              </div>
                              <div class="mb-3 pe-1 pe-lg-3 col-md-3">
                                 <label for="price" class="form-label w-100">Unit Price</label>
                                 <input type="text" disabled readonly  class="form-control price" value="" />
                              </div>
                              <div class="col-md-12 d-flex justify-content-end">
                                 <button type="button" disabled class="btn btn-primary text-uppercase mt-2 add-to-cart">Add</button>
                              </div>
                           </div>
                        </div>
                        <hr />
                        @endif
                        <div class="row col-md-12">
                           <div class="p-3 col-md-6">
                           </div>
                           <div class="p-3 col-md-6">
                              <input type="file" accept=".csv" class="text-uppercase mt-2" id="orders_csv_file" />
                              <button type="button" style="float: right;" class="upload-file btn btn-primary text-uppercase mt-2">Load</button>
                              <p>Download <a target="_blank" href="{{route('dashboard.samplefiles', 'order')}}">sample.csv</a></p>
                           </div>
                        </div>
                        <div class="table-responsive">
                           <table class="table mt-4 text-center line-items-table" id="myTable">
                              <thead class="table-dark">
                                 <tr>
                                    <th></th>
                                    <th>Item ID</th>
                                    <th>Quantity</th>
                                    <th>Side Mark</th>
                                    <!-- <th>Unit Price</th> -->
                                    <!-- <th>Total</th> -->
                                 </tr>
                              </thead>
                              <tbody id="data_body">
                                 @if(old('ItemID'))
                                 @foreach(old('ItemID') as $k => $item_id)
                                 <tr id="order_data_row" style="border-bottom: 1px solid #ddd;">
                                    <td class="d-flex justify-content-center">
                                       <i class="remove-row bi bi-x-lg"></i>
                                    </td>
                                    <td><input data-required="true" class="form-control item-id" name="ItemID[]" maxlength="250" type="text" value="{{$item_id}}" /></td>
                                    <td><input data-required="true" name="OrderQty[]" class="form-control item-quantity" max="9999" maxlength="4" min="1" step="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="number" value="{{old('OrderQty')[$k]}}" /></td>
                                    <td><input maxlength="35" name="MarkFor[]" class="form-control item-sidemark" type="text" value="{{old('MarkFor')[$k]}}" /></td>
                                    <!-- <td><input data-required="true" name="UnitPrice[]" class="form-control item-price" type="number" value="" /></td> -->
                                    <!-- <td class="item-total-price">$ 0.00</td> -->
                                 </tr>
                                 @endforeach
                                 @else
                                 <tr id="order_data_row" style="border-bottom: 1px solid #ddd;">
                                    <td class="d-flex justify-content-center">
                                       <i class="remove-row bi bi-x-lg"></i>
                                    </td>
                                    <td><input data-required="true" class="form-control item-id" maxlength="250" name="ItemID[]" type="text" value="" /></td>
                                    <td><input data-required="true" name="OrderQty[]" class="form-control item-quantity" maxlength="4" max="9999" min="1" step="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="number" value="" /></td>
                                    <td><input maxlength="35" name="MarkFor[]" class="form-control item-sidemark" type="text" value="" /></td>
                                    <!-- <td><input data-required="true" name="UnitPrice[]" class="form-control item-price" type="number" value="" /></td> -->
                                    <!-- <td class="item-total-price">$ 0.00</td> -->
                                 </tr>
                                 @endif
                              </tbody>
                              <!-- <tfoot>
                                 <tr>
                                    <td colspan="4"></td>
                                    <td class="items-grand-total">$ 0.00</td>
                                 </tr>
                              </tfoot> -->
                           </table>
                        </div>
                        <div class="col-md-12 d-flex">
                           <div class="p-3 col-md-6">
                              <button type="button" class="add-row btn btn-primary text-uppercase mt-2 me-2">Insert new row</button>
                           </div>
                           <div class="p-3 col-md-6 d-flex justify-content-end">
                              <button type="submit" class="btn btn-primary text-uppercase mt-2">Place Order</button>
                           </div>
                        </div>
                     </form>

                     <div class="table-container">
                        @if(isset($place_orders['Message']))
                        @include('dashboard.components.table')
                        @elseif(strstr($_SERVER['REQUEST_URI'], "?"))
                        <h4>Data not available.</h4>
                        @endif
                     </div>
                     <div class="col-md-4 p-sm-3 py-3 col-sm-12">
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
@section('scripts')
<script>
   $(document).ready(function() {
      $(document).on('click', ".remove-row", function() {
         if ($("table.line-items-table tbody tr").length > 1)
            $(this).closest('tr').remove();
         else
            $("input", $("table.line-items-table tbody tr")).val('');

         update_totals();
      });

      $(document).on('change', '.select-address', function() {
         $('.address-card').addClass('d-none');
         $(`.address-card.${$(this).val()}`).removeClass('d-none');
         $(`.address-card.${$(this).val()} input[type="radio"]`).click();
      }).change();

      function validateEmail(email) {
         var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
         return re.test(email);
      }

      function update_totals() {
         return;
         let total = 0;
         $('#data_body tr').each(function() {
            var price = typeof $('.item-price', $(this)).val().length ? $('.item-price', $(this)).val() : 0;
            var quantity = typeof $('.item-quantity', $(this)).val().length ? $('.item-quantity', $(this)).val() : 0;
            var sub_total = (parseFloat(price) * parseFloat(quantity));
            if (isNaN(sub_total)) sub_total = 0;
            $('.item-total-price', $(this)).html(
               sub_total.toLocaleString('en-US', {
                  style: 'currency',
                  currency: 'USD',
               })
            );
            total = total + sub_total;
         });
         if (isNaN(total)) total = 0;
         $('.items-grand-total').html(
            total.toLocaleString('en-US', {
               style: 'currency',
               currency: 'USD',
            })
         );
      }

      $(document).on('keyup, change', "table input[type='number']", function() {
         update_totals();
      });

      $(".add-row").on("click", function(e) {
         var row = $("table.line-items-table tbody tr:first").clone();
         $("input, textarea", row).val("");
         row.appendTo($("table.line-items-table tbody"));
      });

      $('input[name="FilterType"]').click(function() {
         $('.additional-filters select').html('<option value="">Loading options</option>').attr('disabled', 'disabled');
         $(`.additional-filters .qty`).val('').attr('disabled', 'disabled').removeClass('is-invalid');
         $(`.additional-filters .availability`).val('').attr('disabled', 'disabled');
         $(`.additional-filters .price`).val('').attr('disabled', 'disabled');
         $.post('{{route("dashboard.placeorder.additional-filters")}}', {
            FilterType: $(this).val(),
            _token: '{{csrf_token()}}'
         }, function(data) {
            // var options = "<option>Choose type</option>";
            // data.response.ProductTypes.map((type) => {
            //    options += `<option value="${type.KeyID}">${type.Description}</option>`;
            // });
            // $('.additional-filters select').each(function() {
            //    $(this).html(`<option value="">Choose ${$(this).attr('name')}</option>`);
            // });
            // $('.additional-filters select[name="ProductType"]').html(options).removeAttr('disabled');
            var options = "<option>Choose Category</option>";
            data.response.Categories.map((type) => {
               options += `<option data-tokens="${type.Description.toLowerCase()}" value="${type.KeyID}">${type.Description}</option>`;
            });
            $('.additional-filters select').each(function() {
               $(this).html(`<option value="">Choose ${$(this).attr('name')}</option>`);
            });
            $('.additional-filters select[name="Category"]').html(options).removeAttr('disabled');
            bindSelectChange();
         });
      });
      $('input[name="FilterType"]:checked').click();


      function bindSelectChange() {
         $('.additional-filters .add-to-cart').off('click').on('click', function() {
            if ( $('.additional-filters .qty').val() == "" || $('.additional-filters .qty').val() == 0 ) {
               $('.additional-filters .qty').addClass('is-invalid');
               return false;
            }

            $('.additional-filters .qty').removeClass('is-invalid');
            if ( $('.item-quantity:last').val() !== "" || $('.item-id:last').val() !== "" || $('.item-sidemark:last').val() !== "" ) {
               $('.add-row').click();
            }

            $('.item-quantity:last').val($('.additional-filters .qty').val());
            $('.item-id:last').val($(this).attr('data-item'));
            $('.item-sidemark:last').val('');
         });

         $('.additional-filters select').off('change').on('change', function() {
            var type = $(this).attr('name');
            $(`.additional-filters .add-to-cart`).attr('data-item', '').attr('disabled', 'disabled');
            $(`.additional-filters input[type="text"], .additional-filters input[type="number"]`).val('').attr('disabled', 'disabled');
            $(`.dropdown-menu input[type="text"], .dropdown-menu input[type="number"]`).removeAttr('disabled');
            switch(type) {
               case 'ProductType':
                  $('.additional-filters select[name="Collection"], .additional-filters select[name="Design"], .additional-filters select[name="Color"], .additional-filters select[name="Size"]').html('<option value="">Loading options</option>').attr('disabled', 'disabled');
                  break;
               case 'Category':
                  $('.additional-filters select[name="SubCategory"], .additional-filters select[name="Collection"], .additional-filters select[name="Design"], .additional-filters select[name="Color"], .additional-filters select[name="Size"]').html('<option value="">Loading options</option>').attr('disabled', 'disabled');
                  break;
               case 'SubCategory':
                  if("{{$active_theme->theme_slug}}" == "lr"){
                     $('.additional-filters select[name="Collection"], .additional-filters select[name="Design"], .additional-filters select[name="Color"], .additional-filters select[name="Size"], .additional-filters select[name="FillType"]').html('<option value="">Loading options</option>').attr('disabled', 'disabled');
                     break;
                  }else{
                     $('.additional-filters select[name="Design"], .additional-filters select[name="Color"], .additional-filters select[name="Size"], .additional-filters select[name="FillType"]').html('<option value="">Loading options</option>').attr('disabled', 'disabled');
                     break;
                  }
               case 'Collection':
                  $('.additional-filters select[name="Design"], .additional-filters select[name="Color"], .additional-filters select[name="Size"]').html('<option value="">Loading options</option>').attr('disabled', 'disabled');
                  break;
               case 'Design':
                  $('.additional-filters select[name="Color"], .additional-filters select[name="Size"]').html('<option value="">Loading options</option>').attr('disabled', 'disabled');
                  break;
               case 'Color':
                  $('.additional-filters select[name="Size"]').html('<option value="">Loading options</option>').attr('disabled', 'disabled');
                  break;
               case 'Size':
                  $('.additional-filters select[name="FillType"]').html('<option value="">Loading options</option>').attr('disabled', 'disabled');
                  break;
               default:
                  // return true;
                  break;
            }

            if ( $(this).val() == "" ) return false;

            $.post('{{route("dashboard.placeorder.additional-filters")}}', {
               FilterType: $('input[name="FilterType"]').val(),
               // ProductType: $('select[name="ProductType"]').val(),
               Category: $('select[name="Category"]').val(),
               SubCategory: "",
               Collection: $('select[name="Collection"]').val(),
               Design: $('select[name="Design"]').val(),
               Color: $('select[name="Color"]').val(),
               Size: $('select[name="Size"]').val(),
               Customer: $('[name="customer_id"]').val(),
               _token: '{{csrf_token()}}'
            }, function(data) {
               console.log(data);
               var response = [];
               if (type == 'ProductType') {
                  type = 'Collection';
                  response = data.response.Colections;
               } else if (type == 'Category') {
                  type = 'Collection';
                  response = data.response.Colections;
                  /* type = 'SubCategory';
                  response = data.response.SubCategories; */
               } /* else if (type == 'SubCategory') {
                  type = 'Collection';
                  response = data.response.Colections;
               }  */else if (type == 'Collection') {
                  type = 'Design';
                  response = data.response.Designs;
               } else if (type == 'Design') {
                  type = 'Color';
                  response = data.response.Colors;
               } else if (type == 'Color') {
                  type = 'Size';
                  response = data.response.Sizes;
               } else if ( type == 'Size' ) {
                  type = 'Item';
                  response = data.response;
               }
               console.log(type);
               if ( type == 'Item' ) {
                  if (data.response.Success) {
                     $(`.additional-filters .availability`).val(data.response.ATSQ);
                     $(`.additional-filters .qty`).removeAttr('disabled').removeAttr('readonly');
                     $(`.additional-filters .qty`).attr('max', data.response.ATSQ);
                     $(`.additional-filters .add-to-cart`).attr('data-item', data.response.ItemID).removeAttr('disabled');
                     $(`.additional-filters .price`).val(parseFloat(data.response.ItemPrice).toLocaleString('en-US', {
                        style: 'currency',
                        currency: 'USD',
                     }));
                  }
               } else {
                  var options = `<option>Choose ${type}</option>`;
                  response.map((item) => {
                     options += `<option data-tokens="${item.Description.toLowerCase()}" value="${item.KeyID}">${item.Description}</option>`;
                  });
                  $(`.additional-filters select[name="${type}"]`).html(options).removeAttr('disabled');
                  $('.additional-filters select[name="FillType"]').closest('.col-md-3').addClass('d-none');
               }

               $('.additional-filters select').each(function() {
                  if ($(this).is(':disabled'))
                     $(this).html(`<option value="">Choose ${$(this).attr('name')}</option>`);
               });
               bindSelectChange();
            });
         });
      }

      $("[name='customer_id']").on("change", function(e) {
         $('.addresses-section').html(`
                  <div class="d-flex justify-content-center m-5">
                     <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                     </div>
                  </div>
                  `);
         $('.other-address').addClass('d-none');

         let addresses = '<div class="col-md-12">';
         $.post('{{route("dashboard.customeraddresses")}}', {
            customer: $(this).val(),
            _token: '{{csrf_token()}}'
         }, function(data) {
            if (typeof data !== 'undefined' && data.success && typeof data.addresses !== 'undefined') {
               // TODO - NEEDS TO BE IMPROVED
	       // $('[name="ship_via_id"]').val(data?.addresses?.CustomerShipVias?.[0]?.ShipViaID).change();
		const selectedValue = data?.addresses?.CustomerShipVias?.[0]?.ShipViaID;
		const dropdown = $('[name="ship_via_id"]');
		const optionExists = dropdown.find(`option[value="${selectedValue}"]`).length > 0;
		dropdown.val(optionExists ? selectedValue : dropdown.find('option:first').val()).change();

               var selectedAddress = '{{old("address_id") ? old("address_id") : "" }}';
               addresses += '<div class="row mb-4">';
               addresses += '<div class="col-md-12">';
               addresses += '<label>Address</label>';
               addresses += '<select class="form-control select-address">';
               data.addresses.ShipToAddresses.forEach((address) => {
                  addresses += '<option ' + (selectedAddress == address.AddressID ? 'selected' : '') + ' value="' + address.AddressID + '">' + address.FirstName + ' ' + address.LastName + '</option>';
               });
               addresses += '</select>';
               addresses += '</div>';
               addresses += '</div>';
               addresses += '<div class="row">';

               data.addresses.ShipToAddresses.forEach((address) => {
                  addresses += '<div class="col-md-12 address-card d-none ' + address.AddressID + '">';
                  addresses += '<div class="border border-light card mb-3">';
                  addresses += '<div class="card-body text-dark">';
                  addresses += '<h5 class="align-items-center card-title d-flex">';
                  addresses += '<input type="radio" name="shipping-address" id="' + address.AddressID + '" value="' + address.AddressID + '">';
                  addresses += '<div class="shipping-address-data d-none">' + JSON.stringify(address) + '</div>';
                  addresses += '<label style="width: 90%;" class="m-0 pl-2" for="' + address.AddressID + '">' + address.AddressID + '</label>';
                  addresses += '</h5>';
                  addresses += '<p class="card-text">';
                  addresses += '<label style="width: 100%;" for="' + address.AddressID + '">';
                  addresses += '<span><b>' + address.FirstName + ' ' + address.LastName + '</b></span><br>';
                  addresses += '<span><b>' + address.Address1 + '</b></span><br>';
                  addresses += '<span>' + address.City + '</span>, ';
                  addresses += '<span>' + address.State + '</span> ';
                  addresses += '<span>' + address.Zip + '</span><br>';
                  addresses += '<span>' + address.Country + '</span><br>';
                  addresses += '<span>' + address.Phone1 + '</span><br>';
                  addresses += '</label>';
                  addresses += '</p>';
                  addresses += '</div>';
                  addresses += '</div>';
                  addresses += '</div>';
               });
            }

            // TODO - NEEDS TO BE IMPROVED
            addresses += '</div>';
            addresses += '<div class="row">';
            addresses += '<div class="col-md-12">';
            addresses += '<div class="border border-light card mb-3">';
            addresses += '<div class="card-body text-dark">';
            addresses += '<h5 class="align-items-center card-title d-flex">';
            addresses += '<input type="radio" name="shipping-address" id="other" value="other">';
            addresses += '<label style="width: 90%;" class="m-0 pl-2" for="other">Drop Ship</label>';
            addresses += '</h5>';
            addresses += '</div>';
            addresses += '</div>';
            addresses += '</div>';
            addresses += '</div>';
            addresses += '</div>';

            $('.addresses-section').html(addresses);
            $('.select-address').change();
            bind_radio_clicks();
         });
         bind_radio_clicks();
      }).change();

      function bind_radio_clicks() {
         $('.addresses-section input[type="radio"]')
            .off('click')
            .on('click', function() {
               if ($(this).val() === 'other') {
                  $('[name="address_id"]').val('');
                  $('.other-address').removeClass('d-none');
                  $('.other-address input').each(function() {
                     if ($(this).attr('type') !== 'hidden') {
                        $(this).val($(this).attr('data-old-val'));
                        $(this).attr('data-old-val', '');
                     }
                  });
               } else {
                  $('.other-address').addClass('d-none');
                  const address_data = JSON.parse($('.shipping-address-data', $(this).parent()).html());
                  $('.other-address input').each(function() {
                     if ($(this).attr('type') !== 'hidden' && $('[name="address_id"]').val() == '') {
                        $(this).attr('data-old-val', $(this).val());
                     }
                  });
                  $('[name="address_id"]').val($(this).val());
                  $('[name="first_name"]').val(address_data.FirstName);
                  $('[name="last_name"]').val(address_data.LastName);
                  $('[name="email"]').val(address_data.Email);
                  $('[name="address1"]').val(address_data.Address1);
                  $('[name="address2"]').val(address_data.Address2);
                  $('[name="city"]').val(address_data.City);
                  $('[name="state"]').val(address_data.State);
                  $('[name="postal_code"]').val(address_data.Zip);
               }
            });

         if ($('.addresses-section input[type="radio"]').val() === 'undefined' || $('.addresses-section input[type="radio"]').val() !== 'other')
            $('.addresses-section input[type="radio"]:first').click();
      }

      //grey out ship to address when drop ship selected on checkout
      $(document).on('change', '[name="shipping-address"]',function() {
         if ( $(this).val() == 'other' ) {
            $('.address-card').addClass('disabled-card');
            $(this).closest('.card-body').removeClass('disabled-card');
         } else {
            $('.address-card').removeClass('disabled-card');
            $('[name="shipping-address"][value="other"]').closest('.card-body').addClass('disabled-card');
         }
      }).change();

      $(".upload-file").click(function() {
         var fileUpload = document.getElementById("orders_csv_file");
         var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.csv|.txt)$/;
         var message = "\nYou sure you want to proceed?";

         if (fileUpload.files.length < 1) return alert('Please select a file first.');

         $("table.line-items-table tbody tr:first input").each(function() {
            if ($(this).val().length > 0)
               message = "\nProceeding ahead will remove all the existing items.\n\nYou sure you want to proceed?";
         });

         if (
            confirm(message)
         ) {
            if (regex.test(fileUpload.value.toLowerCase())) {
               if (typeof FileReader != "undefined") {
                  var reader = new FileReader();
                  reader.onload = function(e) {
                     var row_clone = $(
                        "table.line-items-table tbody tr:first"
                     ).clone();
                     $("table.line-items-table tbody tr").remove();
                     var rows = e.target.result.split("\n");
                     for (var i = 1; i < rows.length; i++) {
                        var cells = rows[i].split(",");
                        if (cells.length > 1) {
                           var row = row_clone.clone();
                           $("input[name='ItemID[]']", row).val(cells[0]);
                           $("input[name='MarkFor[]']", row).val(cells[2]);
                           $("input[name='OrderQty[]']", row).val(
                              parseFloat(cells[1].replace("\r", ""))
                           );
                           /*
                           $("input[name='UnitPrice[]']", row).val(
                              parseFloat(cells[2].replace("\r", ""))
                           );
                           update_totals();
                           */
                           row.appendTo($("table.line-items-table tbody"));
                        }
                     }
                  };
                  reader.readAsText(fileUpload.files[0]);
               } else {
                  alert("This browser does not support HTML5.");
               }
            } else {
               alert("Please upload a valid CSV file.");
            }
         }
      });

      $('.place-order-form').on('submit', function() {
         var allOk = true;

         $('input[data-required="true"], select[data-required="true"]').each(function() {
            if ($(this).is(':visible')) {
               if (typeof $(this).val().length === 'undefined') {
                  $(this).addClass('is-invalid');
                  allOk = false;
               } else if ($(this).val().trim().length < 1) {
                  $(this).addClass('is-invalid');
                  allOk = false;
               } else {
                  $(this).removeClass('is-invalid');
               }
            }
         });

         if (allOk && $('input[type="email"]').is(':visible') && !validateEmail($('input[type="email"]').val())) {
            $('input[type="email"]').addClass('is-invalid');
            allOk = false;
         }

         if ( $('input[name="customer_id"]').is(':visible') && !parseInt($('input[name="customer_id"]').val()) ) {
            $('input[name="customer_id"]').addClass('is-invalid');
            allOk = false;
         } else {
            $('input[name="customer_id"]').removeClass('is-invalid');
         }


         return allOk;
      });
   });
</script>
@endsection
