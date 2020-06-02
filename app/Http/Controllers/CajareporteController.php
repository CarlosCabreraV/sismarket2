<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Caja;
use App\Person;
use App\Movimiento;
use App\Tipodocumento;
use App\Concepto;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Support\Facades\Auth;
use Excel;
use App\Exports\CajaExport;
use App\Sucursal;

// class MTCPDF extends TCPDF {

//     // Page footer
//     public function Footer() {
//         // Position at 15 mm from bottom
//         $this->SetY(-15);
//         // Set font
//         $this->SetFont('helvetica', 'I', 8);
//         // Page number
//         $this->Cell(190, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
//     }
// }

class CajareporteController extends Controller
{
    protected $folderview      = 'app.cajareporte';
    protected $tituloAdmin     = 'Caja Reporte';
    protected $tituloRegistrar = 'Reporte de Caja';
    protected $tituloModificar = 'Modificar Caja';
    protected $tituloEliminar  = 'Eliminar Caja';
    protected $rutas           = array('create' => 'cajareporte.create', 
            'index'  => 'cajareporte.index',
        );

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $entidad          = 'Cajareporte';
        $title            = $this->tituloAdmin;
        $ruta             = $this->rutas;
        $sucursales = Sucursal::all();
        $user = Auth::user();
        return view($this->folderview.'.admin')->with(compact('sucursales','entidad', 'title', 'ruta', 'user'));
    }


    public function show($id)
    {
        //
    }


    public function excelCaja(Request $request){
        setlocale(LC_TIME, 'spanish');
        return Excel::download(new CajaExport($request->input('fechainicio'),$request->input('fechafin'), $request->input('caja_id')), 'caja.xlsx');
        // $resultado        = Movimiento::where('movimiento.tipomovimiento_id', '=', 4)
        //                     ->where('movimiento.concepto_id','=',1)
        //                     ->where('movimiento.fecha','>=',$request->input('fechainicio'))
        //                     ->where('movimiento.fecha','<=',$request->input('fechafin'));
        // $resultado        = $resultado->select('movimiento.*');
        // $lista1            = $resultado->get();
        // if (count($lista1) > 0) {     
        //     Excel::create('ExcelCaja', function($excel) use($lista1,$request) {
        //         $excel->sheet('Caja', function($sheet) use($lista1,$request) {
        //             $c=1;
        //             foreach($lista1 as $key1 => $value1){
        //                 $sheet->mergeCells('A'.$c.':J'.$c);
        //                 $cabecera = array();
        //                 $cabecera[] = "REPORTE DE CAJA DEL ".$value1->created_at;
        //                 $sheet->row($c,$cabecera);
        //                 $c=$c+1;
        //                 $detalle = array();
        //                 $detalle[] = "FECHA";
        //                 $detalle[] = "NRO";
        //                 $detalle[] = "CONCEPTO";
        //                 $detalle[] = "PERSONA";
        //                 $detalle[] = "INGRESO";
        //                 $detalle[] = "EGRESO";
        //                 $detalle[] = "TARJETA";
        //                 $detalle[] = "COMENTARIO";
        //                 $detalle[] = "USUARIO";
        //                 $detalle[] = "SITUACION";
        //                 $detalle[] = "HORA";
        //                 $sheet->row($c,$detalle);
        //                 $c=$c+1;
        //                 $cierre = Movimiento::where('movimiento.concepto_id','=',2)
        //                             ->where('movimiento.id','>',$value1->id)
        //                             ->orderBy('movimiento.id','asc')->first();
        //                 if(!is_null($cierre)){
        //                     $idcierre=$cierre->id;
        //                 }else{
        //                     $idcierre="9999999999";
        //                 }
        //                 $resultado        = Movimiento::leftjoin('person as paciente', 'paciente.id', '=', 'movimiento.persona_id')
        //                                             ->join('person as responsable', 'responsable.id', '=', 'movimiento.responsable_id')
        //                                             ->join('concepto','concepto.id','=','movimiento.concepto_id')
        //                                             ->leftjoin('movimiento as m2','movimiento.id','=','m2.movimiento_id')
        //                                             ->whereNull('movimiento.cajaapertura_id')
        //                                             ->where('movimiento.id', '>=', $value1->id)
        //                                             ->where('movimiento.id','<=',$idcierre);
        //                         $resultado        = $resultado->select('movimiento.*','m2.situacion as situacion2',DB::raw('CONCAT(paciente.apellidopaterno," ",paciente.apellidomaterno," ",paciente.nombres) as cliente'),DB::raw('responsable.nombres as responsable'))->orderBy('movimiento.id', 'desc');
        //                 $lista            = $resultado->get();
        //                 $ingreso=0;$egreso=0;$garantia=0;$efectivo=0;$visa=0;$master=0;
        //                 foreach ($lista as $key => $value){
        //                     $detalle = array();
        //                     $detalle[] = $value->fecha;
        //                     $detalle[] = $value->numero;
        //                     $detalle[] = $value->concepto->nombre;
        //                     $detalle[] = $value->cliente;
        //                     if($value->concepto->tipo=="I"){
        //                         $detalle[] = number_format($value->total,2,'.','');
        //                         $detalle[] = number_format(0,2,'.','');
        //                     }else{
        //                         $detalle[] = number_format(0,2,'.','');
        //                         $detalle[] = number_format($value->total,2,'.','');
        //                     }
        //                     if($value->concepto_id<>2 && $value->situacion<>'A'){
        //                         if($value->concepto->tipo=="I"){
        //                             $ingreso = $ingreso + $value->total;    
        //                             $visa = $visa + $value->tarjeta;
        //                             $efectivo = $efectivo + $value->totalpagado;
        //                             //$master = $master + $value->total;
        //                         }else{
        //                             $egreso  = $egreso + $value->total;
        //                         }
        //                     }
        //                     if($value->tarjeta!=""){
        //                         $detalle[] = $value->tarjeta;
        //                     }else{
        //                         $detalle[] = '-';
        //                     }
        //                     $detalle[] = $value->comentario;    
        //                     $detalle[] = $value->responsable;
        //                     $color="";
        //                     $titulo="Ok";
        //                     if($value->conceptopago_id==7 || $value->conceptopago_id==6){
        //                         if($value->conceptopago_id==7){//TRANSFERENCIA EGRESO CAJA QUE ENVIA
        //                             if($value->situacion2=='P'){//PENDIENTE
        //                                 $color='background:rgba(255,235,59,0.76)';
        //                                 $titulo="Pendiente";
        //                             }elseif($value->situacion2=='R'){
        //                                 $color='background:rgba(215,57,37,0.50)';
        //                                 $titulo="Rechazado";
        //                             }elseif($value->situacion2=='C'){
        //                                 $color='background:rgba(10,215,37,0.50)';
        //                                 $titulo="Aceptado";
        //                             }elseif($value->situacion2=='A'){
        //                                 $color='background:rgba(215,57,37,0.50)';
        //                                 $titulo='Anulado'; 
        //                             }    
        //                         }else{
        //                             if($value->situacion=='P'){
        //                                 $color='background:rgba(255,235,59,0.76)';
        //                                 $titulo="Pendiente";
        //                             }elseif($value->situacion=='R'){
        //                                 $color='background:rgba(215,57,37,0.50)';
        //                                 $titulo="Rechazado";
        //                             }elseif($value->situacion=="C"){
        //                                 $color='background:rgba(10,215,37,0.50)';
        //                                 $titulo="Aceptado";
        //                             }elseif($value->situacion=='A'){
        //                                 $color='background:rgba(215,57,37,0.50)';
        //                                 $titulo='Anulado'; 
        //                             } 
        //                         }
        //                     }else{
        //                         $color=($value->situacion=='A')?'background:rgba(215,57,37,0.50)':'';
        //                         $titulo=($value->situacion=='A')?'Anulado':'Ok';            
        //                     }
        //                     $detalle[] = $titulo;
        //                     $detalle[] = $value->created_at;
        //                     $sheet->row($c,$detalle);
        //                     $c=$c+1;
        //                 }
        //                 $detalle = array();
        //                 $detalle[] = "RESUMEN DE CAJA";
        //                 $detalle[] = "INGRESOS";
        //                 $detalle[] = $ingreso;
        //                 $detalle[] = "Efectivo";
        //                 $detalle[] = $efectivo;
        //                 $detalle[] = "Visa";
        //                 $detalle[] = $visa;
        //                 $detalle[] = "EGRESOS";
        //                 $detalle[] = $egreso;
        //                 $detalle[] = "SALDO";
        //                 $detalle[] = number_format($ingreso - $egreso,2,'.','');
        //                 $sheet->row($c,$detalle);
        //                 $c=$c+1;
        //                 $c=$c+1;
        //             }
        //         });
        //     })->export('xls');                    
        // }
    }

}