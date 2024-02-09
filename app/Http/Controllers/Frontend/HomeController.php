<?php

namespace App\Http\Controllers\Frontend;

class HomeController extends FrontendController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return view( 'frontend.'.$this->active_theme->theme_abrv.'.home' );
    }
}
