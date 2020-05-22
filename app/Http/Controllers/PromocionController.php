<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Producto;
use App\Promocion;
use App\Detallepromocion;
use App\Marca;
use App\Unidad;
use App\Categoria;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PromocionController extends Controller
{
    protected $folderview      = 'app.promocion';
    protected $tituloAdmin     = 'Promociones';
    protected $tituloRegistrar = 'Registrar Promocion';
    protected $tituloModificar = 'Modificar Promocion';
    protected $tituloEliminar  = 'Eliminar Promocion';
    protected $rutas           = array('create' => 'promocion.create', 
            'edit'   => 'promocion.edit', 
            'delete' => 'promocion.eliminar',
            'search' => 'promocion.buscar',
            'index'  => 'promocion.index',
        );


     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Mostrar el resultado de búsquedas
     * 
     * @return Response 
     */
    public function buscar(Request $request)
    {
        $pagina           = $request->input('page');
        $filas            = $request->input('filas');
        $entidad          = 'Promocion';
        $nombre           = Libreria::getParam($request->input('nombre'));
        $resultado        = Promocion::join('unidad','unidad.id','=','promocion.unidad_id')
                                ->join('categoria','categoria.id','=','promocion.categoria_id')
                                ->where('promocion.nombre','like','%'.strtoupper($nombre).'%');
        if($request->input('categoria')!=""){
            $resultado = $resultado->where('categoria.id','=',$request->input('categoria'));
        }
        if($request->input('marca')!=""){
            $resultado = $resultado->where('marca.id','=',$request->input('marca'));
        }
        $resultado = $resultado->orderBy('promocion.nombre','asc')
                            ->select('promocion.*','categoria.nombre as categoria2','unidad.nombre as unidad2');
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Producto', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Categoria', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Unidad', 'numero' => '1');
        $cabecera[]       = array('valor' => 'P. Venta', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '2');
        
        $titulo_modificar = $this->tituloModificar;
        $titulo_eliminar  = $this->tituloEliminar;
        $ruta             = $this->rutas;
        if (count($lista) > 0) {
            $clsLibreria     = new Libreria();
            $paramPaginacion = $clsLibreria->generarPaginacion($lista, $pagina, $filas, $entidad);
            $paginacion      = $paramPaginacion['cadenapaginacion'];
            $inicio          = $paramPaginacion['inicio'];
            $fin             = $paramPaginacion['fin'];
            $paginaactual    = $paramPaginacion['nuevapagina'];
            $lista           = $resultado->paginate($filas);
            $request->replace(array('page' => $paginaactual));
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'ruta'));
        }
        return view($this->folderview.'.list')->with(compact('lista', 'entidad'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entidad          = 'Promocion';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
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
        
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta', 'cboCategoria', 'cboMarca'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $entidad  = 'Producto';
        $promocion = null;
        $detalle = null;
        $cboCategoria = array();
        $categoria = Categoria::orderBy('nombre','asc')->get();
        foreach($categoria as $k=>$v){
            $cboCategoria = $cboCategoria + array($v->id => $v->nombre);
        }
        $cboUnidad = array();
        $unidad = Unidad::orderBy('nombre','asc')->get();
        foreach($unidad as $k=>$v){
            $cboUnidad = $cboUnidad + array($v->id => $v->nombre);
        }
        $formData = array('promocion.store');
        $formData = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('promocion', 'formData', 'entidad', 'boton', 'listar', 'cboUnidad', 'cboCategoria', 'detalle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $reglas     = array('nombre' => 'required|max:50',
                        
                            'precioventa' => 'required'
                        );
        $mensajes = array(
            'nombre.required'         => 'Debe ingresar un nombre',
        
            'precioventa.required'         => 'Debe ingresar un precio de venta',
            );
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $promocion = new Promocion();
            $promocion->nombre = $request->input('nombre');
            $promocion->unidad_id = $request->input('unidad_id');
            $promocion->categoria_id = $request->input('categoria_id');
            $promocion->precioventa = $request->input('precioventa');
            $promocion->save();

            $arr=explode(",",$request->input('listProducto'));
            for($c=0;$c<count($arr);$c++){
                $detalle = new Detallepromocion();
                $detalle->producto_id = $request->input('txtIdProducto'.$arr[$c]);
                $detalle->cantidad = $request->input('txtCant'.$arr[$c]);
                $detalle->promocion_id = $promocion->id;
                $detalle->save();
            }
        });
        return is_null($error) ? "OK" : $error;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $existe = Libreria::verificarExistencia($id, 'promocion');
        if ($existe !== true) {
            return $existe;
        }
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $promocion = Promocion::find($id);
        $cboCategoria = array();
        $categoria = Categoria::orderBy('nombre','asc')->get();
        foreach($categoria as $k=>$v){
            $cboCategoria = $cboCategoria + array($v->id => $v->nombre);
        }
        $cboUnidad = array();
        $unidad = Unidad::orderBy('nombre','asc')->get();
        foreach($unidad as $k=>$v){
            $cboUnidad = $cboUnidad + array($v->id => $v->nombre);
        }
        $detalle = Detallepromocion::where('promocion_id','=',$id)->get();
        $entidad  = 'Promocion';
        $formData = array('promocion.update', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Modificar';
        return view($this->folderview.'.mant')->with(compact('promocion', 'formData', 'entidad', 'boton', 'listar', 'cboCategoria',  'cboUnidad', 'detalle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'promocion');
        if ($existe !== true) {
            return $existe;
        }
        $reglas     = array('nombre' => 'required|max:50',
                            'precioventa' => 'required');
        $mensajes = array(
            'nombre.required'         => 'Debe ingresar un nombre',
            'precioventa.required'         => 'Debe ingresar un precio de venta',
            );
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $promocion = Promocion::find($id);
            $promocion->nombre = $request->input('nombre');
            $promocion->unidad_id = $request->input('unidad_id');
            $promocion->categoria_id = $request->input('categoria_id');
            $promocion->precioventa = $request->input('precioventa');
            $promocion->save();

            $detalle = Detallepromocion::where('promocion_id','=',$id)->get();
            foreach ($detalle as $key => $value) {
                $value->delete();
            }

            $arr=explode(",",$request->input('listProducto'));
            for($c=0;$c<count($arr);$c++){
                $detalle = new Detallepromocion();
                $detalle->producto_id = $request->input('txtIdProducto'.$arr[$c]);
                $detalle->cantidad = $request->input('txtCant'.$arr[$c]);
                $detalle->promocion_id = $promocion->id;
                $detalle->save();
            }
        });
        return is_null($error) ? "OK" : $error;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existe = Libreria::verificarExistencia($id, 'promocion');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $producto = Promocion::find($id);
            $producto->delete();
        });
        return is_null($error) ? "OK" : $error;
    }

    public function eliminar($id, $listarLuego)
    {
        $existe = Libreria::verificarExistencia($id, 'promocion');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Producto::find($id);
        $entidad  = 'Producto';
        $formData = array('route' => array('promocion.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function productoautocompletar($searching){
        $resultado        = Producto::where('producto.nombre','like','%'.strtoupper($searching).'%')->orderBy('nombre', 'ASC');
        $list      = $resultado->select('producto.*')->get();
        $data = array();
        foreach ($list as $key => $value) {
            $data[] = array(
                            'label' => trim($value->nombre),
                            'id'    => $value->id,
                            'value' => trim($value->nombre),
                        );
        }
        return json_encode($data);
    }
}