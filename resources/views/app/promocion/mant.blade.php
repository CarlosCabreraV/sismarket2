<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($promocion, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	{!! Form::hidden('listProducto', null, array('id' => 'listProducto')) !!}
	<div class="form-group">
		{!! Form::label('nombre', 'Nombre:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::text('nombre', null, array('class' => 'form-control input-xs', 'id' => 'nombre', 'placeholder' => 'Ingrese nombre')) !!}
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 col-lg-6">
			<div class="form-group">
				{!! Form::label('fechainicio', 'Fecha Inicio:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::date('fechainicio', null, array('class' => 'form-control input-xs', 'id' => 'fechainicio')) !!}
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-6">
			<div class="form-group">
				{!! Form::label('fechafin', 'Fecha Fin:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::date('fechafin', null, array('class' => 'form-control input-xs', 'id' => 'fechafin')) !!}
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 col-lg-6">
			<div class="form-group">
				{!! Form::label('categoria_id', 'Categoria:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::select('categoria_id', $cboCategoria, null, array('class' => 'form-control input-xs', 'id' => 'categoria_id')) !!}
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-6">
			<div class="form-group">
				{!! Form::label('unidad_id', 'Unidad:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::select('unidad_id', $cboUnidad, null, array('class' => 'form-control input-xs', 'id' => 'unidad_id')) !!}
				</div>
			</div>
		</div>
	</div>

	
	<div class="row">
		<div class="col-md-6 col-lg-6">
			<div class="form-group">
				{!! Form::label('precioventa', 'P. Venta:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::text('precioventa', null, array('class' => 'form-control input-xs', 'id' => 'precioventa')) !!}
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-6">
			<div class="form-group">
				{!! Form::label('producto', 'Producto:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
				<div class="col-lg-12 col-md-12 col-sm-12">
					{!! Form::text('producto', null, array('class' => 'form-control input-xs', 'id' => 'producto')) !!}
				</div>
			</div>
		</div>
	</div>
    
	<div class="form-group">
		<table class="table table-sm table-condensed table-border" id="tbDetalle">
			<thead class="bg-gray">
				<tr>
					<th>Cant.</th>
					<th>Producto</th>
					<th></th>
				</tr>
			</thead>
			<?php
			use Illuminate\Support\Facades\DB;
			use App\Producto;
			$js="";
			if(!is_null($detalle)){
				foreach($detalle as $key=>$value){
					$producto = Producto::where('id',$value->producto_id)->first();
					echo "<tr id='tr".$value->producto_id."'>";
					echo "<td><input type='text' data='numero' class='form-control input-xs' size='5' style='width: 40px;' name='txtCant".$value->producto_id."' id='txtCant".$value->producto_id."' value='".round($value->cantidad,0)."' /></td>";
					echo "<td><input type='hidden' name='txtIdProducto".$value->producto_id."' id='txtIdProducto".$value->producto_id."' value='".$value->producto_id."' /><input type='text' class='form-control input-xs'  name='txtProducto".$value->producto_id."' id='txtProducto".$value->producto_id."' value=".$producto->nombre." readonly='' /></td>";
					echo "<td><a href='#' onclick=\"quitarProducto2('".$value->producto_id."')\"><i class='fas fa-minus-circle' title='Quitar' width='20px' height='20px'></i></td>";
					echo "</tr>";
					$js.="carro.push($value->producto_id);";
				}
			}
			?>
		</table>
	</div>
    <div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-check "></i> '.$boton, array('class' => 'btn btn-primary btn-sm', 'id' => 'btnGuardar', 'onclick' => '$(\'#listProducto\').val(carro);guardar(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-undo "></i> Cancelar', array('class' => 'btn btn-default btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('600');
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