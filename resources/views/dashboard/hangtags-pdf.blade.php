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

    function splitString($string, $chunkSize) {
    $chunks = [];
    $words = explode(" ", $string);
    $currentChunk = '';
    
    foreach ($words as $word) {
        if (strlen($currentChunk) + strlen($word) <= $chunkSize) {
            $currentChunk .= $word . " ";
        } else {
            $chunks[] = trim($currentChunk);
            $currentChunk = $word . " ";
        }
    }

    // Add the remaining part
    if (!empty($currentChunk)) {
        $chunks[] = trim($currentChunk);
    }
    // die(print_r($chunks));
    return $chunks;
}

@endphp
<html>

<head>
    <title>Hang Tags</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- <link href="https://fonts.cdnfonts.com/css/times-new-roman" rel="stylesheet"> -->


    <style type="text/css">
        @font-face {
            font-family: "montserrat", sans-serif;
            /* font-style: normal;
            font-weight: normal; */
            /* src: url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Protest+Riot&display=swap'); */
            src: url("https://use.typekit.net/lba7uat.css");
        }
        @page {
            size: 30cm 20cm;
            margin: 0;
        }
        body {
            font-family: "montserrat", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
            margin:0;
            padding: 0;
        }


        <!--
        .style2 {
            font-size: 12px;
        }

        .style3 {
            font-size: large;
        }

        .style5 {
            font-size: large;
            font-weight: bold;
        }
        -->
        .divbg
        {
        /*background-image:
        url('./Images/grey.png');
        background-repeat:
        repeat;
        */
        background:
        white;
        }
        .divHeaderFooterbg
        {
        /*background-image:
        url('./Images/Headerbg.png');
        background-repeat:
        repeat;
        */
        background:
        white;
        }
        .imgHeight
        {
        max-width:
        316px
         !important;
        max-height:
        350px
         !important;
        }
        .imgHeightNew1
        {
        max-width:
        316px
         !important;
        max-height:
        250px
         !important;
        }
        .imgHeightNew2
        {
        max-width:
        316px
         !important;
        max-height:
        200px
         !important;
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

<body marginstyle="width:0" marginheight="0" topmargin="0">
    @foreach($products as $k => $product)
    <div style="width:100%;" class="divbg">
        <div style="height:50px;" class="divHeaderFooterbg"></div>

        {{-- new design by asad 23/01/2024 --}}
        <center>

            <table width="100%" style="width: 100%; height:100%;">
                <tbody>
                    <tr>
                        <td style="width:50%; vertical-align: top; padding:0; margin:0;" class="left-div">
                          <p style="border:0; background-color: #ffffff; ">
                              <div style="border:0; background-color: #ffffff; ">
                                <center>
                                    <font color="#000000" style="font-size:40px; color:rgb(158, 155, 155);" >
                                        <span>SIZES AVAILABLE</span>
                                    </font>

                                <div class="" style="margin-top: 70px;">
                                    @if (isset($product['barcodes']))
                                        @foreach($product['barcodes'] as $barcode)
                                        <span style="border:0; background-color: #ffffff; width: 280px; display: inline-block; margin-bottom: 40px;line-space:1.2; position: relative;">
                                            @php
                                                $maxLength = 23;
                                                $chunks = splitString($barcode['label'], $maxLength);
                                            @endphp
                                            @foreach($chunks as $key => $value)
                                            {{-- <p> {{ $key }}</p> --}}
                                            <div>   
                                                <p style="font-size:24px; font-weight:normal; color: grey;overflow-wrap: break-word; ">{{$value}}</p><br>
                                             </div>
                                            @endforeach
                                            <div style="margin-top: 5px; margin-bottom: 10px;">
                                                <img src="data:image/png;base64,{!!DNS1D::getBarcodePNG($barcode['code'], 'UPCA', 1, 30, array(0,0,0), false)!!}" width="170px" height="45px">

                                                {{-- <img src="data:image/png;base64,{!! DNS1D::getBarcodePNG($barcode['code'], 'UPCA', 1, 30, array(0,0,0), true) !!}" width="150px" height="60px" alt="Barcode"> --}}
                                            </div>
                                            <span style="margin-top: 10px;font-size: 10px; font-weight: bold;"> {{ $barcode['code']  }}</span>
                                        </span>
                                        @endforeach

                                    @else
                                        @foreach($product['sizes'] as $size)
                                            <span style="width: 150px; display: inline-block; margin-bottom: 30px;">{{$size['label']}}</span>
                                        @endforeach
                                    @endif
                                </div>

                            </center>
                              </div>
                          </p>
                        </td>
                        <td style="width:50%; vertical-align: top; padding:0; margin:20%;" class="right-div">
                                    {{-- logo --}}
                                    <div class="mb-5 pb-3" style="margin-bottom: 3rem; padding-bottom: 3rem; text-align: center;">
                                        <img src = "{{ $product['logo'] }}" width="175" onerror="this.onerror=null; this.src='{{url('/').$error_image}}'" style="text-align: right;" />
                                        @if(isset($header))
                                            <div>
                                                <font face="arial" style="color: gray;font-style: italic;font-size: 30px;">
                                                    {{$header}}
                                                </font>
                                            </div>
                                        @endif
                                    </div>
                                    {{-- titles and details --}}

                                    <div style="font-size: 28px; color: grey; text-align: center; margin-bottom: 10px;">
                                        <p style="margin-bottom: 40px; overflow-wrap: break-word;"><b>{{$product['category']}}</b></p>
                                        <p style="margin-bottom: 10px; overflow-wrap: break-word;">{{$product['title']}}</p>
                                    </div>

                                        <table style="width:100%" border="0" cellspacing="0" cellpadding="0" style="padding-top: 0px;">
                                            <tbody>
                                                {{-- line between --}}
                                                <tr>
                                                    <td><hr class="col-2 m-auto p-3 mt-4" style="width:16.66%; margin-top:60px; margin-bottom:30px; auto; padding:1rem; opacity: 1;"></td>
                                                </tr>

                                                {{-- details --}}

                                                <tr>
                                                    <td>
                                                        @foreach($product['attributes'] as $attribute)
                                                            <div class="style2 text-center" style="text-align: center;margin-top: 30px;">
                                                                <div style="font-size:26px; color: grey;margin-bottom: 60px;">
                                                                    <span style="color:rgb(104, 102, 102)">{{$attribute['label']}}:</span>
                                                                    <span class="mb-0" style="overflow-wrap: break-word;">{{$attribute['value']}} </span>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                        </td>
                      </tr>
                </tbody>
            </table>
        </center>

        {{-- old design --}}
        <center class="d-none" style="display: none">
            <div style="width:650;background-color:white;min-height:150px;">
                <center>
                    <img src="{!!$logo!!}" style="width:100%; max-width: 150px;" onerror="this.onerror=null; this.src='{{url('/').$error_image}}'" style="max-width:550px;margin-top:0px;max-height:110px" />
                    <br>
                    @if(isset($header))
                    <div>
                        <font face="arial" style="color: gray;font-style: italic;font-size: 20px;">
                            {{$header}}
                        </font>
                    </div>
                    @endif
                </center>
            </div>
            <div style="background-color:white;width: 650;max-height: 795px; min-height: 600px;">
                <br>
                <table style="width:100%" border="0" cellspacing="0" cellpadding="0" style="padding-top: 0px;">
                    <tbody>
                        <tr>
                            <td style="width:100%">
                                <table style="width:100%" border="0" align="center" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <td style="width:33%">&nbsp;</td>
                                            <td style="width:33%">
                                                <div align="center">
                                                    <font face="Arial" style="font-size:22px;color: grey;">
                                                        {{$product['category']}}
                                                    </font>
                                                </div>
                                            </td>
                                            <td style="width:34%"></td>
                                        </tr>
                                        <tr>
                                            <td style="width:33%">&nbsp;</td>
                                            <td style="width:33%">
                                                <div align="center">
                                                    <font face="Verdana" style="font-size:17px;color: grey; ">{{$product['title']}}</font>
                                                </div>
                                            </td>
                                            <td style="width:34%"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="center">
                                <table id="tblSize" style="width:100%" border="0" cellspacing="0" cellpadding="0">
                                    <tbody>
                                        <tr style="height:10px;">
                                            <td class="Label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                            <td class="Label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="width:33%" valign="top">
                                                <table align="center" style="width:70%" border="0" cellspacing="0" cellpadding="0">
                                                    <caption>
                                                        <font face="Arial" style="font-size:14px;font-weight:bold;color: grey;text-decoration:underline;">Available Sizes</font>
                                                    </caption>
                                                    <tbody>
                                                        <tr>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                        @foreach($product['sizes'] as $size)
                                                        <tr class="txt11black">
                                                            <td style="width:1%"></td>
                                                            <td style="width:99%" align="center" valign="top" style="padding-bottom: 6px;">
                                                                <font face="Arial" style="font-size:15px;font-weight:bolder">
                                                                    {{$size['label']}}
                                                                </font>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </td>

                                            <td style="width:34%" valign="top">
                                                @if(!$without_price)
                                                <table align="center" style="width:70%" border="0" cellspacing="0" cellpadding="0">
                                                    <caption>
                                                        <font face="Arial" style="font-size:14px;font-weight:bold;color: grey;text-decoration:underline;">Sale Price</font>
                                                    </caption>
                                                    <tbody>
                                                        <tr>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                        @foreach($product['sizes'] as $size)
                                                        <tr class="txt11black">
                                                            <td style="width:1%"></td>
                                                            <td style="width:99%" align="center" valign="top" style="padding-bottom: 6px;">
                                                                <font face="Arial" style="font-size:15px;font-weight:bolder">
                                                                    {{$size['price']}}
                                                                </font>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <br>
                                <table style="width:100%" border="0" align="center" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                            <td style="width:33%" valign="top">
                                                <table align="center" style="width:100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tbody>
                                                        @foreach($product['attributes'] as $attribute)
                                                        <tr class="txt11black">
                                                            <td style="width:1%">
                                                                <div align="center" class="style2">
                                                                    <font color="#000000" face="Arial" style="font-size:14px;font-weight:bold;color: grey;text-decoration:underline;">
                                                                    {{$attribute['label']}}
                                                                    </font>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                        <tr class="txt11black">
                                                            <td style="width:99%">
                                                                <div align="center" class="style2">
                                                                    <font color="#000000" face="Arial">
                                                                        {{$attribute['value']}}
                                                                    </font>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                        @endforeach
                                                        <tr class="txt11black"></tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="width:1%" valign="top"></td>
                                            <td style="width:33%" valign="top">
                                                <div align="center" class="style2">
                                                    @php
                                                    $image = $product['image'];
                                                    try {
                                                        if (
                                                            !isset($print)
                                                        ) {
                                                            $image = curl_get_contents($image);
                                                            if ( getimagesizefromstring($image) )
                                                                $image = 'data:image/jpg;base64, ' . base64_encode($image);
                                                            else
                                                                $image = url('/').$error_image;
                                                        }
                                                    } catch(\Exception $e) {
                                                        $image = url('/').$error_image;
                                                    } catch(\Error $e) {
                                                        $image = url('/').$error_image;
                                                    }
                                                    @endphp
                                                    <img src="{{$image}}" onerror="this.onerror=null; this.src='{{url('/').$error_image}}'" id="imgID" align="middle" border="0" class="imgHeight">
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="height:50px;" class="divHeaderFooterbg">
                <center class="divHeaderFooterbg" style="padding-top:15px">
                    {{$footer}}
                </center>
            </div>

        </center>
    </div>
    {{-- @if(isset($product['barcodes']) || $k < (count($products) - 1))
    <p style="page-break-after:always;"></p>
    @endif --}}
    @if (isset($product['barcodes']))
    <center class="d-none" style="display: none">
        <table style="width:100%; text-align: center;" border="0" align="center" cellpadding="0" cellspacing="0">
            <tbody>
                @foreach($product['barcodes'] as $barcode)
                <tr>
                    <td style="width:99%" valign="bottom">
                        <font face="Arial" style="font-size:15px;font-weight:bolder">
                            {{$barcode['label']}}
                        </font>
                    </td>
                </tr>
                <tr>
                    <td style="width:99%" style="padding-bottom: 6px;">
                        <img src="data:image/png;base64,{!!DNS1D::getBarcodePNG($barcode['code'], 'UPCA', 1, 30, array(0,0,0), true)!!}">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </center>
    @if($k < (count($products) - 1))
    <!-- <p style="page-break-after:always;"></p> -->
    @endif
    @endif
    @endforeach
</body>

</html>
