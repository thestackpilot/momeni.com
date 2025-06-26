<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Frontend\DesignController;
use App\Http\Controllers\Frontend\FrontendController;

class SearchController extends FrontendController
{
    public $design;

    public function __construct()
    {
        parent::__construct();
        $this->design = new DesignController();
    }

    public function addSearchUrls( $results )
    {
        $ret     = array();
        $counter = 0;

//prr($results);
        if ( $results )
        {
            foreach ( $results['OutPut'] as $result )
            {
                $ret[$counter] = $result;
                $temp          = $this->design->addDesignUrls( $result, $result['MainCollectionID'], 'filterpage' );
                foreach ( $temp['Designs'] as &$design )
                {
                    $design['Colors'] = [];
                    if ( isset( $results['Colors'] ) )
                    {

                        foreach ( $results['Colors'] as $color )
                        {
                            if ( $design['DesignID'] == $color['DesignID'] )
                            {
                                $design['Colors'][] = $color['Description'];
                            }

                        }

                    }

                }

                $ret[$counter++]['Designs'] = $temp['Designs'];
            }

        }

        return $ret;
    }

    /**
     * Display the landing page of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $search, $type = '' )
    {
        
        $search         = base64_decode( $search );
       
        $search_results = $this->addSearchUrls( $this->ApiObj->Get_Search_Text( $search, $type ) );
       // dd($search_results);
        $this->append_breadcrumbs( "Search", route( 'frontend.search', [$search] ) );

        // die("<pre>".print_r($search_results, 1)."</pre>");
        return view( 'frontend.'.$this->active_theme->theme_abrv.'.filterpage', [
            'type'          => $type,
            'filterpages'   => $search_results,
            'view_title'    => 'Search : '.$search,
            'search_string' => $search
        ] );
    }

}
