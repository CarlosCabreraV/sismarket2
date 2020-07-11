@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
{!! $paginacion !!}
<table id="example1" class="table table-bordered table-striped table-condensed table-hover">

	<thead>
		<tr>
			@foreach($cabecera as $key => $value)
				<th @if( (int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
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
                $title='Eliminado';
                $color='background:#f73232d6';
            }else{
                $title='';
                $color='';
            }
            ?>
		<tr title="{{ $title }}" style="{{ $color }};">
			<td>{{ $contador }}</td>
            <td>{{ date("d/m/Y",strtotime($value->fecha))." ".date("H:i:s",strtotime($value->created_at)) }}</td>
            <td>{{ $value->tipodocumento->nombre }}</td>
            <td>{{ $value->motivo->nombre }}</td>
            <td>{{ $value->numero }}</td>
	    <td>{{ $value->comentario }}</td>
            <td>{{ $value->sucursal->nombre }}</td>
	    <td>{{ $value->responsable2 }}</td>
            <td><a target="_blank" href="{{route('movimientoalmacen.pdf' ,['id' => $value->id])}}"><button class="btn btn-primary btn-sm"><i class="fa fa-file"></i> PDF</button></a></td>
			
			@if ($value->situacion == "P")
				<td align="center">{!! Form::button('<div class="fas fa-check"></div> Confirmar', array('onclick' => 'modal (\''.URL::route($ruta["show"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_ver.'\', this);', 'class' => 'btn btn-success btn-sm')) !!}</td>
			@else
            	<td align="center">{!! Form::button('<div class="fas fa-eye"></div> Ver', array('onclick' => 'modal (\''.URL::route($ruta["show"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_ver.'\', this);', 'class' => 'btn btn-info btn-sm')) !!}</td>
			@endif
			<!--td>{!! Form::button('<div class="fas fa-edit"></div> Editar', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_modificar.'\', this);', 'class' => 'btn btn-warning btn-sm')) !!}</td-->
			@if ($value->movimiento == null)
				@if($value->situacion!='A')
					<td align="center">{!! Form::button('<div class="fas fa-trash"></div> Eliminar', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-danger btn-sm')) !!}</td>
				@else
					<td align="center"> - </td>
				@endif
			@else
				@if($value->situacion!='A' && $value->situacion=="C" && $value->movimiento->situacion == "P" || ($value->situacion =="C" && $value->motivo_id == "5"))
					<td align="center">{!! Form::button('<div class="fas fa-trash"></div> Eliminar', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-danger btn-sm')) !!}</td>
				@else
					<td align="center"> - </td>	
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
				<th @if( (int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</tfoot>
</table>
{!! $paginacion !!}
@endif