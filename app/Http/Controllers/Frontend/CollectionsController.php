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
        // die(print_r($this->getSelectedFilters( json_decode( base64_decode( $filter ), true ) ) ));
        $hash        = md5( json_encode( ['id' => join( '~', [$id, $type, $filter] ), 'method' => 'Get_'.$type, 'theme' => $this->active_theme->id] ) );
        $collectionID = json_decode(CommonController::escape_string( base64_decode( $filter )), true)['Filters'][0]['Values'][0];
        $collections = $this->addCollectionUrls( $this->ApiObj->Get_Collections_With_Filters( $id, $type, CommonController::escape_string( base64_decode( $filter ), 1 ) ), $id, $type, true, $collectionID );

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
        $filters         = $this->ApiObj->Get_Filters( $id, isset( $subCategory['id'] ) ? $subCategory['id'] : '', $this->getSelectedFilters( json_decode( base64_decode( $filter ), true ) ) );

        // $filters         = $this->addSelectedFilters( ConstantsController::NO_FILTER_FLAG, $filters );
        $filters    = $this->addSelectedFilters( json_decode( base64_decode( $filter ), true ), $filters );

        $main_collection = ( new MainCollectionController() )->get_main_collection( $id );
        $favourites      = ( new FavouriteController() )->getFavs( $id );

        $this->append_breadcrumbs( $main_collection['Description'], route( 'frontend.favourite', $id ) );
        $this->append_breadcrumbs( $type, route( 'frontend.collections', [$id, $type] ) );

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
                    $collection['ImageName']   = $content[$key]['image'];
                    $collection['ImageUrl']    = $content[$key]['image'];
                    // $collection['Description'] = $content[$key]['title'] . '<br/>' . $content[$key]['description'];
                    $collection['Description'] = $content[$key]['title'];
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
