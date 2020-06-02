<?php 
use App\Movimiento;
use Illuminate\Support\Facades\DB;
?>
@if (count($lista1)>0)
    @foreach ($lista1 as $value1)
    <table>
        <thead>
        <tr>
            <th colspan="11" style="text-align: center"><b>REPORTE DE CAJA DEL {{ $value1->created_at }}</b></th>
        </tr>
        <tr>
            <th style="width: 20px"><b>FECHA</b></th>
            <th style="width: 20px"><b>NRO</b></th>
            <th style="width: 20px"><b>CONCEPTO</b></th>
            <th style="width: 20px"><b>PERSONA</b></th>
            <th style="width: 20px"><b>INGRESO</b></th>
            <th style="width: 20px"><b>EGRESO</b></th>
            <th style="width: 20px"><b>TARJETA</b></th>
            <th style="width: 50px"><b>COMENTARIO</b></th>
            <th style="width: 20px"><b>USUARIO</b></th>
            <th style="width: 20px"><b>SITUACION</b></th>
            <th style="width: 20px"><b>HORA</b></th>
        </tr>
        </thead>
        <tbody>
        @php
            $cierre = Movimiento::where('movimiento.concepto_id','=',2)
                        ->where('movimiento.id','>',$value1->id);
                        if($caja_id && $caja_id != '0'){
                            $cierre = $cierre->where('movimiento.caja_id',$caja_id);
                        }else{
                            $cierre = $cierre->where('movimiento.caja_id',$value1->caja_id);
                        }
                        $cierre = $cierre->orderBy('movimiento.id','asc')->first();
            if(!is_null($cierre)){
                $idcierre=$cierre->id;
            }else{
                $idcierre="9999999999";
            }
            $resultado        = Movimiento::leftjoin('person as paciente', 'paciente.id', '=', 'movimiento.persona_id')
                                        ->join('person as responsable', 'responsable.id', '=', 'movimiento.responsable_id')
                                        ->join('concepto','concepto.id','=','movimiento.concepto_id')
                                        ->leftjoin('movimiento as m2','movimiento.id','=','m2.movimiento_id')
                                        ->whereNull('movimiento.cajaapertura_id')
                                        ->where('movimiento.id', '>=', $value1->id)
                                        ->where('movimiento.id','<=',$idcierre);
                        if($caja_id && $caja_id != '0'){
                            $resultado = $resultado->where('movimiento.caja_id',$caja_id);
                        }else{
                            $resultado = $resultado->where('movimiento.caja_id',$value1->caja_id);
                        }
                    $resultado        = $resultado->select('movimiento.*','m2.situacion as situacion2',DB::raw('CONCAT(paciente.apellidopaterno," ",paciente.apellidomaterno," ",paciente.nombres) as cliente'),DB::raw('responsable.nombres as responsable'))->orderBy('movimiento.id', 'desc');
            $lista            = $resultado->get();
            $ingreso=0;$egreso=0;$garantia=0;$efectivo=0;$visa=0;$master=0;
        @endphp
        @foreach($lista as $key => $value)
            <tr>
                <td>{{ $value->fecha }}</td>
                <td>{{ $value->numero }}</td>
                <td>{{ $value->concepto->nombre }}</td>
                <td>{{ $value->cliente }}</td>
                @if ($value->concepto->tipo=="I")
                    <td>{{ number_format($value->total,2,'.','') }}</td>
                    <td>{{ number_format(0,2,'.','') }}</td>
                @else
                    <td>{{ number_format(0,2,'.','') }}</td>
                    <td>{{ number_format($value->total,2,'.','') }}</td>
                @endif
                @php
                    if($value->concepto_id<>2 && $value->situacion<>'A'){
                        if($value->concepto->tipo=="I"){
                            $ingreso = $ingreso + $value->total;    
                            $visa = $visa + $value->tarjeta;
                            $efectivo = $efectivo + $value->totalpagado;
                            //$master = $master + $value->total;
                        }else{
                            $egreso  = $egreso + $value->total;
                        }
                    }   
                @endphp
                @if ($value->concepto->tipo=="I")
                    <td>{{ $value->tarjeta }}</td>
                @else
                    <td> - </td>
                @endif
                <td>{{ $value->comentario }}</td>
                <td>{{ $value->responsable }}</td>
                @php
                    $color="";
                    $titulo="Ok";
                    if($value->conceptopago_id==7 || $value->conceptopago_id==6){
                        if($value->conceptopago_id==7){//TRANSFERENCIA EGRESO CAJA QUE ENVIA
                            if($value->situacion2=='P'){//PENDIENTE
                                $color='bgcolor="yellow"';
                                $titulo="Pendiente";
                            }elseif($value->situacion2=='R'){
                                $color='bgcolor="red"';
                                $titulo="Rechazado";
                            }elseif($value->situacion2=='C'){
                                $color='bgcolor="green"';
                                $titulo="Aceptado";
                            }elseif($value->situacion2=='A'){
                                $color='bgcolor="orange"';
                                $titulo='Anulado'; 
                            }    
                        }else{
                            if($value->situacion=='P'){
                                $color='bgcolor="yellow"';
                                $titulo="Pendiente";
                            }elseif($value->situacion=='R'){
                                $color='bgcolor="red"';
                                $titulo="Rechazado";
                            }elseif($value->situacion=="C"){
                                $color='bgcolor="green"';
                                $titulo="Aceptado";
                            }elseif($value->situacion=='A'){
                                $color='bgcolor="orange"';
                                $titulo='Anulado'; 
                            } 
                        }
                    }else{
                        $color=($value->situacion=='A')?'bgcolor:rgba(215,57,37,0.50)':'';
                        $titulo=($value->situacion=='A')?'Anulado':'Ok';            
                    }
                @endphp
                <td>{{ $titulo }}</td>
                <td>{{ $value->created_at }}</td>
            </tr>
        @endforeach
        <tr>
            <td style="background:red"><b>RESUMEN DE CAJA</b></td>
            <td><b>INGRESOS</b></td>
            <td><b>{{ $ingreso }}</b></td>
            <td><b>EFECTIVO</b></td>
            <td><b>{{ $efectivo }}</b></td>
            <td><b>VISA</b></td>
            <td><b>{{ $visa }}</b></td>
            <td><b>EGRESOS</b></td>
            <td><b>{{ $egreso }}</b></td>
            <td><b>SALDO</b></td>
            <td><b>{{ number_format($ingreso - $egreso,2,'.','') }}</b></td>
        </tr>
        </tbody>
    </table>
@endforeach
@else
    <table>
        <tr>
            <td>
                SIN RESULTADOS
            </td>
        </tr>
    </table>
@endif


