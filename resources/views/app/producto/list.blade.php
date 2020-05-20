@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
{!! $paginacion or '' !!}
<table id="example1" class="table table-bordered table-striped table-condensed table-hover">

	<thead>
		<tr>
			@foreach($cabecera as $key => $value)
				<th @if((int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		<?php
		$contador = $inicio + 1;
		?>
		@foreach ($lista as $key => $value)
		<tr>
			<td>{{ $contador }}</td>
            <td>{{ $value->codigobarra }}</td>
            <td>{{ $value->nombre }}</td>
			<td>{{ $value->categoria2 }}</td>
            <td>{{ $value->marca2 }}</td>
            <td>{{ $value->unidad2 }}</td>
            <td>{{ $value->preciocompra }}</td>
            <td>{{ $value->precioventa }}</td>
            <td>{{ number_format($value->stock,2,'.','') }}</td>
			<td>{!! Form::button('<div class="glyphicon glyphicon-pencil"></div> Editar', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_modificar.'\', this);', 'class' => 'btn btn-xs btn-warning')) !!}</td>
			<td>{!! Form::button('<div class="glyphicon glyphicon-list"></div> Presentacion', array('onclick' => 'modal (\''.URL::route($ruta["presentacion"], array($value->id, 'listar'=>'SI')).'\', \'Presentacion\', this);', 'class' => 'btn btn-xs btn-info')) !!}</td>
			@if($value->archivo!="")
				<td>{!! Form::button('<div class="glyphicon glyphicon-search"></div> Imagen', array('onclick' => 'window.open (\'http://localhost/almacen/image/'.$value->id.'-'.$value->archivo.'\',\'_blank\');', 'class' => 'btn btn-xs btn-info')) !!}</td>
			@endif
			<td>{!! Form::button('<div class="glyphicon glyphicon-remove"></div> Eliminar', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-xs btn-danger')) !!}</td>
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			@foreach($cabecera as $key => $value)
				<th @if((int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</tfoot>
</table>
{!! $paginacion or '' !!}
@endif