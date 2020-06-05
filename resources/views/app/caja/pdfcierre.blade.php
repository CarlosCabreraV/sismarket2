<!DOCTYPE html>
<html>

<head>
    <title>REPORTE DE CAJA</title>
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

        /* main table tr td{
            font-family:Georgia, 'Times New Roman', Times, serif; 
        }
        p {
            font-family:Georgia, 'Times New Roman', Times, serif; 
        } */

        .gray {
            background-color: lightgray
        }
    </style>
</head>

<body>
    <main>
        <h3 style=" padding-top:5px;  margin:0px; text-align: center; ">REPORTE DE CAJA</h3>
        <!--p style=" margin:10px 5px 5px; text-align:center; font-size:15px; ">RUC 12345678911</p>
        <p style=" margin:5px;  text-align:center; font-size:15px;">TEL: 123456789</p>
        <p style=" margin:5px;  text-align:center; font-size:15px;">DIRECCION EMPRESA #360</p-->
        <table width='100%' style="  margin-top:5px; border-collapse: collapse;  ">
            <tr style="">
                <td style="  padding:15px 5px 5px ; font-size:11px; ">
                <b style="width: 50px !important; display:inline-block;"> Usuario</b><p style="display: inline-block; margin:none;">: {{$usuario->apellidopaterno . " " . $usuario->apellidomaterno . " " . $usuario->nombres}}</p>
                </td>
            </tr>
            <tr style="">
                <td style="  padding:0px 5px 5px ; font-size:11px; text-align:left;">
                <b style=" width: 50px !important; display:inline-block;">Fecha</b><p style="display: inline-block; margin:none;">: {{date('d-m-Y H:i:s')}}</p>
                </td>
            </tr>
        </table>
        <hr style="height: 0.5px;">
        <h5 style=" padding-top:0px;  margin:0px; text-align: left; font-size:11px;">DETALLE VENTA:</h5>
        <table width='100%' style=" margin-top:13px; border-collapse: collapse; ">
            <tr >
                <td style="width:25%; text-align:left;   border : 0.5px black solid; border-left:none; border-right:none; font-size:11px;font-weight:bold; "> CANT.</td>
                <td style="width:75%;  text-align:left;   border : 0.5px black solid; border-left:none; border-right:none; font-size:11px;font-weight:bold; "> DESCRIPCION</td>
                
            </tr>
            @foreach ($arrayProductosN as $detalle)
                <tr>
                    <td style="width:25%; padding:4px 2px 2px ;  text-align:left;     font-size:11px; ">{{ number_format($detalle["cantidad"],2) }}</td>
                    <td style="width:75%; padding:4px 2px 2px;  text-align:left;   font-size:11px;  ">{{ $detalle["producto"]["nombre"] }}</td>
                </tr>
            @endforeach
                
        </table>
        <hr style="height: 0.5px;">
        <table width='100%'  style=" margin-top:0px; border-collapse: collapse;  ">
            <tr style="">
                <td style="width:45%;  padding:5px 5px 5px ; font-size:11px; text-align:left;">
                    <b style=" width: 90px !important; display:inline-block;"> VENTA TOTAL</b><p style="display: inline-block; margin:none;">: </p>
                </td>
                <td style="width:25%;  padding:5px 5px 5px ; font-size:11px; text-align:right;">
                     {{number_format($totalVenta,2)}} 
                </td>
                <td style="width:30%;  padding:5px 5px 5px ; font-size:11px; text-align:right;">
                </td>
            </tr>
            <tr style="">
                <td style="width:45%;  padding:5px 5px 5px ; font-size:11px; text-align:left;">
                    <b style=" width: 90px !important; display:inline-block;">TARJETA (T)</b><p style="display: inline-block; margin:none;">: </p>
                </td>
                <td style="width:25%;  padding:5px 5px 5px ; font-size:11px; text-align:right;">
                    {{number_format($totalTarjeta,2)}} 
                </td>
                <td style="width:30%;  padding:5px 5px 5px ; font-size:11px; text-align:right;">
                </td>
            </tr>
            <tr style="">
                <td style="width:45%;  padding:0px 5px 5px ; font-size:11px; text-align:left;">
                    <b style=" width: 90px !important; display:inline-block;">EFECTIVO (E)</b><p style="display: inline-block; margin:none;">: </p>
                </td>
                <td style="width:25%;  padding:0px 5px 5px ; font-size:11px; text-align:right;">
                     {{number_format($totalEfectivo,2)}} 
                </td>
                <td style="width:30%;  padding:5px 5px 5px ; font-size:11px; text-align:right;">
                </td>
            </tr>
            <tr style="">
                <td style="width:45%;  padding:0px 5px 5px ; font-size:11px; text-align:left;">
                    <b style=" width: 90px !important; display:inline-block;">CAJA INICIO </b><p style="display: inline-block; margin:none;">: </p>
                </td>
                <td style="width:25%;  padding:0px 5px 5px ; font-size:11px; text-align:right;">
                     {{number_format($cajaInicio,2)}} 
                </td>
                <td style="width:30%;  padding:5px 5px 5px ; font-size:11px; text-align:right;">
                </td>
            </tr>
        </table>
        <hr style="height: 0.5px;">
        <h5 style=" padding-top:0px;  margin:0px; text-align: left; font-size:11px;">INGRESOS:</h5>
        <table width='100%' style=" margin-top:4px; border-collapse: collapse; ">
            @foreach ($arrayIngresos as $detalle)
                <tr>
                    <td style="width:100%; padding:4px 2px 2px;  text-align:left;   font-size:11px;  ">{{ " - ".$detalle["concepto"]." (".$detalle["comentario"]."): ". number_format($detalle["total"],2)}}</td>
                </tr>
            @endforeach
                
        </table>
        <h5 style=" padding-top:5px;  margin:0px; text-align: right; font-size:11px;">TOTAL INGRESOS: {{number_format($totalIngresos,2)}} </h5>
        <hr style="height: 0.5px;">
        <h5 style=" padding-top:0px;  margin:0px; text-align: left; font-size:11px;">GASTOS:</h5>
        <table width='100%' style=" margin-top:4px; border-collapse: collapse; ">
            @foreach ($arrayGastos as $detalle)
                <tr>
                    <td style="width:100%; padding:4px 2px 2px;  text-align:left;   font-size:11px;  ">{{ " - ".$detalle["concepto"]." (".$detalle["comentario"]."): ". number_format($detalle["total"],2)}}</td>
                </tr>
            @endforeach
                
        </table>
        <h5 style=" padding-top:5px;  margin:0px; text-align: right; font-size:11px;">TOTAL GASTOS: {{number_format($totalGastos,2)}} </h5>
        <hr style="height: 0.5px;">
        <h5 style=" padding-top:0px;  margin:0px; text-align: left; font-size:11px;">CIERRE CAJA:</h5>
        <table width='100%' style=" margin-top:4px; border-collapse: collapse; ">
            @foreach ($arrayGastos as $detalle)
                <tr>
                    <td style="width:100%; padding:4px 2px 2px;  text-align:left; font-weight:bold;  font-size:12px;  ">E + I - G : {{ number_format($totalEfectivo + $totalIngresos - $totalGastos,2) }}</td>
                </tr>
                <tr>
                    <td style="width:100%; padding:4px 2px 2px;  text-align:left;   font-size:11px;  ">REAL : </td>
                </tr>
                <tr>
                    <td style="width:100%; padding:4px 2px 2px;  text-align:left;   font-size:11px;  ">CAJA CHICA FINAL : </td>
                </tr>
            @endforeach
                
        </table>
        <hr style="height: 0.5px;">
        <h5 style=" padding-top:0px;  margin:0px; text-align: left; font-size:11px;">ANULADOS:</h5>
        <table width='100%' style=" margin-top:13px; border-collapse: collapse; ">
            <tr >
                <td style="width:25%; text-align:left;   border : 0.5px black solid; border-left:none; border-right:none; font-size:11px;font-weight:bold; "> CANT.</td>
                <td style="width:75%;  text-align:left;   border : 0.5px black solid; border-left:none; border-right:none; font-size:11px;font-weight:bold; "> DESCRIPCION</td>
                
            </tr>
            @foreach ($arrayProductosA as $detalle)
                <tr>
                    <td style="width:25%; padding:4px 2px 2px ;  text-align:left;     font-size:11px; ">{{ number_format($detalle["cantidad"],2) }}</td>
                    <td style="width:75%; padding:4px 2px 2px;  text-align:left;   font-size:11px;  ">{{ $detalle["producto"]["nombre"] }}</td>
                </tr>
            @endforeach
                
        </table>
        <hr style="height: 0.5px;">
                <h5 style=" padding-top:0px;  margin:0px; text-align: left; font-size:11px;">OBSERVACIONES:</h5>

    </main>

</body>

</html>