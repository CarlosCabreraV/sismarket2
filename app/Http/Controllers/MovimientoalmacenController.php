<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Tipodocumento;
use App\Tipomovimiento;
use App\Movimiento;
use App\Concepto;
use App\Producto;
use App\Detalleproducto;
use App\Detallemovimiento;
use App\Stockproducto;
use App\Person;
use App\Sucursal;
use App\Motivo;
use App\Configuracion;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Imports\DocAlmacenImport;
use Excel;
use Barryvdh\DomPDF\Facade as PDF;

class MovimientoalmacenController extends Controller
{
    protected $folderview      = 'app.movimientoalmacen';
    protected $tituloAdmin     = 'Doc. Almacen';
    protected $tituloRegistrar = 'Registrar Doc. Almacen';
    protected $tituloModificar = 'Modificar compra';
    protected $tituloEliminar  = 'Eliminar Doc. Almacen';
    protected $tituloVer       = 'Ver Doc. Almacen';
    protected $rutas           = array(
        'create' => 'movimientoalmacen.create',
        'edit'   => 'movimientoalmacen.edit',
        'show'   => 'movimientoalmacen.show',
        'delete' => 'movimientoalmacen.eliminar',
        'search' => 'movimientoalmacen.buscar',
        'index'  => 'movimientoalmacen.index',
        'import' => 'movimientoalmacen.import',
    );


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        define("CODIGO_BARRAS", Configuracion::where("nombre", "=", "CODIGO_BARRAS")->first()->valor);
        define("STOCK_NEGATIVO", Configuracion::where("nombre", "=", "STOCK_NEGATIVO")->first()->valor);
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
        $entidad          = 'Movimientoalmacen';
        $nombre           = Libreria::getParam($request->input('cliente'));
        $sucursal         = Libreria::getParam($request->input('sucursal_id'));
        $fecinicio        = Libreria::getParam($request->input('fechainicio'));
        $fecfin           = Libreria::getParam($request->input('fechafin'));
        $tipodocumento    = Libreria::getParam($request->input('tipodocumento'));
        $numero           = Libreria::getParam($request->input('numero'));
        $producto_id      = Libreria::getParam($request->input('producto_id'), 0);
        $resultado        = Movimiento::listarDocAlmacen($sucursal, $fecinicio, $fecfin, $tipodocumento, $numero, $producto_id);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Tipo Doc.', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Motivo.', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nro', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Comentario', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Sucursal', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Usuario', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '3');

        $titulo_modificar = $this->tituloModificar;
        $titulo_eliminar  = $this->tituloEliminar;
        $titulo_ver       = $this->tituloVer;
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
            return view($this->folderview . '.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'ruta', 'titulo_ver'));
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
        $current_user     = Auth::User();
        $entidad          = 'Movimientoalmacen';
        $sucursal         = "";
        if (!$current_user->isSuperAdmin()) {
            $sucursal = " Sucursal: " . $current_user->sucursal->nombre;
        }
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboTipoDocumento = array('' => 'Todos');
        $cboTipoDocumento = array('' => 'Todos') + Tipodocumento::where('tipomovimiento_id', '=', 3)->orderBy('nombre', 'asc')->pluck('nombre', 'id')->all();
        $cboProducto = array('' => 'Todos') + Producto::orderBy('nombre', 'asc')->pluck('nombre', 'id')->all();
        $cboSucursal = ["" => "TODOS"] + Sucursal::pluck('nombre', 'id')->all();
        if (!$current_user->isAdmin() && !$current_user->isSuperAdmin()) {
            $cboSucursal = Sucursal::where('id', '=', $current_user->sucursal_id)->pluck('nombre', 'id')->all();
        }
        return view($this->folderview . '.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta', 'cboTipoDocumento', 'sucursal', 'cboSucursal', 'cboProducto'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $current_user     = Auth::User();
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $entidad    = 'Movimientoalmacen';
        $movimiento = null;
        $formData   = array('movimientoalmacen.store');
        $formData   = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        $boton      = 'Registrar';
        $conf_codigobarra = CODIGO_BARRAS;
        $cboTipoDocumento = Tipodocumento::where('tipomovimiento_id', '=', 3)->orderBy('nombre', 'asc')->pluck('nombre', 'id')->all();
        $cboMotivo        = Motivo::where('tipo', '=', "I")->where("id", "<>", "5")->orderBy('nombre', 'asc')->pluck('nombre', 'id')->all();
        $cboSucursal      = ["" => "SELECCIONE SUCURSAL"] + Sucursal::pluck('nombre', 'id')->all();
        $cboSucursalDestino = ["" => "SELECCIONE SUCURSAL"];
        if (!$current_user->isAdmin() && !$current_user->isSuperAdmin()) {
            $cboSucursal  = Sucursal::where('id', '=', $current_user->sucursal_id)->pluck('nombre', 'id')->all();
            $cboSucursalDestino = Sucursal::where('id', '<>', $current_user->sucursal_id)->pluck('nombre', 'id')->all();
        }
        return view($this->folderview . '.mant')->with(compact('movimiento', 'formData', 'entidad', 'boton', 'listar', 'cboTipoDocumento', 'cboMotivo', 'cboSucursal', 'cboSucursalDestino', 'conf_codigobarra'));
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
            'sucursal_id'   => 'required|integer|exists:sucursal,id,deleted_at,NULL',
            'motivo_id'   => 'required|integer|exists:motivo,id,deleted_at,NULL',
        );
        $mensajes   = array(
            'sucursal_id.required' => 'Debe seleccionar una sucursal.',
            'motivo_id.required' => 'Debe seleccionar un motivo.',
        );
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $user = Auth::user();
        $dat = array();
        try {
            $error = DB::transaction(function () use ($request, $user, &$dat) {
                date_default_timezone_set("America/Lima");
                $Venta       = new Movimiento();
                $Venta->fecha = $request->input('fecha');
                $Venta->numero = $request->input('numero');
                $Venta->subtotal = $request->input('total');
                $Venta->igv = 0;
                $Venta->total = $request->input('total');
                $Venta->tipomovimiento_id = 3; //MOV ALMACEN
                $Venta->tipodocumento_id = $request->input('tipodocumento');
                $Venta->persona_id = $request->input('persona_id') == "0" ? 1 : $request->input('persona_id');
                $Venta->situacion = 'C'; //Pendiente => P / Cobrado => C / Boleteado => B
                $Venta->comentario = Libreria::getParam($request->input('comentario'), '');
                $Venta->responsable_id = $user->person_id;
                $Venta->voucher = '';
                $Venta->totalpagado = 0;
                $Venta->tarjeta = '';
                $Venta->sucursal_id = $request->input('sucursal_id');
                $Venta->sucursal_envio_id = $request->input('sucursaldestino');
                $Venta->motivo_id = $request->input('motivo_id');
                $Venta->save();
                $arr = explode(",", $request->input('listProducto'));
                for ($c = 0; $c < count($arr); $c++) {
                    $Detalle = new Detallemovimiento();
                    $Detalle->movimiento_id = $Venta->id;
                    $Detalle->producto_id = $request->input('txtIdProducto' . $arr[$c]);
                    $Detalle->cantidad = $request->input('txtCantidad' . $arr[$c]);
                    $Detalle->precioventa = $request->input('txtPrecioVenta' . $arr[$c]);
                    $Detalle->preciocompra = $request->input('txtPrecio' . $arr[$c]);
                    $Detalle->save();

                    $Producto = Producto::find($Detalle->producto_id);
                    $Producto->preciocompra = $Detalle->preciocompra;
                    $Producto->save();
                    $detalleproducto = Detalleproducto::where('producto_id', '=', $Detalle->producto_id)->get();
                    if (count($detalleproducto) > 0) {
                        foreach ($detalleproducto as $key => $value) {
                            $stock = Stockproducto::where('producto_id', '=', $value->presentacion_id)->where("sucursal_id", "=", $request->input('sucursal_id'))->first();
                            if ($stock != null) {
                                if ($Venta->tipodocumento_id == 8) { //INGRESO
                                    $stock->cantidad = $stock->cantidad + $Detalle->cantidad * $value->cantidad;
                                } else {
                                    if (($stock->cantidad - $Detalle->cantidad * $value->cantidad) < 0 && STOCK_NEGATIVO == "N") {
                                        $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                        throw new \Exception(json_encode($dat));
                                    } else {
                                        $stock->cantidad = $stock->cantidad - $Detalle->cantidad * $value->cantidad;
                                    }
                                }
                                $stock->save();
                            } else {
                                $stock = new Stockproducto();
                                $stock->sucursal_id = $request->input('sucursal_id');
                                $stock->producto_id = $value->presentacion_id;
                                if ($Venta->tipodocumento_id == 8) { //INGRESO
                                    $stock->cantidad = $Detalle->cantidad * $value->cantidad;
                                } else {
                                    if (($Detalle->cantidad * (-1) * $value->cantidad) < 0 && STOCK_NEGATIVO == "N") {
                                        $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                        throw new \Exception(json_encode($dat));
                                    } else {
                                        $stock->cantidad = $Detalle->cantidad * (-1) * $value->cantidad;
                                    }
                                }
                                $stock->save();
                            }
                        }
                    } else {
                        $stock = Stockproducto::where('producto_id', '=', $Detalle->producto_id)->where("sucursal_id", "=", $request->input('sucursal_id'))->first();
                        if ($stock != null) {
                            if ($Venta->tipodocumento_id == 8) { //INGRESO
                                $stock->cantidad = $stock->cantidad + $Detalle->cantidad;
                            } else {
                                if (($stock->cantidad - $Detalle->cantidad) < 0 && STOCK_NEGATIVO == "N") {
                                    $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                    throw new \Exception(json_encode($dat));
                                } else {
                                    $stock->cantidad = $stock->cantidad - $Detalle->cantidad;
                                }
                            }
                            $stock->save();
                        } else {
                            $stock = new Stockproducto();
                            $stock->sucursal_id = $request->input('sucursal_id');
                            $stock->producto_id = $Detalle->producto_id;
                            if ($Venta->tipodocumento_id == 8) { //INGRESO
                                $stock->cantidad = $Detalle->cantidad;
                            } else {
                                if (($Detalle->cantidad * (-1)) < 0 && STOCK_NEGATIVO == "N") {
                                    $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                    throw new \Exception(json_encode($dat));
                                } else {
                                    $stock->cantidad = $Detalle->cantidad * (-1);
                                }
                            }
                            $stock->save();
                        }
                    }
                }
                if ($request->input('motivo_id') == "3") {
                    $traslado       = new Movimiento();
                    $traslado->fecha = $request->input('fecha');
                    $numeroventa = "001-" . Movimiento::NumeroSigue(3, 8);
                    $traslado->numero = $numeroventa;
                    $traslado->subtotal = $request->input('total');
                    $traslado->igv = 0;
                    $traslado->total = $request->input('total');
                    $traslado->tipomovimiento_id = 3; //MOV ALMACEN
                    $traslado->tipodocumento_id = 8; //INGRESO
                    $traslado->persona_id = $request->input('persona_id') == "0" ? 1 : $request->input('persona_id');
                    $traslado->situacion = 'P'; //Pendiente => P / Cobrado => C / Boleteado => B
                    $traslado->comentario = Libreria::getParam($request->input('comentario'), '');
                    $traslado->responsable_id = $user->person_id;
                    $traslado->voucher = '';
                    $traslado->totalpagado = 0;
                    $traslado->tarjeta = '';
                    $traslado->sucursal_id = $request->input('sucursaldestino');
                    $traslado->sucursal_envio_id = $request->input('sucursal_id');
                    $traslado->movimiento_id = $Venta->id;
                    $traslado->motivo_id = 5; // TRASLADO DE INGRESO
                    $traslado->save();
                    $Venta->movimiento_id = $traslado->id;
                    $arr = explode(",", $request->input('listProducto'));
                    for ($c = 0; $c < count($arr); $c++) {
                        $Detalle = new Detallemovimiento();
                        $Detalle->movimiento_id = $traslado->id;
                        $Detalle->producto_id = $request->input('txtIdProducto' . $arr[$c]);
                        $Detalle->cantidad = $request->input('txtCantidad' . $arr[$c]);
                        $Detalle->precioventa = $request->input('txtPrecioVenta' . $arr[$c]);
                        $Detalle->preciocompra = $request->input('txtPrecio' . $arr[$c]);
                        $Detalle->save();
                    }
                }

                $dat[0] = array("respuesta" => "OK", "venta_id" => $Venta->id);
            });
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return is_null($error) ? json_encode($dat) : $error;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $existe = Libreria::verificarExistencia($id, 'movimiento');
        if ($existe !== true) {
            return $existe;
        }
        $listar              = Libreria::getParam($request->input('listar'), 'NO');
        $venta = Movimiento::find($id);
        $entidad             = 'Movimientoalmacen';
        $cboTipoDocumento    = Tipodocumento::pluck('nombre', 'id')->all();
        $formData            = array('movimientoalmacen.update', $id);
        $formData            = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        $boton               = 'Confirmar';
        $conf_codigobarra = CODIGO_BARRAS;
        //$cuenta = Cuenta::where('movimiento_id','=',$compra->id)->orderBy('id','ASC')->first();
        //$fechapago =  Date::createFromFormat('Y-m-d', $cuenta->fecha)->format('d/m/Y');
        $detalles = Detallemovimiento::where('movimiento_id', '=', $venta->id)->get();
        //$numerocuotas = count($cuentas);
        return view($this->folderview . '.mantView')->with(compact('venta', 'formData', 'entidad', 'boton', 'listar', 'cboTipoDocumento', 'detalles', 'conf_codigobarra'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        // $existe = Libreria::verificarExistencia($id, 'seccion');
        // if ($existe !== true) {
        //     return $existe;
        // }
        // $listar   = Libreria::getParam($request->input('listar'), 'NO');
        // $seccion = Seccion::find($id);
        // $cboEspecialidad = array();
        // $especialidad = Especialidad::orderBy('nombre', 'asc')->get();
        // foreach ($especialidad as $k => $v) {
        //     $cboEspecialidad = $cboEspecialidad + array($v->id => $v->nombre);
        // }
        // $cboCiclo = array();
        // $ciclo = Grado::orderBy('nombre', 'asc')->get();
        // foreach ($ciclo as $k => $v) {
        //     $cboCiclo = $cboCiclo + array($v->id => $v->nombre);
        // }

        // $entidad  = 'Seccion';
        // $formData = array('seccion.update', $id);
        // $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        // $boton    = 'Modificar';
        // return view($this->folderview . '.mant')->with(compact('seccion', 'formData', 'entidad', 'boton', 'listar', 'cboEspecialidad', 'cboCiclo'));
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
        $existe = Libreria::verificarExistencia($id, 'movimiento');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function () use ($request, $id) {
            $movimiento = Movimiento::find($id);
            $movimiento->situacion = "C";
            $movimiento->save();
            $detallesMovimiento = Detallemovimiento::where('movimiento_id', '=', $movimiento->id)->get();
            foreach ($detallesMovimiento as $key => $Detalle) {
                $detalleproducto = Detalleproducto::where('producto_id', '=', $Detalle->producto_id)->get();
                if (count($detalleproducto) > 0) {
                    foreach ($detalleproducto as $key => $value) {
                        $stock = Stockproducto::where('producto_id', '=', $value->presentacion_id)->where("sucursal_id", "=", $movimiento->sucursal_id)->first();
                        if ($stock != null) {
                            if ($movimiento->tipodocumento_id == 8) { //INGRESO
                                $stock->cantidad = $stock->cantidad + $Detalle->cantidad * $value->cantidad;
                            }
                            $stock->save();
                        } else {
                            $stock = new Stockproducto();
                            $stock->sucursal_id = $movimiento->sucursal_id;
                            $stock->producto_id = $value->presentacion_id;
                            if ($movimiento->tipodocumento_id == 8) { //INGRESO
                                $stock->cantidad = $Detalle->cantidad * $value->cantidad;
                            }
                            $stock->save();
                        }
                    }
                } else {
                    $stock = Stockproducto::where('producto_id', '=', $Detalle->producto_id)->where("sucursal_id", "=", $movimiento->sucursal_id)->first();
                    if ($stock != null) {
                        if ($movimiento->tipodocumento_id == 8) { //INGRESO
                            $stock->cantidad = $stock->cantidad + $Detalle->cantidad;
                        }
                        $stock->save();
                    } else {
                        $stock = new Stockproducto();
                        $stock->sucursal_id = $movimiento->sucursal_id;
                        $stock->producto_id = $Detalle->producto_id;
                        if ($movimiento->tipodocumento_id == 8) { //INGRESO
                            $stock->cantidad = $Detalle->cantidad;
                        }
                        $stock->save();
                    }
                }
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
        $existe = Libreria::verificarExistencia($id, 'movimiento');
        if ($existe !== true) {
            return $existe;
        }
        $dat = array();
        try {
            $error = DB::transaction(function () use ($id, &$dat) {
                $movimiento = Movimiento::find($id);
                $arrayMovimientos = array();
                $arrayMovimientos[] = $movimiento;
                if ($movimiento->movimiento != null) {
                    $arrayMovimientos[] = $movimiento->movimiento;
                }
                foreach ($arrayMovimientos as $venta) {
                    if ($venta->situacion == "P") {
                        $venta->situacion = 'A';
                        $venta->save();
                    } else {
                        $venta->situacion = 'A';
                        $venta->save();
                        $lst = Detallemovimiento::where('movimiento_id', '=', $id)->get();
                        foreach ($lst as $key => $Detalle) {
                            $detalleproducto = Detalleproducto::where('producto_id', '=', $Detalle->producto_id)->get();
                            if (count($detalleproducto) > 0) {
                                foreach ($detalleproducto as $key => $value) {
                                    $stock = Stockproducto::where('producto_id', '=', $value->presentacion_id)->where("sucursal_id", "=", $venta->sucursal_id)->first();
                                    if (count($stock) > 0) {
                                        if ($venta->tipodocumento_id == 8) { //INGRESO
                                            if (($stock->cantidad - $Detalle->cantidad * $value->cantidad) < 0 && STOCK_NEGATIVO == "N") {
                                                $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                                throw new \Exception(json_encode($dat));
                                            } else {
                                                $stock->cantidad = $stock->cantidad - $Detalle->cantidad * $value->cantidad;
                                            }
                                        } else {
                                            $stock->cantidad = $stock->cantidad + $Detalle->cantidad * $value->cantidad;
                                        }
                                        $stock->save();
                                    } else {
                                        $stock = new Stockproducto();
                                        $stock->producto_id = $value->presentacion_id;
                                        $stock->sucursal_id = $venta->sucursal_id;
                                        if ($venta->tipodocumento_id == 8) { //INGRESO
                                            if (($Detalle->cantidad * (-1) * $value->cantidad) < 0 && STOCK_NEGATIVO == "N") {
                                                $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                                throw new \Exception(json_encode($dat));
                                            } else {
                                                $stock->cantidad = $Detalle->cantidad * (-1) * $value->cantidad;
                                            }
                                        } else {
                                            $stock->cantidad = $Detalle->cantidad * $value->cantidad;
                                        }
                                        $stock->save();
                                    }
                                }
                            } else {
                                $stock = Stockproducto::where('producto_id', '=', $Detalle->producto_id)->where("sucursal_id", "=", $venta->sucursal_id)->first();
                                if (count($stock) > 0) {
                                    if ($venta->tipodocumento_id == 8) { //INGRESO
                                        if (($stock->cantidad - $Detalle->cantidad) < 0 && STOCK_NEGATIVO == "N") {
                                            $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                            throw new \Exception(json_encode($dat));
                                        } else {
                                            $stock->cantidad = $stock->cantidad - $Detalle->cantidad;
                                        }
                                    } else {
                                        $stock->cantidad = $stock->cantidad + $Detalle->cantidad;
                                    }
                                    $stock->save();
                                } else {
                                    $stock = new Stockproducto();
                                    $stock->sucursal_id = $venta->sucursal_id;
                                    $stock->producto_id = $Detalle->producto_id;
                                    if ($venta->tipodocumento_id == 8) { //INGRESO
                                        if (($Detalle->cantidad * (-1)) < 0 && STOCK_NEGATIVO == "N") {
                                            $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                            throw new \Exception(json_encode($dat));
                                        } else {
                                            $stock->cantidad = $Detalle->cantidad * (-1);
                                        }
                                    } else {
                                        $stock->cantidad = $Detalle->cantidad;
                                    }
                                    $stock->save();
                                }
                            }
                        }
                    }
                }
                $dat[0] = array("respuesta" => "OK", "venta_id" => $arrayMovimientos[0]->id);
            });
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return is_null($error) ? json_encode($dat) : $error;
    }

    public function eliminar($id, $listarLuego)
    {
        $existe = Libreria::verificarExistencia($id, 'movimiento');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Movimiento::find($id);
        $entidad  = 'Movimientoalmacen';
        $formData = array('route' => array('movimientoalmacen.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminarAlmacen')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function buscarproducto(Request $request)
    {
        $tipodocumento = $request->input("tipodocumento");
        $sucursal_id = $request->input("sucursal_id");
        $descripcion = $request->input("descripcion");
        $resultado = Producto::leftjoin('stockproducto', function ($subquery) use ($sucursal_id) {
            $subquery->whereRaw('stockproducto.producto_id = producto.id')->where("stockproducto.sucursal_id", "=", $sucursal_id);
        })
            ->where('nombre', 'like', '%' . strtoupper($descripcion) . '%')
            ->where(function ($subquery) use ($tipodocumento) {
                if ($tipodocumento == "9" && STOCK_NEGATIVO == "N") {
                    $subquery->where('cantidad', '>', '0');
                }
            })
            ->select('producto.*', 'stockproducto.cantidad')->get();
        $c = 0;
        $data = array();
        if (count($resultado) > 0) {
            foreach ($resultado as $key => $value) {
                $producto = Producto::where('id',$value->id)->first();
                $data[$c] = array(
                    'producto' => $value->nombre,
                    'codigobarra' => $value->codigobarra,
                    'precioventa' => $value->precioventa,
                    'preciocompra' => $value->preciocompra,
                    'unidad' => $producto->unidad->nombre,
                    'idproducto' => $value->id,
                    'tipo' => 'P',
                    'stock' => round($value->cantidad, 2),
                );
                $c++;
            }
        } else {
            $data = array();
        }
        return json_encode($data);
    }

    public function buscarproductobarra(Request $request)
    {
        $tipodocumento = $request->input("tipodocumento");
        $sucursal_id = $request->input("sucursal_id");
        $codigobarra = $request->input("codigobarra");
        $resultado = Producto::leftjoin('stockproducto', function ($subquery) use ($sucursal_id) {
            $subquery->whereRaw('stockproducto.producto_id = producto.id')->where("stockproducto.sucursal_id", "=", $sucursal_id);
        })
            ->where(function ($subquery) use ($tipodocumento) {
                if ($tipodocumento == "9" && STOCK_NEGATIVO == "N") {
                    $subquery->where('cantidad', '>', '0');
                }
            })
            ->where(DB::raw('trim(codigobarra)'), 'like', trim($codigobarra))
            ->select('producto.*', 'stockproducto.cantidad')->get();
        $c = 0;
        $data = array();
        if (count($resultado) > 0) {
            foreach ($resultado as $key => $value) {
                $producto = Producto::where('id',$value->id)->first();
                $data[$c] = array(
                    'producto' => $value->nombre,
                    'codigobarra' => $value->codigobarra,
                    'precioventa' => $value->precioventa,
                    'preciocompra' => $value->preciocompra,
                    'unidad' => $producto->unidad->nombre,
                    'idproducto' => $value->id,
                    'tipo' => 'P',
                    'stock' => round($value->cantidad, 2),
                );
                $c++;
            }
        } else {
            $data = array();
        }
        return json_encode($data);
    }

    public function personautocompletar($searching)
    {
        $resultado        = Person::join('rolpersona', 'rolpersona.person_id', '=', 'person.id')->where('rolpersona.rol_id', '=', 2)
            ->where(function ($sql) use ($searching) {
                $sql->where(DB::raw('CONCAT(apellidopaterno," ",apellidomaterno," ",nombres)'), 'LIKE', '%' . strtoupper($searching) . '%')->orWhere('bussinesname', 'LIKE', '%' . strtoupper($searching) . '%');
            })
            ->whereNull('person.deleted_at')->whereNull('rolpersona.deleted_at')->orderBy('apellidopaterno', 'ASC');
        $list      = $resultado->select('person.*')->get();
        $data = array();
        foreach ($list as $key => $value) {
            $name = '';
            if ($value->bussinesname != null) {
                $name = $value->bussinesname;
            } else {
                $name = $value->apellidopaterno . " " . $value->apellidomaterno . " " . $value->nombres;
            }
            $data[] = array(
                'label' => trim($name),
                'id'    => $value->id,
                'value' => trim($name),
                'ruc'   => $value->ruc,
            );
        }
        return json_encode($data);
    }

    public function generarNumero(Request $request)
    {
        $numeroventa = Movimiento::NumeroSigue(3, $request->input('tipodocumento'));
        echo "001-" . $numeroventa;
    }

    function cambiarMotivo(Request $request)
    {
        $tipodocumento = Tipodocumento::find($request->input('tipo'));
        $motivo = Motivo::listar(null, $tipodocumento->abreviatura)->where("id", "<>", "5");
        $motivo = $motivo->get();
        $cadena = '';
        foreach ($motivo as $key => $value) {
            $cadena = $cadena . "<option value=" . $value->id . ">" . $value->nombre . "</option>";
        }
        return json_encode(array("motivos" => $cadena));
    }

    function cambiarSucursalDestino(Request $request)
    {
        $sucursal = Sucursal::where("id", "<>", $request->input('sucursal_id'));
        $sucursal = $sucursal->get();
        $cadena = '';
        foreach ($sucursal as $key => $value) {
            $cadena = $cadena . "<option value=" . $value->id . ">" . $value->nombre . "</option>";
        }
        return json_encode(array("sucursales" => $cadena));
    }

    /**
     * Funcion para abrir modal de importar.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $listar              = Libreria::getParam($request->input('listar'), 'NO');
        $entidad             = "Movimientoalmacen";
        $current_user        = Auth::User();
        $movimiento          = null;
        $formData            = array('movimientoalmacen.saveimport');
        $formData            = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
        $boton               = 'Importar';
        $cboSucursal         = ["" => "SELECCIONE SUCURSAL"] + Sucursal::pluck('nombre', 'id')->all();
        if (!$current_user->isAdmin() && !$current_user->isSuperAdmin()) {
            $cboSucursal  = Sucursal::where('id', '=', $current_user->sucursal_id)->pluck('nombre', 'id')->all();
        }
        return view($this->folderview . '.mant2')->with(compact('movimiento', 'formData', 'entidad', 'boton', 'listar', 'cboSucursal'));
    }

    /**
     * Funcion para importar.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     */
    public function saveimport(Request $request)
    {
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $validacion = Validator::make(
            $request->all(),
            array(
                'file'           => 'required',
            ),
            array(
                'file.required'  => 'El campo Excel es necesario.',
            )
        );
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $error = DB::transaction(function () use ($request) {
            if ($request->hasFile("file")) {
                $file = $request->file("file");
                if ($file->getClientMimeType() == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || $file->getClientMimeType() == "application/vnd.ms-excel" || $file->getClientMimeType() == "application/octet-stream") {
                    session(['import_docalmacen_sucursal_id' => $request->input("sucursal_id")]);
                    $namefile = time() . $file->getClientOriginalName();
                    $file->move(public_path() . '/imports/', $namefile);
                    $data = Excel::import(new DocAlmacenImport, public_path() . '/imports/' . $namefile);
                } else {
                    echo json_encode(array("file" => array("El archivo debe ser formato xls o xlsx")));
                    exit();
                }
            }
        });
        return is_null($error) ? "OK" : $error;
    }

    public function pdf($id){

        $existe = Libreria::verificarExistencia($id, 'movimiento');
        if ($existe !== true) {
            return $existe;
        }
        $movimiento = Movimiento::find($id);
        $conf_codigobarra = CODIGO_BARRAS;
        //$cuenta = Cuenta::where('movimiento_id','=',$compra->id)->orderBy('id','ASC')->first();
        //$fechapago =  Date::createFromFormat('Y-m-d', $cuenta->fecha)->format('d/m/Y');
        $detalles = Detallemovimiento::where('movimiento_id', '=', $movimiento->id)->get();
        //$numerocuotas = count($cuentas);

        $detalles = Detallemovimiento::where('movimiento_id',$id)->get();
        $pdf = PDF::loadView('app.movimientoalmacen.pdf',compact('movimiento','detalles'))->setPaper('a4');  
        //HOJA HORIZONTAL ->setPaper('a4', 'landscape')
    //descargar
       // return $pdf->download('F'.$cotizacion->documento->correlativo.'.pdf');  
    //Ver
       return $pdf->stream('docalmacen.pdf');
    
    }
}
