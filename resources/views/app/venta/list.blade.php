@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
{!! $paginacion !!}
<?php 
$caja_sesion_id = session('caja_sesion_id','0'); 
$current_user = Auth::user();
 ?>
<table id="example1" class="table table-bordered table-striped table-condensed table-hover">

	<thead>
		<tr>
			@foreach($cabecera as $key => $value)
				@if($value['valor'] == 'Operaciones')
					@if($caja_sesion_id != '0' && !$current_user->isAdmin() && !$current_user->isSuperAdmin())
						<th @if( (int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
					@endif
				@else
					<th @if( (int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
				@endif
			@endforeach
		</tr>
	</thead>
	<tbody>
		<?php
		$contador = $inicio + 1;
		?>
		@foreach ($lista as $key => $value)
            <?php 
            if($value->situacion=='A'){
                $title='Anulado';
				$color='background:#f73232d6';
				$btno ='-';
            }else{
                $title='';
				$color='';
				$btno='-';
            }
            ?>
		<tr title="{{ $title }}" style="{{ $color }};">
			<td>{{ $contador }}</td>
            <td>{{ date("d/m/Y",strtotime($value->fecha)) }}</td>
            <td>{{ date("H:i:s",strtotime($value->created_at)) }}</td>
            <td>{{ $value->tipodocumento->nombre }}</td>
            <td>{{ $value->numero }}</td>
            <td>{{ $value->cliente }}</td>
			<td>{{ number_format($value->total,2,'.','') }}</td>
			<td>{{ $value->responsable2 }}</td>
			@if($caja_sesion_id != '0' && !$current_user->isAdmin() && !$current_user->isSuperAdmin())
				<td>{!! Form::button('<div class="fas fa-eye"></div> Ver', array('onclick' => 'modal (\''.URL::route($ruta["show"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_ver.'\', this);', 'class' => 'btn btn-sm btn'.$btno.'info')) !!}</td>
				<td><a target="_blank" href="{{route('venta.verpdf' , ['id'=> $value->id])}}"><button class="btn btn-sm btn-primary"><i class="fas fa-print"></i> Imprimir</button></a>
				{{--{!! Form::button('<div class="glyphicon glyphicon-file"></div> Declarar', array('onclick' => 'declarar (\''.$value->id.'\','.$value->tipodocumento_id.' );', 'class' => 'btn btn-xs btn-warning')) !!}--}}
				@if($value->situacion!='A')
					<td>{!! Form::button('<div class="fas fa-minus"></div> Anular', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-sm btn'.$btno.'danger')) !!}</td>
				@else
					<td>{!! Form::button('<div class="fas fa-minus"></div> Anular', array('onclick' => 'return false', 'class' => 'disabled btn btn-sm btn'.$btno.'default')) !!}</td>
				@endif
			@endif	
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
	<tfoot>
		<tr>
			@foreach($cabecera as $key => $value)
				@if($value['valor'] == 'Operaciones')
					@if($caja_sesion_id != '0' && !$current_user->isAdmin() && !$current_user->isSuperAdmin())
						<th @if( (int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
					@endif
				@else
				<th @if( (int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
				@endif
			@endforeach
		</tr>
	</tfoot>
</table>
{!! $paginacion !!}
@endif