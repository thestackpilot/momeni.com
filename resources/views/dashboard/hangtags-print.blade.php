@php
    function curl_get_contents($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
@endphp
<html>

<head>
    <title>Hang Tags</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- <link href="https://fonts.cdnfonts.com/css/times-new-roman" rel="stylesheet"> -->


    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Protest+Riot&display=swap');

        body {
            font-family: "montserrat", sans-serif;
            /* font-family: "Montserrat", ; */
            font-optical-sizing: auto;
            font-style: normal;
            margin: 0%;
            padding: 0%;
        }

        .style2 {
            font-size: 12px;
        }

        .hangtags-wrapper {
            width: 100%;
            display: flex;
            flex-direction: row;
        }

        .left-wrapper {
            width: 50%;
            text-align: center;
            padding: 5rem 0;
        }

        .left-wrapper h3 {
            font-size: 32px;
            margin: 0;
            text-transform: uppercase;
            color: rgb(95, 93, 93);
        }

        .barcodes {
            margin-top: 40px;
            width: 100%;
            display: inline-block;
            text-align: center;
        }

        .barcode {
            width: 200px;
            /* float: left; */
            display: inline-block;
        }

        .barcode p {
            font-size: 24px;
            margin: 0;
            line-height: 25px;
            color: grey;
        }

        p.barcode-label {
            margin-top: 0px;
            font-size: 12px;
            color: #000;
            font-weight: 700;
        }
        
        .right-wrapper {
            width: 50%;
            text-align: center;
        }
    </style>

    <script>
        function PrintDoc() {
            window.print();
            var tblHeight = $('#tblSize').height();

            if (parseInt(tblHeight) >= 500 || parseInt(tblHeight) == 700) {
                $('#imgID').removeClass('imgHeight');
                $('#imgID').addClass('imgHeightNew1');
            }

            if (tblHeight >= 700 || tblHeight == 1000) {
                $('#imgID').removeClass('imgHeight');
                $('#imgID').addClass('imgHeightNew2');
            }
        }
    </script>

</head>

<body onload="{{isset($print) && $print ? 'PrintDoc()' : ''}}" marginstyle="width:0" marginheight="0" topmargin="0">
@foreach($products as $i => $product)
    @if(isset($product['barcodes']))
        @foreach($product['barcodes'] as $j => $barcodes)
            <div class="hangtags-wrapper">
                <div class="left-wrapper">
                    <h3>Sizes Available</h3>
                    <div class="barcodes">
                        @foreach($barcodes as $k => $barcode)
                            <div class="barcode">
                            <p>{{$barcode['label']}}</p>
                            <div style="margin-top: 5px;">
                                <img
                                    src="data:image/png;base64,{!!DNS1D::getBarcodePNG($barcode['code'], 'UPCA', 1, 30, array(0,0,0), false)!!}"
                                    width="170px" height="45px">
                            </div>
                            <p class="barcode-label"> {{ $barcode['code']  }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="right-wrapper">
                    <div class="mb-5 pb-3" style="margin-bottom: 3rem; padding-bottom: 3rem; text-align: center;">
                        <img src="{{ $product['logo'] }}" width="150"
                             onerror="this.onerror=null; this.src='{{url('/').$error_image}}'"
                             style="text-align: center;"/>
                        @if(isset($header))
                            <div>
                                <font face="arial" style="color: gray;font-style: italic;font-size: 20px;">
                                    {{$header}}
                                </font>
                            </div>
                        @endif
                    </div>
                    <div style="font-size:27px;color: rgb(80, 78, 78); text-align: center; margin-bottom: 30px;">
                        <p style="margin-bottom: 10px;"><b>{{$product['category']}}</b></p>
                        <p class="mb-0">{{$product['title']}}</p>
                    </div>
                    <table style="width:100%" border="0" cellspacing="0" cellpadding="0" style="padding-top: 0px;">
                        <tbody>
                        <tr>
                            <td>
                                <hr class="col-2 m-auto p-3 mt-4"
                                    style="width:16.66%; margin:0 auto; padding:1rem; opacity: 1;">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                @foreach($product['attributes'] as $attribute)
                                    <div class="style2 text-center" style="text-align: center;margin-top: 10px;">
                                        <div style="font-size:26px;margin-bottom: 20px;">
                                            <span style="color:rgb(136, 139, 139)">{{$attribute['label']}}:</span>&nbsp;
                                            <span class="mb-0"
                                                  style=" color: rgb(98, 99, 99);">{{$attribute['value']}} </span>
                                        </div>
                                    </div>
                                @endforeach
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

    @endif
@endforeach
{{--@foreach($products as $k => $product)--}}
{{--    <div style="width:100%;" class="divbg">--}}
{{--        <div style="height:50px;" class="divHeaderFooterbg"></div>--}}

{{--        --}}{{-- new design by asad 23/01/2024 --}}
{{--        <center>--}}

{{--            <table width="100%" style="width: 100%; height:100%;">--}}
{{--                <tbody>--}}
{{--                <tr>--}}
{{--                    <td style="width:45%; vertical-align: top; padding:0; margin:0;" class="left-div">--}}
{{--                        <p style="border:0; background-color: #ffffff; ">--}}
{{--                        <div style="border:0; background-color: #ffffff; ">--}}
{{--                            <center>--}}
{{--                                <font color="#000000" style="font-size:40px;color: rgb(95, 93, 93);" >--}}
{{--                                    <span>SIZES AVAILABLE</span>--}}
{{--                                </font>--}}

{{--                                <div class="" style="margin-top: 40px;">--}}
{{--                                    @if (isset($product['barcodes']))--}}
{{--                                        @foreach($product['barcodes'] as $barcode)--}}
{{--                                            <span style="border:0; background-color: #ffffff; width: 225px; display: inline-block; margin-bottom: 40px;">--}}
{{--                                            <span class="m-0" style="margin: 0; font-size:24px; font-weight:normal; color: grey;">{{$barcode['label']}}</span>--}}
{{--                                            <div style="margin-top: 5px;">--}}
{{--                                                <img src="data:image/png;base64,{!!DNS1D::getBarcodePNG($barcode['code'], 'UPCA', 1, 30, array(0,0,0), false)!!}" width="170px" height="45px">--}}

{{--                                                --}}{{-- <img src="data:image/png;base64,{!! DNS1D::getBarcodePNG($barcode['code'], 'UPCA', 1, 30, array(0,0,0), true) !!}" width="150px" height="60px" alt="Barcode"> --}}
{{--                                            </div>--}}
{{--                                            <span style="margin-top: 4px;font-size: 12px; font-weight: bold;"> {{ $barcode['code']  }}</span>--}}
{{--                                        </span>--}}
{{--                                        @endforeach--}}

{{--                                    @else--}}
{{--                                        @foreach($product['sizes'] as $size)--}}
{{--                                            <span style="width: 150px; display: inline-block; margin-bottom: 30px;">{{$size['label']}}</span>--}}
{{--                                        @endforeach--}}
{{--                                    @endif--}}
{{--                                </div>--}}

{{--                            </center>--}}
{{--                        </div>--}}
{{--                        </p>--}}
{{--                    </td>--}}
{{--                    <td style="width:65%; vertical-align: top; padding:0; margin:0;" class="right-div">--}}
{{--                        <div style="border:0; background-color: #ffffff; ">--}}

{{--                            --}}{{-- logo --}}



{{--                            --}}{{-- titles and details --}}



{{--                        </div>--}}
{{--                    </td>--}}
{{--                </tr>--}}
{{--                </tbody>--}}
{{--            </table>--}}
{{--        </center>--}}

{{--        --}}{{-- old design --}}
{{--        <center class="d-none" style="display: none">--}}
{{--            <div style="width:650;background-color:white;min-height:150px;">--}}
{{--                <center>--}}
{{--                    <img src="{!!$logo!!}" style="width:100%; max-width: 150px;" onerror="this.onerror=null; this.src='{{url('/').$error_image}}'" style="max-width:550px;margin-top:0px;max-height:110px" />--}}
{{--                    <br>--}}
{{--                    @if(isset($header))--}}
{{--                        <div>--}}
{{--                            <font face="arial" style="color: gray;font-style: italic;font-size: 20px;">--}}
{{--                                {{$header}}--}}
{{--                            </font>--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                </center>--}}
{{--            </div>--}}
{{--            <div style="background-color:white;width: 650;max-height: 795px; min-height: 600px;">--}}
{{--                <br>--}}
{{--                <table style="width:100%" border="0" cellspacing="0" cellpadding="0" style="padding-top: 0px;">--}}
{{--                    <tbody>--}}
{{--                    <tr>--}}
{{--                        <td style="width:100%">--}}
{{--                            <table style="width:100%" border="0" align="center" cellpadding="0" cellspacing="0">--}}
{{--                                <tbody>--}}
{{--                                <tr>--}}
{{--                                    <td style="width:33%">&nbsp;</td>--}}
{{--                                    <td style="width:33%">--}}
{{--                                        <div align="center">--}}
{{--                                            <font face="Arial" style="font-size:22px;color: grey;">--}}
{{--                                                {{$product['category']}}--}}
{{--                                            </font>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
{{--                                    <td style="width:34%"></td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td style="width:33%">&nbsp;</td>--}}
{{--                                    <td style="width:33%">--}}
{{--                                        <div align="center">--}}
{{--                                            <font face="Verdana" style="font-size:17px;color: grey; ">{{$product['title']}}</font>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
{{--                                    <td style="width:34%"></td>--}}
{{--                                </tr>--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td>&nbsp;</td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td align="center">--}}
{{--                            <table id="tblSize" style="width:100%" border="0" cellspacing="0" cellpadding="0">--}}
{{--                                <tbody>--}}
{{--                                <tr style="height:10px;">--}}
{{--                                    <td class="Label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>--}}
{{--                                    <td class="Label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>--}}
{{--                                </tr>--}}
{{--                                <tr>--}}
{{--                                    <td style="width:33%" valign="top">--}}
{{--                                        <table align="center" style="width:70%" border="0" cellspacing="0" cellpadding="0">--}}
{{--                                            <caption>--}}
{{--                                                <font face="Arial" style="font-size:14px;font-weight:bold;color: grey;text-decoration:underline;">Available Sizes</font>--}}
{{--                                            </caption>--}}
{{--                                            <tbody>--}}
{{--                                            <tr>--}}
{{--                                                <td>&nbsp;</td>--}}
{{--                                            </tr>--}}
{{--                                            @foreach($product['sizes'] as $size)--}}
{{--                                                <tr class="txt11black">--}}
{{--                                                    <td style="width:1%"></td>--}}
{{--                                                    <td style="width:99%" align="center" valign="top" style="padding-bottom: 6px;">--}}
{{--                                                        <font face="Arial" style="font-size:15px;font-weight:bolder">--}}
{{--                                                            {{$size['label']}}--}}
{{--                                                        </font>--}}
{{--                                                    </td>--}}
{{--                                                </tr>--}}
{{--                                            @endforeach--}}
{{--                                            </tbody>--}}
{{--                                        </table>--}}
{{--                                    </td>--}}

{{--                                    <td style="width:34%" valign="top">--}}
{{--                                        @if(!$without_price)--}}
{{--                                            <table align="center" style="width:70%" border="0" cellspacing="0" cellpadding="0">--}}
{{--                                                <caption>--}}
{{--                                                    <font face="Arial" style="font-size:14px;font-weight:bold;color: grey;text-decoration:underline;">Sale Price</font>--}}
{{--                                                </caption>--}}
{{--                                                <tbody>--}}
{{--                                                <tr>--}}
{{--                                                    <td>&nbsp;</td>--}}
{{--                                                </tr>--}}
{{--                                                @foreach($product['sizes'] as $size)--}}
{{--                                                    <tr class="txt11black">--}}
{{--                                                        <td style="width:1%"></td>--}}
{{--                                                        <td style="width:99%" align="center" valign="top" style="padding-bottom: 6px;">--}}
{{--                                                            <font face="Arial" style="font-size:15px;font-weight:bolder">--}}
{{--                                                                {{$size['price']}}--}}
{{--                                                            </font>--}}
{{--                                                        </td>--}}
{{--                                                    </tr>--}}
{{--                                                @endforeach--}}
{{--                                                </tbody>--}}
{{--                                            </table>--}}
{{--                                        @endif--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                </tbody>--}}
{{--                            </table>--}}

{{--                            <br>--}}
{{--                            <table style="width:100%" border="0" align="center" cellpadding="0" cellspacing="0">--}}
{{--                                <tbody>--}}
{{--                                <tr>--}}
{{--                                    <td style="width:33%" valign="top">--}}
{{--                                        <table align="center" style="width:100%" border="0" cellspacing="0" cellpadding="0">--}}
{{--                                            <tbody>--}}
{{--                                            @foreach($product['attributes'] as $attribute)--}}
{{--                                                <tr class="txt11black">--}}
{{--                                                    <td style="width:1%">--}}
{{--                                                        <div align="center" class="style2">--}}
{{--                                                            <font color="#000000" face="Arial" style="font-size:14px;font-weight:bold;color: grey;text-decoration:underline;">--}}
{{--                                                                {{$attribute['label']}}--}}
{{--                                                            </font>--}}
{{--                                                        </div>--}}
{{--                                                    </td>--}}
{{--                                                </tr>--}}
{{--                                                <tr>--}}
{{--                                                    <td>&nbsp;</td>--}}
{{--                                                </tr>--}}
{{--                                                <tr class="txt11black">--}}
{{--                                                    <td style="width:99%">--}}
{{--                                                        <div align="center" class="style2">--}}
{{--                                                            <font color="#000000" face="Arial">--}}
{{--                                                                {{$attribute['value']}}--}}
{{--                                                            </font>--}}
{{--                                                        </div>--}}
{{--                                                    </td>--}}
{{--                                                </tr>--}}
{{--                                                <tr>--}}
{{--                                                    <td>&nbsp;</td>--}}
{{--                                                </tr>--}}
{{--                                            @endforeach--}}
{{--                                            <tr class="txt11black"></tr>--}}
{{--                                            </tbody>--}}
{{--                                        </table>--}}
{{--                                    </td>--}}
{{--                                    <td style="width:1%" valign="top"></td>--}}
{{--                                    <td style="width:33%" valign="top">--}}
{{--                                        <div align="center" class="style2">--}}
{{--                                            @php--}}
{{--                                                $image = $product['image'];--}}
{{--                                                try {--}}
{{--                                                    if (--}}
{{--                                                        !isset($print)--}}
{{--                                                    ) {--}}
{{--                                                        $image = curl_get_contents($image);--}}
{{--                                                        if ( getimagesizefromstring($image) )--}}
{{--                                                            $image = 'data:image/jpg;base64, ' . base64_encode($image);--}}
{{--                                                        else--}}
{{--                                                            $image = url('/').$error_image;--}}
{{--                                                    }--}}
{{--                                                } catch(\Exception $e) {--}}
{{--                                                    $image = url('/').$error_image;--}}
{{--                                                } catch(\Error $e) {--}}
{{--                                                    $image = url('/').$error_image;--}}
{{--                                                }--}}
{{--                                            @endphp--}}
{{--                                            <img src="{{$image}}" onerror="this.onerror=null; this.src='{{url('/').$error_image}}'" id="imgID" align="middle" border="0" class="imgHeight">--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                                </tbody>--}}
{{--                            </table>--}}

{{--                        </td>--}}
{{--                    </tr>--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--            <div style="height:50px;" class="divHeaderFooterbg">--}}
{{--                <center class="divHeaderFooterbg" style="padding-top:15px">--}}
{{--                    {{$footer}}--}}
{{--                </center>--}}
{{--            </div>--}}

{{--        </center>--}}
{{--    </div>--}}
{{--    --}}{{-- @if(isset($product['barcodes']) || $k < (count($products) - 1))--}}
{{--    <p style="page-break-after:always;"></p>--}}
{{--    @endif --}}
{{--    @if (isset($product['barcodes']))--}}
{{--        <center class="d-none" style="display: none">--}}
{{--            <table style="width:100%; text-align: center;" border="0" align="center" cellpadding="0" cellspacing="0">--}}
{{--                <tbody>--}}
{{--                @foreach($product['barcodes'] as $barcode)--}}
{{--                    <tr>--}}
{{--                        <td style="width:99%" valign="bottom">--}}
{{--                            <font face="Arial" style="font-size:15px;font-weight:bolder">--}}
{{--                                {{$barcode['label']}}--}}
{{--                            </font>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td style="width:99%" style="padding-bottom: 6px;">--}}
{{--                            <img src="data:image/png;base64,{!!DNS1D::getBarcodePNG($barcode['code'], 'UPCA', 1, 30, array(0,0,0), true)!!}">--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
{{--                </tbody>--}}
{{--            </table>--}}
{{--        </center>--}}
{{--        @if($k < (count($products) - 1))--}}
{{--            <!-- <p style="page-break-after:always;"></p> -->--}}
{{--        @endif--}}
{{--    @endif--}}
{{--@endforeach--}}
</body>

</html>
