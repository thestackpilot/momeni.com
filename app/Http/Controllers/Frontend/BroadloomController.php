<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\ItemController;
use Illuminate\Http\Request;

class BroadloomController extends FrontendController
{
    public function index($id, $cust_id, $color_id){
        $item = $this->ApiObj->Get_Items("",$id,"","","","", $color_id);
        // dd($item);
        $item_id = 0;
        $obj = new ItemController();
        $images = $obj->generate_image_urls($item);
        foreach($images['Items'] as $row){
            if($row['ProductType'] == 'Broadloom'){
                $item_id = $row['ItemID'];
                $image_name = $row['ImageNameArray'][0];
            }
        }
        $roll_pieces = $this->ApiObj->Get_ItemsRollAndCutPieceList($item_id);
        $surging_types = $this->ApiObj->Get_SurgingTypes();
        return view( 'frontend.'.$this->active_theme->theme_abrv.'.broadloom', [
            'surging_types'     => $surging_types,
            'roll_pieces'       => $roll_pieces,
            'cust_id'           => $cust_id,
            'image'        => $image_name
        ]);
    }

    public function shopping_cart(){
        return view( 'frontend.'.$this->active_theme->theme_abrv.'.broadloom-shopping-cart', [
        ]);
    }

    public function AddCutPiece(Request $request){
        $rollId = $request->input('roll_id');
        $itemId = $request->input('item_id');
        $cutpieceId = $request->input('cutpiece_id');
        $atslength = $request->input('atslength');
        $totalWidth = $request->input('totalwidth');
        $totalSqft = $request->input('totalsqft');
        $cutType = $request->input('cuttype');
        $locationId = $request->input('locationid');
        $charges = $request->input('charges');
        $description = $request->input('desc');
        $sergingTypeNo = $request->input('sergingtypeno');
        $tempsalesorderno = $request->input('tempsalesorderno');
        $waste = $request->input('waste');
        $remnant = $request->input('Remnant');
        $available = $request->input('AvailableForSale');
        $isremship = $request->input('IsremnantShipable');
        $serging = $request->input('serging');
        $line = $request->input('LineNo');
        $userremarks = $request->input('UserRemarks');

        $res = $this->ApiObj->Get_AddCutPiece($tempsalesorderno, $cutpieceId, $rollId, $itemId, $atslength, $totalWidth, $totalSqft, $cutType, $description, $charges, $sergingTypeNo, $locationId, $waste, $remnant, $available, $isremship, $serging, $line, $userremarks );
        // dd($res);
        return [
            'cut_piece'     => $res,
        ];
    }
}
