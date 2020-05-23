<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($producto, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	{!! Form::hidden('id', $producto->id, array('id' => 'id')) !!}
	{!! Form::hidden('listProducto', null, array('id' => 'listProducto')) !!}
	<div class="form-group">
		{!! Form::label('nombre', 'Nombre:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::text('nombre', null, array('class' => 'form-control input-xs', 'id' => 'nombre', 'placeholder' => 'Ingrese nombre', 'readonly' => 'true')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('producto', 'Producto:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::text('producto', null, array('class' => 'form-control input-xs', 'id' => 'producto')) !!}
		</div>
	</div>
	<div class="form-group">
		<table class="table table-condensed table-border" id="tbDetalle">
			<thead>
				<tr>
					<th>Cant.</th>
					<th>Producto</th>
					<th></th>
				</tr>
			</thead>
			<?php
			$js="";
			if(!is_null($detalle)){
				foreach($detalle as $key=>$value){
					echo "<tr id='tr".$value->presentacion_id."'>";
					echo "<td><input type='text' data='numero' class='form-control input-xs' size='5' style='width: 40px;' name='txtCant".$value->presentacion_id."' id='txtCant".$value->presentacion_id."' value='".round($value->cantidad,0)."' /></td>";
					echo "<td><input type='hidden' name='txtIdProducto".$value->presentacion_id."' id='txtIdProducto".$value->presentacion_id."' value='".$value->presentacion_id."' /><input type='text' class='form-control input-xs'  name='txtProducto".$value->presentacion_id."' id='txtProducto".$value->presentacion_id."' value='".$value->presentacion->nombre."' readonly='' /></td>";
					echo "<td><a href='#' onclick=\"quitarProducto2('".$value->presentacion_id."')\"><i class='fa fa-minus-circle' title='Quitar' width='20px' height='20px'></i></td>";
					echo "</tr>";
					$js.="carro.push($value->presentacion_id);";
				}
			}
			?>
		</table>
	</div>
    <div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-primary btn-sm', 'id' => 'btnGuardar', 'onclick' => '$(\'#listProducto\').val(carro);guardar(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-undo fa-lg"></i> Cancelar', array('class' => 'btn btn-default btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('420');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
    $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="precioventa"]').inputmask('decimal', { radixPoint: ".", autoGroup: true, groupSeparator: "", groupSize: 3, digits: 2 });
    $(':input[data="numero"]').inputmask('decimal', { radixPoint: ".", autoGroup: true, groupSeparator: "", groupSize: 3, digits: 2 });
});
$(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="nombre"]').focus();


    var producto2 = new Bloodhound({
		
		datumTokenizer: function (d) {
			return Bloodhound.tokenizers.whitespace(d.value);
		},
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: {
			url: 'promocion/productoautocompletar/%QUERY',
			filter: function (producto2) {
				return $.map(producto2, function (movie) {
					return {
						value: movie.value,
						id: movie.id,
					};
				});
			}
		}
	});
	producto2.initialize();
	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="producto"]').typeahead(null,{
		displayKey: 'value',
		source: producto2.ttAdapter()
	}).on('typeahead:selected', function (object, datum) {
		$("#tbDetalle").append("<tr id='tr"+datum.id+"'><td><input type='hidden' id='txtIdProducto"+datum.id+"' name='txtIdProducto"+datum.id+"' value='"+datum.id+"' /><input type='text' data='numero' style='width: 40px;' class='form-control input-xs' id='txtCant"+datum.id+"' name='txtCant"+datum.id+"' value='1' size='3' /></td>"+
            "<td align='left'><input type='text' class='form-control input-xs'  name='txtProducto"+datum.id+"' id='txtProducto"+datum.id+"' value='"+datum.value+"' readonly='' /></td>"+
            "<td><a href='#' onclick=\"quitarProducto('"+datum.id+"')\"><i class='fa fa-minus-circle' title='Quitar' width='20px' height='20px'></i></td></tr>");
        carro.push(datum.id);
	});

var carro = new Array();

function quitarProducto2(id){
    $("#tr"+id).remove();
    for(c=0; c < carro.length; c++){
        if(carro[c] == id) {
            carro.splice(c,1);
        }
    }
}

<?=$js?>
</script>