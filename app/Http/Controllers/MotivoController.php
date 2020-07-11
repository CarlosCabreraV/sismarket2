<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Motivo;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MotivoController extends Controller
{
    protected $folderview      = 'app.motivo';
    protected $tituloAdmin     = 'Motivo';
    protected $entidad 		   = 'Motivo';
    protected $tabla 		   = 'motivo';
    protected $tituloRegistrar = 'Registrar Sucursal';
    protected $tituloModificar = 'Modificar Sucursal';
    protected $tituloEliminar  = 'Eliminar Sucursal';
    protected $rutas           = array('create' => 'motivo.create', 
            'edit'   => 'motivo.edit', 
            'delete' => 'motivo.eliminar',
            'search' => 'motivo.buscar',
            'index'  => 'motivo.index',
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
        $entidad          = $this->entidad;
        $nombre           = Libreria::getParam($request->input('nombre'));
        $tipo           = Libreria::getParam($request->input('tipo'));
        $resultado        = Motivo::listar($nombre,$tipo);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombre', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Tipo', 'numero' => '1');
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
        $entidad          = $this->entidad;
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboTipo = [""=>"Todos"]+["I"=>"INGRESO"]+["S"=>"SALIDA"];
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta','cboTipo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $entidad  = $this->entidad;
        $motivo = null;
        $formData = array('motivo.store');
        $formData = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Registrar'; 
        $cboTipo = [""=>"ELIJA TIPO DE MOTIVO"]+["I"=>"INGRESO"]+["S"=>"SALIDA"];
        return view($this->folderview.'.mant')->with(compact('motivo', 'formData', 'entidad', 'boton', 'listar', 'cboTipo'));
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
        $reglas     = array(
        	'nombre' => 'required',
        	'tipo' => 'required',
    		);
        $mensajes 	= array(
            'nombre.required'         => 'Debe ingresar un nombre',
            'tipo.required'         => 'Debe seleccionar un tipo',
            );
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function() use($request){
            $motivo = new Motivo();
            $motivo->nombre = Libreria::getParam(strtr(strtoupper($request->input('nombre')),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ"),"");
            $motivo->tipo = $request->input('tipo');
            $motivo->save();
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
        $existe = Libreria::verificarExistencia($id, $this->tabla);
        if ($existe !== true) {
            return $existe;
        }
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $motivo = Motivo::find($id);
        $entidad  = $this->entidad;
        $formData = array('motivo.update', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Modificar';
        $cboTipo = [""=>"ELIJA TIPO DE MOTIVO"]+["I"=>"INGRESO"]+["S"=>"SALIDA"];
        return view($this->folderview.'.mant')->with(compact('motivo', 'formData', 'entidad', 'boton', 'listar', 'cboTipo'));
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
        $existe = Libreria::verificarExistencia($id, $this->tabla);
        if ($existe !== true) {
            return $existe;
        }
        $reglas     = array(
        	'nombre' => 'required',
        	'tipo' => 'required',
    		);
        $mensajes 	= array(
            'nombre.required'         => 'Debe ingresar un nombre',
            'tipo.required'         => 'Debe seleccionar un tipo',
            );
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $motivo = Motivo::find($id);
            $motivo->nombre = Libreria::getParam(strtr(strtoupper($request->input('nombre')),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ"),"");
            $motivo->tipo = $request->input('tipo');
            $motivo->save();
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
        $existe = Libreria::verificarExistencia($id, $this->tabla);
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function() use($id){
            $motivo = Motivo::find($id);
            $motivo->delete();
        });
        return is_null($error) ? "OK" : $error;
    }

    public function eliminar($id, $listarLuego)
    {
        $existe = Libreria::verificarExistencia($id, $this->tabla);
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Motivo::find($id);
        $entidad  = $this->entidad;
        $formData = array('route' => array('motivo.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }
}
