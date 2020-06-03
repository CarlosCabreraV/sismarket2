<!DOCTYPE html >
<html>
<head>
 <title>BOLETA</title>
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
    height: 90px;
    border: 1px solid black;
    float: left;
}
.divLogoEmpresa{
    width: 20%;
    top:-20px;
    position: relative; 
    height: 90px;
    float: left;
}
.logo{
    margin-top:0px;
}
.divDatosCliente{
    margin-top:15px;
    margin-bottom:15px;
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
    height: 50%;
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

</style>
</head>
<body>
  <main>
        <div class="divTitulo">
            <div class="divLogoEmpresa "><img class="logo" src="dist/img/logo2.png" alt="" width="150px"/></div>
            <div class="divDatosEmpresa">
                <p class="p3 bold" >MI MERCADITO</p>
                <p class="p">CAL.NICOLAS LA TORRE NRO. 126 </p>
                <p class="p">URB. MAGISTERIAL </p>
                <p class="p">LAMBAYEQUE - CHICLAYO - CHICLAYO </p>
            </div>
            <div class="divFactura">
                <p class="pp p5 bold center  pfactura">BOLETA DE VENTA</p>
                <p class="pp p6 bold center pfactura">ELECTRÓNICA</p>
                <p class="pp   center">RUC:  20602871119</p>
            <p class="pp center">B001-00000001</p>
            </div>
        </div>
        <div class="divDatosCliente">
            <table>
                <tr>
                    <td  ><span class="izquierda">FECHA EMISIÓN:</span><span class="derecha">{{date('d-m-Y')}}</span></td>
                </tr>
                <tr>
                    <td  ><span class="izquierda">DNI:</span><span class="derecha">71207654</span></td>
                </tr>
                <tr>
                    <td  ><span class="izquierda">SEÑOR(ES):</span><span class="derecha">Carlos Cabrera</span></td>
                </tr>
                <tr>
                    <td  ><span class="izquierda">MONEDA:</span><span class="derecha">PEN</span></td>
                </tr>
            </table>
        </div>
        <div class="divDetallesFactura">
            <table class="bordered">
                <thead>
                    <tr>
                    <th>COD.</th>
                    <th>CANT.</th>
                    <th class="left">CONCEPTO</th>
                    <th>IMPORTE</th>
                    <th>DSCTO</th>
                    <th>IGV</th>
                    <th>SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="center">1</td>
                        <td class="center">1</td>
                        <td class="left padd">Descripcion producto 1</td>
                        <td class="center">50.00</td>
                        <td class="center">{{'-'}}</td>
                        <td class="center">10.00</td>
                        <td class="center"></td>
                    </tr>
                    <tr>
                        <td class="center">1</td>
                        <td class="center">1</td>
                        <td class="left padd">Descripcion producto 1</td>
                        <td class="center">50.00</td>
                        <td class="center">{{'-'}}</td>
                        <td class="center">10.00</td>
                        <td class="center"></td>
                    </tr>
                    <tr>
                        <td class="center">1</td>
                        <td class="center">1</td>
                        <td class="left padd">Descripcion producto 1</td>
                        <td class="center">50.00</td>
                        <td class="center">{{'-'}}</td>
                        <td class="center">10.00</td>
                        <td class="center"></td>
                    </tr>
                    <tr>
                        <td class="center">1</td>
                        <td class="center">1</td>
                        <td class="left padd">Descripcion producto 1</td>
                        <td class="center">50.00</td>
                        <td class="center">{{'-'}}</td>
                        <td class="center">10.00</td>
                        <td class="center"></td>
                    </tr>
                </tbody>
            </table>
            <div class="tfooter">
                <p class="p"><span class="bold izquierda2 ">OP. GRAVADA:</span><span class="centro">S/</span><span class="monto">200.00</span></p>
                <p class="p"><span class="bold izquierda2 ">OP. INAFECTA:</span><span class="centro">S/</span><span class="monto">0.00</span></p>
                <p class="p"><span class="bold izquierda2 ">OP. EXONERADA:</span><span class="centro">S/</span><span class="monto">0.00</span></p>
                <p class="p"><span class="bold izquierda2 ">I.G.V:</span><span class="centro">S/</span><span class="monto">36.00</span></p>
                <hr>
                <p class="p"><span class="bold izquierda2 ">TOTAL:</span><span class="centro ">S/</span><span class="monto">236.00</span></p>
            </div>
        </div>
        <div class="divCantidad">
        <p class="p bold"><span class="izquierda3">SON:</span><span class="derecha2">DOSCIENTOS TREINTA SEIS</span></p>
        </div>
        <div class="divcodigoQR">
            <div class="codigoQR">
                <img src="" >
            </div>
            <div class="divSeparador"></div>
          <div class="textCodigoQR">
              <p class="p">Representación impresa de la Factura Electrónica, consulte en <a  href="https://facturae-garzasoft.com" target="_blank">https://facturae-garzasoft.com</a> </p>
          </div>
        </div>
      
</main>
 

</body>
</html>