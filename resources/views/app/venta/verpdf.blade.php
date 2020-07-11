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
	.divFirma{
            border: 1px solid black;
            border-right: none;
            border-left: none;
            width: 100%;
            padding: 0 5px;
            margin-top: 35px;
        }
        .firma{
            width: 50%;
            margin-right: auto;
            margin-left: auto;
            justify-content: center;
        }
    </style>
</head>

<body>
    <main>
        @php
            $iddoc = $venta->tipodocumento_id;
            $nombre_doc = ($iddoc == 3)?'BOLETA ELECTRÓNICA':(($iddoc==4)?'FACTURA ELECTRONICA':'TICKET');
	    $inafecta = 0;
	    $exonerada= 0;
	    $gravada = $venta->subtotal;
        @endphp
        <h3 style=" padding-top:15px; font-family:Georgia, 'Times New Roman', Times, serif;  margin:0px; text-align: center; ">MARAKOS GRIL S.A.C.</h3>
        <p style=" margin:5px 5px 5px; text-align:center; font-size:15px; font-weight:bold;">RUC 20605066942</p>
        <!--p style=" margin:5px;  text-align:center; font-size:13px;">TEL: 123456789</p-->
        <p style=" margin:5px;  text-align:center; font-size:13px;">AV. SANTA VICTORIA NRO. 472 URB. SANTA VICTORIA LAMBAYEQUE - CHICLAYO - CHICLAYO</p>
    <p style=" margin:5px;  text-align:center; font-size:14px; font-weight:bold;">{{$nombre_doc}}</p>
        <p style=" margin:5px;  text-align:center; font-size:14px; font-weight:bold;">{{$venta->numero}}</p>
        <table width='100%' style="  margin-top:10px; border-collapse: collapse;  ">
            <tr style="">
                <td style="  height:2px;  border-bottom : 0.5px black solid ">
                   </td>
            </tr>
            <tr style="">
                <td style="  padding:15px 5px 5px ; font-size:12px; ">
                   <b style="width: 80px !important; display:inline-block;"> FECHA</b><p style="display: inline-block; margin:none;">: {{date('d/m/Y',strtotime($venta->fecha)).' '.substr($venta->created_at,10,9)}}</p>
                </td>
            </tr>
            <tr style="">
                <td style="  padding:0px 5px 5px ; font-size:12px; text-align:left;">
                <b style=" width: 80px !important; display:inline-block;">DNI/RUC</b><p style="display: inline-block; margin:none;">: {{($venta->persona->dni)?($venta->persona->dni):($venta->persona->ruc)}}</p>
                </td>
            </tr>
            <tr style=" ">
                <td style="  padding:0px 5px 5px; font-size:12px; text-align:left; ">
                    <b style="width: 80px !important; display:inline-block;">NOMBRE</b><p style="display: inline-block; margin:none;">: {{$venta->persona->nombres.' '.$venta->persona->apellidopaterno.' '.$venta->persona->apellidomaterno}}</p>
                </td>
            </tr>
            <tr style="">
                <td style=" padding:0px 5px 5px; font-size:12px; text-align:left; ">
                    <b style="width: 80px !important; display:inline-block;">DIRECCION</b><p style="display: inline-block; margin:none;width:80%;">: {{$venta->persona->direccion}} </p>
                </td>
            </tr>
        </table>
        
        <table width='100%' style="  margin-top:5px; border-collapse: collapse; ">
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
		<?php
		if($detalle->producto->igv=="N") $exonerada= $exonerada+ round($detalle->cantidad*$detalle->precioventa,2);
		?>
            <tr>
            <td style="width:60%;  padding: 1px;  text-align:left;     font-size:13px; ">{{$detalle->producto_id?$detalle->producto->nombre:$detalle->promocion->nombre}}</td>
            <td style="width:20%; padding: 1px; text-align:center;   font-size:13px;  ">{{number_format($detalle->cantidad,2)}}</td>
            <td style="width:20%;  padding: 1px; text-align:center; padding-right:10px;font-size:13px;  ">{{number_format($detalle->cantidad*$detalle->precioventa,2)}}</td>
            </tr>
            @endforeach
@if($venta->persona->isPersonal() || $venta->descuento)
	    
	    <tr>
            <td style="width:60%;  padding: 1px;  text-align:left;     font-size:13px; ">Descuento aut.</td>
            <td style="width:20%; padding: 1px; text-align:center;   font-size:13px;  ">-</td>
            <td style="width:20%;  padding: 1px; text-align:center; padding-right:10px;font-size:13px;  ">{{$venta->descuento}}</td>
            </tr>
@endif
        </table>
        <table width='100%' style=" margin-top:10px; border-collapse: collapse; ">
	@if($iddoc==5)
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
	@else
	<?php
	$gravada = round(($venta->total - $exonerada)/1.18,2);
	$igv = round($venta->total - $exonerada - $gravada,2);
	?>
            <tr >
                <td
                    style="width:35%; text-align:left;   border : 0.5px black solid; border-bottom:none;border-left:none; border-right:none; font-size:13px;  font-weight:bold;">
                    Op. Gravada</td>
                <td style="width:65%; text-align:right;   border : 0.5px black solid; border-bottom:none; border-left:none; border-right:none; font-size:13px;  font-weight:bold;">
                    {{number_format($gravada,2)}}</td>
	   </tr>
	   <tr >
                <td
                    style="width:35%; text-align:left; font-size:13px;  font-weight:bold;">
                    I.G.V.(18%)</td>
                <td style="width:65%; text-align:right; font-size:13px;  font-weight:bold;">
                    {{number_format($igv,2)}}</td>
	   </tr>
	   <tr >
                <td
                    style="width:35%; text-align:left; font-size:13px;  font-weight:bold;">
                    Op. Inafecta</td>
                <td style="width:65%; text-align:right; font-size:13px;  font-weight:bold;">
                    {{number_format($inafecta,2)}}</td>
	   </tr>
	   <tr >
                <td
                    style="width:35%; text-align:left; font-size:13px;  font-weight:bold;">
                    Op. Exonerada</td>
                <td style="width:65%; text-align:right; font-size:13px;  font-weight:bold;">
                    {{number_format($exonerada,2)}}</td>
	   </tr>
	   <tr >
                <td
                    style="width:35%; text-align:left; font-size:13px;  font-weight:bold;">
                    TOTAL:</td>
                <td style="width:65%; text-align:right; font-size:13px;  font-weight:bold;">
                    {{number_format($venta->total,2)}}</td>
	   </tr>
	@endif
        </table>
	@if($iddoc==5)
        <table width='100%' style=" margin-top:8px; border-collapse: collapse; ">
            <tr>
                <td style="height:23px; text-align:center;   border : 2px black solid; border-left:none; border-right:none; font-size:15px;  font-weight:bold;">
                  Total a pagar : <span style="font-size:16px;"> S/.  {{number_format($venta->total,2)}} </span>  </td>
                
            </tr>
            
        </table>
	@else
	<p style="text-align: rigth;font-size:13px;  font-weight:bold;">SON: {{$enletras}}</p>
	@endif
        <p style="text-align: center;">¡Gracias por su compra !</p>
        <p style="text-align: center;font-size:12px;  font-weight:bold;">
           {{($iddoc == 5)?'Este no es un comprobante válido, canjear por boleta o factura.':'Representación impresa del Comprobante Electrónico, consulte en https://facturae-garzasoft.com'}}  
        </p>
	<!--<div style="page-break-after:always;"></div>-->
	@if($venta->situacion == 'P' && !$venta->pedido_id)
        <div class="divFirma">
           <!-- <p style="font-style: italic;">Venta realizada a cr&eacute;dito, es necesario registrar su firma para el descuento posterior en planilla.</p> -->
            <div class="firma">
                <hr style="margin-top:40px;">
                <p style="margin-top: 0px; width:50%; margin-left:auto; margin-right:auto;">FIRMA</p>
            </div>
        </div>
        @endif
    </main>

</body>

</html>