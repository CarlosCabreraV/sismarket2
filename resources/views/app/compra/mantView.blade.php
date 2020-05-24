<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($venta, $formData) !!}
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
    <div class="row">
    	<div class="col-lg-12 col-md-12 col-sm-12">
    		<div class="form-group">
        		{!! Form::label('fecha', 'Fecha:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::date('fecha', $venta->fecha, array('class' => 'form-control input-xs', 'id' => 'fecha', 'readonly' => 'true')) !!}
        		</div>
            </div>
            <div class="form-group">
                {!! Form::label('tipodocumento', 'Tipo Doc.:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::text('tipodocumento', $venta->tipodocumento->nombre, array('class' => 'form-control input-xs', 'id' => 'tipodocumento', 'readonly' => 'true')) !!}
        		</div>
            </div>
            <div class="form-group">
                {!! Form::label('numero', 'Nro:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
        			{!! Form::text('numero', $venta->numero, array('class' => 'form-control input-xs', 'id' => 'numero', 'readonly' => 'true')) !!}
        		</div>
        	</div>
            <div class="form-group">
        		{!! Form::label('persona', 'Proveedor:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
        		<div class="col-lg-12 col-md-12 col-sm-12">
                {!! Form::hidden('persona_id', 0, array('id' => 'persona_id')) !!}
                {!! Form::hidden('dni', '', array('id' => 'dni')) !!}
        		{!! Form::text('persona', $venta->persona->apellidopaterno." ".$venta->persona->apellidomaterno." ".$venta->persona->nombres , array('class' => 'form-control input-xs', 'id' => 'persona', 'placeholder' => 'Ingrese Cliente','disabled'=>'true')) !!}
        		</div>
        	</div>
    	</div>
     </div>
	<div class="box">
        <div class="box-header">
            <h2 class="box-title col-lg-5 col-md-5 col-sm-5">Detalle </h2>
        </div>
        <div class="box-body">
            <table class="table table-condensed table-border" id="tbDetalle">
                <thead>
                    <th class="text-center">Cant.</th>
                    {{-- <th class="text-center">Cod. Barra</th> --}}
                    <th class="text-center">Producto</th>
                    <th class="text-center">Precio</th>
                    <th class="text-center">Subtotal</th>
                </thead>
                <tbody>
                @foreach($detalles as $key => $value)
					<tr>
                        <td class="text-center">{!! number_format($value->cantidad,2,'.','') !!}</td>
						{{-- <td class="text-center">{!! $value->producto->codigobarra !!}</td> --}}
                        <td class="text-left">{!! $value->producto->nombre !!}</td>
						<td class="text-center">{!! number_format($value->preciocompra,2,'.','') !!}</td>
						<td class="text-center">{!! number_format($value->preciocompra*$value->cantidad,2,'.','') !!}</td>
					</tr>
                @endforeach
                </tbody>
                <tfoot>
                    <th class="text-right" colspan="3">Total</th>
                    <th class="text-center" align="center">{!! number_format($venta->total,2,'.','') !!}</th>
                </tfoot>
            </table>
        </div>
     </div>
    <br>
	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">	
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('900');
}); 
</script>