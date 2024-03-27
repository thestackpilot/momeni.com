<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Frontend\FrontendController;
use Illuminate\Http\Request;

class BroadloomController extends FrontendController
{
    public function index($id, $cust_id){
        $roll_pieces = $this->ApiObj->Get_ItemsRollAndCutPieceList($id);
        $surging_types = $this->ApiObj->Get_SurgingTypes();

        // dd($payload);
        return view( 'frontend.'.$this->active_theme->theme_abrv.'.broadloom', [
            'surging_types'     => $surging_types,
            'roll_pieces'       => $roll_pieces,
            'cust_id'           => $cust_id,
            // 'cart_details'              => $payload
        ]);
    }

    public function shopping_cart(){
        $payload = [
            'items' => [
                [
                    'item_id' => 'CHEVOCHE-BBGED400',
                    'item_customer_id' => '081370',
                    'item_name' => 'DESIGN: CHE-B',
                    'item_quantity' => 1,
                    'item_price' => 169.00,
                    'item_total' => 169.00,
                    'item_color' => 'BEIGE',
                    'item_size' => '9\'6" x 5\'5"',
                    'item_currency' => '$',
                    'item_image' => 'https://media.momeni.com/Full_Img/CHEVOCHE-BBGE.jpg',
                    'item_eta' => '2024-09-23',
                    'item_data' => 's:1813:"{"ItemID":"CHEVOCHE-BBGED400","ItemName":"DESIGN: CHE-B","Dimensions":"WOOL & POLYESTER","BasePrice":0,"ATSQ":13,"QualityDescription":"BROADLOOM CHEVRON","IntroDate":"2018-12-06T00:00:00","TimeStamp":"1544094557","UPC":"039425443673","PriceLevel1":0,"PriceLevel2":0,"PriceLevel3":0,"PriceLevel4":0,"PriceLevel5":0,"UDF1":"","UDF2":"","UDF3":"","UDF4":"","UDF5":"","UDF6":"","UDF7":"","UDF8":"","UDF9":"SHADES OF BEIGE","UDF10":"","UDF11":"","UDF12":"","UDF13":"","UDF14":"","UDF15":"","UDF16":"","UDF17":"","UDF18":"","UDF19":"","UDF20":"","PhotoName":"","Discontinued":"False","Source":"CHE-B","IsDeleted":"0","Weight":12.3,"QualityID":"","DesignID":"CHE-B","ColorID":"BGE000","SizeID":"D400","ShapeID":null,"DesignDescription":null,"ExternalID":"","ProductType":"Broadloom","DimentionalWeight":0,"ImageName":"Full_Img/CHEVOCHE-BBGE.jpg","Country":"INDIA","ProductDescription":"","CareInstructions":"","UDFFields":[{"FieldName":"Shades of Color","Value":"SHADES OF BEIGE"}],"NewArrivalExpiry":"False","Clearence":"False","TopSeller":"False","SpecialBuy":"False","Thickness":"","PileHeight":"","HotBuy":"False","RugPad":"False","MarketSpecial":"False","PrePad":"","ULTPad":"","GroupPricing":"","VideoURL":"","ImageNameArray":["https://media.momeni.com/Full_Img/CHEVOCHE-BBGE.jpg"],"ItemColor":"BEIGE","ItemColorImage":"https://media.momeni.com/Full_Img/CHEVOCHE-BBGE.jpg","CutPieces":[{"ItemID":"CHEVOCHE-BBGED400","RollID":"CHE-BBGED406057","CutPieceID":"","ATSLength":"114","ATSWidth":"65","TotalUsedLength":"114","LengthStatus":"F","TempSalesOrderNo":"104","CPTempLine_No":"1","LengthID":"359"},{"ItemID":"CHEVOCHE-BBGED400","RollID":"CHE-BBGED406057","CutPieceID":"","ATSLength":"114","ATSWidth":"115","TotalUsedLength":"114","LengthStatus":"R","TempSalesOrderNo":"104","CPTempLine_No":"1","LengthID":"360"}]}"',
                    'item_atsq' => 1,
                    'item_only_max_quantity' => null,
                ],
            ],
            'cart_currency' => '$',
            'cart_count' => 1,
            'cart_total' => 169.00,
        ];
        // dd($payload);
        return view( 'frontend.'.$this->active_theme->theme_abrv.'.broadloom-shopping-cart', [
            'cart_details'     => $payload,
        ]);
    }

    public function order_complete(){
        return view( 'frontend.'.$this->active_theme->theme_abrv.'.broadloom-order-complete', [
        ]);
    }

    public function checkout(){
        return view( 'frontend.'.$this->active_theme->theme_abrv.'.broadloom-checkout', [
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
