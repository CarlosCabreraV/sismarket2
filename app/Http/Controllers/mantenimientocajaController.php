<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Caja;
use App\Sucursal;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class mantenimientocajaController extends Controller
{
    protected $folderview      = 'app.mantenimientocaja';
    protected $tituloAdmin     = 'Caja';
    protected $entidad            = 'Caja';
    protected $tabla            = 'caja';
    protected $tituloRegistrar = 'Registrar Caja';
    protected $tituloModificar = 'Modificar Caja';
    protected $tituloEliminar  = 'Eliminar Caja';
    protected $rutas           = array(
        'create' => 'mantenimientocaja.create',
        'edit'   => 'mantenimientocaja.edit',
        'delete' => 'mantenimientocaja.eliminar',
        'search' => 'mantenimientocaja.buscar',
        'index'  => 'mantenimientocaja.index',
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
        $sucursal_id      = Libreria::getParam($request->input('sucursal_id'));
        $resultado        = Caja::listar($nombre, $sucursal_id);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nombre', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Serie', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Sucursal', 'numero' => '1');
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
            return view($this->folderview . '.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'ruta'));
        }
        return view($this->folderview . '.list')->with(compact('lista', 'entidad'));
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
        $cboSucursal      = ["" => "TODOS"] + Sucursal::pluck("nombre", "id")->all();
        return view($this->folderview . '.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta', 'cboSucursal'));
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
        $caja = null;
        $formData = array('mantenimientocaja.store');
        $formData = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        $boton    = 'Registrar';
        $cboSucursal = ["" => "ELIJA UNA SUCURSAL"] + Sucursal::pluck("nombre", "id")->all();
        return view($this->folderview . '.mant')->with(compact('caja', 'formData', 'entidad', 'boton', 'listar', 'cboSucursal'));
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
            'sucursal_id' => 'required|integer|exists:sucursal,id,deleted_at,NULL',
            'nombre' => 'required'
        );
        $mensajes     = array(
            'nombre.required'         => 'Debe ingresar un nombre.',
            'sucursal_id.required'    => 'Debe seleccionar una sucursal.',
            'sucursal_id.exists'    => 'La sucursal seleccionada no existe.',
            'sucursal_id.integer'    => 'El formato de la sucursal es incorrecto.'
        );
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function () use ($request) {
            $caja = new Caja();
            $caja->nombre = Libreria::getParam(strtr(strtoupper($request->input('nombre')), "àèìòùáéíóúçñäëïöü", "ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ"), "");
            $caja->sucursal_id = $request->input('sucursal_id');
            $caja->serie = Libreria::getParam($request->input('serie'), "1");
            $caja->save();
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
        $caja = Caja::find($id);
        $entidad  = $this->entidad;
        $formData = array('mantenimientocaja.update', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        $boton    = 'Modificar';
        $cboSucursal      = ["" => "TODOS"] + Sucursal::pluck("nombre", "id")->all();
        return view($this->folderview . '.mant')->with(compact('caja', 'formData', 'entidad', 'boton', 'listar', 'cboSucursal'));
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
            'sucursal_id' => 'required|integer|exists:sucursal,id,deleted_at,NULL',
            'nombre' => 'required'
        );
        $mensajes     = array(
            'nombre.required'         => 'Debe ingresar un nombre.',
            'sucursal_id.required'    => 'Debe seleccionar una sucursal.',
            'sucursal_id.exists'    => 'La sucursal seleccionada no existe.',
            'sucursal_id.integer'    => 'El formato de la sucursal es incorrecto.'
        );
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function () use ($request, $id) {
            $caja = Caja::find($id);
            $caja->nombre = Libreria::getParam(strtr(strtoupper($request->input('nombre')), "àèìòùáéíóúçñäëïöü", "ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ"), "");
            $caja->sucursal_id = $request->input('sucursal_id');
            $caja->serie = Libreria::getParam($request->input('serie'), "1");
            $caja->save();
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
        $error = DB::transaction(function () use ($id) {
            $caja = Caja::find($id);
            $caja->delete();
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
        $modelo   = Caja::find($id);
        $entidad  = $this->entidad;
        $formData = array('route' => array('mantenimientocaja.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function asignarcaja()
    {
        $entidad  = $this->entidad;
        $caja = null;
        $formData = array('mantenimientocaja.guardarasignarcaja');
        $formData = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        $boton    = 'Aceptar';
        $user = Auth::user();
        $cajas = Caja::where('sucursal_id', $user->sucursal_id)->where('estado', 'CERRADA');
        $cboCajas = ["" => "ELIJA UNA CAJA"] + $cajas->pluck("nombre", "id")->all();
        return view('app.asignarCaja')->with(compact('caja', 'formData', 'entidad', 'boton', 'cboCajas'));
    }
    public function guardarasignarcaja(Request $request)
    {

        $reglas     = array(
            'caja_id' => 'required|integer|exists:caja,id,deleted_at,NULL',
        );
        $mensajes     = array(
            'caja_id.required'    => 'Debe seleccionar una caja.',
            'caja_id.exists'    => 'La caja seleccionada no existe.',
            'caja_id.integer'    => 'El formato de la caja es incorrecto.'
        );
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }

        session(['caja_sesion_id' => $request->caja_id]);

        return "OK";
    }
}
