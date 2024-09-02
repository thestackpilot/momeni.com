@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp 
<div class="container breadcrumbs margin-top-90">
    <ul class="d-flex flex-row justify-content-start m-0 p-0 muted">
        @php
            $i = 0;
            $numItems = count($breadcrumbs);
        @endphp
        @foreach ($breadcrumbs as $key => $value)
        <li>
            <a href="{{$value}}">{{$key}}</a> 
            @if(++$i !== $numItems)
            <span class="m-2"> / </span>
            @endif
        </li>
        @endforeach
    </ul>
</div>