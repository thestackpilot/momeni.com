<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\ConstantsController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\FavouriteController;

class CollectionsController extends FrontendController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addCollectionUrls( $collections, $id, $type, $with_title = false, $collectionID = "" )
    {
        $cols    = array();
        $counter = 0;

        if ( isset( $collections[$type] ) )
        {
            foreach ( $collections[$type] as $collection )
            {
                $url_link    = '';
                $link_filter = array( 'Filters' => array() );
                foreach ( $collection as $key => $value )
                {

                    if ( strpos( $key, 'ID' ) !== false )
                    {

                        if ( trim( $value ) != '' )
                        {
                            if($id == "BroadLoom" && $type == "Collections"){
                                $key = "Category";
                                $link_filter['Filters'][] = array(
                                    "FilterID" => $key,
                                    "Values"   => is_array( $value ) ? $value : [$value]
                                );
                            }else if($id == "BroadLoom" && $type == "Colors"){
                                $link_filter['Filters'][] = array(
                                    "FilterID" => "Shades_Of_Color",
                                    "Values"   => is_array( $value ) ? $value : [$value]
                                );
                                $link_filter['Filters'][] = array(
                                    "FilterID" => "Category",
                                    "Values"   => [$collectionID]
                                );
                            }else{
                                $key = str_replace( 'ID', '', trim( $key ) );
                                $link_filter['Filters'][] = array(
                                    "FilterID" => $key,
                                    "Values"   => is_array( $value ) ? $value : [$value]
                                );
                            }
                        }

                    }

                }

                $cols[$counter] = $collection;

                if ( $with_title )
                {
                    if($id == "BroadLoom" && $type == "Collections") {
                        $cols[$counter]['LinkUrl'] =   url('/designs/BroadLoom/') . '/' . base64_encode(json_encode($link_filter)) . '/Colors/1';
                    } else {
                        $cols[$counter]['LinkUrl'] = route( 'frontend.designs_with_title', [$id, base64_encode( json_encode( $link_filter ) ), $type, 1] );
                    }
                }
                else
                {
                    $cols[$counter]['LinkUrl'] = route( 'frontend.designs', [$id, base64_encode( json_encode( $link_filter ) ), $type] );
                }

                $cols[$counter++]['ImageUrl'] = CommonController::getApiFullImage( $collection['ImageName'] );

                if ( false && $counter > ConstantsController::LISTING_LIMIT )
                {
                    break;
                }

            }

        }

        // TODO - Needs to be corrected once we have pagination in API call

        return array( $type => $cols, 'TotalRows' => count( $cols ) );
    }

    public function collection_with_filters( $id, $type, $filter )
    {
        $rehashFlag=0;
        \Log::info("=====================================");
        //\Log::info(json_decode( base64_decode( $filter)));
        \Log::info("=====================================");
        $rehashFlag=sizeof(( json_decode( base64_decode( $filter)))->Filters)-1;
        $newFilter=$filter;
        $object = new \stdClass();
        $object->Filters[0]=((( json_decode( base64_decode( $newFilter)))->Filters[sizeof(( json_decode( base64_decode( $newFilter)))->Filters)-1]));
        $json=((json_encode($object, JSON_UNESCAPED_SLASHES)));
        $json = str_replace('"Filters":[', '"Filters": [', $json);
        $finalFilter=base64_encode($json);
        $hash        = md5( json_encode( ['id' => join( '~', [$id, $type, $filter] ), 'method' => 'Get_'.$type, 'theme' => $this->active_theme->id] ) );
        $collectionID = json_decode(CommonController::escape_string( base64_decode( $filter )), true)['Filters'][0]['Values'][0];
        $collections = $this->addCollectionUrls( $this->ApiObj->Get_Collections_With_Filters( $id, $type, CommonController::escape_string( base64_decode( $filter ), 1 ) ), $id, $type, true, $collectionID );
        if(isset($filter)){
            foreach($collections['Collections'] as &$col) {
               $chLink=(json_decode(base64_decode(explode('/', $col['LinkUrl'])[5])))->Filters[0];  
               $finalLink=(json_decode(base64_decode(explode('/', $col['LinkUrl'])[5])));
               $finalLink->Filters[0]=json_decode(base64_decode($filter))->Filters[0];
               $finalLink->Filters[1]=$chLink;
               $enLink= base64_encode(json_encode($finalLink)); 
               $col['LinkUrl']= str_replace(explode('/', $col['LinkUrl'])[5], $enLink, $col['LinkUrl']);
           }
        }
        $updated_content = $this->content_model->get_content( $this->active_theme->id, $hash );
        $pageData=[]; 
        if ( $updated_content && $updated_content->content )
        {
            $content = json_decode( unserialize( $updated_content->content ), 1 );
            
            if(isset($content['title'])){
                $pageData['title']=$content['title'];
            }
            else{
                $pageData['title']="";
            }
            if(isset($content['description'])){
                $pageData["description"]=$content['description'];
            }
            else{
                $pageData['description']="";
            }
            if(isset($content['image'])){
                $pageData["image"]=$content['image'];
            }
            else{
                $pageData['image']="";
            }

            foreach ( $collections[$type] as &$collection )
            {

                if ( array_key_exists( $collection['Description'], $content ) )
                {
                    $key                       = $collection['Description'];
                    $collection['ImageName']   = $content[$key]['image'];
                    $collection['Description'] = $content[$key]['title'];
                }

            }

        }

        $subCategory     = $this->active_theme_json->general->category_based_filters ? $this->checkSubcategoryForFilters( $filter ) : '';
        $filters         = $this->ApiObj->Get_Filters( $id, isset( $subCategory['id'] ) ? $subCategory['id'] : '', $this->getSelectedFilters( json_decode( base64_decode( $filter ), true ) ), CommonController::escape_string( base64_decode( $filter ) ) );

        // $filters         = $this->addSelectedFilters( ConstantsController::NO_FILTER_FLAG, $filters );
        $filters    = $this->addSelectedFilters( json_decode( base64_decode( $filter ), true ), $filters );

        $main_collection = ( new MainCollectionController() )->get_main_collection( $id );
        $favourites      = ( new FavouriteController() )->getFavs( $id );

        $this->append_breadcrumbs( $main_collection['Description'], route( 'frontend.favourite', $id ) );
        $this->append_breadcrumbs( $type, route( 'frontend.collections', [$id, $type] ) );
        if($rehashFlag){
             $hash= md5( json_encode( ['id' => join( '~', [$id, $type, $finalFilter] ), 'method' => 'Get_'.$type, 'theme' => $this->active_theme->id] ) );
        $updated_content = $this->content_model->get_content( $this->active_theme->id, $hash );
        if ( $updated_content && $updated_content->content )
        {
            $content = json_decode( unserialize( $updated_content->content ), 1 );
        if(isset($content['title'])){
            $pageData['title']=$content['title'];
        }
        else{
            $pageData['title']="";
        }
        if(isset($content['description'])){
            $pageData["description"]=$content['description'];
        }
        else{
            $pageData['description']="";
        }
        if(isset($content['image'])){
            $pageData["image"]=$content['image'];
        }
        else{
            $pageData['image']="";
        }
        }}
        return view( 'frontend.'.$this->active_theme->theme_abrv.'.collection', [
            'pageData' =>$pageData,
            "setFilter"=>true,
            'collections'     => $collections,
            'favourites'      => $favourites,
            'main_collection' => $main_collection,
            'filters'         => $filters,
            'default_filter'  => base64_decode( $filter ),
            'sub_category'    => isset( $subCategory['title'] ) ? $subCategory['title'] : '',
            'return_type_id'  => $type
        ] );
    }

    
    // public function collection_with_filters($id, $type, $filter)
    // {
    //     /* -------------------------------------------------
    //     | Decode & validate filter ONCE
    //     -------------------------------------------------*/
    //     $decodedFilter = json_decode(base64_decode($filter), true);

    //     if (
    //         empty($decodedFilter) ||
    //         !isset($decodedFilter['Filters']) ||
    //         !is_array($decodedFilter['Filters'])
    //     ) {
    //         \Log::error('Invalid filter payload', [
    //             'raw'     => $filter,
    //             'decoded' => $decodedFilter
    //         ]);
    //         abort(400, 'Invalid filter data');
    //     }

    //     \Log::info('================ FILTER ================');
    //     \Log::info($decodedFilter);
    //     \Log::info('========================================');

    //     /* -------------------------------------------------
    //     | Rehash flag & final filter
    //     -------------------------------------------------*/
    //     $rehashFlag  = count($decodedFilter['Filters']) > 1;
    //     $finalFilter = $filter;

    //     if ($rehashFlag) {
    //         $finalFilterArray = [
    //             'Filters' => [
    //                 end($decodedFilter['Filters'])
    //             ]
    //         ];

    //         $finalFilter = base64_encode(
    //             json_encode($finalFilterArray, JSON_UNESCAPED_SLASHES)
    //         );
    //     }

    //     /* -------------------------------------------------
    //     | Hash & collection ID
    //     -------------------------------------------------*/
    //     $hash = md5(json_encode([
    //         'id'     => implode('~', [$id, $type, $filter]),
    //         'method' => 'Get_' . $type,
    //         'theme'  => $this->active_theme->id
    //     ]));

    //     $collectionID = $decodedFilter['Filters'][0]['Values'][0] ?? null;

    //     /* -------------------------------------------------
    //     | API collections
    //     -------------------------------------------------*/
    //     $collections = $this->addCollectionUrls(
    //         $this->ApiObj->Get_Collections_With_Filters(
    //             $id,
    //             $type,
    //             CommonController::escape_string(base64_decode($filter), 1)
    //         ),
    //         $id,
    //         $type
    //     );

    //     /* -------------------------------------------------
    //     | Fix collection URLs (merge filters)
    //     -------------------------------------------------*/
    //     if (!empty($filter) && isset($collections['Collections'])) {
    //         foreach ($collections['Collections'] as &$col) {

    //             $parts = explode('/', $col['LinkUrl']);
    //             if (!isset($parts[5])) {
    //                 continue;
    //             }

    //             $linkDecoded = json_decode(base64_decode($parts[5]), true);
    //             if (!isset($linkDecoded['Filters'][0])) {
    //                 continue;
    //             }

    //             $originalFilter = $linkDecoded['Filters'][0];

    //             $linkDecoded['Filters'][0] = $decodedFilter['Filters'][0];
    //             $linkDecoded['Filters'][1] = $originalFilter;

    //             $parts[5] = base64_encode(json_encode($linkDecoded));
    //             $col['LinkUrl'] = implode('/', $parts);
    //         }
    //     }

    //     /* -------------------------------------------------
    //     | Page content
    //     -------------------------------------------------*/
    //     $pageData = [
    //         'title'       => '',
    //         'description' => '',
    //         'image'       => ''
    //     ];

    //     $updated_content = $this->content_model->get_content(
    //         $this->active_theme->id,
    //         $hash
    //     );

    //     if ($updated_content && $updated_content->content) {
    //         $content = json_decode(unserialize($updated_content->content), true);

    //         $pageData['title']       = $content['title'] ?? '';
    //         $pageData['description'] = $content['description'] ?? '';
    //         $pageData['image']       = $content['image'] ?? '';

    //         foreach ($collections[$type] ?? [] as &$collection) {
    //             if (isset($content[$collection['Description']])) {
    //                 $key = $collection['Description'];
    //                 $collection['ImageName']   = $content[$key]['image'];
    //                 $collection['Description'] = $content[$key]['title'];
    //             }
    //         }
    //     }

    //     /* -------------------------------------------------
    //     | Subcategory & filters
    //     -------------------------------------------------*/
    //     $subCategory = $this->active_theme_json->general->category_based_filters
    //         ? $this->checkSubcategoryForFilters($filter)
    //         : '';

    //     $filters = $this->ApiObj->Get_Filters(
    //         $id,
    //         $subCategory['id'] ?? '',
    //         $this->getSelectedFilters($decodedFilter),
    //         CommonController::escape_string( base64_decode( $filter ) )
    //     );

    //     $filters = $this->addSelectedFilters($decodedFilter, $filters);

    //     /* -------------------------------------------------
    //     | Rehash content if needed
    //     -------------------------------------------------*/
    //     if ($rehashFlag) {
    //         $hash = md5(json_encode([
    //             'id'     => implode('~', [$id, $type, $finalFilter]),
    //             'method' => 'Get_' . $type,
    //             'theme'  => $this->active_theme->id
    //         ]));

    //         $updated_content = $this->content_model->get_content(
    //             $this->active_theme->id,
    //             $hash
    //         );

    //         if ($updated_content && $updated_content->content) {
    //             $content = json_decode(unserialize($updated_content->content), true);

    //             $pageData['title']       = $content['title'] ?? '';
    //             $pageData['description'] = $content['description'] ?? '';
    //             $pageData['image']       = $content['image'] ?? '';
    //         }
    //     }

    //     /* -------------------------------------------------
    //     | Extra data
    //     -------------------------------------------------*/
    //     $main_collection = (new MainCollectionController())->get_main_collection($id);
    //     $favourites      = (new FavouriteController())->getFavs($id);

    //     $this->append_breadcrumbs(
    //         $main_collection['Description'],
    //         route('frontend.favourite', $id)
    //     );
    //     $this->append_breadcrumbs(
    //         $type,
    //         route('frontend.collections', [$id, $type])
    //     );

    //     /* -------------------------------------------------
    //     | View
    //     -------------------------------------------------*/
    //     return view(
    //         'frontend.' . $this->active_theme->theme_abrv . '.collection',
    //         [
    //             'pageData'        => $pageData,
    //             'setFilter'       => true,
    //             'collections'     => $collections,
    //             'favourites'      => $favourites,
    //             'main_collection' => $main_collection,
    //             'filters'         => $filters,
    //             'default_filter'  => base64_decode($filter),
    //             'sub_category'    => $subCategory['title'] ?? '',
    //             'return_type_id'  => $type
    //         ]
    //     );
    // }

    
    //do not get fucking blind - this function is written by you and you need it in the item controller
    public function generate_single_filter( $key, $value )
    {
        $link_filter              = array( 'Filters' => array() );
        $link_filter['Filters'][] = array
            (
            "FilterID" => $key,
            "Values"   => is_array( $value ) ? $value : [$value]
        );

        return base64_encode( json_encode( $link_filter ) );
    }

    public function index( $id, $type )
    {
        $hash            = md5( json_encode( ['id' => join( '~', [$id, $type, ''] ), 'method' => 'Get_'.$type, 'theme' => $this->active_theme->id] ) );
        $collections     = $this->addCollectionUrls( $this->ApiObj->Get_Collections( $id, $type ), $id, $type, true, "" );
        $updated_content = $this->content_model->get_content( $this->active_theme->id, $hash );
        
        // prr( $id );
        // prr( $collections );

        if ( $updated_content && $updated_content->content )
        {
           // dd($updated_content->content);
            $content = json_decode( unserialize( $updated_content->content ), 1 );
            foreach ( $collections[$type] as &$collection )
            {

                if ( array_key_exists( $collection['Description'], $content ) )
                {

                    $key = $collection['Description'];
                    $collection['ImageNameRaw']= $collection['ImageName'];
                    $collection['ImageName']   = $content[$key]['image'];
                    $collection['ImageUrl']    = $content[$key]['image'];
                    // $collection['Description'] = $content[$key]['title'] . '<br/>' . $content[$key]['description'];
                    $collection['Description'] = $content[$key]['title'];
                }else{
                    $collection['ImageNameRaw']= $collection['ImageName'];
                }

            }

        }

        $filters = $this->ApiObj->Get_Filters( $id );
        $filters = $this->addSelectedFilters( ConstantsController::NO_FILTER_FLAG, $filters );

        $main_collection = ( new MainCollectionController() )->get_main_collection( $id );
        $favourites      = ( new FavouriteController() )->getFavs( $id );

        $this->append_breadcrumbs( $main_collection['Description'], route( 'frontend.favourite', $id ) );
        $this->append_breadcrumbs( $type, route( 'frontend.collections', [$id, $type] ) );
        // die("<pre>".print_r($collections,1)."</pre>");

        return view( 'frontend.'.$this->active_theme->theme_abrv.'.collection', ['collections' => $collections, 'favourites' => $favourites, 'main_collection' => $main_collection, 'filters' => $filters, 'return_type_id' => $type] );
    }

}
