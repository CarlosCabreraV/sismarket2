@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
{!! $paginacion !!}
<table id="example1" class="table table-sm text-center table-striped  table-hover">

	<thead>
		<tr>
			@foreach($cabecera as $key => $value)
				<th @if((int)$value['numero'] > 1) colspan="{!! $value['numero'] !!}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		<?php
		$contador = $inicio + 1;
		?>
		@foreach ($lista as $key => $value)
		<!-- 
		 NUEVO    : bg-gray
		 ACEPTADO : bg-primary
		 ENVIADO : bg-olive
		 FINALIZADO: bg-success
		 RECHAZADO: bg-danger
		 POR RECOGER: bg-
		-->
		<?php
			$user = Auth::user();
			$textoEstado = '';
			$backgroundClase ='';
			$opcionEliminar ='';
			$opcionEditar ='';
			$opcionSiguienteEtapa ='';
			$textoSiguienteEtapa ='';
			if($value->estado =='N'){
				$textoEstado ='NUEVO';
				$backgroundClase ='bg-gray';
				$opcionEliminar ='S';
				$opcionSiguienteEtapa ='S';
				$opcionEditar ='S';
				$textoSiguienteEtapa ='Aceptar el pedido';
			}elseif ($value->estado =='A'){
				$textoEstado ='ACEPTADO';
				$backgroundClase ='bg-primary';
				$opcionEliminar ='S';
				$opcionSiguienteEtapa ='S';
				$opcionEditar ='S';
				$textoSiguienteEtapa ='Enviar el pedido';
			}elseif ($value->estado =='E'){
				$textoEstado ='Enviado';
				$backgroundClase ='bg-olive';
				$opcionEliminar ='N';
				$opcionSiguienteEtapa ='S';
				$opcionEditar ='N';
				$textoSiguienteEtapa ='Finalizar el pedido';
			}elseif ($value->estado =='F'){
				$textoEstado ='Finalizado';
				$backgroundClase ='bg-success';
				$opcionEliminar ='N';
				$opcionSiguienteEtapa ='N';
				$opcionEditar ='N';
			}else if ($value->estado =='R'){
				$textoEstado ='Rechazado';
				$backgroundClase ='bg-danger';
				$opcionEliminar ='N';
				$opcionSiguienteEtapa ='N';
				$opcionEditar ='N';
			}else if ($value->estado =='PR'){
				$textoEstado ='Por recoger';
				$backgroundClase='bg-navy';
				$opcionEliminar ='S';
				$opcionSiguienteEtapa ='S';
				$opcionEditar ='S';
				$textoSiguienteEtapa ='Finalizar el pedido';
			}
		?>
        
        <tr >
			<td>{{ $contador }}</td>
			<td>{{ date_format($value->created_at,'d-m-Y') }}</td>
			<td>{{ $value->documento->nombre }}</td>
			<td>{{ $value->nombre?($value->nombre):$value->cliente}}</td>
			<td>{{$value->responsable}}</td>
			@if($value->delivery == 'S')
				<td> <i class="fa fa-check " style="color:#28a745;" ></i> </td>
			@else
				<td> <i class="fa fa-minus " style="color: #6c757d;" ></i> </td>
			@endif
            <td><span class="badge {{$backgroundClase}}"> {{$textoEstado}}</span></td>
			<td>{{ number_format($value->total,2)}}</td>
			
			@if(!$user->isAdmin() && !$user->isSuperAdmin() )

				@if($opcionSiguienteEtapa == 'S')
				<td>{!! Form::button('<div class="fas fa-redo"></div> ', array('onclick' => 'modal (\''.URL::route($ruta["siguiente"], array($value->id, 'SI')).'\', \''.$titulo_modificar.'\', this);', 'class' => 'btn btn-sm btn-outline-success' , 'title'=>$textoSiguienteEtapa)) !!}</td>
				@else
				<td>{!! Form::button('<div class="fas fa-redo"></div> ', array('onclick' => 'return false', 'class' => 'btn btn-sm btn-outline-success disabled')) !!}</td>
				@endif
				<td>{!! Form::button('<div class="fas fa-eye"></div> ', array('onclick' => 'modal (\''.URL::route($ruta["show"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_ver.'\', this);', 'class' => 'btn btn-sm btn-outline-primary','title'=>'Ver detalles del pedido')) !!}</td>
				@if($opcionEditar == 'S')
				<td>{!! Form::button('<div class="fas fa-edit"></div> ', array('onclick' => 'modal (\''.URL::route($ruta["viewUpdate"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_modificarPedido.'\', this);', 'class' => 'btn btn-sm btn-outline-success','title'=>'Editar el pedido')) !!}</td>
				@else
				<td>{!! Form::button('<div class="fas fa-edit"></div> ', array('onclick' => 'return false', 'class' => 'btn btn-sm btn-outline-success disabled')) !!}</td>
				@endif
				@if($opcionEliminar == 'S')
				<td>{!! Form::button('<div class="fas fa-trash-alt"></div> ', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-sm btn-outline-danger','title'=>'Rechazar el pedido')) !!}</td>
				@else
				<td>{!! Form::button('<div class="fas fa-trash-alt"></div>', array('onclick' => 'return false', 'class' => 'btn btn-sm btn-outline-danger disabled')) !!}</td>
				@endif
			@else
				<td colspan="4">-</td>
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
				<th @if((int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</tfoot>
</table>
{!! $paginacion !!}
@endif