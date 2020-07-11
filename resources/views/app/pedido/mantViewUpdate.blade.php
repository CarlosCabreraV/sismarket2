<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($pedido, $formData) !!}	
    {!! Form::hidden('listar', $listar, array('id' => 'listar' , 'class'=>'form-horizontal')) !!}
    {!! Form::hidden('listProducto', null, array('id' => 'listProducto')) !!}
    <div class="row">
        <div class="col-md-5 ">
        <!--DATOS PEDIDO -->
            <div class="card bg-light">
                <div class="card-body">
                    <div class="card-text">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row mr-2">
                                    <label class="col-md-4 col-form-label">Fecha:</label>
                                    <div class="col-md-8 ">
                                        {!! Form::date('fecha', date('Y-m-d'), array('class' => 'form-control form-control-sm pr-0', 'id' => 'fecha', 'readonly' => 'true')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label ">Doc :</label>
                                    <div class="col-md-9">
                                        {!! Form::select('tipodocumento',$cboTipoDocumento, $pedido->tipodocumento_id, array('class' => 'form-control form-control-sm', 'id' => 'tipodocumento' , 'readonly'=>'true', 'disabled'=>'true')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                   
                                <div class="form-group row ">
                                        <label class="col-md-3 col-form-label">Sucursal :</label>
                                        <div class="col-md-9">
                                                {!! Form::select('sucursal_id', $cboSucursal, $pedido->sucursal_id, array('class' => 'form-control form-control-sm', 'id' => 'sucursal_id', 'readonly'=>'true', 'disabled'=>'true')) !!}
                                        </div>
                                </div>
                        
                        <div class="form-group row">
                                <label class="col-md-3 col-form-label">Cliente:</label>
                                <div class="col-md-9 ">
                                    
                                    {!! Form::text('persona', $cliente, array('class' => 'form-control form-control-sm ', 'id' => 'persona', 'placeholder' => ' Busca un cliente...', 'readonly'=>'true', 'disabled'=>'true')) !!}
                                </div>
                        </div>
                        <div class="form-group row">
                                <label class="col-md-3 col-form-label">Telefono :</label>
                                <div class="col-md-9">
                                        {!! Form::text('telefono', $pedido->telefono, array('class' => 'form-control form-control-sm', 'id' => 'telefono')) !!}
                                </div>
                        </div>
                        <div class="form-group row">
                                <label class="col-md-3 col-form-label">Direccion :</label>
                                <div class="col-md-9">
                                        {!! Form::text('direccion',$pedido->direccion, array('class' => 'form-control form-control-sm', 'id' => 'direccion')) !!}
                                </div>
                        </div>
                        <div class="form-group row">
                                <label class="col-md-3 col-form-label">Ref. :</label>
                                <div class="col-md-9">
                                        {!! Form::text('referencia', $pedido->referencia, array('class' => 'form-control form-control-sm', 'id' => 'referencia')) !!}
                                </div>
                        </div>
                        <div class="form-group row">
                                <label class="col-md-3 col-form-label">Detalle :</label>
                                <div class="col-md-9">
                                        {!! Form::textarea('detalle', $pedido->detalle, array('class' => 'form-control form-control-sm', 'id' => 'detalle' , 'rows'=>2 , 'style'=>'resize:none;')) !!}
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        <!--FIN DATOS PEDIDO -->

        <!--TOTAL -->
            <div class="row">
                
                <div class="col-md-6 ">
                    <div class="card bg-light">
                        <div class="card-header bg-success">
                            <h6 class="card-title">Servicio delivery</h6>
                        </div>
                        <div class="card-body justify-content-center align-items-center d-flex pb-0">
                            <div class="form-group row">
                                <div class="custom-control custom-switch custom-switch-off-default custom-switch-on-success">
                                    <input type="hidden" name='delivery' id="delivery" value="S" >
                                    <input type="checkbox"  class="custom-control-input"  style="height: 100px;" id='deliverylabel' value="S" onchange="deliveryChange(this.checked)" {{($pedido->delivery=='S')?'checked':''}}>
                                    <label class="custom-control-label" for="deliverylabel">Delivery</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body ">
                                <div class="row form-group">
                                            <label class="col-md-5 col-form-label" style="font-size:20px; font-weight:bold;">TOTAL :</label>
                                        <div class="col-md-7">
                                                {!! Form::text('total', $pedido->total, array('class' => 'form-control form-control-lg bg-dark', 'id' => 'total' , 'readonly'=>true , 'style'=>'font-size:23px; font-weight:bold; ')) !!}
                                        </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            
       <!-- FIN TOTAL -->
        </div>
        <!--DATOS AGREGAR PRODUCTO -->
        <div class="col-md-7 ">
            <div class="card bg-light ">
                <div class="card-body">
                    <div class="card-text">
                        <div class="row">
                            <!--BUSCAR PRODUCTO-->
                            <div class="col-md-5">
                                <div class="form-group row mr-2">
                                    <label class="col-md-3 col-form-label">Cod:</label>
                                    <div class="col-md-9">
                                        {!! Form::text('codigobarra', '', array('class' => 'form-control form-control-sm', 'id' => 'codigobarra')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label ">Producto:</label>
                                    <div class="col-md-8    ">
                                        {!! Form::text('descripcion', '', array('class' => 'form-control form-control-sm', 'id' => 'descripcion')) !!}
                                    </div>
                                </div>
                            </div>
                            <!--FIN BUSCAR PRODUCTO -->
                            
                        </div>
                        <!-- RESULTADOS BUSQUEDA -->
                        <div class="row mx-2">
                            <div class="table-responsive busqueda " id='divBusqueda'>
                                
                            </div>
                        </div>
                        <!--FIN RESULTADOS BUSQUEDA -->
                        <!-- DETALLES PEDIDO-->
                        <div class="row mt-3 " id ='divDetalles'>
                            <div class="table-responsive">
                                <table class=" table table-sm table-striped table-hover table-borderless " width='100%' >
                                    <thead class="thead-dark">
                                            <th class="text-center">Cant.</th>
                                            <th class="text-center">Producto</th>
                                            <th class="text-center">Precio</th>
                                            <th class="text-center">Subtotal</th>
                                            <th class="text-center">Quitar</th>
                                    </thead>
                                    <tbody id="tbDetalle">
                                        
                                    </tbody>
                                    <tfoot>
                                        <tr></tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                       
                    </div>
                </div>
            </div>

            <!--PAGO -->
                <div class="card card-outline card-lightblue d-none" id='divPago'>
                    <div class="card-body">
                        <div class="row form-group" style="margin-bottom: 0px !important;">
                            <label class="col-md-2">Modo pago:</label>
                            <div class="col-md-3">
                                    {!! Form::select('modopago', $cboModoPago, $pedido->modopago, array('class' => 'form-control form-control-sm', 'id' => 'modopago' , 'onchange'=>'VerificarModoPago(this.value)')) !!}
                            </div>
                        <div class="col-md-5 {{$pedido->modopago=='TARJETA'?'':'d-none'}}" id='divcboTarjeta'>
                                <div class="row " >
                                    <label class="col-md-3">    Tarjeta</label>
                                    <div class="col-md-9">
                                        {!! Form::select('tarjeta', $cboTarjetas, $pedido->tarjeta, array('class' => 'form-control form-control-sm', 'id' => 'tarjeta')) !!}
                                    </div>
                                </div>
                            </div>
                            <div  id='divefectivo' class="col-md-7 {{$pedido->modopago=='EFECTIVO'?'':'d-none'}}">
                                <div class="row">
                                    <div class="col-md-7" >
                                        <div class="row">
                                            <label class="col-md-4">Efectivo</label>
                                            <div class="col-md-8">
                                                {!! Form::text('dinero','', array('class' => 'form-control form-control-sm ', 'id' => 'dinero','onkeyup' => 'calcularVuelto();')) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5" >
                                        <div class="row">
                                            <label class="col-md-4">Vuelto</label>
                                            <div class="col-md-8">
                                                {!! Form::text('vuelto','', array('class' => 'form-control form-control-sm', 'id' => 'vuelto', 'readonly'=>true)) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>
                </div>
            <!-- FIN PAGO -->
            <div class="form-group">
                <div class="col-lg-12 col-md-12 col-sm-12 text-right">
                    {!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-primary btn-sm', 'id' => 'btnGuardar', 'onclick' => '$(\'#listProducto\').val(carro);guardarPago(\''.$entidad.'\', this);')) !!}
                    {!! Form::button('<i class="fa fa-undo fa-lg"></i> Cancelar', array('class' => 'btn btn-default btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
                </div>
            </div>
        </div>
        <!--FIN DATOS AGREGAR PRODUCTO -->

    </div>
    

{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('1300');
    init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
    $('#totalpagado').inputmask("decimal", { min: 0, allowMinus: false });

    $(':input[id="descripcion"]').focus();

    $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="descripcion"]').on( 'keydown', function () {
        var e = window.event; 
        var keyc = e.keyCode || e.which;
        if(this.value.length>1 && keyc == 13){
            buscarProducto(this.value);
            valorbusqueda=this.value;
            this.focus();
            return false;
        }
        if(keyc == 38 || keyc == 40 || keyc == 13) {
            var tabladiv='tablaProducto';
			var child = document.getElementById(tabladiv).rows;
			var indice = -1;
			var i=0;
            $('#tablaProducto tr').each(function(index, elemento) {
                if($(elemento).hasClass("tr_hover")) {
    			    $(elemento).removeClass("par");
    				$(elemento).removeClass("impar");								
    				indice = i;
                }
                if(i % 2==0){
    			    $(elemento).removeClass("tr_hover");
    			    $(elemento).addClass("impar");
                }else{
    				$(elemento).removeClass("tr_hover");								
    				$(elemento).addClass('par');
    			}
    			i++;
    		});		 
			// return
			if(keyc == 13) {        				
			     if(indice != -1){
					var seleccionado = '';			 
					if(child[indice].id) {
					   seleccionado = child[indice].id;
					} else {
					   seleccionado = child[indice].id;
					}		 		
					seleccionarProducto(seleccionado);
				}
			} else {
				// abajo
				if(keyc == 40) {
					if(indice == (child.length - 1)) {
					   indice = 1;
					} else {
					   if(indice==-1) indice=0;
	                   indice=indice+1;
					} 
				// arriba
				} else if(keyc == 38) {
					indice = indice - 1;
					if(indice==0) indice=-1;
					if(indice < 0) {
						indice = (child.length - 1);
					}
				}	 
				child[indice].className = child[indice].className+' tr_hover';
			}
        }
    });
    
    
    
}); 

function buscarProducto(valor){
    if($(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="sucursal_id"]').val() == ""){
        toastr.warning("Debe seleccionar una sucursal", 'Error:');
    }else{
            $.ajax({
                type: "POST",
                url: "venta/buscarproducto",
                data:"sucursal_id="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="sucursal_id"]').val()+"&descripcion="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="descripcion"]').val()+"&_token="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="_token"]').val(),
                success: function(a) {

                    let tabla = `<table class="table-striped table-hover table-busqueda " id='tablaProducto' width='100%'>
                                    <thead >
                                            <th>Cod.</th>
                                            <th>Producto</th>
                                            <th>Stock</th>
                                            <th>P.Unit</th>
                                    </thead>
                                    <tbody id='tbodyProducto'>
                                        
                                    </tbody>
                                </table>`;
                    $('#divBusqueda').html(tabla);
                    datos=JSON.parse(a);
                    var pag=parseInt($("#pag").val());
                    var d=0;
                    for(c=0; c < datos.length; c++){
                        let a =`<tr id='${datos[c].idproducto}' onclick="seleccionarProducto('${datos[c].idproducto}','${datos[c].codigobarra}','${datos[c].producto}','${datos[c].preciocompra}','${datos[c].precioventa}','${datos[c].stock}','${datos[c].tipo}');">`;
                        
                        @if ($conf_codigobarra=="S")
                            a = a + "<td>"+datos[c].codigobarra+"</td>";
                        @endif 
                        a = a + "<td>"+datos[c].producto+"</td><td>"+datos[c].stock+"</td><td>"+datos[c].precioventa+"</td></tr>";
                        $("#tbodyProducto").append(a);           
                    }
                    
                    $('#tablaProducto').DataTable({
                        "scrollY":        "150px",
                        "scrollCollapse": true,
                        "paging":         false,
                    });
                    $('#tablaProducto_filter').css('display','none');
                    $("#tablaProducto_info").css("display","none");
                }
            });
    }
}

var carro = new Array();
var carroDoc = new Array();
var copia = new Array();
var idant = 0;
function seleccionarProducto(idproducto,codigobarra,descripcion,preciocompra,precioventa,stock, tipo , cantidad=1){
    var band=true;
    var id=idproducto;
    idproducto = idproducto+'-'+tipo;
    for(c=0; c < carro.length; c++){
        if(carro[c]==idproducto){
            band=false;
        }      
    }
    
    if(band){
        var strDetalle = "<tr id='tr"+idproducto+"'><td align='center'><input type='hidden' id='txtIdProducto"+idproducto+"' name='txtIdProducto"+idproducto+"' value='"+id+"' />"+
                            "<input type='hidden' id='txtTipo"+idproducto+"' name='txtTipo"+idproducto+"' value='"+tipo+"' />"+
                            "<input type='text' data='numero' style='width: 50px;' class='form-control form-control-sm m-1' id='txtCantidad"+idproducto+"' name='txtCantidad"+idproducto+"' value='1'  onkeydown=\"if(event.keyCode==13){calcularTotalItem('"+idproducto+"')}\" onblur=\"calcularTotalItem('"+idproducto+"')\" /></td>";
       

        strDetalle = strDetalle + "<td align='center'>"+descripcion+"</td>" + 
        "<td align='center'>"+
            "<input type='hidden' id='txtPrecioCompra"+idproducto+"' name='txtPrecioCompra"+idproducto+"' value='"+preciocompra+"' />"+
            "<input type='hidden' id='txtPrecioVenta"+idproducto+"' name='txtPrecioVenta"+idproducto+"' value='"+precioventa+"' />"+
            "<input type='text' size='5' class='form-control form-control-sm' data='numero' id='txtPrecio"+idproducto+"' style='width: 80px;' name='txtPrecio"+idproducto+"' value='"+precioventa+"' onkeydown=\"if(event.keyCode==13){calcularTotalItem('"+idproducto+"')}\" onblur=\"calcularTotalItem('"+idproducto+"')\" /></td>"+
        "<td align='center'>"+
            "<input type='text' readonly='' data='numero' class='form-control form-control-sm' name='txtTotal"+idproducto+"' style='width: 80px;' id='txtTotal"+idproducto+"' value='"+precioventa+"' /></td>"+
        "<td align='center'>"+
        "<a href='#' onclick=\"quitarProducto('"+idproducto+"')\"><i class='fa fa-minus-circle' title='Quitar' width='20px' height='20px'></i></td></tr>";
       
        $("#tbDetalle").append(strDetalle);
        if(carro.length == 0){
            $('#divPago').removeClass('d-none');
            $('#divDetalles').removeClass('d-none');
        }
         carro.push(idproducto);
        if(idant>0){
            $("#tdDescripcion"+idant).css('font-size','');
            $("#tdDescripcion"+idant).css('color','');
            $("#tdDescripcion"+idant).css('font-weight','');
        }
        $("#tdDescripcion"+idproducto).css('font-size','14px');
        $("#tdDescripcion"+idproducto).css('color','');
        $("#tdDescripcion"+idproducto).css('font-weight','');
        idant=idproducto;
        if(cantidad == 1){
        $('#txtCantidad'+idproducto).val("1");
        }else{
        $('#txtCantidad'+idproducto).val(cantidad);
        }
        $('#txtCantidad'+idproducto).keyup(function (e) {
            var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
                $('#descripcion').val("");
				$('#descripcion').focus();
			}
        });
        $(':input[data="numero"]').inputmask('decimal', { radixPoint: ".", autoGroup: true, groupSeparator: "", groupSize: 3, digits: 2 });
        $('#txtCantidad'+idproducto).select();
        
        calcularTotal();
        calcularVuelto();

    }else{
        if(idant>0){
            $("#tdDescripcion"+idant).css('font-size','');
            $("#tdDescripcion"+idant).css('color','');
            $("#tdDescripcion"+idant).css('font-weight','');
        }
        $("#tdDescripcion"+idproducto).css('font-size','14px');
        $("#tdDescripcion"+idproducto).css('color','');
        $("#tdDescripcion"+idproducto).css('font-weight','');
        idant=idproducto;
        var cant = parseInt($('#txtCantidad'+idproducto).val())+1; 
        $('#txtCantidad'+idproducto).val(cant);
        $('#txtCantidad'+idproducto).select();
        $('#txtCantidad'+idproducto).keyup(function (e) {
            var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				$('#descripcion').val("");
				$('#descripcion').focus();
			}
        });
        
        calcularTotalItem(idproducto);
        calcularVuelto();

    }
    
}
function calcularTotal(){
    var total2=0;
    for(c=0; c < carro.length; c++){
        var tot=parseFloat($("#txtTotal"+carro[c]).val());
        total2=Math.round((total2+tot) * 100) / 100;        
    }
    $("#total").val(total2);
    $("#totalpagado").val(total2);
    calcularVuelto();
}

function calcularTotalItem(id){
    var cant=parseFloat($("#txtCantidad"+id).val());
    var pv=parseFloat($("#txtPrecio"+id).val());
    var total=Math.round((pv*cant) * 100) / 100;
    $("#txtTotal"+id).val(total);
    calcularTotal();
    calcularVuelto();
}

function quitarProducto(id){
    $("#tr"+id).remove();
    for(c=0; c < carro.length; c++){
        if(carro[c] == id) {
            carro.splice(c,1);
        }
    }
    if(carro.length == 0){
            $('#divPago').addClass('d-none');
            $('#divDetalles').addClass('d-none');
    }
    calcularTotal();
    calcularVuelto();
}
function calcularVuelto(){
    var tot=parseFloat($("#total").val());
    var din=parseFloat($("#dinero").val());
    var vue=Math.round((din - tot) * 100) / 100;
    $("#vuelto").val(vue);
}
function deliveryChange(check){
    if(check){
        $('#delivery').val("S");
        $('#deliverylabel').val("S");
    }else{
        $('#delivery').val("N");
        $('#deliverylabel').val("N");
    }
}
function VerificarModoPago(val){
    if (val == 'TARJETA'){
        $('#divcboTarjeta').removeClass('d-none');
        $('#divefectivo').addClass('d-none');
    }else if(val =='EFECTIVO'){
        $('#divcboTarjeta').addClass('d-none');
        $('#divefectivo').removeClass('d-none');
    }else{
        $('#divcboTarjeta').addClass('d-none');
        $('#divefectivo').addClass('d-none');
    }
}

var contador=0;
function guardarPago(entidad, idboton) {
    var band=true;
    var msg="";
    if($("#person_id").val()==0){
        band = false;
        msg += " *No se selecciono un cliente \n";    
    }
    if(carro.length==0){
        band = false;
        msg += " *No se agregó ningún producto \n";    
    }
    if(parseFloat($("#total").val())>700 && $("#tipodocumento").val()=="3"){//BOLETA
        if($("#dni").val().trim().length!=8){
            band = false;
            msg += " *El cliente debe tener DNI correcto \n";
        }
    }   
    if($("#tipodocumento").val()=="4"){//FACTURA
        var ruc = $("#ruc").val();
        ruc = ruc.replace("_"," ");
        console.log(ruc);
        if(ruc.trim().length<11){
            band = false;
            msg += " *Debe registrar un correcto RUC \n";   
        }
    }
    if(band && contador==0){
        contador=1;
    	var idformulario = IDFORMMANTENIMIENTO + entidad;
    	var data         = submitForm(idformulario);
    	var respuesta    = '';
        var error = '';
    	var btn = $(idboton);
    	btn.button('loading');
    	data.done(function(msg) {
    		respuesta = msg;
    	}).fail(function(xhr, textStatus, errorThrown) {
    		respuesta = 'ERROR';
            contador=0;
    	}).always(function() {
    		btn.button('reset');
            contador=0;
    		if(respuesta === 'ERROR'){
                console.log(error);
    		}else{
    		  //alert(respuesta);
                var dat = JSON.parse(respuesta);
                if(dat[0]!==undefined){
                    resp=dat[0].respuesta;    
                }else{
                    resp='VALIDACION';
                }
                
    			if (resp === 'OK') {
                    if(dat[0].tipodocumento_id!="5"){
                        console.log('DECLARAR');
                        //declarar(dat[0].venta_id,dat[0].tipodocumento_id);
                    }
    				cerrarModal();
                    buscarCompaginado('', 'Accion realizada correctamente', entidad, 'OK');
                    //window.open('/juanpablo/ticket/pdfComprobante3?ticket_id='+dat[0].ticket_id,'_blank')
    			} else if(resp === 'ERROR') {
    				toastr.error(dat[0].msg , 'Error');
    			} else {
    				mostrarErrores(respuesta, idformulario, entidad);
    			}
    		}
    	});
    }else{
        toastr.error(msg , "Corrige los siguientes errores");
    }
}
</script>
@foreach($detalles as $detalle)
        <?php 
            $idproducto = $detalle->producto_id?$detalle->producto_id:$detalle->promocion_id;
            $tipo = $detalle->producto_id?'P':'C';
            $codigobarra = ($detalle->producto_id)?$detalle->producto->codigobarra:'';
            $descripcion = ($detalle->producto_id)?$detalle->producto->nombre: $detalle->promocion->nombre;
            $preciocompra = $detalle->preciocompra;   
            $precioventa = $detalle->precioventa;  
            $cantidad = $detalle->cantidad; 
        ?>

        <script>
            seleccionarProducto('{{$idproducto}}','{{$codigobarra}}','{{$descripcion}}','{{$preciocompra}}','{{$precioventa}}',0, '{{$tipo}}','{{$cantidad}}');
        </script>
@endforeach