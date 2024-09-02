@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp

@extends('frontend.' . $active_theme -> theme_abrv . '.layouts.app')
@section('title','Checkout')
@section('content')
<div class="wrapper bg-EDECE9">
   @include('frontend.'.$active_theme -> theme_abrv.'.components.header')
   <main class="main-content checkout-page">
      <section class="collection-section">
         <div class="container">
            <div class="d-flex flex-row justify-content-between flex-dir-col">
               @if(count((array)$cart->items))
               <div class="col-md-5 col-sm-12 col-12">
                  <div class="p-1">
                     <!-- <h2 class="text-center">Sign in with Email</h2> -->
                     <div class="d-flex flex-column dafault-form form-checkout" id="order_form">
                        <input type="hidden" value="{{Auth::user()->customer_id}}" name="customer_id">
                        <div class="mb-5">
                           <label for="InputEmail1" class="form-label">Delivery method </label>
                           
                           <div class="form-check bg-white shipping-dropdown-box d-flex align-items-center">
                              <label for="ship-pickup" class="form-label"><img src="{{url('/')}}/images/truck.svg" class="icon-img icon-truck" /></label>
                              <select name="ship-pickup" class="form-control">
                                 @foreach($shippings as $shipping)
                                 <option value="{{$shipping['ShipViaID']}}" {{ $default_ship_via_id == $shipping['ShipViaID'] ? 'selected' : '' }}>{{$shipping['ShipViaID']}} - {{$shipping['Description']}}</option>
                                 @endforeach
                              </select>
                           </div>
                        </div>
                        <div class="mb-5">
                           <label for="InputEmail1" class="form-label">Shipping Address</label>
                           <label class="order_form_validation_error d-none"></label>
                           @if($shipping_addresses && isset($shipping_addresses['ShipToAddresses']) && count($shipping_addresses['ShipToAddresses']))
                           <div class="col-md-12 address-cards">
                              <div class="card mb-3 ship-to-address">
                                 <div class="card-body text-dark">
                                    <h5 class="align-items-center card-title p-2 row">
                                       <input type="radio" class="col-md-1" name="shipping-address" id="existing-address" value="existing-address" />
                                       <select class="col-md-10 form-control select-address" style="width: 90%;">
                                          @foreach($shipping_addresses['ShipToAddresses'] as $address)
                                          <option value="{{$address['AddressID']}}">
                                             {{$address['AddressID']}} : {!!$address['FirstName'] ? $address['FirstName'] . ( $address['LastName'] ? " {$address['LastName']}" : '' ) : ''!!}
                                          </option>
                                          @endforeach
                                       </select>
                                    </h5>
                                    @foreach($shipping_addresses['ShipToAddresses'] as $address)
                                    <p class="card-text address-card d-none {{$address['AddressID']}}" id="{{$address['AddressID']}}">
                                       <input type="hidden" name="shipping-address-data" value="{{json_encode($address)}}" />
                                       <label style="width: 100%;" for="existing-address">
                                          {!!$address['FirstName'] ? '<span>' . $address['FirstName'] . ( $address['LastName'] ? " {$address['LastName']}" : '' ) . '</span><br />' : ''!!}
                                          {!!$address['Address1'] ? '<span>' . $address['Address1'] . ( $address['Address2'] ? ", {$address['Address2']}" : '' ) . '</span><br />' : ''!!}
                                          <!-- {!!$address['Phone1'] ? '<span>Phone: ' . $address['Phone1'] . '</span>, ' : ''!!} -->
                                          {!!$address['City'] ? '<span>' . $address['City'] . '</span>, ' : ''!!}
                                          {!!$address['State'] ? '<span>' . $address['State'] . '</span>, ' : ''!!}
                                          {!!$address['Zip'] ? '<span>' . $address['Zip'] . '</span>, ' : ''!!}
                                          {!!$address['Country'] ? '<span>' . $address['Country'] . '</span>' : ''!!}
                                       </label>
                                    </p>
                                    @endforeach
                                 </div>
                              </div>
                              <div class="card border-dark mb-3 dropship-card">
                                 <div class="card-body text-dark">
                                    <h5 class="card-title mb-0">
                                       <input type="radio" name="shipping-address" id="other" value="other" />
                                       <label style="width: 90%;" for="other" class="dropship-label">Dropship</label>
                                    </h5>
                                 </div>
                              </div>
                           </div>
                           @endif
                           <div class="other-address" {!!$shipping_addresses && isset($shipping_addresses['ShipToAddresses']) && count($shipping_addresses['ShipToAddresses']) ? 'style="display: none;"' : '' !!}>
                              <div class="d-flex flex-row justify-content-between column-gap-20 mb-3">
                                 <input type="text" data-required="true" class="form-control bg-white " maxlength="35" name="FirstName" aria-describedby="FirstName" placeholder="First Name*">
                                 <input type="text" data-required="true" class="form-control bg-white" name="LastName" maxlength="35" aria-describedby="LastName" placeholder="Last Name*">
                              </div>
                              <div class="d-flex flex-row justify-content-between column-gap-20 mb-3">
                                 <input type="email" data-required="true" class="form-control bg-white" name="Email" maxlength="60" aria-describedby="Email" placeholder="Email*">
                              </div>
                              <div class="d-flex flex-column">
                                 <!--                                            <input type="text" class="form-control bg-white mb-3" name="Company" aria-describedby="Company" placeholder="Company (optional)">-->
                                 <input type="text" data-required="true" class="form-control bg-white mb-3" name="Address1" maxlength="35" aria-describedby="Address" placeholder="Address*">
                                 <input type="text" class="form-control bg-white mb-3" name="Address2" aria-describedby="Apartment" maxlength="35" placeholder="Apartment, suite, etc. (optional)">

                                 @if($states)
                                 <select  data-required="true" class="form-control bg-white mb-3" name="State">
                                    @foreach($states['States'] as $state)
                                    <option value="{{$state['StateCode']}}">{{$state['StateName']}}</option>
                                    @endforeach
                                 </select>
                                 @else
                                 <input type="text" data-required="true" class="form-control bg-white mb-3" name="State" maxlength="50" aria-describedby="State" placeholder="State*">
                                 @endif

                                 <input type="text" data-required="true" class="form-control bg-white mb-3" name="City" maxlength="35" aria-describedby="City" placeholder="City*">

                              </div>
                              <div class="d-flex flex-row justify-content-between column-gap-20 mb-3">
                                 @if($countries)
                                 <select  data-required="true" class="form-control bg-white" name="Country">
                                    @foreach($countries['Countries'] as $country)
                                    <option {{$country['OriginCode'] == 'US' ? 'selected' : ''}} data-country-no="{{$country['CountryNo']}}" value="{{$country['OriginCode']}}">{{$country['Description']}}</option>
                                    @endforeach
                                 </select>
                                 @else
                                 <input type="text" data-required="true" name="country" class="form-control bg-white" maxlength="30" aria-describedby="Country" placeholder="Country*">
                                 @endif
                                 <input type="text" data-required="true" class="form-control bg-white" name="Zip" maxlength="10" aria-describedby="PostalCode" placeholder="Postal Code*">
                              </div>
                           </div>
                           <div class="mb-4 other-information">
                              <label for="InputEmail1" class="form-label">Additional Information</label>
                              <div class="d-flex flex-row justify-content-between mb-3">
                                 <div class="form-group col-md-12 p-0 pr-3 add-info-field">
                                    <input maxlength="250" type="text" data-required="true" class="form-control bg-white" id="reference_number" name="reference_number" placeholder="P.O or Reference Number*">
                                 </div>
                              </div>
                              <div class="d-flex flex-row justify-content-between mb-3">
                                 <div class="form-group col-md-12 p-0 pr-3 add-info-field">
                                    <div class="input-group">
                                          <span class="border-end-0 input-group-addon">
                                             Shipping Date:
                                          </span>
                                          <input name="ship_date" autocomplete="off" data-required="true" id="ship_date" value="{{date('m/d/Y')}}" class="form-control datepicker px-3 border-start-0" type="text" />
                                          <span class="input-group-addon">
                                             <i class="bi bi-calendar"></i>
                                          </span>
                                    </div>
                                 </div>
                              </div>
                              <div class="d-flex flex-row justify-content-between column-gap-20 mb-3">
                                 <textarea maxlength="4000" name="shipping_instructions" class="form-control bg-white" placeholder="Shipping Instructions"></textarea>
                              </div>
                           </div>
                           <div class="d-flex flex-row justify-content-between order_form_button_outer">
                              <button type="button" class="{{!count((array)$cart->items) ? 'disabled' : ''}} btn btn-primary text-uppercase place-order-btn col-md-12" style="max-width: 100%">Place Order</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-6 col-sm-12 m-md-2 bg-fafafa checkout-balance col-12">
                  <div class="checkout_items_wrap">
                     @if(count((array)$cart->items))
                     @foreach($cart->items as $item)
                     <div class="d-flex flex-row justify-content-lg-between p-5 align-items-center pb-2" id="{{$item -> item_id}}__{{$item -> item_customer_id}}">
                        <div class="col-md-3 products-thumbnails position-relative">
                           <a href="javascript:void(0)" class="d-flex">
                              <i class="bi bi-x-circle-fill remove-product-cart" onclick="removeItemFromCart('{{$item -> item_id}}','{{csrf_token()}}','{{$item -> item_customer_id}}')"></i>
                              <img src="{{$item -> item_image}}" onerror="this.onerror=null; this.src='{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}'" alt="{{$item -> item_name}}">
                           </a>
                        </div>
                        <div class="col-md-9">
                           <h3 class="font-ropa m-0">{{$item -> item_name}}</h3>
                           <!-- <p class="specs m-0"> <strong class="font-crimson"> Customer ID: </strong> <span class="font-ropa"> {{$item -> item_customer_id}} </span> </p> -->
                           <p class="specs m-0"> <strong class="font-crimson"> Item ID: </strong> <span class="font-ropa"> {{$item -> item_id}} </span> </p>
                           <p class="specs m-0"> <strong class="font-crimson"> Color: </strong> <span class="font-ropa"> {{$item -> item_color}} </span> </p>
                           <p class="specs m-0"> <strong class="font-crimson"> Size: </strong> <span class="font-ropa"> {{$item -> item_size}} </span> </p>
                           <p class="price justify-content-end m-0"> {{$item -> item_currency . $item -> item_total}} </p>
                           <hr class="minicart-seprator" />
                           <div class="action-item-sm p-2 px-0 d-flex flex-row align-items-center justify-content-between col-sm-12">
                              <input id="input-quantity" type="number" oninput="showUpdateCartButton('{{$item -> item_id}}__{{$item -> item_customer_id}}')" onkeydown="if(this.key==='.'){this.preventDefault();}" class="form-control" min="1" max="{{ $item->item_only_max_quantity ? ($item->item_atsq > $item -> item_quantity ? $item->item_atsq : $item -> item_quantity) : '9999'}}" maxlength="4" value="{{$item -> item_quantity}}" />
                              <a href="javascript:void(0);" style="display: none;" class="update-cart-button font-ropa" onclick="updateCart('{{$item -> item_id}}','{{csrf_token()}}','{{$item -> item_customer_id}}')"> Update </a>
                              <div id="updating-cart" class="d-none d-flex flex-column text-center">
                                 <div class="spinner-border" role="status">
                                    <span class="sr-only" style="opacity:0;">Loading...</span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <p class="font-nexa-light m-0 sidemark-section">
                                 <a href="javascript:void(0);" style="font-size: 12px;" class="btn--border-bottom m-0 mt-1 mb-1 add-sidemark text-black-50"> Add Sidemark </a>
                                 <textarea class="form-control d-none" maxlength="30" name="sidemark[{{$item -> item_id}}]"></textarea>
                              </p>
                           </div>
                        </div>
                     </div>
                     @endforeach
                     @else
                     <div class="d-flex flex-row p-5 align-items-center">
                        <div class="col-md-12">
                           <h3 class="font-ropa m-0">Cart is Empty</h3>
                        </div>
                     </div>
                     @endif
                  </div>
                  <div class="col-md-12 p-5 py-1 mb-5">
                     <hr class="minicart-seprator mb-2">
                     <p class="specs m-0 d-flex justify-content-between mb-2 d-none"> <strong class="font-crimson"> Sub Total </strong> <span class="font-ropa cart_sub_total"> {{$cart->cart_currency . $cart->cart_total}} </span> </p>
                     <input type="hidden" value="{{$cart->cart_total}}" id="cart_sub_total_hidden">
                     <p class="specs m-0 d-flex justify-content-between mb-2 d-none"> <strong class="font-crimson"> Shipping </strong> <span class="font-ropa shipping_price_value"> $0.00 </span> </p>
                     <hr class="minicart-seprator mb-2 d-none">
                     <p class="specs m-0 d-flex justify-content-between total-amount"> <strong class="font-crimson"> Total </strong> <span class="font-ropa cart_total_price"> {{$cart->cart_currency . $cart->cart_total}} </span> </p>
                  </div>
               </div>
               @else
               <div class="bg-fafafa col-12 col-sm-12 m-md-2">
                  <div class="checkout_items_wrap">
                     <div class="d-flex flex-row p-5 align-items-center">
                        <div class="col-md-12 text-center">
                           <h1 class="font-ropa m-0">Cart is empty</h1>
                        </div>
                     </div>
                     <div class="p-5 pt-0 text-center">
                        <a href="{{url('/')}}" class="btn btn-primary place-order-btn text-uppercase" style="max-width: 100%">Back to Shop</a>
                     </div>
                  </div>   
               </div>
               @endif
            </div>
         </div>
      </section>
   </main>
   @include('frontend.'.$active_theme -> theme_abrv.'.components.footer')
</div>
<div class="modal fade bd-example-modal-lg popupModal login-modal-popup"  data-backdrop="static" data-keyboard="false" id="checkOut_popup" tabindex="-1" role="dialog" aria-labelledby="checkOutLabel" aria-hidden="true">
   <div class="backdrop" style="display:none;"></div>
   <div class="modal-dialog modal-lg wunst" role="document">
      <div class="modal-content">
         <div class="modal-header col-sm-12 justify-content-center flex-column">
            <h2 class="title d-flex flex-column justify-content-center text-center">
               <i class="bi bi-info-circle-fill d-none" style="color:#c90f41;font-size:30px;"></i>
               <i class="bi bi-check-circle-fill d-none" style="color:#127812;font-size:30px;"></i>
               Order Received
            </h2>
            <!-- <a href="http://vcs.ashtexsolutions.com" class="position-absolute close-icon" style="text-decoration: none;font-size: 30px;line-height: 12px;"><span aria-hidden="true" onclick="location.href='/'">&times;</span></a> -->
         </div>
         <div class="modal-body thanku flex-column justify-content-center" style="margin-top:-0;">
            <p class="thanku-msg text-center">We have received your order.</p>
            <a href="{{route('frontend.home')}}" class="btn btn-primary text-uppercase checkout-signin m-auto btn-back-to-home">BACK TO HOME</a>
         </div>
      </div>
   </div>
</div>
@endsection
@section('scripts')
<script>
   $(document).ready(function() {
      function validateEmail(email) {
         var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
         return re.test(email);
      }

      $('#cart-icon').removeAttr('id');
      $('.address-cards input[type="radio"]').on('click', function() {
         if ($(this).val() === 'other') $('.other-address').show();
         else $('.other-address').hide();
      });

      $('.select-address').on('change', function() {
         $('.address-card').addClass('d-none');
         $(`.address-card.${$(this).val()}`).removeClass('d-none');
         $(`input[type="radio"]`, $(this).parent()).click();
      }).change();

      $('.add-sidemark').click(function() {
         $('textarea', $(this).closest('.sidemark-section')).toggleClass('d-none');
      });

      $('select[name="ship-pickup"]').on('change', function() {
         $.post("{{route('frontend.checkout.shipping-rate')}}", {
            "_token": "{{ csrf_token() }}",
            "shipping_method": $(this).val()
         }, function(data) {
            // data = JSON.parse(data);
            if (data.success) {
               var _total = "{{$cart->cart_total}}";
               _total = parseFloat(_total.replace(/,/g, '')) + data.data;

               $('.shipping_price_value').attr('price', data.data).html(data.data.toLocaleString('en-US', {
                  style: 'currency',
                  currency: 'USD',
               }));
               $('.cart_total_price').html(_total.toLocaleString('en-US', {
                  style: 'currency',
                  currency: 'USD',
               }));
            }
         });
      });

      $('#checkOut_popup .btn-back-to-home').click(function() {
         if (typeof $(this).attr('data-dismiss') !== "undefined") {
            $('#checkOut_popup').modal('hide');
         } else
            return true;
      });

      /*
      $('select[name="Country"]').change(function() {
         $('[name="State"]').html('<option>Loading States...</option>').val('Loading States...').addClass('muted').css('pointer-events', 'none');
         $.post('{{route("checkout.states")}}', {
            '_token': "{{ csrf_token() }}",
            'country': $(`option[value="${$(this).val()}"]`, $('select[name="Country"]')).attr('data-country-no')
         }, function(response){
            $('[name="State"]').remove();
            if (response.States.length) {
               var options = "";

               response.States.map((state) => {
                  console.log(state);
                  options += `<option value="${state.StateCode}">${state.StateName}</option>`;
               });

               $(`
                  <select data-required="true" class="form-control bg-white mb-3" name="State">
                     ${options}
                  </select>
               `).insertBefore($('[name="City"]'));
            } else {
               $(`
                  <input type="text" data-required="true" class="form-control bg-white mb-3" name="State" maxlength="50" aria-describedby="State" placeholder="State*">
               `).insertBefore($('[name="City"]'));
            }
         })
      }).change();
      */

      $('select[name="ship-pickup"]:first, input[name="shipping-address"]:first').click();
      $('.place-order-btn').on('click', function() {
         var allOk = true;

         if ($('.other-address').is(':visible'))
            $('input[data-required="true"], select[data-required="true"]').each(function() {
               if (typeof $(this).val().length === 'undefined') {
                  $(this).addClass('is-invalid');
                  allOk = false;
               } else if ($(this).val().trim().length < 1) {
                  $(this).addClass('is-invalid');
                  allOk = false;
               } else {
                  $(this).removeClass('is-invalid');
               }
            });

         $('input[data-required="true"]', $('.other-information')).each(function() {
            if (typeof $(this).val().length === 'undefined') {
               $(this).addClass('is-invalid');
               allOk = false;
            } else if ($(this).val().trim().length < 1) {
               $(this).addClass('is-invalid');
               allOk = false;
            } else {
               $(this).removeClass('is-invalid');
            }
         });

         if (allOk && $('input[type="email"]').is(':visible') && !validateEmail($('input[type="email"]:visible').val())) {
            $('input[type="email"]').addClass('is-invalid');
            allOk = false;
         }

         if (allOk) {
            $('.place-order-btn').attr('disabled', 'disabled');
            $('.checkout_items_wrap input[type="number"]').attr('readonly', 'readonly');

            var _formData = {};
            if ($('.other-address').is(':visible')) {
               $('.other-address input[type="text"], .other-address input[type="email"]').each(function() {
                  _formData[$(this).attr('name')] = $(this).val();
               });
               _formData['shipping_method'] = $('select[name="ship-pickup"]').val();
            } else {
               _formData = JSON.parse($('input[name="shipping-address-data"]', $(`.${$('.select-address').val()}`)).val());

            }

            $('.sidemark-section textarea').each(function() {
               _formData[$(this).attr('name')] = $(this).val();
            });

            $('.other-information input, textarea').each(function() {
               _formData[$(this).attr('name')] = $(this).val();
            });

            _formData['_token'] = "{{ csrf_token() }}";
            _formData['shipping_method'] = $('select[name="ship-pickup"]').val();
            _formData['shipping_cost'] = $('.shipping_price_value').attr('price');

            $.post("{{route('frontend.checkout.place_order')}}", _formData, function(data) {
               // data = JSON.parse(data);
               if (data.success) {
                  $('#checkOut_popup .title').html('<i class="bi bi-check-circle-fill" style="color:#127812;font-size:30px;"></i> Order Placed');
                  $("#checkOut_popup .btn-back-to-home").removeAttr('data-dismiss').attr('href', "{{route('frontend.home')}}").html("Back to Home");
               } else {
                  $('#checkOut_popup .title').html('<i class="bi bi-info-circle-fill" style="color:#c90f41;font-size:30px;"></i>Oops!');
                  $('#checkOut_popup .btn-back-to-home').attr('data-dismiss', 'modal').attr('href', "#").html("Close");
                  $('#checkOut_popup .thanku-msg').html('There is an error while generating your order.');
                  $('.place-order-btn').removeAttr('disabled');
               }
               // $('#checkOut_popup .title').html(data.success ? '<i class="bi bi-check-circle-fill" style="color:#127812;font-size:30px;"></i> Order Placed' : '<i class="bi bi-info-circle-fill" style="color:#c90f41;font-size:30px;"></i>Oops!');
               $('#checkOut_popup .thanku-msg').html(data.msg);
               $('.backdrop').show();
               $('#checkOut_popup').modal({
                  keyboard: false,
                  backdrop: 'static'
               });
               $('#checkOut_popup').modal('show');
               $('.modal-backdrop').hide();
            });
         }

         $('.checkout_items_wrap input[type="number"]').removeAttr('readonly');
         return false;
      });

      $(document).on("keyup, keydown", function(e) {
         if (e.which === 27 && $('#checkOut_popup').is(':visible')) {
            e.preventDefault();
            return false;
         } 
      });

      //grey out ship to address when drop ship selected on checkout
      $('#other').on('change',function(){
         if ( $(this).prop("checked") == true ) {
            $('.card.mb-3.ship-to-address').addClass('disabled-card');
            $('.card.border-dark.mb-3.dropship-card').removeClass('disabled-card');
         }
      });

      $('#existing-address').on('change',function(){
         if ( $(this).prop("checked") == true ) {
            $('.card.mb-3.ship-to-address').removeClass('disabled-card');
            $('.card.border-dark.mb-3.dropship-card').addClass('disabled-card');
         }
      });

   });
</script>
@endsection
