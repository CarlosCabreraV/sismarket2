<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Tipodocumento;
use App\Tipomovimiento;
use App\Movimiento;
use App\Configuracion;
use App\Producto;
use App\Sucursal;
use App\Detalleproducto;
use App\Detallemovimiento;
use App\Stockproducto;
use App\Person;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CompraController extends Controller
{
    protected $folderview      = 'app.compra';
    protected $tituloAdmin     = 'Compras';
    protected $tituloRegistrar = 'Registrar compra';
    protected $tituloModificar = 'Modificar compra';
    protected $tituloEliminar  = 'Eliminar compra';
    protected $tituloVer       = 'Ver Compra';
    protected $rutas           = array(
        'create' => 'compra.create',
        'edit'   => 'compra.edit',
        'show'   => 'compra.show',
        'delete' => 'compra.eliminar',
        'search' => 'compra.buscar',
        'index'  => 'compra.index',
    );
    protected $current_user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        define("CODIGO_BARRAS", Configuracion::where("nombre", "=", "CODIGO_BARRAS")->first()->valor);
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
        $entidad          = 'Compra';
        $nombre           = Libreria::getParam($request->input('cliente'));
        $sucursal         = Libreria::getParam($request->input('sucursal_id'));
        $fecinicio        = Libreria::getParam($request->input('fechainicio'));
        $fecfin           = Libreria::getParam($request->input('fechafin'));
        $tipodocumento    = Libreria::getParam($request->input('tipodocumento'));
        $numero           = Libreria::getParam($request->input('numero'));
        $proveedor        = Libreria::getParam($request->input('proveedor'));
        $resultado        = Movimiento::listarCompra($sucursal, $fecinicio, $fecfin, $tipodocumento, $numero, $proveedor);
        $lista            = $resultado->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Hora', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Tipo Doc.', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nro', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Proveedor', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Total', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Usuario', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '2');

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
        $entidad          = 'Compra';
        $title            = $this->tituloAdmin;
        $sucursal         = "";
        if (!$current_user->isSuperAdmin()) {
            $sucursal = " Sucursal: " . $current_user->sucursal->nombre;
        }
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboTipoDocumento = array('' => 'Todos') + Tipodocumento::where('tipomovimiento_id', '=', 1)->orderBy('nombre', 'asc')->pluck('nombre', 'id')->all();
        $cboSucursal = ["" => "TODOS"] + Sucursal::pluck('nombre', 'id')->all();
        if (!$current_user->isAdmin() && !$current_user->isSuperAdmin()) {
            $cboSucursal = Sucursal::where('id', '=', $current_user->sucursal_id)->pluck('nombre', 'id')->all();
        }
        return view($this->folderview . '.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta', 'cboTipoDocumento', 'sucursal', 'cboSucursal'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $current_user     = Auth::User();
        $entidad  = 'Compra';
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $movimiento = null;
        $formData = array('compra.store');
        $formData = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        $boton    = 'Registrar';
        $conf_codigobarra = CODIGO_BARRAS;
        $cboTipoDocumento = Tipodocumento::where('tipomovimiento_id', '=', 1)->orderBy('nombre', 'asc')->pluck('nombre', 'id')->all();
        $cboSucursal = ["" => "SELECCIONE SUCURSAL"] + Sucursal::pluck('nombre', 'id')->all();
        if (!$current_user->isAdmin() && !$current_user->isSuperAdmin()) {
            $cboSucursal = Sucursal::where('id', '=', $current_user->sucursal_id)->pluck('nombre', 'id')->all();
        }
        return view($this->folderview . '.mant')->with(compact('movimiento', 'formData', 'entidad', 'boton', 'listar', 'cboTipoDocumento', 'cboSucursal', 'conf_codigobarra'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reglas     = array(
            'persona' => 'required|max:500',
            'sucursal_id'   => 'required|integer|exists:sucursal,id,deleted_at,NULL',
        );
        $mensajes   = array(
            'persona.required'         => 'Debe ingresar un proveedor',
            'sucursal_id.required' => 'Debe seleccionar una sucursal.'
        );
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $user = Auth::user();
        $dat = array();
        $error = DB::transaction(function () use ($request, $user, &$dat) {
            date_default_timezone_set("America/Lima");
            $Venta       = new Movimiento();
            $Venta->fecha = $request->input('fecha');
            $Venta->numero = $request->input('numero');
            if ($request->input('tipodocumento') == "2") { //FACTURA
                $Venta->subtotal = round($request->input('total') / 1.18, 2);
                $Venta->igv = round($request->input('total') - $Venta->subtotal, 2);
            } else {
                $Venta->subtotal = $request->input('total');
                $Venta->igv = 0;
            }
            $Venta->total = $request->input('total');
            $Venta->tipomovimiento_id = 1; //COMPRA
            $Venta->tipodocumento_id = $request->input('tipodocumento');
            $Venta->persona_id = $request->input('persona_id') == "0" ? 1 : $request->input('persona_id');
            $Venta->situacion = 'C'; //Pendiente => P / Cobrado => C / Boleteado => B / Anulado => A
            $Venta->comentario = '';
            $Venta->voucher = '';
            $Venta->totalpagado = 0;
            $Venta->tarjeta = '';
            $Venta->responsable_id = $user->person_id;
            $Venta->sucursal_id = $request->input('sucursal_id');
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
                            $stock->cantidad = $stock->cantidad + $Detalle->cantidad * $value->cantidad;
                            $stock->save();
                        } else {
                            $stock = new Stockproducto();
                            $stock->sucursal_id = $request->input('sucursal_id');
                            $stock->producto_id = $value->presentacion_id;
                            $stock->cantidad = $Detalle->cantidad * $value->cantidad;
                            $stock->save();
                        }
                    }
                } else {
                    $stock = Stockproducto::where('producto_id', '=', $Detalle->producto_id)->where("sucursal_id", "=", $request->input('sucursal_id'))->first();
                    if ($stock != null) {
                        $stock->cantidad = $stock->cantidad + $Detalle->cantidad;
                        $stock->save();
                    } else {
                        $stock = new Stockproducto();
                        $stock->sucursal_id = $request->input('sucursal_id');
                        $stock->producto_id = $Detalle->producto_id;
                        $stock->cantidad = $Detalle->cantidad;
                        $stock->save();
                    }
                }
            }
            $dat[0] = array("respuesta" => "OK", "venta_id" => $Venta->id);
        });
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
        $venta               = Movimiento::find($id);
        $entidad             = 'Compra';
        $cboTipoDocumento    = Tipodocumento::pluck('nombre', 'id')->all();
        $formData            = array('venta.update', $id);
        $formData            = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        $boton               = 'Modificar';
        $conf_codigobarra    = CODIGO_BARRAS;
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
        // //$seccion = Seccion::find($id);
        // $cboEspecialidad = array();
        // //$especialidad = Especialidad::orderBy('nombre','asc')->get();
        // foreach($especialidad as $k=>$v){
        //     $cboEspecialidad = $cboEspecialidad + array($v->id => $v->nombre);
        // }
        // $cboCiclo = array();
        // //$ciclo = Grado::orderBy('nombre','asc')->get();
        // foreach($ciclo as $k=>$v){
        //     $cboCiclo = $cboCiclo + array($v->id => $v->nombre);
        // }

        // $entidad  = 'Seccion';
        // $formData = array('seccion.update', $id);
        // $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        // $boton    = 'Modificar';
        // return view($this->folderview.'.mant')->with(compact('seccion', 'formData', 'entidad', 'boton', 'listar', 'cboEspecialidad', 'cboCiclo'));
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
        // $existe = Libreria::verificarExistencia($id, 'seccion');
        // if ($existe !== true) {
        //     return $existe;
        // }
        // $reglas     = array('nombre' => 'required|max:50');
        // $mensajes = array(
        //     'nombre.required'         => 'Debe ingresar un nombre'
        //     );
        // $validacion = Validator::make($request->all(), $reglas, $mensajes);
        // if ($validacion->fails()) {
        //     return $validacion->messages()->toJson();
        // } 
        // $error = DB::transaction(function() use($request, $id){
        //     $anio = Anio::where('situacion','like','A')->first();
        //     $seccion = Seccion::find($id);
        //     $seccion->nombre = strtoupper($request->input('nombre'));
        //     $seccion->grado_id = $request->input('grado_id');
        //     $seccion->especialidad_id = $request->input('especialidad_id');
        //     $seccion->anio_id = $anio->id;
        //     $seccion->save();
        // });
        // return is_null($error) ? "OK" : $error;
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
        $error = DB::transaction(function () use ($id) {
            $venta = Movimiento::find($id);
            $venta->situacion = 'A';
            $venta->save();
            $sucursal_id = $venta->sucursal_id;
            $lst = Detallemovimiento::where('movimiento_id', '=', $id)->get();
            foreach ($lst as $key => $Detalle) {
                $detalleproducto = Detalleproducto::where('producto_id', '=', $Detalle->producto_id)->get();
                if (count($detalleproducto) > 0) {
                    foreach ($detalleproducto as $key => $value) {
                        $stock = Stockproducto::where('producto_id', '=', $value->presentacion_id)->where("sucursal_id", "=", $sucursal_id)->first();
                        if (count($stock) > 0) {
                            $stock->cantidad = $stock->cantidad - $Detalle->cantidad * $value->cantidad;
                            $stock->save();
                        } else {
                            $stock = new Stockproducto();
                            $stock->sucursal_id = $sucursal_id;
                            $stock->producto_id = $value->presentacion_id;
                            $stock->cantidad = $Detalle->cantidad * $value->cantidad * (-1);
                            $stock->save();
                        }
                    }
                } else {
                    $stock = Stockproducto::where('producto_id', '=', $Detalle->producto_id)->where("sucursal_id", "=", $sucursal_id)->first();
                    if (count($stock) > 0) {
                        $stock->cantidad = $stock->cantidad - $Detalle->cantidad;
                        $stock->save();
                    } else {
                        $stock = new Stockproducto();
                        $stock->sucursal_id = $sucursal_id;
                        $stock->producto_id = $Detalle->producto_id;
                        $stock->cantidad = $Detalle->cantidad * (-1);
                        $stock->save();
                    }
                }
            }
        });
        return is_null($error) ? "OK" : $error;
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
        $entidad  = 'Compra';
        $formData = array('route' => array('compra.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        $boton    = 'Eliminar';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }

    public function buscarproducto(Request $request)
    {
        $sucursal_id = $request->input("sucursal_id");
        $descripcion = $request->input("descripcion");
        $resultado = Producto::leftjoin('stockproducto', function ($subquery) use ($sucursal_id) {
            $subquery->whereRaw('stockproducto.producto_id = producto.id')->where("stockproducto.sucursal_id", "=", $sucursal_id);
        })
            ->where('nombre', 'like', '%' . strtoupper($descripcion) . '%')
            ->select('producto.*', 'stockproducto.cantidad')->get();
        $c = 0;
        $data = array();
        //echo json_encode($resultado);exit();
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
        $sucursal_id = $request->input("sucursal_id");
        $codigobarra = $request->input("codigobarra");
        $resultado = Producto::leftjoin('stockproducto', function ($subquery) use ($sucursal_id) {
            $subquery->whereRaw('stockproducto.producto_id = producto.id')->where("stockproducto.sucursal_id", "=", $sucursal_id);
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
}
