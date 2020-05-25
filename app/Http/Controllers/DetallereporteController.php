<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Categoria;
use App\Marca;
use App\Caja;
use App\Person;
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

class MTCPDF extends TCPDF {

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(190, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

class DetallereporteController extends Controller
{
    protected $folderview      = 'app.detallereporte';
    protected $tituloAdmin     = 'Reporte Detalle Venta ';
    protected $tituloRegistrar = 'Reporte de Ventas';
    protected $tituloModificar = 'Modificar Caja';
    protected $tituloEliminar  = 'Eliminar Caja';
    protected $rutas           = array('create' => 'detallereporte.create', 
            'index'  => 'detallereporte.index',
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
        $entidad          = 'Detallereporte';
        $title            = $this->tituloAdmin;
        $ruta             = $this->rutas;
        $user = Auth::user();
        $cboCategoria = array('' => 'Todos');
        $categoria = Categoria::orderBy('nombre','asc')->get();
        foreach($categoria as $k=>$v){
            $cboCategoria = $cboCategoria + array($v->id => $v->nombre);
        }
        $cboMarca = array('' => 'Todos');
        $marca = Marca::orderBy('nombre','asc')->get();
        foreach($marca as $k=>$v){
            $cboMarca = $cboMarca + array($v->id => $v->nombre);
        }
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'ruta', 'user','cboCategoria','cboMarca'));
    }


    public function show($id)
    {
        //
    }


    public function excelDetalle(Request $request){
        setlocale(LC_TIME, 'spanish');
        $guia = $request->input('guia');
        $resultado        = Movimiento::where('movimiento.tipomovimiento_id', '=', 2)
                            ->join('detallemovimiento','detallemovimiento.movimiento_id','=','movimiento.id')
                            ->join('producto','producto.id','=','detallemovimiento.producto_id')
                            ->join('categoria','producto.categoria_id','=','categoria.id')
                            ->join('marca','producto.marca_id','=','marca.id')
                            ->where('movimiento.fecha','>=',$request->input('fechainicio'))
                            ->whereNotIn('movimiento.situacion',['A'])
                            ->where('movimiento.fecha','<=',$request->input('fechafin'));
        if($request->input('marca')!=""){
            $resultado = $resultado->where('producto.marca_id','=',$request->input('marca'));
        }
        if($request->input('categoria')!=""){
            $resultado = $resultado->where('producto.categoria_id','=',$request->input('categoria'));
        }
        $resultado        = $resultado->select('producto.nombre as producto',DB::raw('sum(detallemovimiento.cantidad) as cantidad'),'categoria.nombre as categoria','marca.nombre as marca','detallemovimiento.precioventa')
                            ->groupBy('producto.nombre')
                            ->groupBy('categoria.nombre')
                            ->groupBy('detallemovimiento.precioventa');
        $lista            = $resultado->get();
        if (count($lista) > 0) {     
            Excel::create('ExcelDetalle', function($excel) use($lista,$request) {
                $excel->sheet('Detalle', function($sheet) use($lista,$request) {
                    $c=1;
                    $sheet->mergeCells('A'.$c.':J'.$c);
                    $cabecera = array();
                    $cabecera[] = "REPORTE DETALLE DEL ".$request->input('fechainicio')." AL ".$request->input('fechafin');
                    $sheet->row($c,$cabecera);
                    $c=$c+1;
                    $detalle = array();
                    $detalle[] = "PRODUCTO";
                    $detalle[] = "CATEGORIA";
                    $detalle[] = "MARCA";
                    $detalle[] = "CANTIDAD";
                    $detalle[] = "P. VENTA";
                    $detalle[] = "SUBTOTAL";
                    $sheet->row($c,$detalle);
                    $c=$c+1;$total=0;
                    foreach($lista as $key => $value){
                        $detalle = array();
                        $detalle[] = $value->producto;
                        $detalle[] = $value->categoria;
                        $detalle[] = $value->marca;
                        $detalle[] = $value->cantidad;
                        $detalle[] = $value->precioventa;
                        $detalle[] = $value->cantidad*$value->precioventa;
                        $total = $total + $value->cantidad*$value->precioventa;
                        $sheet->row($c,$detalle);
                        $c=$c+1;
                    }
                    $detalle = array();
                    $detalle[] = "";
                    $detalle[] = "";
                    $detalle[] = "";
                    $detalle[] = "";
                    $detalle[] = "TOTAL";
                    $detalle[] = $total;
                    $sheet->row($c,$detalle);
                    
                });
            })->export('xls');                    
        }
    }

}