@php
// $active_theme object is available containing the theme developer json loaded.
// This is for the theme developers who want to load further view assets

use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\CommonController;

@endphp 
<div class="sidebar">
    <div class="filler-show-btn">
        <button onclick="setVisibility('sub3', 'inline');" ;="">Filters <span class="bi bi-funnel"></span></button>
    </div>
    <div class="list-fillter" id="sub3">
        <button onclick="setVisibility('sub3', 'none');" ; class="close-fillter-btn bi bi-x"></button>
        <input type="hidden" name="main_collection_id" value="{{$main_collection['MainCollectionID']}}" >
        <input type="hidden" name="return_type_id" value="{{$return_type_id}}" >
        <input type="hidden" name="default_filter_id" value="{{isset($default_filter) && $default_filter ? $default_filter : ConstantsController::NO_FILTER_FLAG}}" >

        <div id="selected_filters">
            @if( (!empty($filters['Filters'])) && ($filters['Selected_Filters_Count'] > 0) )
                <div class="filter-header d-flex align-items-center">
                    <h3 class="font-crimson mb-0">SELECTED FILTERS</h3>
                </div>
                <div class="p-4 pt-3 filter-content mb-1">
                @foreach($filters['Filters'] as $filter)
                    @foreach($filter['Values'] as $value)
                        @if($value['checked'])
                            <span class="mb-1 badge {{$value['color_code']}}">
                                {{$filter['FilterID']}} : {{$value['value']}}
                                <button class = "remove-filer-cross" type="button" class="close" aria-label="Dismiss">
                                    <input type="hidden" class="remove-filter-value" value="{{$filter['FilterID']}} : {{$value['value']}}" }}>
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </span>
                        @endif
                    @endforeach
                @endforeach
                </div>
            @endif
        </div>
        <!-- <div class="filter-header d-flex align-items-center">
            <h3 class="font-crimson mb-0">CATEGORY</h3>
            <a href="#0" class="sidebar-close"> <i class="bi bi-chevron-double-left"></i> </a>
        </div> -->
        <ul class="p-4 pt-3 d-flex flex-column filter-content mb-1">
            @foreach($favourites['Favourities'] as $favs)
            @php if ( $favs['Title'] != "View All" ) continue; @endphp
            <li> <a href="{{$favs['LinkUrl']}}"> {{$favs['Title']}} </a></li>
            @endforeach
            <li> <a href="{{route('frontend.designs',[$main_collection['MainCollectionID'], base64_encode('{"Filters": [{"FilterID":"Special_Buys","Values":["1"]}]}'), $type ?? 0])}}"> Special Buy {{ $main_collection['Description'] }} </a></li>
            <li> <a href="{{route('frontend.designs',[$main_collection['MainCollectionID'], base64_encode('{"Filters": [{"FilterID":"Clearances","Values":["1"]}]}'), $type ?? 0])}}"> Clearance {{ $main_collection['Description'] }} </a></li>
        </ul>
        @if(!empty($filters['Filters']))
        <div class="filter-by">
            <div class="filter-header d-flex align-items-center justify-content-between">
                <h3 class="font-crimson mb-0">FILTER BY</h3>
                <div class="search_filters">       
                    <button type="button" class="btn btn-dark" id="search_filters_btn">Search</button>
                </div>
            </div>
            @foreach($filters['Filters'] as $filter)
                @if($filter['Description'] == "Current In-Line")
                <h4 class="filter-subheading p-4 pt-3 pb-0">{{$filter['Description']}} {{$main_collection['Description']}}</h4>
                @else
                <h4 class="filter-subheading p-4 pt-3 pb-0">{{$filter['Description']}}</h4>
                @endif
                <ul class="p-4 pt-3 d-flex flex-column filter-content mb-1" id="filter-id-{{str_replace(' ', '', $filter['FilterID'])}}">
                    @foreach($filter['Values'] as $value)
                    <li>
                        <div class="form-check">
                            <input class="form-check-input sidebar-filters-input" type="checkbox" name="{{$filter['FilterID']}}" value="{{$value['value']}}" {{($value['checked'])?'checked = ':''}} {{($value['checked'])?'"checked"':''}} id="flexCheckChecked-{{md5($filter['Description'].$value['value'])}}">
                            <label class="form-check-label" for="flexCheckChecked-{{md5($filter['Description'].$value['value'])}}"> {{$value['value']}} </label>
                        </div>
                    </li>
                    @endforeach
                </ul>
            @endforeach
        </div>
        @endif
    </div>
    
</div>
@section('scripts')
@parent
<script>
    function setVisibility(id, visibility) {
        document.getElementById(id).style.display = visibility;
    }

    function fill_filter_array(type) 
    {
        var temp = [];
        var str = '';
        $.each($("input[name='" + type + "']:checked"), function() 
        {
            temp.push('"' + ($(this).val()).trim() + '"');
        });
        if (temp.length != 0) 
        {
            return '{"FilterID":"' + type + '","Values":[' + temp + ']}';
        }
        return '';
    }
    function filterManager(Filterarr) 
    {

        if ($('#pageLoader').length) 
        {
            $('#pageLoader').removeClass('d-none');
        }

        if (xhr != null) 
        {
            xhr.abort();
        }

        var FiltersArray = btoa('{!!ConstantsController::NO_FILTER_FLAG!!}');
        if (Filterarr == null) 
        {
            var Filters = [];
            var filter_types = ['Current In-Line', 'Color', 'Style', 'Material', 'Pattern', 'Weaving', 'Discount', 'Collection', 'Construction'];
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
                    var defaultFilter = JSON.parse(default_filter_id);
                    defaultFilter['Filters'].forEach(function(filter){
                        if (!filter_types.includes(filter.FilterID) && typeof filter.FilterID.length !== 'undefined')
                            Filterarr.push(JSON.stringify(filter));
                    });
                    if ( Filterarr.length > 0 )
                        Filters.push(Filterarr);
                }
                FiltersArray = btoa('{"Filters": [' + Filters + ']}');
            }
            else if ('{{isset($default_filter) && $default_filter}}' == '1') {
                var defaultFilter = JSON.parse('{!!$default_filter ?? ""!!}');
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

        var mainCollectionId = $('input[name="main_collection_id"]').val();
        var return_type = $('input[name="return_type_id"]').val();
        
        var url = window.location.origin + "/designs/" + mainCollectionId + "/" + FiltersArray + "/" + return_type;

        var designPagePath = window.location.origin + '/designs';
        var currentPagePath = window.location.origin + window.location.pathname;
        if (currentPagePath.match(designPagePath) == null) 
        {
            window.location.href = url;
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
            removeFilter(sel_filter[0].trim(), sel_filter[1].trim());
        });
    }
    function removeFilter(filterType, filterValue)
    {
        $("#filter-id-" + (filterType.replace(' ', ''))).find("input[name='" + filterType + "']:checked").each(function(index,filter)
        {
            if($(filter).val().trim() == filterValue)
            {
                $(this).prop("checked", false);
            }
        });
        filterManager();
    }
    var xhr = null;
    $(document).ready(function() 
    {
        // $(".sidebar-filters-input").change(function() 
        // {
        //     filterManager();
        // });
        $('#search_filters_btn').click(function()
        {
            filterManager();
        });
        applyFilterTrigger();
    });
</script>
@endsection
