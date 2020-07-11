<!DOCTYPE html >
<html>
<head>
 <title>MOVIMIENTO ALMACEN</title>
 <style type="text/css">
  * {
      font-family: Verdana, Arial, sans-serif;
  }
  table{
      font-size: x-small;
  }
  
  @page {
        
            margin: 0cm 0cm;
            font-family: Arial;
        }
 
        body {
            margin: 1.5cm 1cm 1cm 1cm ;
        }
  
  .gray {
      background-color: lightgray
  }
  
.divTitulo{
    width: 100%;
    height: 90px;
}
.divDatosEmpresa{
    width: 52%;
    height: 90px;
    float: left;
}
.divFactura{
    width: 28%;
    height: 65px;
    border: 1px solid black;
    float: left;
}
.divLogoEmpresa{
    width: 20%;
    height: 90px;
    float: left;
}
.logo{
    margin-top:0px;
}
.divDatosCliente{
    margin-top:15px;
    padding: 15px 13px;
    height: 70px;
    width: 100%;
   
}
.divcodigoQR{
    padding: 20px;
    width: 100%;
    height: 120px;
}
.codigoQR{
    width: 20%;
    height: 130px;
    float: left;
}
.divSeparador{
    width:30%;
    height: 120px;
    float: left;
}
.textCodigoQR{
    width: 50%;
    height: 120px;
    float: left;
}
.divDetallesFactura{
    margin-top:10px;
    padding: 5px;
    height: 48%;
    width: 100%;
    
}
.divCantidad{
    margin-top:15px;
    padding: 5px;
    height: 20px;
    width: 100%;
    border:1px solid black;
}
p {
    border: none;
    margin: none;
    font-size: 12px;
    font-weight: normal;
    
}
.p , .p3{
    padding-left: 25px;
    margin: 3px;
}
.p3{
    font-size: 15px;
    margin-bottom: 4px;
}

.pp{
    margin: 2px;
    font-size: 14px;
}
.bold{
    font-weight: bold;
}
.center{
    text-align: center;
}
.left{
    text-align: left;
}
.p5{
    margin-top: 7px;
    margin-bottom:-4px;
}
.p6{
    margin-bottom: 7px;
}
.pfactura{
    font-size: 15px;
}
table{
    border-collapse: collapse;
    width: 100%;
}
table.bordered , table.bordered tr th , table.bordered tr td{
    border: 1px solid black;
}
table.bordered tr th , table.bordered tr td{
    padding:3px;
}
table tr td {
}
span.izquierda{
    width: 130px;
    display: inline-block;
    font-weight: bold;
}
span.derecha{
    display: inline-block;
    top:-5px;
}
.tfooter{
    width: 35%;
    float: right;
    height: 90px;
    padding: 35px 0px 15px 0px;
}

span.centro , span.monto , span.izquierda2{
    display: inline-block;
    top:-5px;
    
}
span.izquierda2{
    width: 130px;
}
span.monto {
    width: 60px;
    
}
span.centro {
    width: 20px;
}
span.izquierda3{
    display: inline-block;
    width: 50px;
    font-weight: normal;
}
span.derecha2{
    display: inline-block;
}
.padd{
    padding: 10px !important;
}
.yellow{
    background: yellow;
}
.f14{
    font-size: 14px;    
}
hr{
    background: black;
}
.link{
    
}
.tabladatos {
    border-collapse: collapse;
}
.tabladatos td {
    padding: 4px;
}
.divFirma{
            width: 100%;
            padding: 10px 5px;
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
        <div class="divTitulo">
            <div class="divLogoEmpresa "><img class="logo" src="dist/img/logo-marakos.jpg" alt="" width="150px"/></div>
            <div class="divDatosEmpresa">
                <p class="p3 bold" >MARAKOS GRIL S.A.C</p>
                <p class="p">AV. SANTA VICTORIA NRO. 472 </p>
                <p class="p">URB. SANTA VICTORIA </p>
                <p class="p">LAMBAYEQUE - CHICLAYO - CHICLAYO </p>
            </div>
            
            <div class="divFactura">
                <p class="pp p5 bold center  pfactura">{{ $movimiento->motivo->nombre }}</p>
                <p class="pp center">{{$movimiento->numero }}</p>
            </div>
        
        </div>
        <div class="divDatosCliente" style="margin-top:90px;">
            <table class="tabladatos" >
                <tr >
                    <td ><span class="izquierda" >FECHA EMISIÓN:</span><span class="derecha">{{$movimiento->fecha}}</span></td>
                </tr>
                <tr >
                    <td  ><span class="izquierda">TIPO DOC.:</span><span class="derecha">{{$movimiento->tipodocumento->nombre}}</span></td>
                </tr>
                <tr >
                    <td ><span class="izquierda">{{($movimiento->motivo_id==3)?'SUCURSAL ORIGEN:':'SUCURSAL:'}}</span><span class="derecha">{{$movimiento->sucursal->nombre}}</span></td>
                </tr>
                @if($movimiento->motivo_id==3)
                <tr >
                    <td ><span class="izquierda">SUCURSAL DESTINO:</span><span class="derecha">{{$movimiento->sucursalenvio->nombre}}</span></td>
                </tr>
                @endif
                <tr >
                    <td  ><span class="izquierda">RESPONSABLE:</span><span class="derecha">{{$movimiento->resoonsable->nombres.' '.$movimiento->resoonsable->apellidopaterno.' '.$movimiento->resoonsable->apellidomaterno}}</span></td>
                </tr>
            </table>
        </div>

        <div class="divDetallesFactura" style="margin-top: 60px;" >
            <table class="bordered">
                <thead>
                    <tr>
                    <th>CANT.</th>
                    <th>CODIGO.</th>
                    <th class="left">DESCRIPCION</th>
                    <th>PRECIO</th>
                    <th>SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detalles as $detalle)
                    <tr>
                        <td class="center">{{number_format($detalle->cantidad,3)}}</td>
                        <td class="center">{{$detalle->producto->codigobarra}}</td>
                        <td class="left padd">{{$detalle->producto->nombre}}</td>
                        <td class="center">{{number_format( $detalle->preciocompra ,2)}}</td>
                        <td class="center">{{number_format($detalle->cantidad*$detalle->preciocompra,2)}}</td>
                    </tr>
                    @endforeach
                    
                </tbody>
            </table>
            <div class="tfooter">
                <p class="p"><span class="bold izquierda2 ">TOTAL:</span><span class="centro ">S/</span><span class="monto">{{number_format($movimiento->total,2)}}</span></p>
            </div>
        </div>
        <div class="divFirma">
            <div class="firma">
                <hr style="margin-top:40px;">
                <p style="margin-top: 0px; width:50%; margin-left:auto; margin-right:auto;">FIRMA DEL RESPONSABLE</p>
            </div>
        </div>
        <!--
        <div class="divcodigoQR">
            <div class="codigoQR">
          <?php //  echo '<img src="data:'.$qrCode->getContentType().';base64,'.$qrCode->generate().'" />'; ?>
            </div>
            <div class="divSeparador"></div>
          <div class="textCodigoQR">
              <p class="p">Representación impresa de la Factura Electrónica, consulte en <a  href="https://facturae-garzasoft.com" target="_blank">https://facturae-garzasoft.com</a> </p>
          </div>
        </div> -->
      
</main>
 

</body>
</html>