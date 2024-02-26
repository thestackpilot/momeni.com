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
            font-family: "montserrat", sans-serif;
            /* font-style: normal;
            font-weight: normal; */
            /* src: url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Protest+Riot&display=swap'); */
            src: url("https://use.typekit.net/lba7uat.css");
        }

        body {
            font-family: "montserrat", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
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
        }

        .left-wrapper {
            display: inline;
            text-align: center;
            padding: 5rem 0;
            height: 100%;
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
            margin: 0 10px;
            display: inline-block;
        }

        .barcode p {
            font-size: 24px;
            margin: 4px 0 0;
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

        .hr {
            width: 100%;
            margin: 1rem 0;
            position: relative;
            text-align: center;
        }

        .hr div {
            border-bottom: 2px solid grey;
            width: 16.66%;
            display: inline-block;
        }
    </style>

</head>

<body onload="{{isset($print) && $print ? 'PrintDoc()' : ''}}" marginstyle="width:0" marginheight="0" topmargin="0">
@foreach($products as $i => $product)
    @if(isset($product['barcodes']))
        @foreach($product['barcodes'] as $j => $barcodes)
            <div class="hangtags-wrapper">
                <table>
                    <tr>
                        <td>
                            <div class="left-wrapper">
                                <h3>Sizes Available</h3>
                                <div class="barcodes">
                                    @foreach($barcodes as $k => $barcode)
                                        <div class="barcode">
                                            <p>{{ strlen($barcode['label']) > 16 ? substr($barcode['label'], 0, 15) . '..' : $barcode['label'] }}</p>
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
                        </td>
                        <td>
                            <div class="right-wrapper">
                                <table style="width: 100%">
                                    <tbody>
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
                                            <p style="margin-bottom: 10px;"><b>{{$product['category']}}</b></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:27px;color: rgb(80, 78, 78); text-align: center; margin-bottom: 30px; margin-top: 20px">
                                            <p style="margin-bottom: 0;">{{$product['title']}}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 100%; text-align: center">
                                            <hr style="width: 16.66%; display: inline-block; margin: 20px 0;">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            @foreach($product['attributes'] as $attribute)
                                                <div class="style2 text-center"
                                                     style="text-align: center;margin-top: 20px;">
                                                    <div style="font-size:26px;margin-bottom: 20px;">
                                                        <p style="color:rgb(136, 139, 139)">{{$attribute['label']}}:
                                                            <span style=" color: rgb(98, 99, 99);">{{$attribute['value']}} </span>
                                                        </p>
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
