@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp

<div class="sidebar admin-sidebar">
    <a href="#0" class="sidebar-close" style="text-align: center;" class="me-2">
        <i class="bi bi-chevron-double-left"></i>
    </a>
    <ul class="pd-flex flex-column filter-content mb-1 p-0">
        @foreach($sidebar as $item)
        @if($item['permission'] && strcmp(ConstantsController::USER_ROLES['staff'], Auth::user()->role) === 0 && !in_array($item['permission'], Auth::user()->getPermissions())) {{''}}
        @elseif(Auth::user()->is_customer && $item['slug'] === 'dashboard.saleshistory')
            <li> <a href="{{route($item['slug'])}}" class="{{ str_contains(url()->current(),route($item['slug']))  ?'active': $item['slug'] }}"> {{$item['label']}} </a></li>
        @elseif($item['slug'] !== "dashboard.paymentoptions" )
        <li> <a href="{{route($item['slug'])}}" class="{{ str_contains(url()->current(),route($item['slug']))  ?'active': $item['slug'] }}"> {{$item['label']}} </a></li>
        @endif
        @endforeach
        <li> <a href="{{route('auth.logout')}}" class=""> Logout </a></li>
    </ul>
</div>

<!-- || ( ! Auth::user()->is_customer && $item['slug'] !== 'dashboard.saleshistory') -->
