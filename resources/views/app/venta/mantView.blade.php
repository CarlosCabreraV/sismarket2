<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($venta, $formData) !!}
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
    <div class="row">
    	<div class="col-lg-12 col-md-12 col-sm-12">
    		<div class="form-group">
        		{!! Form::label('fecha', 'Fecha', array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label')) !!}
        		<div class="col-lg-6 col-md-6 col-sm-6 mb-2">
        			{!! Form::date('fecha', $venta->fecha, array('class' => 'form-control input-xs', 'id' => 'fecha', 'readonly' => 'true')) !!}
        		</div>
                {!! Form::label('tipodocumento', 'Tipo Documento', array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label')) !!}
        		<div class="col-lg-6 col-md-6 col-sm-6 mb-2">
        			{!! Form::select('tipodocumento',$cboTipoDocumento, $venta->tipodocumento_id, array('class' => 'form-control input-xs', 'id' => 'tipodocumento', 'readonly' => 'true','disabled'=>'true')) !!}
        		</div>
                {!! Form::label('numero', 'Numero', array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label')) !!}
        		<div class="col-lg-6 col-md-6 col-sm-6">
        			{!! Form::text('numero', $venta->numero, array('class' => 'form-control input-xs', 'id' => 'numero', 'readonly' => 'true')) !!}
        		</div>
        	</div>
            <div class="form-group">
        		{!! Form::label('persona', 'Cliente:', array('class' => 'col-lg-1 col-md-1 col-sm-1 control-label')) !!}
        		<div class="col-lg-9 col-md-9 col-sm-9">
                {!! Form::hidden('persona_id', 0, array('id' => 'persona_id')) !!}
                {!! Form::hidden('dni', '', array('id' => 'dni')) !!}
        		{!! Form::text('persona', $venta->persona->apellidopaterno." ".$venta->persona->apellidomaterno." ".$venta->persona->nombres , array('class' => 'form-control input-xs', 'id' => 'persona', 'placeholder' => 'Ingrese Cliente','disabled'=>'true')) !!}
        		</div>
        	</div>
    	</div>
     </div>
	<div class="box">
        <div class="box-header">
            <h2 class="box-title col-lg-5 col-md-5 col-sm-5">Detalle</h2>
        </div>
        <div class="box-body">
            <table class="table table-condensed table-border" id="tbDetalle">
                <thead>
                    <th class="text-center">Cant.</th>
                    <th class="text-left">Producto</th>
                    <th class="text-center">Precio</th>
                    <th class="text-center">Subtotal</th>
                </thead>
                <tbody>
                @foreach($detalles as $key => $value)
					<tr>
                        <td class="text-center">{!! number_format($value->cantidad,3,'.','') !!}</td>
                        @if(!is_null($value->producto_id) && $value->producto_id>0)
                            <td class="text-left">{!! $value->producto->nombre !!}</td>
                        @else
                            <td class="text-left">{!! $value->promocion->nombre !!}</td>
                        @endif
						<td class="text-center">{!! number_format($value->precioventa,2,'.','') !!}</td>
						<td class="text-center">{!! number_format($value->precioventa*$value->cantidad,2,'.','') !!}</td>
					</tr>
                @endforeach
		@if($venta->persona->isPersonal() && $venta->descuento)
                            <tr  bgcolor="#0cfa7f" >
                                <td  class="pl-3" style="font-style:italic;">DESCUENTO DEL 10% APLICADO</td>
                                <td></td>
                                <td></td>
                                <td  style="font-style:italic;"> DESCUENTO: <b >{{$venta->descuento?$venta->descuento:'0.00'}}</b></td>
                            </tr>
                @endif
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
			{!! Form::button('<i class="fa fa-undo fa-lg"></i> Cancelar', array('class' => 'btn btn-primary btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('900');
}); 
</script>