<?php 
$user = Auth::user(); 
?>
@if($caja->estado == 'ABIERTA' && ($caja->user_id != $user->id) && !$user->isAdmin() && !$user->isSuperAdmin())
    <!--ALERTA CAJA APERTURADA-->
    <div class="alert alert-warning ">
        <h5><i class="icon fas fa-exclamation-triangle"></i> Caja aperturada!</h5>
        La caja ha sido aperturada por otro usuario , vuelve a iniciar sesión y elige otra.
    </div>
    <!--ALERTA CAJA APERTURADA-->
@elseif((!$user->isSuperAdmin() && !$user->isAdmin() || ($caja->estado == 'CERRADA' && !$user->isAdmin() && !$user->isSuperAdmin())))
    <!--OPCIONES CAJA-->
    <div class="row">
        @if($estado_caja == 'ABIERTA')
            <div class="col-md-2 col-lg-2">
                {!! Form::button('<i class="fas fa-plus"></i> Apertura', array('class' => 'btn btn-block btn-outline-info btn-sm', 'disabled' => 'true', 'id' => 'btnApertura', 'onclick' => 'modalCaja (\''.URL::route($ruta["apertura"], array('listar'=>'SI')).'\', \''.$titulo_apertura.'\', this);')) !!}
            </div>
            <div class="col-md-2 col-lg-2">
                {!! Form::button('<i class="fas fa-hand-holding-usd"></i> Nuevo', array('class' => 'btn btn-block btn-outline-success btn-sm', 'id' => 'btnCerrar', 'onclick' => 'modalCaja (\''.URL::route($ruta["create"], array('listar'=>'SI')).'\', \''.$titulo_registrar.'\', this);')) !!}
            </div>
            <div class="col-md-2 col-lg-2">
                {!! Form::button('<i class="fas fa-trash-alt"></i> Cierre', array('class' => 'btn btn-block btn-outline-danger btn-sm', 'id' => 'btnCerrar', 'onclick' => 'modalCaja (\''.URL::route($ruta["cierre"], array('listar'=>'SI')).'\', \''.$titulo_cierre.'\', this);')) !!}
            </div>
        @elseif($estado_caja == 'CERRADA')
            <div class="col-md-2 col-lg-2">
                {!! Form::button('<i class="fas fa-plus"></i> Apertura', array('class' => 'btn btn-block btn-outline-info btn-sm', 'id' => 'btnApertura', 'onclick' => 'modalCaja (\''.URL::route($ruta["apertura"], array('listar'=>'SI')).'\', \''.$titulo_apertura.'\', this);')) !!}
            </div>
            <div class="col-md-2 col-lg-2">
                {!! Form::button('<i class="fas fa-hand-holding-usd"></i> Nuevo', array('class' => 'btn btn-block btn-outline-success btn-sm', 'disabled' => 'true', 'id' => 'btnCerrar', 'onclick' => 'modalCaja (\''.URL::route($ruta["create"], array('listar'=>'SI')).'\', \''.$titulo_registrar.'\', this);')) !!}
            </div>
            <div class="col-md-2 col-lg-2">
                {!! Form::button('<i class="fas fa-trash-alt"></i> Cierre', array('class' => 'btn btn-block btn-outline-danger btn-sm' , 'disabled' => 'true', 'id' => 'btnCerrar', 'onclick' => 'modalCaja (\''.URL::route($ruta["cierre"], array('listar'=>'SI')).'\', \''.$titulo_cierre.'\', this);')) !!}
            </div>
        @else
            <div class="col-md-2 col-lg-2">
                {!! Form::button('<i class="fas fa-plus"></i> Apertura', array('class' => 'btn btn-block btn-outline-info btn-sm', 'disabled' => 'true', 'id' => 'btnApertura', 'onclick' => 'modalCaja (\''.URL::route($ruta["apertura"], array('listar'=>'SI')).'\', \''.$titulo_apertura.'\', this);')) !!}
            </div>
            <div class="col-md-2 col-lg-2">
                {!! Form::button('<i class="fas fa-hand-holding-usd"></i> Nuevo', array('class' => 'btn btn-block btn-outline-success btn-sm', 'id' => 'btnCerrar', 'onclick' => 'modalCaja (\''.URL::route($ruta["create"], array('listar'=>'SI')).'\', \''.$titulo_registrar.'\', this);')) !!}
            </div>
            <div class="col-md-2 col-lg-2">
                {!! Form::button('<i class="fas fa-trash-alt"></i> Cierre', array('class' => 'btn btn-block btn-outline-danger btn-sm', 'id' => 'btnCerrar', 'onclick' => 'modalCaja (\''.URL::route($ruta["cierre"], array('listar'=>'SI')).'\', \''.$titulo_cierre.'\', this);')) !!}
            </div>
        @endif
            <div class="col-md-2 col-lg-2">
                {!! Form::button('<i class="fas fa-print"></i> Imprimir A4', array('class' => 'btn btn-block btn-outline-warning btn-sm', 'id' => 'btnDetalle', 'onclick' => 'imprimir();')) !!}   
            </div>
            <div class="col-md-2 col-lg-2">
				<td><a target="_blank" href="{{route('caja.verpdfcierre' , ['caja_id'=> $caja->id])}}"><button class="btn btn-block btn-outline-warning btn-sm"><i class="fas fa-print"></i> Imprimir Ticket</button></a>
                {{-- {!! Form::button('<i class="fas fa-print"></i> Imprimir Ticket', array('class' => 'btn btn-block btn-outline-warning btn-sm', 'id' => 'btnDetalle', 'onclick' => 'imprimir();')) !!}    --}}
            </div>
    </div>
    <!--OPCIONES CAJA-->
@endif

@if($user->isAdmin() || $user->isSuperAdmin() || ($caja->user_id == $user->id) || ($caja->estado=='CERRADA') )
<?php 
$saldo = number_format($ingreso - $egreso,2,'.','');
?>
{!! Form::hidden('saldo', $saldo, array('id' => 'saldo')) !!}   
<hr />
@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
{!! $paginacion  !!}
<div class="table-responsive">
<table id="example1" class="table table-sm text-center table-striped  table-hover">

	<thead>
		<tr>
			@foreach($cabecera as $key => $value)
				<th class="text-center" @if((int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		<?php
		$contador = $inicio + 1;
		?>
		@foreach ($lista as $key => $value)
        <?php
        $color="";
        $color2="";
        $titulo="";
        $color=($value->situacion=='A')?'background:rgba(215,57,37,0.50)':'';
        $titulo=($value->situacion=='A')?'Anulado':'';            
        if($value->concepto->tipo=='I'){
            $color2='color:green;font-weight: bold;';
        }else{
            $color2='color:red;font-weight: bold;';
        }
        ?>
		<tr style="{{ $color }}" title="{{ $titulo }}">
            <td>{{ date('d/m/Y',strtotime($value->fecha)).' '.date('H:i:s',strtotime($value->created_at)) }}</td>
            <td>{{ $value->numero }}</td>
            <td>{{ $value->concepto->nombre }}</td>
            <td>{{ $value->cliente}}</td>
            @if(!is_null($value->situacion) && $value->situacion<>'R' && !is_null($value->situacion2) && $value->situacion2<>'R')
                @if($value->concepto_id>0 && !is_null($value->concepto_id) && $value->concepto->tipo=="I")
                    <td align="center" style='{{ $color2 }}'>{{ number_format($value->total,2,'.','') }}</td>
                    <td align="center">0.00</td>
                @else
                    <td align="center">0.00</td>
                    <td align="center" style='{{ $color2 }}'>{{ number_format($value->total,2,'.','') }}</td>
                @endif
            @else
                @if($value->concepto->tipo=="I")
                    <td align="center" style='{{ $color2 }}'>{{ number_format($value->total,2,'.','') }}</td>
                    <td align="center">0.00</td>
                @else
                    <td align="center">0.00</td>
                    <td align="center" style='{{ $color2 }}'>{{ number_format($value->total,2,'.','') }}</td>
                @endif
            @endif 
            <td>{{ $value->comentario }}</td>
            <td>{{ $value->responsable }}</td>
            @if($estado_caja != 'CERRADA' && $value->situacion<>'A' && $value->concepto_id<>3 && $value->concepto_id<>1 && !$user->isAdmin())
                <td align="center">{!! Form::button('<div class="fas fa-trash-alt m-1"></div> Eliminar', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_anular.'\', this);', 'class' => 'btn btn-xs btn-outline-danger', 'title' => 'Anular')) !!}</td>
            @else                
                <td align="center"> - </td>
            @endif
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
{!! $paginacion  !!}

<div class="row mt-3 mb-3">
    <div class="col-lg-4 col-md-4">
        <div class="info-box mb-3 bg-success">
            <span class="info-box-icon"><i class="fas fa-arrow-up"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Ingresos</span>
              <span class="info-box-number">{{ number_format($ingreso,2,'.','') }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="info-box mb-3 bg-danger">
            <span class="info-box-icon"><i class="fas fa-arrow-down"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Egresos</span>
              <span class="info-box-number">{{ number_format($egreso,2,'.','') }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="info-box mb-3 bg-info">
            <span class="info-box-icon"><i class="fas fa-money-check-alt"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Saldo</span>
              <span class="info-box-number">{{ number_format($ingreso - $egreso,2,'.','') }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
    </div>
</div>

</div>
@endif
@endif