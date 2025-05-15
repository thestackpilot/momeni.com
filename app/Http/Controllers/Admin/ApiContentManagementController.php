<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ApiContentManagement;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\Admin\AdminController;

class ApiContentManagementController extends AdminController
{
    public $pages, $customRoutes;

    private $content_model;

    public function __construct()
    {
        parent::__construct();
        $this->content_model = new ApiContentManagement();
        $this->pageData = [
            'title' => '',
            'description' => '',
            'image' => ''
        ];
    }

    public function get_collections_content( $id, $page = '', $filters = '' )
    {
        if(isset($filters)){
            $isfilter=true;
        }

        if ( $page || $filters )
        {
            $hash            = md5( json_encode( ['id' => join( '~', [$id, $page, $filters] ), 'method' => 'Get_'.$page, 'theme' => $this->active_theme->id] ) );
            $collections     = $this->ApiObj->Get_Collections_With_Filters( $id, $page, $filters ? base64_decode( $filters ) : '' );
            $updated_content = $this->content_model->get_content( $this->active_theme->id, $hash );

// die( '<pre>'.print_r( $collections, 1 ) );

if (  !$updated_content )
            
        {
                foreach ( $collections[$page] as $collection )
                {
                    $content[$collection['Description']] = [
                        'title'       => $collection['Description'],
                        'description' => '',
                        'image'       => CommonController::getApiFullImage( $collection['ImageName'] ),
                        'raw'         => $collection
                    ];
                }

            }
            else
            {
                $content = json_decode( unserialize( $updated_content->content ), 1 );
            }

            $this->content_model->create_update_content( $this->active_theme->id, $hash, [
                'content'  => serialize( json_encode( $content ) ),
                'endpoint' => 'Get_'.$page,
                'request'  => serialize( json_encode( array( 'MainCollectionID' => $id, 'Filters' => $filters ) ) ),
                'response' => serialize( json_encode( $collections ) )
            ] );

            return view( 'admin.content-management', [
                "filter"=>$isfilter,
                'favourite_id'  => $id,
                'collections'   => $collections[$page],
                'content'       => $content,
                'api'           => ['key' => join( '~', [$id, $page, $filters] ), 'method' => 'Get_'.$page],
                'template_type' => 'update-collections'
            ] );
        }
        else
        {
            $hash            = md5( json_encode( ['id' => $id, 'method' => 'Get_Favourities', 'theme' => $this->active_theme->id] ) );
            $favourites      = $this->ApiObj->Get_Favourities( $id );
            $updated_content = $this->content_model->get_content( $this->active_theme->id, $hash );
            $collections     = $content     = [];

            foreach ( $favourites['Favourities'] as $favourite )
            {

                if ( strtolower( $favourite['LinkPage'] ) != 'designs' )
                {
                    $collections[] = array_merge( ['URL' => route( 'admin.collections', [$id, $favourite['LinkPage'], base64_encode( $favourite['LinkFilter'] )] )], $favourite );
                }

            }

            if ( $updated_content )
            {
                $content = json_decode( unserialize( $updated_content->content ), 1 );
            }

            return view( 'admin.content-management', [
                'favourites'    => $favourites['Favourities'],
                'favourite_id'  => $id,
                'collections'   => $collections,
                'content'       => $content,
                'template_type' => 'display-collections'
            ] );
        }

    }

    public function get_favourities_content( $id )
    {
        $hash            = md5( json_encode( ['id' => $id, 'method' => 'Get_Favourities', 'theme' => $this->active_theme->id] ) );
        $favourites      = $this->ApiObj->Get_Favourities( $id );
        $updated_content = $this->content_model->get_content( $this->active_theme->id, $hash );
        $content         = [];

        if ( ! $updated_content )
        {

            foreach ( $favourites['Favourities'] as $favourite )
            {
                $content[$favourite['Title']] = [
                    'title' => $favourite['Title'],
                    'image' => CommonController::getApiFullImage( $favourite['Image'] ),
                    'raw'   => $favourite
                ];
            }

        }
        else
        {
            $content = json_decode( unserialize( $updated_content->content ), 1 );
        }

        $this->content_model->create_update_content( $this->active_theme->id, $hash, [
            'content'  => serialize( json_encode( $content ) ),
            'endpoint' => 'Get_Favourities',
            'request'  => serialize( json_encode( array( 'MainCollectionID' => $id ) ) ),
            'response' => serialize( json_encode( $favourites ) )
        ] );

        return view( 'admin.content-management', [
            'favourites'    => $favourites['Favourities'],
            'favourite_id'  => $id,
            'content'       => $content,
            'api'           => ['key' => $id, 'method' => 'Get_Favourities'],
            'template_type' => 'update-favourites'
        ] );
    }

    public function save_api_content( Request $request )
    {

        if ( $request->all() )
        {
try {
            $data = [];
            $hash = md5( json_encode( ['id' => $request->api_key, 'method' => $request->api_method, 'theme' => $this->active_theme->id] ) );

        if(isset($request->pageImage)){
            $image = $request->file('pageImage');
            $filename = $request->pageTitle . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'),$filename );
            $img=$filename;
        }
        else if($request->pageImageold){
            

            // Split the string into an array
            $parts = explode(',', $request->pageImageold);
            
            // Access the two parts
            $first = $parts[0];
            $second = isset($parts[1]) ? $parts[1] : null;

            if(isset($second)&& $second==="delete"){
 
                $imagePath = public_path('images\\' . $first);
     
                            if (file_exists($imagePath)) {

                    unlink($imagePath);
                    $img=""; 
            }

        }}

        $this->pageData['image'] = $img;
        $this->pageData['title'] = $request->pageTitle;
        $this->pageData['description'] = $request->pageDescription;


            $keys = array_keys( $request->title );

            foreach ( $keys as $key )
            {

                $data[$key] = [
                    'title' => $request->title[$key],
                    'raw'   => json_decode( $request->raw[$key], 1 )
                ];
                if ( isset($request->description[$key]) )
                {
                    $data[$key]['description'] = $request->description[$key];
                }
                else{
                    
                    $data[$key]['description'] = "";
                }
                
                if ( $request->hasFile( 'file.'.$key ) && $request->file( 'file.'.$key )->isValid() )
                {
                    // $data[$key]['image'] = asset( $request->{'file.'.$key}->store( 'storage' ) );
                    $data[$key]['image'] = CommonController::upload_file_ftp($request->{'file.'.$key});
                    $data[$key]['ImageName'] = CommonController::upload_file_ftp($request->{'file.'.$key});
                }
                else if(isset($request->image[$key]))
                {
                    $data[$key]['raw']['ImageName'] = $request->image[$key];
                    $data[$key]['image'] = $request->image[$key];
                    $data[$key]['ImageName'] = $request->image[$key];
                }
                else{
                    $data[$key]['raw']['ImageName'] = "";
                    $data[$key]['image'] = "";
                    $data[$key]['ImageName'] = "";
                }

            }

            $this->content_model->create_update_content( $this->active_theme->id, $hash, [
                'content' => serialize( json_encode(array_merge( $data,$this->pageData ) ))
            ] );

            return redirect()->back()->with( 'message', ['type' => 'success', 'body' => 'Content updated successfully.'] );
} catch (\Exception $e) {
	// die(print_r([$e->getMessage()],1));
}
        }

        return redirect()->back()->with( 'message', ['type' => 'danger', 'body' => 'Something went wrong, please try again.'] );
    }

}
