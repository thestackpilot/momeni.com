@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;
$design_page = TRUE;
@endphp 

@section('title','Designs')
@include('frontend.'.$active_theme -> theme_abrv.'.collection')