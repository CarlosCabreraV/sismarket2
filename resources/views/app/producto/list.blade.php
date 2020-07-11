{{-- @php
	echo DNS1D::getBarcodeHTML('00001', 'C128',2,50,'black', true);
@endphp --}}
@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
{!! $paginacion !!}
<table id="example1" class="table table-sm text-center table-striped  table-hover">

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
			@if ($conf_codbarras=="S")
            	<td>{{ $value->codigobarra }}</td>
			@endif
            <td>{{ $value->nombre }}</td>
	    <td>{{ $value->categoria2 }}</td>
            <td>{{ $value->marca2 }}</td>
            <td>{{ $value->unidad2 }}</td>
            <td>{{ $value->preciocompra }}</td>
            <td>{{ $value->precioventa }}</td>
            <td>{{ $value->precioventaespecial }}</td>
            <td>{{ $value->precioventaespecial2 }}</td>
            <td>{{ number_format($value->stock,2,'.','') }}</td>
			<td>{!! Form::button('<div class="fas fa-edit"></div>', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_modificar.'\', this);', 'class' => 'btn btn-sm btn-outline-success')) !!}</td>
           	<td>{!! Form::button('<div class="fas fa-list"></div> ', array('onclick' => 'modal (\''.URL::route($ruta["presentacion"], array($value->id, 'listar'=>'SI')).'\', \'Presentacion\', this);', 'class' => 'btn btn-sm btn-outline-primary')) !!}</td>
			@if($value->archivo!="")
				<td>{!! Form::button('<div class="fas fa-search-plus"></div>', array('class'=>'btn btn-outline-info btn-sm', 'onclick' => 'verImagen(\''.$value->id.'-'.$value->archivo.'\')')) !!}</td>
			@endif
			<td>{!! Form::button('<div class="fas fa-trash"></div> ', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-sm btn-outline-danger')) !!}</td>
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
{!! $paginacion !!}
@endif
<style>
	.medium{
		width: 350px;
		height: 350px;
	}
</style>
<script>
	function verImagen(imagen_url){
		if(imagen_url.includes('imagen.png')){
			imagen_url = 'imagen.png';
		}
		bootbox.dialog({
			centerVertical: true,
			centerHorizontal: true,
    		message: `<img align='center' class='p-3 mt-2' width='300' height='300' src="image/${imagen_url}">`
		}).find("div.modal-content").addClass("medium");
	}
</script>