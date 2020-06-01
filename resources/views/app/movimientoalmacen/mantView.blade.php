<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($venta, $formData) !!}
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
    <div class="row">
    	<div class="col-lg-12 col-md-12 col-sm-12">
            <div class="row col-lg-12 col-md-12 col-sm-12 p-0 m-0">
                <div class="col-lg-4 col-md-4 col-sm-4 p-0">
                    <div class="form-group">
                        {!! Form::label('fecha', 'Fecha:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            {!! Form::date('fecha', $venta->fecha, array('class' => 'form-control input-xs', 'id' => 'fecha', 'readonly' => 'true')) !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 p-0">
                    <div class="form-group">
                        {!! Form::label('numero', 'Nro:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            {!! Form::text('numero', $venta->numero, array('class' => 'form-control input-xs', 'id' => 'numero', 'readonly' => 'true')) !!}
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 p-0">
                    <div class="form-group">
                        {!! Form::label('tipodocumento', 'Tipo Doc.:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            {!! Form::text('tipodocumento', $venta->tipodocumento->nombre, array('class' => 'form-control input-xs', 'id' => 'tipodocumento', 'readonly' => 'true')) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row col-lg-12 col-md-12 col-sm-12 p-0 m-0">
                <div class="col-lg-4 col-md-4 col-sm-4 p-0">
                    <div class="form-group">
                        {!! Form::label('motivo', 'Motivo:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            {!! Form::text('motivo', $venta->motivo->nombre, array('class' => 'form-control input-xs', 'id' => 'motivo', 'readonly' => 'true')) !!}
                        </div>
                    </div>
                </div>
                @if ($venta->motivo_id == "3")
                    <div class="col-lg-4 col-md-4 col-sm-4 p-0">
                        <div class="form-group">
                            {!! Form::label('sucursal_id', 'Sucursal Origen:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                {!! Form::text('sucursal_id', $venta->sucursal->nombre, array('class' => 'form-control input-xs', 'id' => 'sucursal_id', 'readonly' => 'true')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 p-0">
                        <div class="form-group">
                            {!! Form::label('sucursal_envio_id', 'Sucursal Destino:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                {!! Form::text('sucursal_envio_id', $venta->sucursalenvio->nombre, array('class' => 'form-control input-xs', 'id' => 'sucursal_envio_id', 'readonly' => 'true')) !!}
                            </div>
                        </div>
                    </div>
                @elseif($venta->motivo_id == "5")
                    <div class="col-lg-4 col-md-4 col-sm-4 p-0">
                        <div class="form-group">
                            {!! Form::label('sucursal_envio_id', 'Sucursal Origen:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                {!! Form::text('sucursal_envio_id', $venta->sucursalenvio->nombre, array('class' => 'form-control input-xs', 'id' => 'sucursal_envio_id', 'readonly' => 'true')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 p-0">
                        <div class="form-group">
                            {!! Form::label('sucursal_id', 'Sucursal Destino:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                {!! Form::text('sucursal_id', $venta->sucursal->nombre, array('class' => 'form-control input-xs', 'id' => 'sucursal_id', 'readonly' => 'true')) !!}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-lg-4 col-md-4 col-sm-4 p-0">
                        <div class="form-group">
                            {!! Form::label('sucursal_id', 'Sucursal:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                {!! Form::text('sucursal_id', $venta->sucursal->nombre, array('class' => 'form-control input-xs', 'id' => 'sucursal_id', 'readonly' => 'true')) !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
    		
            
            <div class="form-group" hidden>
                {!! Form::label('persona', 'Proveedor:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
                <div class="col-lg-12 col-md-12 col-sm-12">
                {!! Form::hidden('persona_id', 0, array('id' => 'persona_id')) !!}
                {!! Form::hidden('dni', '', array('id' => 'dni')) !!}
                {!! Form::text('persona', $venta->persona->apellidopaterno." ".$venta->persona->apellidomaterno." ".$venta->persona->nombres , array('class' => 'form-control input-xs', 'id' => 'persona', 'placeholder' => 'Ingrese Cliente','disabled'=>'true')) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('comentario', 'Comentario:', array('class' => 'col-lg-2 col-md-2 col-sm-2 control-label')) !!}
                <div class="col-lg-12 col-md-12 col-sm-12">
                    {!! Form::textarea('comentario', $venta->comentario, array('class' => 'form-control input-xs', 'id' => 'comentario', 'rows' => '2', 'readonly' => 'true')) !!}
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
                    @if ($conf_codigobarra=="S")
                        <td class="text-center">Cod. Barra</td>
                    @endif
                    <th class="text-center">Producto</th>
                    <th class="text-center">Precio</th>
                    <th class="text-center">Subtotal</th>
                </thead>
                <tbody>
                @foreach($detalles as $key => $value)
					<tr>
                        <td class="text-center">{!! number_format($value->cantidad,2,'.','') !!}</td>
						@if ($conf_codigobarra=="S")
                            <td class="text-center">{!! $value->producto->codigobarra !!}</td>
                        @endif
                        <td class="text-left">{!! $value->producto->nombre !!}</td>
						<td class="text-center">{!! number_format($value->preciocompra,2,'.','') !!}</td>
						<td class="text-center">{!! number_format($value->preciocompra*$value->cantidad,2,'.','') !!}</td>
					</tr>
                @endforeach
                </tbody>
                <tfoot>
                    @php
                        $colspan1 = ($conf_codigobarra=="S")?"4":"3";
                    @endphp
                    <th class="text-right" colspan="{{$colspan1}}">Total</th>
                    <th class="text-center" align="center">{!! number_format($venta->total,2,'.','') !!}</th>
                </tfoot>
            </table>
        </div>
     </div>
    <br>
	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
            @if($venta->motivo_id == "5" && $venta->situacion == "P")
                {!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this);')) !!}
            @endif	
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('1100');
}); 
</script>