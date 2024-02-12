@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

$filter_arr = array();
function getCount( $subfilters, $filter ) {
    foreach($subfilters as $subfilter) {
        if ( strcmp($filter, $subfilter['Description']) === 0 ) {
            return $subfilter['Count'];
        }
    }
}

$is_discontinued = false;
if(isset($default_filter) && $default_filter && isset(json_decode($default_filter, 1)['Filters'])) {
foreach(json_decode($default_filter, 1)['Filters'] as $filter) {
	foreach($filter['Values'] as $value) {
		if ($filter['FilterID'] === 'Discontinued') $is_discontinued = $value;

	}
}
}
@endphp
<div class="sidebar">
    <div class="filler-show-btn pull-right">
        <button onclick="setVisibility('sub3', 'inline');" ;="">Filters <span class="icon-funnel"></span></button>
    </div>
    <div class="list-fillter" id="sub3">
        <button onclick="setVisibility('sub3', 'none');" ; class="close-fillter-btn bi bi-x"></button>
        <input type="hidden" name="main_collection_id" value="{{$main_collection['MainCollectionID']}}" >
        <input type="hidden" name="return_type_id" value="{{$return_type_id}}" >
        <input type="hidden" name="default_filter_id" value="{{isset($default_filter) && $default_filter ? $default_filter : ConstantsController::NO_FILTER_FLAG}}" >

        <div id="selected_filters">
            @if( ($is_discontinued) || (!empty($filters['Filters'])) && ($filters['Selected_Filters_Count'] > 0) )
                <div class="filter-header d-flex align-items-center">
                    <h3 class="selected-fltr mb-0">SELECTED FILTERS</h3>
                </div>

                @if(($filters['Filters_Count']) > 0)
                @php
                    $new_arrival = 0;
                    if (false && request('filter')) {
                        if ( $new_arrival ) {
                            foreach($filters['Filters'] as $filter) {
                                if(strtolower($filter['FilterID']) == 'sort') {
                                    foreach($filter['Values'] as $value) {
                                        if ( $value['checked'] ) {
                                            $new_arrival = 0;
                                        }
                                    }
                                }
                            }
                        }
                    }
                @endphp

                @endif
                <div class="p-4 pt-3 filter-content mb-1">
		@if(isset($default_filter) && $default_filter && isset(json_decode($default_filter, 1)['Filters']))
		@foreach(json_decode($default_filter, 1)['Filters'] as $filter)
                    @foreach($filter['Values'] as $value)
                        @if($filter['FilterID'] === 'Discontinued' && $value == 1)
                            <span class="mb-1 badge">
                                {{str_replace('_', ' ', $filter['FilterID'])}}
                                <button class = "remove-filer-cross" type="button" class="close" aria-label="Dismiss">
                                    <input type="hidden" class="remove-filter-value" value="{{$filter['FilterID']}} : {{$value}}" }}>
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </span>
                        @endif
                    @endforeach
                @endforeach
		@endif

                @foreach($filters['Filters'] as $filter)
                    @foreach($filter['Values'] as $value)
                        @if($value['checked'])
                            <span class="mb-1 badge">
                                {{str_replace('_', ' ', $filter['FilterID'])}} : {{$value['value']}}
                                <button class = "remove-filer-cross" type="button" class="close" aria-label="Dismiss">
                                    @if($filter['FilterID'] === 'Generic_Size')
                                    <input type="hidden" class="remove-filter-value" value="{{$filter['FilterID']}} : {{str_replace('"', '\\"', $value['value'])}}" }}>
                                    @else
                                    <input type="hidden" class="remove-filter-value" value="{{$filter['FilterID']}} : {{$value['value']}}" }}>
                                    @endif
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </span>
                        @endif
                    @endforeach
                @endforeach
                </div>
                <div class="clear-badge-fltr me-3 text-end">
                    <span class="clear-all">
                        <button class="btn btn-outline-dark btn-sm clear_all_filters">Clear All</button>
                    </span>
                </div>
            @endif
        </div>
        <!-- <div class="filter-header d-flex align-items-center">
            <h3 class="font-crimson mb-0">CATEGORY</h3>
            <a href="#0" class="sidebar-close"> <i class="bi bi-chevron-double-left"></i> </a>
        </div> -->
        <!-- <ul class="p-4 pt-3 d-flex flex-column filter-content mb-1">
            @foreach($favourites['Favourities'] as $favs)
            @php if ( $favs['Title'] != "View All" ) continue; @endphp
            <li> <a href="{{$favs['LinkUrl']}}"> {{$favs['Title']}} </a></li>
            @endforeach
            <li> <a href="{{route('frontend.designs',[$main_collection['MainCollectionID'], base64_encode('{"Filters": [{"FilterID":"Special_Buys","Values":["1"]}]}'), $type ?? 0])}}"> Special Buy {{ $main_collection['Description'] }} </a></li>
            <li> <a href="{{route('frontend.designs',[$main_collection['MainCollectionID'], base64_encode('{"Filters": [{"FilterID":"Clearances","Values":["1"]}]}'), $type ?? 0])}}"> Clearance {{ $main_collection['Description'] }} </a></li>
        </ul> -->
        {{-- @if($main_collection['MainCollectionID'] == 'Rugs')
        <ul class="d-flex filter-content flex-column mb-1 mt-3 pt-3" style="padding: 1.2rem">
            <li> <a style="font-size: 16px; text-transform: uppercase; color: #727272;" href="{{route('frontend.designs',[$main_collection['MainCollectionID'], base64_encode('{"Filters": [{"FilterID":"Discontinued","Values":["1"]}]}'), $type ?? 0])}}"> Show Discontinued {{ $main_collection['Description'] }} </a></li>
        </ul>
        @endif --}}
        @if(!empty($filters['Filters']))
        <div class="filter-by">
            <div class="filter-header d-flex align-items-center justify-content-between">
                <h3 class="filter-search-btn-txt mb-0">FILTER BY</h3>
                <div class="search_filters d-none">
                    <button type="button" class="filter-search-btn" id="search_filters_btn">Search</button>
                </div>
            </div>
            @foreach($filters['Filters'] as $filter)
                @php
                    if(strtolower(trim($filter['Description'])) != 'size')
                    {
                        $filter_arr[] = $filter['FilterID'];
                    }
                @endphp
                <div class="{{strtolower($filter['FilterID']) == 'sort' ? 'd-none' : ''}}">
                    @if($filter['Description'] == "Current In-Line")
                    <h4 class="filter-subheading">{{$filter['Description']}} {{$main_collection['Description']}}</h4>
                    @else
                    <h4 class="filter-subheading">{{$filter['Description']}}</h4>
                    @endif
                    <ul class="p-4 pt-3 d-flex flex-column filter-content mb-1" id="filter-id-{{str_replace(' ', '', $filter['FilterID'])}}">
                        @foreach($filter['Values'] as $value)
                        <li>
                            <div class="form-check">
                                @if($filter['FilterID'] === 'Generic_Size')
                                <input class="form-check-input sidebar-filters-input" type="checkbox" name="{{$filter['FilterID']}}" value="{{str_replace('"', '\\"', $value['value']) }}" {{($value['checked'])?'checked = ':''}} {{($value['checked'])?'"checked"':''}} id="flexCheckChecked-{{md5($filter['Description'].$value['value'])}}">
                                @else
                                <input class="form-check-input sidebar-filters-input" type="checkbox" name="{{$filter['FilterID']}}" value="{{$value['value']}}" {{($value['checked'])?'checked = ':''}} {{($value['checked'])?'"checked"':''}} id="flexCheckChecked-{{md5($filter['Description'].$value['value'])}}">
                                @endif
                                <label class="form-check-label" for="flexCheckChecked-{{md5($filter['Description'].$value['value'])}}"> {{$value['value']}} </label>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
        @endif
    </div>

</div>
@section('styles')
<style>
    select.sort-filter {
        width: auto;
        min-width: 170px;
        border: 1px solid #e0e0e0;
        height: 40px;
        margin-top: 20px;
        font-size: 14px;
        /* font-family: 'Conv_Nexa Light'; */
        font-weight: 500;
        color: #444343;
        padding: 5px 10px;
    }
</style>
@endsection
@section('scripts')
@parent
<script>
    function setVisibility(id, visibility) {
        document.getElementById(id).style.display = visibility;
    }
    if (!localStorage.getItem('main_url')) {
        localStorage.setItem('main_url', window.location.href);
    }
    var filter_type = '';
    var filter_value = '';
    var filter_array = null;
    var main_url = localStorage.getItem('main_url');
    var not_redirect = "{{ \Illuminate\Support\Facades\Route::current()->getName() }}"
    if (not_redirect !== 'frontend.designs') {
        main_url = window.location.href;
    }

    function fill_filter_array(type)
    {
        var temp = [];
        var str = '';
        if(type !== 'Sort')
        {
            $.each($("input[name='" + type + "']:checked"), function()
            {
                temp.push('"' + ($(this).val()).trim() + '"');
            });
            filter_value = '';
        }else{
            $.each($("select[name='" + type.toLowerCase() + "'] option:selected"), function()
            {
                temp.push('"' + ($(this).val()).trim() + '"');
                filter_type = 'Sort';
                if($(this).val() != '0')
                {
                    filter_value = $(this).val();
                }
                console.log($(this).val())
            });
        }

        if (temp.length != 0)
        {
            return '{"FilterID":"' + type + '","Values":[' + temp + ']}';
        }
        return '';
    }

    function filterManager(discontinued_filter, Filterarr, discontinued, redirect_back = 0)
    {

        if ($('#pageLoader').length)
        {
            $('#pageLoader').removeClass('d-none');
        }

        if (xhr != null)
        {
            xhr.abort();
        }

        var FiltersArray = btoa(`{!!ConstantsController::NO_FILTER_FLAG!!}`);

        if (Filterarr == null)
        {
            var Filters = [];
            var filter_types = "{{ implode( ',', $filter_arr ) }}".split(','); //['Current In-Line', 'Color', 'Style', 'Material', 'Pattern', 'Weaving', 'Discount', 'Collection', 'Construction'];
            var default_filter_id = $('input[name="default_filter_id"]').val();

            filter_types.forEach(function(filter)
            {
                var response = fill_filter_array(filter);
                if (response.length)
                {
                    Filters.push(response);
                }
            });

            var sizes = [];
            $.each($("input[name='Size']:checked"), function()
            {
                var val = $(this).val();
                sizes.push('"' + val.replace(/"/g, '\\"') + '"');
            });

            if (sizes.length != 0)
            {
                Filters.push('{"FilterID":"Size","Values":[' + sizes + ']}')
            }


            if (Filters.length != 0)
            {
                Filterarr = [];
                if ( default_filter_id && default_filter_id != '{!!ConstantsController::NO_FILTER_FLAG!!}' ) {
                    var defaultFilter = JSON.parse(`${default_filter_id}`);
                    defaultFilter['Filters'].forEach(function(filter){
                        if (!filter_types.includes(filter.FilterID) && typeof filter.FilterID.length !== 'undefined')
                            Filterarr.push(JSON.stringify(filter));
                    });
                    if ( Filterarr.length > 0 )
                        Filters.push(Filterarr);
                }
                FiltersArray = btoa('{"Filters": [' + Filters + ']}');
            }
            else if ('{{isset($default_filter) && $default_filter}}' == '1' && false) {
                var defaultFilter = JSON.parse(`${default_filter_id}`);//JSON.parse('{!!$default_filter ?? ""!!}');
                var count = 0;
                defaultFilter['Filters'].forEach(function(filter){
                    if (filter_types.includes(filter.FilterID))
                        count++;
                });
                if ( count == (defaultFilter['Filters']).length )
                    FiltersArray = btoa('{!!ConstantsController::NO_FILTER_FLAG!!}');
                else
                    FiltersArray = btoa(default_filter_id);
            }
        }
        else
        {
            FiltersArray = btoa(Filterarr);
        }
        console.log(FiltersArray);

        console.log(typeof discontinued);
        if (typeof discontinued !== "undefined" ) {
            console.log("discontinued");
        var temp = atob(FiltersArray);
            let jsonData = JSON.parse(temp);
            console.log(temp);
        let discontinuedFilterIndex = jsonData.Filters.findIndex(filter => filter.FilterID === 'Discontinued');
        console.log(discontinuedFilterIndex);
        if (discontinuedFilterIndex !== -1) {
            jsonData.Filters.splice(discontinuedFilterIndex, 1);
            // let currentDiscontinuedValue = jsonData.Filters[discontinuedFilterIndex].Values[0];
            // jsonData.Filters[discontinuedFilterIndex].Values[0] = (currentDiscontinuedValue === '1') ? '0' : '1';
            }else if (discontinued_filter !== null && typeof discontinued_filter !== "undefined")
            {
                console.log('discountinued:', discontinued_filter);
                jsonData.Filters.push({
                    FilterID: 'Discontinued',
                    Values: ['1']
                });
            }

            console.log("in discountinued");
            console.log(jsonData);
        FiltersArray = btoa(JSON.stringify(jsonData));
        }
        filter_array   = FiltersArray;
        console.log(FiltersArray);
        var mainCollectionId = $('input[name="main_collection_id"]').val();
        var return_type = $('input[name="return_type_id"]').val();

        var url = window.location.origin + "/designs/" + mainCollectionId + "/" + FiltersArray + "/" + return_type;
        var collection_url = window.location.origin + "/collections/" + mainCollectionId + "/" + return_type + "/" + FiltersArray ;

        var designPagePath = window.location.origin + '/designs';
        var currentPagePath = window.location.origin + window.location.pathname;

        if( currentPagePath.match(designPagePath) == null )
        {
            // console.log(filter_type);
            // console.log(filter_value);
            // console.log(currentPagePath.match(collection_url) == null && filter_type == 'Sort' && filter_value != '');
            if(currentPagePath.match(collection_url) == null && filter_type == 'Sort' && filter_value != '')
            {
                    console.log(collection_url);
                    if(filter_type == '0' )
                    {
                        window.location.href = window.location.origin + "/collections/" + mainCollectionId + "/" + return_type;
                    }
                    else{
                        xhr = $.ajax(
                        {
                            method: 'GET',
                            url: collection_url,
                            data: {
                                '_token': '{{csrf_token()}}',
                            }
                        }).
                        done(function(response)
                        {
                            var base_url = window.location.origin;
                            window.history.pushState('', '', collection_url);
                            var new_html = $($.parseHTML(response));
                            console.log(new_html.find('#selected_filters').html());

                            $('#sub_collections_wrapper').html(new_html.find('#sub_collections_wrapper').html());
                            $('#collection_heading').html(new_html.find('#collection_heading').html());
                            $('#selected_filters').html(new_html.find('#selected_filters').html());

                            if ($('#pageLoader').length)
                            {
                                $('#pageLoader').addClass('d-none');
                            }
                            applyFilterTrigger();
                        });
                    }
                    // filter_type = '';
                    // filter_value = '';
            }
            else{
                window.location.href = url;
                console.log('else');
            }

        }
        else
        {
            xhr = $.ajax(
            {
                method: 'GET',
                url: url,
                data: {
                    '_token': '{{csrf_token()}}',
                }
            }).
            done(function(response)
            {
                if (redirect_back) {
                    window.location.href = main_url;
                    localStorage.removeItem('main_url')
                    return;
                }
                console.log("ajax");
                var base_url = window.location.origin;
                window.history.pushState('', '', url);
                var new_html = $($.parseHTML(response));

                $('#sub_collections_wrapper').html(new_html.find('#sub_collections_wrapper').html());
                $('#collection_heading').html(new_html.find('#collection_heading').html());
                $('#selected_filters').html(new_html.find('#selected_filters').html());

                if ($('#pageLoader').length)
                {
                    $('#pageLoader').addClass('d-none');
                }
                applyFilterTrigger();
            });
        }
    }

    function applyFilterTrigger()
    {
        $('.remove-filer-cross').off('click');
        $('.remove-filer-cross').on('click', function()
        {
            var sel_filter = $(this).find('.remove-filter-value').val().toString().trim().split(':');
            removeFilter(sel_filter[0].trim(), sel_filter[1].trim(), sel_filter[0].trim() == 'Discontinued', sel_filter[1].trim() == 1 ? 0 : 1);
        });
    }

    function removeFilter(filterType, filterValue, discontinued, dvalue)
    {
        $("#filter-id-" + (filterType.replace(' ', ''))).find("input[name='" + filterType + "']:checked").each(function(index,filter)
        {
            if($(filter).val().trim() == filterValue)
            {
                $(this).prop("checked", false);
            }
        });

        $("#filter-id-" + (filterType.replace(' ', '')).toLowerCase()).find("select[name='" + filterType.toLowerCase() + "'] option").each(function(index, option) {
            if ($(option).val().trim() == filterValue)
            {
                $(option).removeAttr("selected");
            }
        });
        // console.log("test");
        // console.log($("#filter-id-" + (filterType.replace(' ', '')).toLowerCase()).find("select[name='" + filterType.toLowerCase() + "'] option:selected").val());
	if (discontinued)
    {
        $('#flexCheckChecked-discontinued').prop('checked', false);
        console.log(dvalue);
        filterManager(null, null, dvalue);
        console.log('check');
    }   else filterManager();
    }

    var xhr = null;
    $(document).ready(function()
    {
        $(document).off('change', '.sidebar-filters-input').on('change', '.sidebar-filters-input', function()
        {
            filterManager();
        });

        /* $(document).off('click', '#search_filters_btn').on('click', '#search_filters_btn', function()
        {
            filterManager();
        });  */
        $(document)
        .off('change', 'select.sort-filter')
        .on('change', 'select.sort-filter', function() {
            $(`input[value="${$(this).val()}"]`).trigger('click');
        });
        applyFilterTrigger();

        $(document).off('click', '.clear_all_filters').on('click', '.clear_all_filters', function() {
            $('.filter-content .sidebar-filters-input').each(function(){
                $(this).prop('checked', false);
            })
            $('.filter-content .sidebar-filters-input').each(function(){
                $(this).removeAttr('checked');
            });
            filterManager(null,null, 0, 1);
        });

        $(document)
        .off('click', '#flexCheckChecked-discontinued')
        .on('click', '#flexCheckChecked-discontinued', function() {

            if ($(this).prop('checked')) {
                console.log('Checkbox is checked');

                discontinued_filter = '{"FilterID":"Discontinued","Values":["1"]}';
                filterManager(discontinued_filter, null, 0);
                // var mainCollectionId = $('input[name="main_collection_id"]').val();
                // var filter_arr = btoa('{"Filters": [{"FilterID":"Discontinued","Values":["1"]}]}');

                // var return_type = $('input[name="return_type_id"]').val();

                // var url = window.location.origin + "/designs/" + mainCollectionId + "/" + filter_arr + "/" + return_type;
                // console.log(return_type);
                // window.location.href = url;
            } else {

                removeFilter('Discontinued', 1,true, 0);

            }
        });
    });
</script>
@endsection
