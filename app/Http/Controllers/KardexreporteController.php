<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Categoria;
use App\Sucursal;
use App\Marca;
use App\Caja;
use App\Person;
use App\Producto;
use App\Venta;
use App\Movimiento;
use App\Tipodocumento;
use App\Concepto;
use App\Detallemovcaja;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Support\Facades\Auth;
use Excel;
use App\Exports\KardexExport;

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

class KardexreporteController extends Controller
{
    protected $folderview      = 'app.kardexreporte';
    protected $tituloAdmin     = 'Reporte Kardex ';
    protected $tituloRegistrar = 'Reporte de Kardex';
    protected $tituloModificar = 'Modificar Caja';
    protected $tituloEliminar  = 'Eliminar Caja';
    protected $rutas           = array(
        'create' => 'kardexreporte.create',
        'index'  => 'kardexreporte.index',
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
        $entidad          = 'Kardexreporte';
        $title            = $this->tituloAdmin;
        $ruta             = $this->rutas;
        $user = Auth::user();
        $cboProductos = array('0' => 'Todos');
        $cboCategoria = array('0' => 'Todos');
        $cboSubcategoria = array('0' => 'Todos');
        $cboSucursal = Sucursal::orderBy("nombre")->pluck("nombre", "id")->all();

        return view($this->folderview . '.admin')->with(compact('cboProductos', 'cboSubcategoria', 'entidad', 'title', 'ruta', 'user', 'cboCategoria', 'cboSucursal'));
    }


    public function show($id)
    {
        //
    }


    public function excelKardex(Request $request)
    {
        setlocale(LC_TIME, 'spanish');
        return Excel::download(new KardexExport($request->input('fechainicio'), $request->input('fechafin'), $request->input('categoria'), $request->input('subcategoria'), $request->input('producto'), $request->input('sucursal')), 'kardex.xlsx');
        // $resultado        = Movimiento::join('detallemovimiento','detallemovimiento.movimiento_id','=','movimiento.id')
        //                     ->join('producto','producto.id','=','detallemovimiento.producto_id')
        //                     ->join('categoria','producto.categoria_id','=','categoria.id')
        //                     ->join('tipodocumento','tipodocumento.id','=','movimiento.tipodocumento_id')
        //                     ->join('marca','producto.marca_id','=','marca.id')
        //                     ->where('movimiento.fecha','>=',$request->input('fechainicio'))
        //                     ->where('movimiento.fecha','<=',$request->input('fechafin'))
        //                     ->whereNotIn('movimiento.situacion',['A']);
        // if($request->input('subcategoria')!="0"  && $request->input('subcategoria')){
        //     $resultado = $resultado->where('categoria.id','=',$request->input('subcategoria'));
        // }
        // if($request->input('categoria')!="0" && $request->input('categoria')){
        //     $resultado = $resultado->where('categoria.categoria_id','=',$request->input('categoria'));
        // }
        // if($request->input('producto')!="0" && $request->input('producto')){
        //     $resultado = $resultado->where('detallemovimiento.producto_id','=',$request->input('producto'))
        //                            ->orWhere('detallemovimiento.promocion_id','in',DB::raw('(select promocion_id from detallepromocion where detallemovimiento.producto_id=producto_id)'));
        // }
        // $resultado        = $resultado->select('producto.nombre as producto','detallemovimiento.cantidad','categoria.nombre as categoria','marca.nombre as marca','movimiento.fecha','movimiento.numero','tipodocumento.nombre as tipodocumento','tipodocumento.stock','detallemovimiento.preciocompra')
        //                     ->orderBy('movimiento.fecha','asc')
        //                     ->orderBy('movimiento.numero','asc');
        // $lista            = $resultado->get();
        // if (count($lista) > 0) {     
        //     Excel::create('ExcelKardex', function($excel) use($lista,$request) {
        //         $excel->sheet('Kardex', function($sheet) use($lista,$request) {
        //             if($request->input('categoria')!="0"){
        //                 $producto = Producto::where('categoria_id','=',$request->input('categoria'))->get();
        //                 $c=1;
        //                 foreach ($producto as $key1 => $value1) {
        //                     $sheet->mergeCells('A'.$c.':K'.$c);
        //                     $cabecera = array();
        //                     $cabecera[] = "REPORTE KARDEX DEL ".$request->input('fechainicio')." AL ".$request->input('fechafin');
        //                     $sheet->row($c,$cabecera);
        //                     $c=$c+1;
        //                     $sheet->mergeCells('A'.$c.':C'.$c);
        //                     $cabecera = array();
        //                     $cabecera[] = "Producto: ".$value1->nombre;
        //                     $sheet->row($c,$cabecera);
        //                     $c=$c+1;
        //                     $detalle = array();
        //                     $detalle[] = "FECHA";
        //                     $detalle[] = "NUMERO";
        //                     $detalle[] = "ENTRADAS";
        //                     $detalle[] = "";
        //                     $detalle[] = "";
        //                     $detalle[] = "SALIDAS";
        //                     $detalle[] = "";
        //                     $detalle[] = "";
        //                     $detalle[] = "SALDO";
        //                     $detalle[] = "";
        //                     $detalle[] = "";
        //                     $sheet->row($c,$detalle);
        //                     $sheet->mergeCells('A3:A4');
        //                     $sheet->mergeCells('B3:B4');
        //                     $sheet->mergeCells('C'.$c.':E'.$c);
        //                     $sheet->mergeCells('F'.$c.':H'.$c);
        //                     $sheet->mergeCells('I'.$c.':K'.$c);
        //                     $c=$c+1;
        //                     $detalle = array();
        //                     $detalle[] = "";
        //                     $detalle[] = "";
        //                     $detalle[] = "CANT.";
        //                     $detalle[] = "PRECIO";
        //                     $detalle[] = "SUBTOTAL";
        //                     $detalle[] = "CANT";
        //                     $detalle[] = "PRECIO";
        //                     $detalle[] = "SUBTOTAL";
        //                     $detalle[] = "CANT";
        //                     $detalle[] = "PRECIO";
        //                     $detalle[] = "SUBTOTAL";
        //                     $sheet->row($c,$detalle);
        //                     $c=$c+1;
        //                     $dat = Movimiento::join('detallemovimiento','detallemovimiento.movimiento_id','=','movimiento.id')
        //                                 ->join('tipodocumento','tipodocumento.id','=','movimiento.tipodocumento_id')
        //                                 ->where('detallemovimiento.producto_id','=',$value1->id)
        //                                 ->where('movimiento.fecha','<',$request->input('fechainicio'))
        //                                 ->whereNotIn('movimiento.situacion',['A'])
        //                                 ->select(DB::raw('sum(case when tipodocumento.stock=\'S\' then detallemovimiento.cantidad else detallemovimiento.cantidad*(-1) end) as stock'))
        //                                 ->groupBy('detallemovimiento.producto_id')
        //                                 ->first();
        //                     if(!is_null($dat)){
        //                         $inicial = $dat->stock;
        //                     }else{
        //                         $inicial = 0;
        //                     }
        //                     $dat = Movimiento::join('detallemovimiento','detallemovimiento.movimiento_id','=','movimiento.id')
        //                                 ->join('tipodocumento','tipodocumento.id','=','movimiento.tipodocumento_id')
        //                                 ->whereNotIn('movimiento.situacion',['A'])
        //                                 ->where('tipodocumento.id','=','movimiento.tipodocumento_id')
        //                                 ->where('detallemovimiento.producto_id','=',$value1->id)
        //                                 ->where('movimiento.fecha','<',$request->input('fechainicio'))
        //                                 ->select('detallemovimiento.preciocompra')
        //                                 ->orderBy('movimiento.fecha','desc')
        //                                 ->first();
        //                     if(!is_null($dat)){
        //                         $preciocompra = $dat->preciocompra;
        //                     }else{
        //                         $preciocompra = 0;
        //                     }
        //                     $detalle = array();
        //                     $detalle[] = "";
        //                     $detalle[] = "SALDO INICIAL";
        //                     $detalle[] = "";
        //                     $detalle[] = "";
        //                     $detalle[] = "";
        //                     $detalle[] = "";
        //                     $detalle[] = "";
        //                     $detalle[] = "";
        //                     $detalle[] = $inicial;
        //                     $detalle[] = $preciocompra;
        //                     $detalle[] = $inicial*$preciocompra;
        //                     $sheet->row($c,$detalle);
        //                     $c=$c+1;
        //                     $resultado        = Movimiento::join('detallemovimiento','detallemovimiento.movimiento_id','=','movimiento.id')
        //                                         ->join('producto','producto.id','=','detallemovimiento.producto_id')
        //                                         ->join('categoria','producto.categoria_id','=','categoria.id')
        //                                         ->join('tipodocumento','tipodocumento.id','=','movimiento.tipodocumento_id')
        //                                         ->join('marca','producto.marca_id','=','marca.id')
        //                                         ->where('movimiento.fecha','>=',$request->input('fechainicio'))
        //                                         ->where('detallemovimiento.producto_id','=',$value1->id)
        //                                         ->whereNotIn('movimiento.situacion',['A'])
        //                                         ->where('movimiento.fecha','<=',$request->input('fechafin'));
        //                     $resultado        = $resultado->select('producto.nombre as producto','detallemovimiento.cantidad','categoria.nombre as categoria','marca.nombre as marca','movimiento.fecha','movimiento.numero','tipodocumento.nombre as tipodocumento','tipodocumento.stock','detallemovimiento.preciocompra')
        //                                         ->orderBy('movimiento.fecha','asc')
        //                                         ->orderBy('movimiento.numero','asc');
        //                     $lista = $resultado->get();
        //                     foreach($lista as $key => $value){
        //                         $detalle = array();
        //                         $detalle[] = $value->fecha;
        //                         $detalle[] = $value->numero;
        //                         if($value->stock=="S"){
        //                             $detalle[] = $value->cantidad;
        //                             $detalle[] = $value->preciocompra;
        //                             $detalle[] = $value->cantidad*$value->preciocompra;
        //                             $detalle[] = "";
        //                             $detalle[] = "";
        //                             $detalle[] = "";
        //                             $div=($value->cantidad+$inicial);
        //                             $preciocompra = round(($value->cantidad*$value->preciocompra + $inicial*$preciocompra)/($div==0?1:$div),2);
        //                             $inicial = $inicial + $value->cantidad;
        //                         }else{
        //                             $detalle[] = "";
        //                             $detalle[] = "";
        //                             $detalle[] = "";
        //                             $detalle[] = $value->cantidad;
        //                             $detalle[] = $value->preciocompra;
        //                             $detalle[] = $value->cantidad*$value->preciocompra;
        //                             $inicial = $inicial - $value->cantidad;
        //                             if($preciocompra==0){
        //                                 $preciocompra=$value->preciocompra;
        //                             }
        //                         }
        //                         $detalle[] = $inicial;
        //                         $detalle[] = $preciocompra;
        //                         $detalle[] = $inicial*$preciocompra;
        //                         $sheet->row($c,$detalle);
        //                         $c=$c+1;
        //                     }
        //                     $c=$c+3;
        //                 }
        //             }else{
        //                 $c=1;

        //                 $sheet->mergeCells('A'.$c.':K'.$c);
        //                 $cabecera = array();
        //                 $cabecera[] = "REPORTE KARDEX DEL ".$request->input('fechainicio')." AL ".$request->input('fechafin');
        //                 $sheet->row($c,$cabecera);
        //                 $c=$c+1;
        //                 $sheet->mergeCells('A'.$c.':C'.$c);
        //                 $cabecera = array();
        //                 $cabecera[] = "Producto: ".$request->input('producto2');
        //                 $sheet->row($c,$cabecera);
        //                 $c=$c+1;
        //                 $detalle = array();
        //                 $detalle[] = "FECHA";
        //                 $detalle[] = "NUMERO";
        //                 $detalle[] = "ENTRADAS";
        //                 $detalle[] = "";
        //                 $detalle[] = "";
        //                 $detalle[] = "SALIDAS";
        //                 $detalle[] = "";
        //                 $detalle[] = "";
        //                 $detalle[] = "SALDO";
        //                 $detalle[] = "";
        //                 $detalle[] = "";
        //                 $sheet->row($c,$detalle);
        //                 $sheet->mergeCells('A3:A4');
        //                 $sheet->mergeCells('B3:B4');
        //                 $sheet->mergeCells('C'.$c.':E'.$c);
        //                 $sheet->mergeCells('F'.$c.':H'.$c);
        //                 $sheet->mergeCells('I'.$c.':K'.$c);
        //                 $c=$c+1;
        //                 $detalle = array();
        //                 $detalle[] = "";
        //                 $detalle[] = "";
        //                 $detalle[] = "CANT.";
        //                 $detalle[] = "PRECIO";
        //                 $detalle[] = "SUBTOTAL";
        //                 $detalle[] = "CANT";
        //                 $detalle[] = "PRECIO";
        //                 $detalle[] = "SUBTOTAL";
        //                 $detalle[] = "CANT";
        //                 $detalle[] = "PRECIO";
        //                 $detalle[] = "SUBTOTAL";
        //                 $sheet->row($c,$detalle);
        //                 $c=$c+1;
        //                 $dat = Movimiento::join('detallemovimiento','detallemovimiento.movimiento_id','=','movimiento.id')
        //                             ->join('tipodocumento','tipodocumento.id','=','movimiento.tipodocumento_id')
        //                             ->where('detallemovimiento.producto_id','=',$request->input('producto'))
        //                             ->whereNotIn('movimiento.situacion',['A'])
        //                             ->where('movimiento.fecha','<',$request->input('fechainicio'))
        //                             ->select(DB::raw('sum(case when tipodocumento.stock=\'S\' then detallemovimiento.cantidad else detallemovimiento.cantidad*(-1) end) as stock'))
        //                             ->groupBy('detallemovimiento.producto_id')
        //                             ->first();
        //                 if(!is_null($dat)){
        //                     $inicial = $dat->stock;
        //                 }else{
        //                     $inicial = 0;
        //                 }
        //                 $dat = Movimiento::join('detallemovimiento','detallemovimiento.movimiento_id','=','movimiento.id')
        //                             ->join('tipodocumento','tipodocumento.id','=','movimiento.tipodocumento_id')
        //                             ->where('tipodocumento.id','=','movimiento.tipodocumento_id')
        //                             ->where('detallemovimiento.producto_id','=',$request->input('producto'))
        //                             ->whereNotIn('movimiento.situacion',['A'])
        //                             ->where('movimiento.fecha','<',$request->input('fechainicio'))
        //                             ->select('detallemovimiento.preciocompra')
        //                             ->orderBy('movimiento.fecha','desc')
        //                             ->first();
        //                 if(!is_null($dat)){
        //                     $preciocompra = $dat->preciocompra;
        //                 }else{
        //                     $preciocompra = 0;
        //                 }
        //                 $detalle = array();
        //                 $detalle[] = "";
        //                 $detalle[] = "SALDO INICIAL";
        //                 $detalle[] = "";
        //                 $detalle[] = "";
        //                 $detalle[] = "";
        //                 $detalle[] = "";
        //                 $detalle[] = "";
        //                 $detalle[] = "";
        //                 $detalle[] = $inicial;
        //                 $detalle[] = $preciocompra;
        //                 $detalle[] = $inicial*$preciocompra;
        //                 $sheet->row($c,$detalle);
        //                 $c=$c+1;
        //                 foreach($lista as $key => $value){
        //                     $detalle = array();
        //                     $detalle[] = $value->fecha;
        //                     $detalle[] = $value->numero;
        //                     if($value->stock=="S"){
        //                         $detalle[] = $value->cantidad;
        //                         $detalle[] = $value->preciocompra;
        //                         $detalle[] = $value->cantidad*$value->preciocompra;
        //                         $detalle[] = "";
        //                         $detalle[] = "";
        //                         $detalle[] = "";
        //                         $div=$value->cantidad+$inicial;
        //                         $preciocompra = round(($value->cantidad*$value->preciocompra + $inicial*$preciocompra)/($div==0?1:$div),2);
        //                         $inicial = $inicial + $value->cantidad;
        //                     }else{
        //                         $detalle[] = "";
        //                         $detalle[] = "";
        //                         $detalle[] = "";
        //                         $detalle[] = $value->cantidad;
        //                         $detalle[] = $value->preciocompra;
        //                         $detalle[] = $value->cantidad*$value->preciocompra;
        //                         $inicial = $inicial - $value->cantidad;
        //                         if($preciocompra==0){
        //                             $preciocompra=$value->preciocompra;
        //                         }
        //                     }
        //                     $detalle[] = $inicial;
        //                     $detalle[] = $preciocompra;
        //                     $detalle[] = $inicial*$preciocompra;
        //                     $sheet->row($c,$detalle);
        //                     $c=$c+1;
        //                 }
        //             }
        //         });
        //     })->export('xls');                    
        // }
    }
}
