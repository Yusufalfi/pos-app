<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Kartu Memeber</title>
    <style>
        .box {
            position: relative;
        }
        .card {
            width: 85.60mm;
        }
        .logo {
            position: absolute;
            top: 3pt;
            right: 0pt;
            font-size: 16pt;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            color: #fff !important;
        }
        .logo p {
            text-align: right;
            margin-right: 16pt;

        }
        .logo img {
            position: absolute;
           margin-top: -5px;
            width: 40px;
            height: 40px;
            right: 60pt;

        }
        .nama {
            position: absolute;
            top: 100pt;
            right: 16pt;
            font-size: 12pt;
            font-family: Arial, Helvetica, sans-serif;
            font-weight: bold;
            color: #fff !important;
        }
        .barcode {
            position: absolute;
            top: 120pt;
            right: 16pt;
            border: 1px solid red;
            padding: .5px;
            background: #fff;
            left: .860rem;
        }
        .text-left {
            text-align: right;
            
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

    <section style="border: 1px solid #888">
        <table width="100%">
            @foreach ($datamember as $key => $data)
                <tr>
                    @foreach ($data as $item)
                        <td class="text-center" width="50%">
                            <div class="box">
                                <img src="{{ asset('/public/img/member.jpg') }}" alt="kaga-keload" srcset="" width="50%">

                                
                                {{-- {{ asset('AdminLTE-2/bower_components/jquery-ui/jquery-ui.min.js')}} --}}
                                <div class="logo">
                                    {{-- <p>{{ config('app.name') }}</p> --}}
                                    {{-- <img src="{{ asset('/public/images/logo.png') }}" alt="logo"> --}}
                                </div>
                                <div class="nama">{{ $item->nama}}</div>
                                <div class="telepon">{{ $item->telepon}}</div>
                                <div class="barcode text-left">
                                    <img src="data:image/png;base64, {{ DNS2D::getBarcodePNG("$item->kode_member", 'QRCODE') }}" alt="qrcode" height="45" width="45">
                                </div>

                            </div>
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </table>
    </section>
    
</body>
</html>