<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Frontend\FrontendController;
use Illuminate\Http\Request;

class BroadloomController extends FrontendController
{
    public function index($id){
        $roll_pieces = $this->ApiObj->Get_ItemsRollAndCutPieceList($id);
        $surging_types = $this->ApiObj->Get_SurgingTypes();
        // dd($roll_pieces);
        return view( 'frontend.'.$this->active_theme->theme_abrv.'.broadloom', [
            'surging_types'     => $surging_types,
            'roll_pieces'       => $roll_pieces
        ]);
    }
}
