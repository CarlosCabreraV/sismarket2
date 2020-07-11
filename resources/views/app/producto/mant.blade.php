<?php 
$current_user = Auth::user();
$category_id = null;
if($producto == null){
	$category_id = 742;
}
?>
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($producto, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="row">
		<div class="col-md-6 col-lg-6">
			<div class="form-group ">
				{!! Form::label('codigobarra', 'Cod. Barra', array('class' => 'col-lg-5 col-md-5 col-sm-5 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12 ">
					{!! Form::text('codigobarra', null, array('class' => 'form-control input-xs', 'id' => 'codigobarra')) !!}
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-6" style="display:none">
			<div class="form-group" >
				{!! Form::label('imagen', 'Imagen', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::file('imagen', null, array('class' => 'form-control input-xs', 'id' => 'imagen')) !!}
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-6 col-lg-6">
			<div class="form-group">
				{!! Form::label('nombre', 'Nombre', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::text('nombre', null, array('class' => 'form-control input-xs', 'id' => 'nombre', 'placeholder' => 'Ingrese nombre')) !!}
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-6">
			<div class="form-group">
				{!! Form::label('abreviatura', 'Abreviatura', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::text('abreviatura', null, array('class' => 'form-control input-xs', 'id' => 'abreviatura', 'placeholder' => 'Ingrese abreviatura')) !!}
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 col-lg-6">
			<div class="form-group">
				{!! Form::label('category_id', 'Categoria', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::select('category_id', $cboCategoria, 742, array('class' => 'form-control input-xs', 'id' => 'category_id')) !!}
				</div>
			</div>
			
		</div>
		<div class="col-md-6 col-lg-6">
			<div class="form-group">
				{!! Form::label('categoria_id', 'Subcategoria', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::select('categoria_id', $cboSubcategoria, null, array('class' => 'form-control input-xs', 'id' => 'categoria_id')) !!}
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 col-lg-6">
			<div class="form-group">
					{!! Form::label('unidad_id', 'Unidad', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
					<div class="col-lg-12 col-md-12 col-sm-12">
						{!! Form::select('unidad_id', $cboUnidad, null, array('class' => 'form-control input-xs', 'id' => 'unidad_id')) !!}
					</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-6">
			<div class="form-group">
				{!! Form::label('marca_id', 'Marca', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::select('marca_id', $cboMarca, null, array('class' => 'form-control input-xs', 'id' => 'marca_id')) !!}
				</div>
			</div>
			
		</div>
	</div>
	<div id="divPrecios">
		<div class="row">
			<div class="col-md-6 col-lg-6">
				<div class="form-group">
						{!! Form::label('preciocompra', 'Precio compra', array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label')) !!}
						<div class="col-lg-12 col-md-12 col-sm-12">
							{!! Form::text('preciocompra', null, array('class' => 'form-control input-xs', 'id' => 'preciocompra', 'onblur' => 'calcularPrecio();')) !!}
						</div>
				</div>
				
			</div>
			<div class="col-md-6 col-lg-6">
				<div class="form-group">
					{!! Form::label('ganancia', '% Ganancia', array('class' => 'col-lg-5 col-md-5 col-sm-5 control-label')) !!}
						<div class="col-lg-12 col-md-12 col-sm-12">
							{!! Form::text('ganancia', null, array('class' => 'form-control input-xs', 'id' => 'ganancia', 'onblur' => 'calcularPrecio();')) !!}
						</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 col-lg-6" style="display:none;">
				<div class="form-group">
					{!! Form::label('stockminimo', 'Stock minimo', array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label')) !!}
					<div class="col-lg-12 col-md-12 col-sm-12">
						{!! Form::text('stockminimo', null, array('class' => 'form-control input-xs', 'id' => 'stockminimo')) !!}
					</div>
				</div>
				
			</div>
			<div class="col-md-6 col-lg-6">
				<div class="form-group">
					{!! Form::label('precioventa', 'Precio venta', array('class' => 'col-lg-5 col-md-5 col-sm-5 control-label')) !!}
					<div class="col-lg-12 col-md-12 col-sm-12">
						{!! Form::text('precioventa', null, array('class' => 'form-control input-xs', 'id' => 'precioventa')) !!}
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-6">
				<div class="form-group">
					{!! Form::label('precioventaespecial', 'P. V. Kiosko:', array('class' => 'col-lg-5 col-md-5 col-sm-5 control-label')) !!}
					<div class="col-lg-12 col-md-12 col-sm-12">
						{!! Form::text('precioventaespecial', null, array('class' => 'form-control input-xs', 'id' => 'precioventaespecial')) !!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 col-lg-6">
				<div class="form-group">
					{!! Form::label('precioventaespecial2', 'P. V. Mayorista:', array('class' => 'col-lg-5 col-md-5 col-sm-5 control-label')) !!}
					<div class="col-lg-12 col-md-12 col-sm-12">
						{!! Form::text('precioventaespecial2', null, array('class' => 'form-control input-xs', 'id' => 'precioventaespecial2')) !!}
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-6">
				<div class="form-group">
					{!! Form::label('lbligv', 'IGV', array('class' => 'col-lg-5 col-md-5 col-sm-5 control-label')) !!}
					<div class="col-lg-12 col-md-12 col-sm-12">
						{!! Form::hidden('igv', 'N', array('class' => 'form-control input-xs', 'id' => 'igv')) !!}
						<input type="checkbox" name="chkIGV" id="chkIGV" onclick="Igv(this.checked);" <?php if(!is_null($producto) && $producto->igv=='S') echo 'checked';?>>
					</div>
					
				</div>
			</div>
	
			<div class="col-md-6 col-lg-6">
				<div class="form-group">
					{!! Form::label('consumo', 'Cons. Tienda:', array('class' => 'col-lg-5 col-md-5 col-sm-5 control-label', 'style' => 'display:none;')) !!}
					<div class="col-lg-12 col-md-12 col-sm-12" style="display: none;">
						{!! Form::hidden('consumo', null, array('class' => 'form-control input-xs', 'id' => 'consumo')) !!}
						<input type="checkbox" name="chkConsumo" id="chkConsumo" onclick="consumo(this.checked);" <?php if(!is_null($producto) && $producto->consumo=='S') echo 'checked';?>>
					</div>
				</div>
			</div>
		</div>

	</div>
	<div class="form-group">
	    {!! Form::label('archivo', 'Imagen', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-3 col-md-3 col-sm-3">
			{!! Form::file('archivo', null, array('class' => 'form-control input-xs', 'id' => 'archivo')) !!}
		</div>
	</div>
    <div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-check "></i> '.$boton, array('class' => 'btn btn-primary btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar2(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-undo "></i> Cancelar', array('class' => 'btn btn-default btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
	</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">


$(document).ready(function() {
	configurarAnchoModal('600');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
    $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="preciocompra"]').inputmask('decimal', { radixPoint: ".", autoGroup: true, groupSeparator: "", groupSize: 3, digits: 2 });
    $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="precioventa"]').inputmask('decimal', { radixPoint: ".", autoGroup: true, groupSeparator: "", groupSize: 3, digits: 2 });
    $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="precioventaespecial"]').inputmask('decimal', { radixPoint: ".", autoGroup: true, groupSeparator: "", groupSize: 3, digits: 2 });
    $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="precioventaespecial2"]').inputmask('decimal', { radixPoint: ".", autoGroup: true, groupSeparator: "", groupSize: 3, digits: 2 });
    $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="ganancia"]').inputmask('decimal', { radixPoint: ".", autoGroup: true, groupSeparator: "", groupSize: 3, digits: 2 });
    $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="stockminimo"]').inputmask('decimal', { radixPoint: ".", autoGroup: true, groupSeparator: "", groupSize: 3, digits: 2 });
	@if(!$current_user->isAdmin() && !$current_user->isSuperAdmin())
		$("#divPrecios").hide();
	@endif
	$('#category_id').select2({
		dropdownParent: $('.modal'),
		ajax: {
			url: "promocion/categoriaautocompletar2",
			dataType: 'json',
			delay: 250,
			data: function(params){
				return{
					q: $.trim(params.term),
				};
			},
			processResults: function(data){
				return{
					results: data
				};
			}
			
		}
	});
	$('#categoria_id').select2({
		dropdownParent: $('.modal'),
		ajax: {
			url: "promocion/subcategoriaautocompletar2",
			dataType: 'json',
			delay: 250,
			data: function(params){
				return{
					q: $.trim(params.term),
					idcat: ($('#category_id').val())?($('#category_id').val()):'0',
				};
			},
			processResults: function(data){
				return{
					results: data
				};
			}
			
		}
	});
	$('#marca_id').select2({
		dropdownParent: $('.modal'),
	});

	
});
		$('#category_id').on('change',function(){
			$('#categoria_id').val(null).trigger('change');
			//buscar('{{ $entidad }}');
		});
function calcularPrecio(){
    var ganancia = $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="ganancia"]').val();
    var compra = $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="preciocompra"]').val();
    if(ganancia!=""){
        var venta = Math.round((parseFloat(compra)*(1+parseFloat(ganancia)/100))*10)/10;
        $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="precioventa"]').val(venta);
    }
} 
function consumo(check){
	if(check){
		$("#consumo").val('S');
	}else{
		$("#consumo").val('N');
	}
}



$(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="codigobarra"]').focus();

function Igv(check){
	if(check){
		$("#igv").val('S');
	}else{
		$("#igv").val('N');
	}
}
function guardar2 (entidad, idboton) {
    var band=true;
	var msg="";
	var contador = 0;
    if(band && contador==0){
        contador=1;
    	var idformulario = IDFORMMANTENIMIENTO + entidad;
    	var data         = submitForm(idformulario);
    	var respuesta    = '';
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
    		}else{
    		 	// alert(respuesta);
				var dat = JSON.parse(respuesta);
                if(dat[0]!==undefined){
                    resp=dat[0].respuesta;    
                }else{
                    resp='VALIDACION';
				}
    			if (resp === 'OK') {
    			    enviarArchivo(dat[0].producto_id , dat[0].accion);
    				cerrarModal();
                    buscarCompaginado('', 'Accion realizada correctamente', entidad, 'OK');
    			} else if(resp === 'ERROR') {
    				alert(dat[0].msg);
    			} else {
    				mostrarErrores(respuesta, idformulario, entidad);
    			}
    		}
    	});
    }else{
        alert("Corregir los sgtes errores: \n"+msg);
    }
}

function submitForm2 (idformulario) {
	var parametros = $(idformulario).serialize();
	var accion     = $(idformulario).attr('action');
	var metodo     = $(idformulario).attr('method');
	var respuesta  = $.ajax({
		url : accion,
		type: metodo,
		data: parametros
	});
	return respuesta;
}

function enviarArchivo(idcompra ,accion){
    //var form = $('#formMantenimientoCompra')[0];
	//var formulario = new FormData(form);
	
    var data = new FormData();
    jQuery.each($(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="archivo"]')[0].files, function(i, file) {
        data.append('file-'+i, file);
    });
    data.append("id",idcompra);
    data.append("accion",accion);
    $.ajax({
		url: '{{ url("/producto/archivos") }}',
		headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
		type: 'POST',
		enctype: 'multipart/form-data',
		data: data,
		processData: false,
		contentType: false,
		cache: false,
		timeout: 600000
	});

	console.log('archivo enviado');
}

</script>