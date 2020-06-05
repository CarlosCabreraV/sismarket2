<!DOCTYPE html>
<html>

<head>
    <title>DOCUMENTO DE PAGO</title>
    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }

        table {
            font-size: x-small;
        }

        @page {
            margin: 0cm 0cm;
         /*   font-family: Arial; */

        }

        body {
            margin: 0.5cm;

        }

        main table tr td{
            font-family:Georgia, 'Times New Roman', Times, serif; 
        }
        p {
            font-family:Georgia, 'Times New Roman', Times, serif; 
        }

        .gray {
            background-color: lightgray
        }
    </style>
</head>

<body>
    <main>
        @php
            $iddoc = $venta->tipodocumento_id;
            $nombre_doc = ($iddoc == 3)?'BOLETA ELECTRÓNICA':(($iddoc==4)?'FACTURA ELECTRONICA':'TICKET');
        @endphp
        <h3 style=" padding-top:15px; font-family:Georgia, 'Times New Roman', Times, serif;  margin:0px; text-align: center; ">EXPRESS MARKET</h3>
        <!--p style=" margin:10px 5px 5px; text-align:center; font-size:15px; ">RUC 12345678911</p>
        <p style=" margin:5px;  text-align:center; font-size:15px;">TEL: 123456789</p>
        <p style=" margin:5px;  text-align:center; font-size:15px;">DIRECCION EMPRESA #360</p-->
    <p style=" margin:5px;  text-align:center; font-size:15px; font-weight:bold;">{{$nombre_doc}}</p>
        <p style=" margin:5px;  text-align:center; font-size:15px; font-weight:bold;">{{$venta->numero}}</p>
        <table width='100%' style="  margin-top:20px; border-collapse: collapse;  ">
            <tr style="">
                <td style="  height:2px;  border-bottom : 0.5px black solid ">
                   </td>
            </tr>
            <tr style="">
                <td style="  padding:15px 5px 5px ; font-size:12px; ">
                   <b style="width: 100px !important; display:inline-block;"> FECHA</b><p style="display: inline-block; margin:none;">: {{date('d-m-Y')}}</p>
                </td>
            </tr>
            <tr style="">
                <td style="  padding:0px 5px 5px ; font-size:12px; text-align:left;">
                <b style=" width: 100px !important; display:inline-block;">DNI/RUC</b><p style="display: inline-block; margin:none;">: {{($venta->persona->dni)?($venta->persona->dni):($venta->persona->ruc)}}</p>
                </td>
            </tr>
            <tr style=" ">
                <td style="  padding:0px 5px 5px; font-size:12px; text-align:left; ">
                    <b style="width: 100px !important; display:inline-block;">NOMBRE</b><p style="display: inline-block; margin:none;">: {{$venta->persona->nombres.' '.$venta->persona->apellidopaterno.' '.$venta->persona->apellidomaterno}}</p>
                </td>
            </tr>
            <tr style="">
                <td style=" padding:0px 5px 5px; font-size:12px; text-align:left; ">
                    <b style="width: 100px !important; display:inline-block;">DIRECCION</b><p style="display: inline-block; margin:none;">: {{$venta->persona->direccion}} </p>
                </td>
            </tr>
        </table>
        
        <table width='100%' style="  margin-top:13px; border-collapse: collapse; ">
            <tr>
                <td style="width:60%; padding-bottom:5px; text-align:left; height:20px;  border : 0.5px black solid; border-left:none; border-right:none; font-size:13px;  font-weight:bold;">
                    Producto</td>
                <td
                    style="width:20%; padding-bottom:5px; text-align:center; height:20px;  border : 0.5px black solid; border-left:none; border-right:none; font-size:13px;  font-weight:bold;">
                    Cant.</td>
                <td
                    style="width:20%; padding-bottom:5px; text-align:center; padding-right:10px; height:20px;  border : 0.5px black solid; border-left:none; border-right:none; font-size:13px;  font-weight:bold;">
                    Subtotal</td>
            </tr>
            @foreach($detalles as $detalle)
            <tr>
            <td style="width:60%;  padding: 1px;  text-align:left;     font-size:13px; ">{{$detalle->producto->nombre}}</td>
            <td style="width:20%; padding: 1px; text-align:center;   font-size:13px;  ">{{number_format($detalle->cantidad,2)}}</td>
            <td style="width:20%;  padding: 1px; text-align:center; padding-right:10px;font-size:13px;  ">{{number_format($detalle->cantidad*$detalle->precioventa,2)}}</td>
            </tr>
            @endforeach
            
        </table>
        <table width='100%' style=" margin-top:13px; border-collapse: collapse; ">
            <tr >
                <td
                    style="width:35%; text-align:center;   border : 0.5px black solid; border-left:none; border-right:none; font-size:13px;  font-weight:bold;">
                    Subtotal</td>
                <td
                    style="width:35%;  text-align:center;   border : 0.5px black solid; border-left:none; border-right:none; font-size:13px;  font-weight:bold;">
                    Igv(18%)</td>
                <td
                    style="width:30%;  text-align:center; padding-right:10px;   border : 0.5px black solid; border-left:none; border-right:none; font-size:13px;  font-weight:bold;">
                    Total</td>
            </tr>
            <tr>
            <td style="width:35%; padding:8px 5px 5px ;  text-align:center;     font-size:11px; ">{{number_format($venta->subtotal,2)}}</td>
                <td style="width:35%; padding:8px 5px 5px;  text-align:center;   font-size:11px;  ">{{number_format($venta->igv,2)}}</td>
                <td  style="width:30%; padding:5px;  text-align:center; padding-right:10px;  font-weight:bold;  font-size:12px;  ">
                {{number_format($venta->total,2)}}</td>
            </tr>
        </table>
        <table width='100%' style=" margin-top:8px; border-collapse: collapse; ">
            <tr>
                <td style="height:23px; text-align:center;   border : 2px black solid; border-left:none; border-right:none; font-size:15px;  font-weight:bold;">
                  Total a pagar : <span style="font-size:16px;"> S/.  {{number_format($venta->total,2)}} </span>  </td>
                
            </tr>
            
        </table>
        <p style="text-align: center;">¡Gracias por su compra !</p>
        <p style="text-align: center; font-weight:bold;">
           {{($iddoc == 5)?'Este no es un comprobante válido, canjear por boleta o factura.':''}}  
        </p>

    </main>

</body>

</html>