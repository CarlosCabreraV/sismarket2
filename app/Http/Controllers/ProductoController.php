<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Producto;
use App\Detalleproducto;
use App\Marca;
use App\Unidad;
use App\Categoria;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Excel;
use File;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    protected $folderview      = 'app.producto';
    protected $tituloAdmin     = 'Productos';
    protected $tituloRegistrar = 'Registrar Producto';
    protected $tituloModificar = 'Modificar Producto';
    protected $tituloEliminar  = 'Eliminar Producto';
    protected $rutas           = array('create' => 'producto.create', 
            'edit'   => 'producto.edit', 
            'delete' => 'producto.eliminar',
            'search' => 'producto.buscar',
            'index'  => 'producto.index',
            'presentacion'   => 'producto.presentacion', 
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
        $entidad          = 'Producto';
        $nombre           = Libreria::getParam($request->input('nombre'));
        $codigobarra      = Libreria::getParam($request->input('codigobarra'));
        $resultado        = Producto::join('marca','marca.id','=','producto.marca_id')
                                ->join('unidad','unidad.id','=','producto.unidad_id')
                                ->join('categoria','categoria.id','=','producto.categoria_id')
                                ->leftjoin('stockproducto','stockproducto.producto_id','=','producto.id')
                                ->where('producto.nombre','like','%'.strtoupper($nombre).'%')
                                ->where('producto.codigobarra','like','%'.trim($codigobarra).'%');
        if($request->input('categoria')!=""){
            $resultado = $resultado->where('categoria.id','=',$request->input('categoria'));
        }
        if($request->input('marca')!=""){
            $resultado = $resultado->where('marca.id','=',$request->input('marca'));
        }
        if($request->input('precio')=="S"){
            $resultado = $resultado->where('producto.precioventa','=',0);
        }
        $resultado = $resultado->orderBy('producto.nombre','asc')
                            ->select('producto.*','categoria.nombre as categoria2','marca.nombre as marca2','unidad.nombre as unidad2','stockproducto.cantidad as stock');
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Producto', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Categoria', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Marca', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Unidad', 'numero' => '1');
        $cabecera[]       = array('valor' => 'P. Compra', 'numero' => '1');
        $cabecera[]       = array('valor' => 'P. Venta', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Stock', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '4');
        
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
        $entidad          = 'Producto';
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
        $producto = null;
        $cboCategoria = array();
        $categoria = Categoria::orderBy('nombre','asc')->get();
        foreach($categoria as $k=>$v){
            $cboCategoria = $cboCategoria + array($v->id => $v->nombre);
        }
        $cboMarca = array();
        $marca = Marca::orderBy('nombre','asc')->get();
        foreach($marca as $k=>$v){
            $cboMarca = $cboMarca + array($v->id => $v->nombre);
        }
        $cboUnidad = array();
        $unidad = Unidad::orderBy('nombre','asc')->get();
        foreach($unidad as $k=>$v){
            $cboUnidad = $cboUnidad + array($v->id => $v->nombre);
        }
        $formData = array('producto.store');
        $formData = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('producto', 'formData', 'entidad', 'boton', 'listar', 'cboUnidad', 'cboMarca', 'cboCategoria'));
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
                            'precioventa' => 'required');
        $mensajes = array(
            'nombre.required'         => 'Debe ingresar un nombre',
            'precioventa.required'         => 'Debe ingresar un precio de venta',
            );
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $dat=array();
        $error = DB::transaction(function() use($request, &$dat){
            $producto = new Producto();
            $producto->codigobarra = "";
            $producto->nombre = $request->input('nombre');
            $producto->abreviatura = $request->input('abreviatura');
            $producto->unidad_id = $request->input('unidad_id');
            $producto->marca_id = $request->input('marca_id');
            $producto->categoria_id = $request->input('categoria_id');
            $producto->preciocompra =  Libreria::getParam($request->input('preciocompra'), '0.00');
            $producto->precioventa = $request->input('precioventa');
            $producto->precioventaespecial = $request->input('precioventaespecial');
            $producto->ganancia =  Libreria::getParam($request->input('ganancia'), '0.00');
            $producto->stockminimo = Libreria::getParam($request->input('stockminimo'), '0.00');
            $producto->consumo = $request->input('consumo');
            $producto->igv = $request->input('igv');
            $producto->save();
            $dat[0]=array("respuesta"=>"OK","producto_id"=>$producto->id, 'accion'=>'store');
        });
        return is_null($error) ? json_encode($dat) : $error;
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
        $existe = Libreria::verificarExistencia($id, 'producto');
        if ($existe !== true) {
            return $existe;
        }
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $producto = Producto::find($id);
        $cboCategoria = array();
        $categoria = Categoria::orderBy('nombre','asc')->get();
        foreach($categoria as $k=>$v){
            $cboCategoria = $cboCategoria + array($v->id => $v->nombre);
        }
        $cboMarca = array();
        $marca = Marca::orderBy('nombre','asc')->get();
        foreach($marca as $k=>$v){
            $cboMarca = $cboMarca + array($v->id => $v->nombre);
        }
        $cboUnidad = array();
        $unidad = Unidad::orderBy('nombre','asc')->get();
        foreach($unidad as $k=>$v){
            $cboUnidad = $cboUnidad + array($v->id => $v->nombre);
        }
        
        $entidad  = 'Producto';
        $formData = array('producto.update', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Modificar';
        return view($this->folderview.'.mant')->with(compact('producto', 'formData', 'entidad', 'boton', 'listar', 'cboCategoria', 'cboMarca', 'cboUnidad'));
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
        $existe = Libreria::verificarExistencia($id, 'producto');
        if ($existe !== true) {
            return $existe;
        }
        $reglas     = array('nombre' => 'required|max:50',
                            'precioventa' => 'required');
        $mensajes = array(
            'nombre.required'         => 'Debe ingresar un nombre',
            'precioventa.required'         => 'Debe ingresar un precio de venta'
            );
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $dat=array();
        $error = DB::transaction(function() use($request, $id, &$dat){
            $producto = Producto::find($id);
            $producto->codigobarra = "";
            $producto->nombre = $request->input('nombre');
            $producto->abreviatura = $request->input('abreviatura');
            $producto->unidad_id = $request->input('unidad_id');
            $producto->marca_id = $request->input('marca_id');
            $producto->categoria_id = $request->input('categoria_id');
            $producto->preciocompra =  Libreria::getParam($request->input('preciocompra'), '0.00');
            $producto->precioventa = $request->input('precioventa');
            $producto->precioventaespecial = $request->input('precioventaespecial');
            $producto->stockminimo = Libreria::getParam($request->input('stockminimo'), '0.00');
            $producto->ganancia =  Libreria::getParam($request->input('ganancia'), '0.00');
            $producto->consumo = $request->input('consumo');
            $producto->igv = $request->input('igv');
            $producto->save();
            $dat[0]=array("respuesta"=>"OK","producto_id"=>$producto->id , 'accion'=>'update');
        });
        return is_null($error) ? json_encode($dat) : $error;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existe = Libreria::verificarExistencia($id, 'producto');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $producto = Producto::find($id);
            $producto->delete();
        });
        return is_null($error) ? "OK" : $error;
    }

    public function eliminar($id, $listarLuego)
    {
        $existe = Libreria::verificarExistencia($id, 'producto');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Producto::find($id);
        $entidad  = 'Producto';
        $formData = array('route' => array('producto.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }


    public function excel(Request $request){
        setlocale(LC_TIME, 'spanish');
        $resultado        = Producto::join('marca','marca.id','=','producto.marca_id')
                                ->join('unidad','unidad.id','=','producto.unidad_id')
                                ->join('categoria','categoria.id','=','producto.categoria_id')
                                ->leftjoin('stockproducto','stockproducto.producto_id','=','producto.id')
                                ->where('producto.nombre','like','%'.strtoupper($request->input('nombre')).'%');
        if($request->input('categoria')!=""){
            $resultado = $resultado->where('categoria.id','=',$request->input('categoria'));
        }
        if($request->input('marca')!=""){
            $resultado = $resultado->where('marca.id','=',$request->input('marca'));
        }
        $resultado = $resultado->orderBy('producto.nombre','asc')
                            ->select('producto.*','categoria.nombre as categoria2','marca.nombre as marca2','unidad.nombre as unidad2','stockproducto.cantidad as stock');
        $lista            = $resultado->get();
        if (count($lista) > 0) {     
            Excel::create('ExcelProducto', function($excel) use($lista,$request) {
                $excel->sheet('PRODUCTO', function($sheet) use($lista,$request) {
                    $c=1;
                    $sheet->mergeCells('A'.$c.':J'.$c);
                    $cabecera = array();
                    $cabecera[] = "REPORTE STOCK DE PRODUCTO ";
                    $sheet->row($c,$cabecera);
                    $c=$c+1;
                    $detalle = array();
                    $detalle[] = "PRODUCTO";
                    $detalle[] = "CATEGORIA";
                    $detalle[] = "MARCA";
                    $detalle[] = "UNIDAD";
                    $detalle[] = "P. COMPRA";
                    $detalle[] = "P. VENTA";
                    $detalle[] = "STOCK";
                    $sheet->row($c,$detalle);
                    $c=$c+1;
                    foreach($lista as $key => $value){
                        $detalle = array();
                        $detalle[] = $value->nombre;
                        $detalle[] = $value->categoria2;
                        $detalle[] = $value->marca2;
                        $detalle[] = $value->unidad2;
                        $detalle[] = $value->preciocompra;
                        $detalle[] = $value->precioventa;
                        $detalle[] = $value->stock;
                        $sheet->row($c,$detalle);
                        $c=$c+1;
                    }
                });
            })->export('xls');                    
        }
    }

    public function presentacion($id, Request $request)
    {
        $existe = Libreria::verificarExistencia($id, 'producto');
        if ($existe !== true) {
            return $existe;
        }
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $producto = Producto::find($id);
        $detalle = Detalleproducto::where('producto_id','=',$id)->get();
        $entidad  = 'Producto';
        $formData = array('producto.presentaciones', $id);
        $formData = array('route' => $formData, 'method' => 'PRESENTACION', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Guardar';
        return view($this->folderview.'.presentacion')->with(compact('producto', 'formData', 'entidad', 'boton', 'listar', 'detalle'));
    }

    public function presentaciones(Request $request)
    {
        $id = $request->input('id');
        $existe = Libreria::verificarExistencia($id, 'producto');
        if ($existe !== true) {
            return $existe;
        }
        $reglas     = array('nombre' => 'required|max:500'
            );
        $mensajes = array(
            'nombre.required'         => 'Debe ingresar un nombre'
            );
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $detalle = Detalleproducto::where('producto_id','=',$id)->get();
            foreach ($detalle as $key => $value) {
                $value->delete();
            }

            $arr=explode(",",$request->input('listProducto'));
            for($c=0;$c<count($arr);$c++){
                $detalle = new Detalleproducto();
                $detalle->presentacion_id = $request->input('txtIdProducto'.$arr[$c]);
                $detalle->cantidad = $request->input('txtCant'.$arr[$c]);
                $detalle->producto_id = $id;
                $detalle->save();
            }
        });
        return is_null($error) ? "OK" : $error;
    }

    public function archivos(Request $request){
        //obtenemos el campo file definido en el formulario
        $file = $request->file('file-0');
        if ($file) {
            //obtenemos el nombre del archivo
            
            /*
            $carpeta = '/P'.$request->input('id');
            if (!file_exists($carpeta)) {
                \Storage::makeDirectory($carpeta);
            }
            */
            if($request->input('accion') == 'update'){
                $old_image =$request->input('id').'-'.(Producto::find($request->input('id'))->archivo);
                $old_path = public_path('image/'.$old_image);
                if(file_exists($old_path)){
                    @unlink($old_path);
                }
            }
            $nombre = $file->getClientOriginalName();
            $path = public_path('image/'.$request->input('id').'-'.$nombre);
        
            $file->move('image', $request->input('id').'-'.$nombre);
        
            $producto = Producto::find($request->input('id'));
            $producto->archivo = $nombre;
            $producto->save();
            return "archivo guardado";
        }else{
            return "Imagen no enviada";
        }
        
    }

    
}
