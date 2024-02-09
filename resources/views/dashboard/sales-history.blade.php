@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets
use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;
@endphp
@extends('dashboard.layouts.app')
@section('title','Dashboard | Sales History')
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
                  <div class="account-content p-5">
                     <h1 class="section-title text-center mb-3 mt-3 font-ropa">Reports</h1>
                     @if($filters)
                     <form method="GET" action="{{ route('dashboard.saleshistory') }}" class="dashboard-forms d-flex flex-lg-row flex-sm-column flex-dir-col flex-wrap mt-3 dafault-form p-1 pt-3">
                        <input type="hidden" value="{{Auth::user()->customer_id}}" name="sales_rep">
                        @foreach($filters as $filter)
                        @if($filter['type'] == 'hidden')
                           <input name="{{str_replace(' ', '_', strtolower($filter['title']))}}" value="{{$filter['value']}}" class="form-control" {!!isset($filter['attributes']) ? $filter['attributes'] : '' !!} type="{{$filter['type']}}" />
                           @else
                           <div class="mb-3 col-md-3 col-sm-12 pe-1 pe-lg-3">
                              <label for="{{str_replace(' ', '_', strtolower($filter['title']))}}" class="form-label">{{$filter['title']}}</label>
                              @if($filter['type'] == 'select')
                              <select name="{{str_replace(' ', '_', strtolower($filter['title']))}}" class="form-control">
                                 @if($filter['options'])
                                    @foreach($filter['options'] as $option)
                                    <option {{old(str_replace(' ', '_', strtolower($filter['title']))) && old(str_replace(' ', '_', strtolower($filter['title']))) == $option['value'] ? 'selected' : ($filter['value'] == $option['value'] ? 'selected' : '' ) }} value="{{$option['value']}}">{{$option['label']}}</option>
                                    @endforeach
                                 @endif
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
                        <div class="col-md-12 d-flex justify-content-end">
                           <button type="submit" class="btn btn-primary text-uppercase mt-2">Search</button>
                        </div>
                       
                     </form>
                     @endif
                     
                  </div>
                  <div class="table-container">
                    <div id="report_details"></div>
                  </div>
            </div>
         </div>
      </section>
   </main>
   @include('dashboard.components.footer')
</div>
@endsection
@section('scripts')
@parent
<script type="text/javascript">
   $(document).ready(function() {
      
      if(`{{ isset($ReportData) && $ReportData }}`) {
         // The Base64 string of a simple PDF file
         var b64 = `{{ isset($ReportData) ? $ReportData : '' }}`

         // Embed the PDF into the HTML page and show it to the user
         var obj = document.createElement('object');
         obj.style.width = '100%';
         obj.style.height = '842pt';
         obj.type = 'application/pdf';
         obj.data = 'data:application/pdf;base64,' + b64;
         // document.body.appendChild(obj);
         $(obj).insertAfter('#report_details');

         // Insert a link that allows the user to download the PDF file
         var link = document.createElement('a');
         link.innerHTML = 'Download Report';
         link.className = 'btn btn-primary my-3 py-3';
         link.download = 'Report.pdf';
         link.href = 'data:application/octet-stream;base64,' + b64;
         // document.body.appendChild(link);
         $(link).insertAfter('#report_details');
      }
      
   });
</script>
@endsection