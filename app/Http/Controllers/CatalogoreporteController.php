<?php

namespace App\Http\Controllers;

use App\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\CatalogoExport;
use Excel;

class CatalogoreporteController extends Controller
{
    protected $folderview      = 'app.catalogoreporte';
    protected $tituloAdmin     = 'Catalogo Reporte';
    protected $tituloRegistrar = 'Reporte de catalogo de productos';
    protected $rutas           = array('create' => 'catalogoreporte.create', 
            'index'  => 'catalogoreporte.index',
            'store'  => 'catalogoreporte.store',
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
        $entidad          = 'Catalogoreporte';
        $title            = $this->tituloAdmin;
        $ruta             = $this->rutas;
        $sucursales = Sucursal::pluck('nombre','id')->all();
        $user = Auth::user();
        return view($this->folderview.'.admin')->with(compact('sucursales','entidad', 'title', 'ruta', 'user'));
    }


    public function store(Request $request)
    {
        return $request;
    }


    public function excelCatalogo(Request $request){
        setlocale(LC_TIME, 'spanish');
        return Excel::download(new CatalogoExport($request->input('sucursal_id'), $request->input('categoria'), $request->input('subcategoria'), $request->input('marca'), $request->input('unidad'), $request->input('precioventa'),$request->input('preciocompra'), $request->input('stock') ,$request->input('codigo'),$request->input('descripcion'),$request->input('abreviatura'),$request->input('precioventaespecial'),$request->input('precioventaespecial2'),$request->input('afectoigv'),$request->input('soloconstock'),$request->input('ganancia')), 'catalogoproductos.xlsx');
    }
}
