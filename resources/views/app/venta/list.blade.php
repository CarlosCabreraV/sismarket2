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
            {{-- @if($value['valor'] == 'Operaciones')
					@if($caja_sesion_id != '0' && !$current_user->isAdmin() && !$current_user->isSuperAdmin())
						<th @if( (int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
            @endif
            @else --}}
            <th @if( (int)$value['numero']> 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
            {{-- @endif --}}
            @endforeach
        </tr>
    </thead>
    <tbody>
        <?php
		$contador = $inicio + 1;
		?>
        @foreach ($lista as $key => $value)
        <?php 
           	$title = '';
				$title2 = '';
				$bg = '';
				$color = '';
            if($value->situacion=='A'){
                $title='Anulado';
                $title2='Anulado';
				$color='background:#f73232d6';
				$bg='bg-danger';
			}else if($value->situacion =='P' && $value->pedido_id){
				$title='Pedido';
				$title2='Venta perteneciente a un pedido';
				$btno ='-';
				$bg='bg-primary';
			}else if($value->situacion =='P' && !$value->pedido_id){
				$title='Pendiente';
				$title2='Pendiente';
				$bg='bg-primary';
			}else if($value->situacion == 'C'){
				$title='Cobrado';
				$title2='Cobrado';
				$bg='bg-success';
			}else if($value->situacion == 'T'){
				$title='Parcial';
				$title2='Parcial';
				$bg='bg-warning';
			}
            ?>
        <tr title="{{ $title2 }}" style="{{ $color }};">
            <td>{{ $contador }}</td>
            <td>{{ date("d/m/Y",strtotime($value->fecha)) }}</td>
            <td>{{ date("H:i:s",strtotime($value->created_at)) }}</td>
            <td>{{ $value->tipodocumento->nombre }}</td>
            <td>{{ $value->numero }}</td>
            <td>{{ $value->cliente }}</td>
            <td>{{ number_format($value->total,2,'.','') }}</td>
            <td><span class="badge {{$bg}}"> {{$title}}</span></td>
            <td>{{ $value->responsable2 }}</td>
            @if ($value->situacion == 'T')
            <td colspan="3" align="CENTER" >{!! Form::button('<div class="fas fa-edit"></div> ', array('onclick' => 'modal (\''.URL::route($ruta["viewParcial"], array($value->id, 'listar'=>'SI')).'\', \''.'Guardar Venta'.'\', this);', 'class' => 'btn btn-sm btn-warning','title'=>'Copiar')) !!}</td>
            <td colspan="2" align="CENTER">{!! Form::button('<div class="fas fa-minus"></div>', array('onclick' => 'modal
                        (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);',
                        'class' => 'btn btn-sm btn-danger', 'title'=>'Anular')) !!}</td>
            @else
            <td>{!! Form::button('<div class="fas fa-copy"></div> ', array('onclick' => 'modal (\''.URL::route($ruta["viewCopiar"], array($value->id, 'listar'=>'SI')).'\', \''.'Guardar Venta'.'\', this);', 'class' => 'btn btn-sm btn-success','title'=>'Copiar')) !!}</td>
            
            <td>{!! Form::button('<div class="fas fa-eye"></div>', array('onclick' => 'modal
                (\''.URL::route($ruta["show"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_ver.'\', this);',
                'class' => 'btn btn-sm btn-info')) !!}</td>
            <td><a target="_blank" href="{{route('venta.verpdf' , ['id'=> $value->id])}}"><button
                        class="btn btn-sm btn-primary" title="Imprimir"><i class="fas fa-print"></i> </button></a>
                {!! Form::button('<div class="glyphicon glyphicon-file"></div> Declarar', array('onclick' => 'declarar2 (\''.$value->id.'\','.$value->tipodocumento_id.' );', 'class' => 'btn btn-xs btn-warning', 'style'=>'display:none')) !!}
                @if($caja_sesion_id != '0' && !$current_user->isAdmin() && !$current_user->isSuperAdmin())
                    @if($value->situacion == 'P' && !$value->pedido_id)
                    <td>{!! Form::button('<div class="fas fa-hand-holding-usd"></div> ', array('onclick' => 'modal (\''.URL::route($ruta["generarPagar"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_pagar.'\', this);', 'class' => 'btn btn-sm btn-success','title'=>'Pagar')) !!}</td>
                    @else
                    <td>{!! Form::button('<div class="fas fa-hand-holding-usd"></div> ', array('onclick' => 'return false', 'class' => 'btn btn-sm btn-outline-success disabled')) !!}</td>
                    @endif
                    
                    @if($value->situacion!='A')
                    <td>{!! Form::button('<div class="fas fa-minus"></div>', array('onclick' => 'modal
                        (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);',
                        'class' => 'btn btn-sm btn-danger', 'title'=>'Anular')) !!}</td>
                    @else
                    <td>{!! Form::button('<div class="fas fa-minus"></div>', array('onclick' => 'return false', 'class'
                        => 'disabled btn btn-sm btn-default')) !!}</td>
                    @endif
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
            {{-- @if($value['valor'] == 'Operaciones')
					@if($caja_sesion_id != '0' && !$current_user->isAdmin() && !$current_user->isSuperAdmin())
						<th @if( (int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
            @endif
            @else --}}
            <th @if( (int)$value['numero']> 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
            {{-- @endif --}}
            @endforeach
        </tr>
    </tfoot>
</table>
{!! $paginacion !!}
@endif