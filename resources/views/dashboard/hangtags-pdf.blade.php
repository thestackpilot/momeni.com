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
        @font-face {
            font-family: "Montserrat";
            src: url({{ asset('fonts/static/Montserrat-Regular.ttf') }}) format('truetype');
            /*    !* font-style: normal;*/
            /*    font-weight: normal; *!*/
            /*    !*src: url("/public/fonts/static/Montserrat.ttf");*!*/
            /*    !*src: url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Protest+Riot&display=swap');*!*/
            /*    !*src: url("https://use.typekit.net/lba7uat.css");*!*/
        }

        body {
            font-family: "Montserrat", sans-serif;
            margin: 0;
            padding: 0;
        }

        .style2 {
            font-size: 12px;
        }

        .hangtags-wrapper {
            width: 100%;
            display: flex;
            flex-direction: row;
            padding-top: 1rem;
        }

        .left-wrapper {
            /*display: inline;*/
            text-align: center;
            /*padding: 5rem 0;*/
            /*margin-top: -2rem;*/
        }

        .barcodes {
            width: 100%;
            display: inline-block;
            text-align: center;
            margin-top: 20px;
        }

        .barcode {
            width: 230px;
            margin: 0 5px;
            display: inline-block;
        }

        .barcode p {
            font-size: 24px;
            margin: 4px 0 0;
            line-height: 25px;
            color: grey;
        }

        p.barcode-label {
            margin-top: -4px;
            font-size: 12px;
            color: #000;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .right-wrapper {
            display: inline;
            text-align: center;
            padding: 5rem 0 0;
            height: 100%;
        }

        .hangtags-wrapper table {
            width: 100%;
        }

        .hangtags-wrapper table td {
            width: 50%;
        }

        td.left-td {
            position: relative;
            /* text-align: center; */
        }

        td.right-td {
            position: relative;
            /* text-align: center; */
        }

        p.sizes {
            /*position: absolute;*/
            /*top: 25px;*/
            /*text-align: center;*/
            /*right: 0;*/
            /*left: 0;*/
            /*font-weight: 600;*/
            font-size: 36px;
            margin-bottom: 10px;
            color: grey;
        }

        p.sizes_12 {
            font-size: 28px !important;
            margin-bottom: 8px !important;
            color: grey;
        }

    </style>

</head>

<body>
@foreach($products as $i => $product)
    @if(isset($product['barcodes']))
        @foreach($product['barcodes'] as $j => $barcodes)
            <div class="hangtags-wrapper">
                <table>
                    <tr>
                        <td class="left-td">
                            @php
                            /*    $margin = -17;
                                $total_barcodes = count($barcodes);

                                if ($total_barcodes <= 6 && $total_barcodes > 2) {
                                    $margin = -11;
                                }

                                if ($total_barcodes <= 8 && $total_barcodes > 6) {
                                    $margin = -4;
                                }

                                if ($total_barcodes <= 10 && $total_barcodes > 8) {
                                    $margin = 2;
                                } */
                                $margin = -19;
                                $total_barcodes = count($barcodes);

                                if ($total_barcodes <= 6 && $total_barcodes > 2) {
                                   $margin = -15;
                                }

                                if ($total_barcodes <= 8 && $total_barcodes > 6) {
                                    $margin = -4;
                                }

                                if ($total_barcodes <= 10 && $total_barcodes > 8) {
                                    $margin = 2;
                                }

                                if ($total_barcodes <= 12 && $total_barcodes > 10) {
                                    $margin = -2;
                                }
                            @endphp
                            <div class="left-wrapper" style="margin-top: {{ $margin }}rem">
                                @if ($total_barcodes <= 12 && $total_barcodes > 10)
                                    <p class="sizes_12">SIZES AVAILABLE</p>
                                @else
                                    <p class="sizes">SIZES AVAILABLE</p>
                                @endif                                <div class="barcodes">
                                    @foreach($barcodes as $k => $barcode)
                                        <div class="barcode">
                                            @if ($total_barcodes <= 12 && $total_barcodes > 10)
                                                <p>{{ strlen($barcode['label']) > 20 ? substr($barcode['label'], 0, 15) . '..' : $barcode['label'] }}</p>
                                            @else
                                                <p>{{ strlen($barcode['label']) > 20 ? substr($barcode['label'], 0, 19) . '..' : $barcode['label'] }}</p>
                                            @endif
                                            <div style="margin-top: 3px;">
                                                @if ($total_barcodes <= 12 && $total_barcodes > 10)
                                                    <img
                                                    src="data:image/png;base64,{!!DNS1D::getBarcodePNG($barcode['code'], 'UPCA', 1, 30, array(0,0,0), false)!!}"
                                                    width="150px" height="40px">
                                                @else
                                                    <img
                                                    src="data:image/png;base64,{!!DNS1D::getBarcodePNG($barcode['code'], 'UPCA', 1, 30, array(0,0,0), false)!!}"
                                                    width="170px" height="45px">
                                                @endif
                                            </div>
                                            <p style="margin-top: -4px; font-size: 12px; color: #000; font-weight: 700; margin-bottom: 3px;"> {{ $barcode['code']  }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </td>
                        <td class="right-td">
                            <div class="right-wrapper">
                                <table style="width: 100%">
                                    <tbody style="line-height: 30px;">
                                    <tr>
                                        <td style="margin-bottom: 3rem; padding-bottom: 3rem; text-align: center;">
                                            <img src="{{ $product['logo'] }}" width="150"
                                                 onerror="this.onerror=null; this.src='{{url('/').$error_image}}'"
                                                 style="text-align: center;"/>`
                                        </td>
                                    </tr>
                                    @if(isset($header))
                                        <tr>
                                            <td>
                                                <h3 style="color: gray;font-style: italic;font-size: 20px;">{{$header}}</h3>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td style="font-size:27px;color: rgb(80, 78, 78); text-align: center; margin-bottom: 30px;">
                                            <p style="margin-bottom: 0px;"><b>{{$product['category']}}</b></p>
                                            <p style="margin-bottom: 20px; font-size:27px;color: rgb(80, 78, 78);;">{{$product['title']. ' '. $product['color']}} </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center">
                                            <a href="https://www.momeni.com/search?q={{ $product['title']}}">
                                                <img
                                                    src="https://api.qrserver.com/v1/create-qr-code/?data=https://www.momeni.com/search?q={{ $product['title']}} "
                                                    onerror="this.onerror=null; this.src='{{url('/').$error_image}}'"
                                                    id="imgID" align="middle" border="0" class="imgHeight" height="100px"
                                                    width="100px" alt="QR Code">
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            @foreach($product['attributes'] as $attribute)
                                                <div class="style2 text-center"
                                                     style="text-align: center;margin-top: 10px;">
                                                    <div
                                                        style="font-size:26px;margin-bottom: 15px; line-height: 30px !important;">
                                                        <span
                                                            style=" color: rgb(98, 99, 99)">{{$attribute['label']}}:</span>&nbsp;
                                                        <span class="mb-0"
                                                              style="color:rgb(136, 139, 139);">{{$attribute['value']}} </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>

            </div>
        @endforeach

    @endif
@endforeach
</body>

</html>
