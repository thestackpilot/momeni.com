@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp

@php
// TODO: use the active theme construct
// TODO: make the image link JSON and constant based
@endphp

@extends('frontend.LR.layouts.app')
@section('title',$view_title)
@section('content')
<div class="wrapper">
   @include('frontend.LR.components.header',['basicSettings' => $basicSettings,'menuMetas' => $menus ,'breadCrumbs'=>$breadcrumbs])
   <main class="main-content search-page">
      <section class="collection-section">
         <div class="container">

            <div class="breadcrumb-area">
               <div class="row breadcrumb_box  align-items-center">
                  <div class="col-lg-12 col-md-12 col-sm-12 text-center text-sm-left">
                     <h2 class="breadcrumb-title text-center">
                        RECORD(S) FOR "{{$search_string}}"
                     </h2>
                  </div>
               </div>
            </div>

            @foreach($filterpages as $dataFilter)
            @if (isset($type) && $type != '' && $type != $dataFilter['MainCollectionID']) @continue; @endif
            <div class="row breadcrumb_box  align-items-center">
               <div class="col-lg-12 col-md-12 col-sm-12 text-center text-sm-left">
                  <h2 class="breadcrumb-title text-center"> {{$dataFilter['Name']}} </h2>
               </div>
            </div>

            <div class="product-listing">
               <div class="row">
                  @foreach($dataFilter['Designs'] as $k => $design)
                  @if((isset($type) && ($type == '' || $type != $dataFilter['MainCollectionID'])) && $k > 8) @break; @endif
                  <div class="col-md-4">
                     <!-- slider-for rug-callection -->
                     <div class="">
                        <a href="{{url('/').'/item/'.$dataFilter['MainCollectionID'].'/'.urlencode(urlencode($design['DesignID']))}}">
                           <img src="{{CommonController::getApiFullImage($design['ImageName'])}}" class="img-responsive" onerror="this.onerror=null; this.src='{{url('/').ConstantsController::IMAGE_PLACEHOLDER}}'" />
                        </a>
                     </div>
                     <div class="product-content inn-p">
                        <a href="{{url('/').'/item/'.$dataFilter['MainCollectionID'].'/'.urlencode(urlencode($design['DesignID']))}}" style="display: inherit; min-height: auto;">
                           <h6 class="prodect-title" title="{{join(', ', $design['Colors'])}}">{{join(', ', $design['Colors'])}}</h6>
                           <h6 class="prodect-title" title="{{$design['Description']}}">{{$design['Description']}}</h6>
                        </a>
                     </div>
                  </div>
                  @endforeach
               </div>
            </div>
            @if(( isset($type) && ($type == '' || $type != $dataFilter['MainCollectionID'])) && $dataFilter && count($dataFilter['Designs']) > 8) 
            <div class="button-box section-space--mt_20 section-space--mb_20 text-center">
               @if (isset($filter_page) && $filter_page)
               <a href="{{route('frontend.designs',[$dataFilter['MainCollectionID'], base64_encode('{"Filters": [{"FilterID":"'.str_replace(' ','_',trim($view_title)).'","Values":["1"]}]}'), $search_string ?? 0])}}"" class="btn btn--md btn--border_1 d-inline">Explore More <i class="icon-arrow-right"></i></a>
               @else
               <a href="{{route('frontend.search',[base64_encode($search_string), $dataFilter['MainCollectionID']])}}" class="btn btn--md btn--border_1 d-inline">Explore More <i class="icon-arrow-right"></i></a>
               @endif
            </div>
            @endif
            @if(!$loop->last)
            <hr class="minicart-seprator filter-page-separator">
            @endif
            @if (isset($type) && $type != '' && $type == $dataFilter['MainCollectionID']) @break; @endif
            @endforeach
         </div>
      </section>
   </main>
   @include('frontend.LR.components.footer',['basicSettings' => $basicSettings, 'footers' => $filterpages, 'footerMeta' => $filterpages])
</div>
@endsection