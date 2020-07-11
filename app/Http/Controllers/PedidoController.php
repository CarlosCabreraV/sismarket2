<?php

namespace App\Http\Controllers;

use App\Caja;
use App\Configuracion;
use App\Detallemovimiento;
use App\Detallepedido;
use App\Detalleproducto;
use App\Detallepromocion;
use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use App\Movimiento;
use App\Pedido;
use App\Stockproducto;
use App\Sucursal;
use App\Tipodocumento;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    protected $folderview      = 'app.pedido';
    protected $tituloAdmin     = 'Pedido';
    protected $tituloRegistrar = 'Registrar pedido';
    protected $tituloModificar = 'Cambiar estado';
    protected $tituloModificarPedido = 'Modificar pedido';
    protected $tituloEliminar  = 'Anular pedido';
    protected $tituloVer       = 'Ver Pedido';
    protected $rutas           = array('create' => 'pedido.create', 
            'edit'   => 'pedido.edit',
            'show'   => 'pedido.show', 
            'delete' => 'pedido.eliminar',
            'search' => 'pedido.buscar',
            'index'  => 'pedido.index',
            'siguiente'  => 'pedido.siguienteEtapa',
            'viewUpdate'  => 'pedido.viewUpdate',
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
        $entidad          = 'Pedido';
        $resultado        = Pedido::join('person as cliente','cliente.id','=','pedido.cliente_id')
                                ->join('person as responsable','responsable.id','=','pedido.responsable_id');
        if($request->input('fechainicio')!=""){
            $resultado = $resultado->where('pedido.created_at','>=',$request->input('fechainicio'));
        }
        if($request->input('fechafin')!=""){
            $resultado = $resultado->where('pedido.created_at','<=',$request->input('fechafin'));
        }
        if($request->input('estado')!=""  && $request->input('estado')){
            $resultado = $resultado->where('estado',$request->input('estado'));
        }
        if($request->input('tipo')!=""  && $request->input('tipo')){
            $resultado = $resultado->where('delivery',$request->input('tipo'));
        }
        if($request->input('cliente')!=""){
            $cliente =$request->input('cliente');
            $resultado = $resultado->where(function($query) use ($cliente){
                            $query->where('pedido.nombre', 'like', '%'.$cliente.'%')
                                ->orWhere('pedido.dni', 'like', '%'.$cliente.'%')
                                ->orWhere('pedido.ruc', 'like', '%'.$cliente.'%')
                                ->orWhere(DB::raw('concat(cliente.apellidopaterno,\' \',cliente.apellidomaterno,\' \',cliente.nombres)'), 'like', '%'.$cliente.'%');
                        });
        }
        if($request->input('tipodocumento_id')!=""){
            $resultado = $resultado->where('pedido.tipodocumento_id','=',$request->input('tipodocumento_id'));
        }
        
        $lista            = $resultado->select('pedido.*',DB::raw('concat(cliente.apellidopaterno,\' \',cliente.apellidomaterno,\' \',cliente.nombres) as cliente'),DB::raw('concat(responsable.apellidopaterno,\' \',responsable.apellidomaterno,\' \',responsable.nombres) as responsable'))->orderBy('pedido.id', 'desc')->orderBy('pedido.created_at', 'desc')->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Tipo Doc.', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Cliente', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Usuario', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Delivery', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Estado', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Total', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Operaciones', 'numero' => '4');
        
        $titulo_modificar = $this->tituloModificar;
        $titulo_modificarPedido = $this->tituloModificarPedido;
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
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'ruta', 'titulo_ver','titulo_modificarPedido'));
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
        $entidad          = 'Pedido';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboEstados = array('' => 'Todos' , 'N'=>'Nuevo','A'=>'Aceptado','E'=>'Enviado','F'=>'Finalizado');
        $cboTipoDocumento = array('' => 'Todos');
        $tipodocumento = Tipodocumento::where('tipomovimiento_id','=',2)->orderBy('nombre','asc')->get();
        foreach($tipodocumento as $k=>$v){
            $cboTipoDocumento = $cboTipoDocumento + array($v->id => $v->nombre);
        }
        return view($this->folderview.'.admin')->with(compact('entidad','cboEstados', 'title', 'titulo_registrar', 'ruta', 'cboTipoDocumento'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $current_user     = Auth::User();
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $entidad  = 'Pedido';
        $pedido = null;
        $formData = array('pedido.store');
        $formData = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        $conf_codigobarra = CODIGO_BARRAS;
        $cboModoPago = ["EFECTIVO" =>'Efectivo','TARJETA'=>'Tarjeta','YAPE'=>'Yape','DEPOSITO'=>'Deposito'];
        $cboTarjetas = ["VISA" =>'Visa','MASTERCARD'=>'Mastercard'];
        if (!$current_user->isAdmin() && !$current_user->isSuperAdmin()) {
        $cboTipoDocumento = Tipodocumento::where('tipomovimiento_id', '=', 2)->orderBy('nombre', 'asc')->pluck('nombre', 'id')->all();
        $cboSucursal = ["" => "SELECCIONE SUCURSAL"] + Sucursal::pluck('nombre', 'id')->all();
            $cboSucursal = Sucursal::where('id', '=', $current_user->sucursal_id)->pluck('nombre', 'id')->all();
        }
        $boton    = 'Registrar';
        return view($this->folderview . '.mant')->with(compact('cboTarjetas','cboModoPago','pedido', 'formData', 'entidad', 'boton', 'listar', 'cboTipoDocumento', 'cboSucursal', 'conf_codigobarra'));
    }

    public function store(Request $request){
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $reglas     = array(
            'persona' => 'required|max:500',
            'sucursal_id'   => 'required|integer|exists:sucursal,id,deleted_at,NULL',
            'telefono'   => 'required',
            'direccion'   => 'required',
        );
        $mensajes = array(
            'nombre.required'         => 'Debe ingresar un cliente',
            'sucursal_id.required' => 'Debe seleccionar una sucursal.'
        );
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $user = Auth::user();
        $dat = array();

        $caja_sesion_id     = session('caja_sesion_id', '0');
        $caja_sesion        = Caja::where('id', $caja_sesion_id)->first();
        $estado_caja        = $caja_sesion->estado;

        try {
            if ($estado_caja == 'CERRADA') {
                $dat[0] = array("respuesta" => "ERROR", "msg" => "CAJA CERRADA");
                throw new \Exception(json_encode($dat));
            }
            $error = DB::transaction(function () use ($request, $user, &$dat) {
                //-------------------CREAR PEDIDO------------------------
                    $Pedido       = new Pedido();
                    /*PARA PEDIDOS WEB
                        if($request->input('nombre_cliente')){
                            $Pedido->nombre = $request->input('nombre_cliente');
                        }
                        if($request->input('dni_cliente')){
                            $Pedido->dni = $request->input('dni_cliente');
                        }
                        if($request->input('ruc_cliente')){
                            $Pedido->ruc = $request->input('ruc_cliente');
                        }
                    */
                    $Pedido->cliente_id = $request->input('persona_id') == "0" ? 1 : $request->input('persona_id');
                    $Pedido->telefono = Libreria::getParam($request->input('telefono'));
                    $Pedido->direccion = Libreria::getParam($request->input('direccion'));
                    $Pedido->referencia = Libreria::getParam($request->input('referencia'));
                    $Pedido->detalle = Libreria::getParam($request->input('detalle'));
                    $Pedido->tipodocumento_id = $request->input('tipodocumento');
                    $Pedido->delivery = $request->input('delivery');
                    $Pedido->modopago = $request->input('modopago');
                    if($request->input('modopago')=='TARJETA'){
                        $Pedido->tarjeta =$request->input('tarjeta');
                    }
                    if($Pedido->delivery == 'S'){
                        $Pedido->estado = 'A'; //ACEPTADO ( PUESTO QUE ES REGISTRADO POR EL CAJERO)
                    }else{
                        $Pedido->estado = 'PR'; //POR RECOGER (RECOJO EN TIENDA)    
                    }
                    $Pedido->responsable_id = $user->person_id;
                    $Pedido->sucursal_id = $request->input('sucursal_id');
                    $Pedido->fechaaceptado =new DateTime();
                    if ($request->input('tipodocumento') == "4" || $request->input('tipodocumento') == "3") { //FACTURA O BOLETA
                        $Pedido->subtotal = round($request->input('total') / 1.18, 2); //82%
                        $Pedido->igv = round($request->input('total') - $Pedido->subtotal, 2); //18%
                    } else { //TICKET
                        $Pedido->subtotal = $request->input('total');
                        $Pedido->igv = 0;
                    }
                    $Pedido->total = str_replace(",", "", $request->input('total')); 
                    $Pedido->save();
                //---------------------FIN CREAR PEDIDO------------------------

                //---------------------DETALLES VENTA------------------------------
                    $arr = explode(",", $request->input('listProducto'));
                    //dd($request);
                    for ($c = 0; $c < count($arr); $c++) {
                        $Detalle = new Detallepedido();
                        $Detalle->pedido_id = $Pedido->id;
                        if($request->input('txtTipo'.$arr[$c])=="P"){
                            $Detalle->producto_id=$request->input('txtIdProducto'.$arr[$c]);
                        }else{
                            $Detalle->promocion_id=$request->input('txtIdProducto'.$arr[$c]);
                        }
                        $Detalle->cantidad = $request->input('txtCantidad' . $arr[$c]);
                        $Detalle->precioventa = $request->input('txtPrecio' . $arr[$c]);
                        $Detalle->preciocompra = Libreria::getParam($request->input('txtPrecioCompra' . $arr[$c]), '0');
                        $Detalle->save();
                        
                        if ($request->input('txtTipo'.$arr[$c])=="P") {
                            //DISMINUIR STOCK DEL PRODUCTO
                                //SI ES UNA PRESENTACION
                                $detalleproducto = Detalleproducto::where('producto_id', '=', $Detalle->producto_id)->get();
                                if (count($detalleproducto) > 0) {
                                    //REDUCIR STOCK EN CADA UNO DE LOS PRODUCTOS DE LA PRESENTACION
                                    foreach ($detalleproducto as $key => $value) {
                                        $stock = Stockproducto::where('producto_id', '=', $value->presentacion_id)->first();
                                        //SI TIENE STOCK REGISTRADO
                                        if (count($stock) > 0) {
                                            //SI EL STOCK ES SUFICIENTE
                                            if ($stock->cantidad >= $Detalle->cantidad * $value->cantidad) {
                                                $stock->cantidad = $stock->cantidad - $Detalle->cantidad * $value->cantidad;
                                                $stock->save();
                                            }
                                            //SI NO HAY STOCK SUFICIENTE
                                            else {
                                                if (STOCK_NEGATIVO == 'S') {
                                                    $stock->cantidad = $stock->cantidad - $Detalle->cantidad * $value->cantidad;
                                                    $stock->save();
                                                } else {
                                                    $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                                    throw new \Exception(json_encode($dat));
                                                }
                                            }
                                        }
                                        //SI NO TIENE STOCK REGISTRADO
                                        else {
                                            $stock = new Stockproducto();
                                            $stock->sucursal_id = $request->input('sucursal_id');
                                            $stock->producto_id = $value->presentacion_id;
                                            if (STOCK_NEGATIVO == 'S') {
                                                $stock->cantidad = $Detalle->cantidad * (-1) * $value->cantidad;
                                                $stock->save();
                                            } else {
                                                $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                                throw new \Exception(json_encode($dat));
                                            }
                                            $stock->save();
                                        }
                                    }
                                }
                                //SI ES UN PRODUCTO
                                else {
                                    $stock = Stockproducto::where('producto_id', '=', $Detalle->producto_id)->first();
                                    // SI TIENE STOCK REGISTRADO
                                    if (count($stock) > 0) {
                                        if ($stock->cantidad >= $Detalle->cantidad) {
                                            $stock->cantidad = $stock->cantidad - $Detalle->cantidad;
                                        } else {
                                            if (STOCK_NEGATIVO == 'S') {
                                                $stock->cantidad = $stock->cantidad - $Detalle->cantidad;
                                            } else {
                                                $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                                throw new \Exception(json_encode($dat));
                                            }
                                        }
                                        $stock->save();
                                    }
                                    //SI NO TIENE STOCK REGISTRADO
                                    else {
                                        $stock = new Stockproducto();
                                        $stock->sucursal_id = $request->input('sucursal_id');
                                        $stock->producto_id = $Detalle->producto_id;
                                        if (STOCK_NEGATIVO == 'S') {
                                            $stock->cantidad = $Detalle->cantidad * (-1);
                                        } else {
                                            $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                            throw new \Exception(json_encode($dat));
                                        }
                                        $stock->save();
                                    }
                                }
                            //FIN DISMINUIR STOCK DEL PRODUCTO
                        }else{

                            //DISMINUIR STOCK DE CADA UNO DE LOS PRODUCTOS DE LA PROMOCION
                            $lista = Detallepromocion::where('promocion_id','=',$Detalle->promocion_id)->get();
                            foreach ($lista as $key => $detallepromo) {
                            //DISMINUIR STOCK DEL PRODUCTO
                                //SI ES UNA PRESENTACION
                                $presentaciones = Detalleproducto::where('producto_id', '=', $detallepromo->producto_id)->get();
                                if (count($presentaciones) > 0) {
                                    //REDUCIR STOCK EN CADA UNO DE LOS PRODUCTOS DE LA PRESENTACION
                                    foreach ($presentaciones as $key => $presentacion) {
                                        $stock = Stockproducto::where('producto_id', '=', $presentacion->presentacion_id)->first();
                                        //SI TIENE STOCK REGISTRADO
                                        if (count($stock) > 0) {
                                            //SI EL STOCK ES SUFICIENTE
                                            if ($stock->cantidad >= ($Detalle->cantidad * $presentacion->cantidad * $detallepromo->cantidad)) {
                                                $stock->cantidad = $stock->cantidad - ($Detalle->cantidad * $detallepromo->cantidad * $presentacion->cantidad);
                                                $stock->save();
                                            }
                                            //SI NO HAY STOCK SUFICIENTE
                                            else {
                                                if (STOCK_NEGATIVO == 'S') {
                                                    $stock->cantidad = $stock->cantidad - ($Detalle->cantidad *$detallepromo->cantidad* $presentacion->cantidad);
                                                    $stock->save();
                                                } else {
                                                    $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                                    throw new \Exception(json_encode($dat));
                                                }
                                            }
                                        }
                                        //SI NO TIENE STOCK REGISTRADO
                                        else {
                                            $stock = new Stockproducto();
                                            $stock->sucursal_id = $request->input('sucursal_id');
                                            $stock->producto_id = $presentacion->presentacion_id;
                                            if (STOCK_NEGATIVO == 'S') {
                                                $stock->cantidad =(-1)*$Detalle->cantidad *$detallepromo->cantidad * $presentacion->cantidad;
                                                $stock->save();
                                            } else {
                                                $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                                throw new \Exception(json_encode($dat));
                                            }
                                        }
                                    }
                                }
                                //SI ES UN PRODUCTO
                                else {
                                    $stock = Stockproducto::where('producto_id', '=', $detallepromo->producto_id)->first();
                                    // SI TIENE STOCK REGISTRADO
                                    if (count($stock) > 0) {
                                        if ($stock->cantidad >= $Detalle->cantidad*$detallepromo->cantidad) {
                                            $stock->cantidad = $stock->cantidad - $Detalle->cantidad*$detallepromo->cantidad;
                                        } else {
                                            if (STOCK_NEGATIVO == 'S') {
                                                $stock->cantidad = $stock->cantidad - $Detalle->cantidad*$detallepromo->cantidad;
                                            } else {
                                                $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                                throw new \Exception(json_encode($dat));
                                            }
                                        }
                                        $stock->save();
                                    }
                                    //SI NO TIENE STOCK REGISTRADO
                                    else {
                                        $stock = new Stockproducto();
                                        $stock->sucursal_id = $request->input('sucursal_id');
                                        $stock->producto_id = $detallepromo->producto_id;
                                        if (STOCK_NEGATIVO == 'S') {
                                            $stock->cantidad = $Detalle->cantidad *$detallepromo->cantidad*(-1);
                                        } else {
                                            $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                            throw new \Exception(json_encode($dat));
                                        }
                                        $stock->save();
                                    }
                                }
                            //FIN DISMINUIR STOCK DEL PRODUCTO
                            }
                        }
                    }
                //-----------------------FIN DETALLES VENTA------------------------------

                //----------------------CAJA--------------------------------
                /*
                    $movimiento        = new Movimiento();
                    $movimiento->fecha = date("Y-m-d");
                    $movimiento->numero = Movimiento::NumeroSigue(4, 6);
                    $movimiento->responsable_id = $user->person_id;
                    $movimiento->persona_id = $request->input('persona_id') == "0" ? 1 : $request->input('persona_id');
                    $movimiento->subtotal = 0;
                    $movimiento->igv = 0;
                    $movimiento->total = str_replace(",", "", $request->input('total'));
                    $movimiento->totalpagado = str_replace(",", "", $request->input('totalpagado'));
                    $movimiento->tarjeta = str_replace(",", "", $request->input('tarjeta'));
                    $movimiento->tipomovimiento_id = 4;
                    $movimiento->tipodocumento_id = 6;
                    $movimiento->concepto_id = 3;
                    $movimiento->voucher = '';
                    $movimiento->comentario = 'Pago de Documento de Venta ' . $Venta->numero;
                    $movimiento->situacion = 'N';
                    $movimiento->movimiento_id = $Venta->id;

                    $movimiento->sucursal_id = $request->input('sucursal_id');
                    $movimiento->caja_id = session('caja_sesion_id', '');
                    $movimiento->save();
                    */
                //------------------------FIN CAJA--------------------------------

                $dat[0] = array("respuesta" => "OK", "pedido_id" => $Pedido->id, "tipodocumento_id" => $Pedido->tipodocumento_id);
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
        $existe = Libreria::verificarExistencia($id, 'pedido');
        if ($existe !== true) {
            return $existe;
        }
        $listar              = Libreria::getParam($request->input('listar'), 'NO');
        $pedido = Pedido::find($id);
        $cliente = '';
        if($pedido->nombre ){
            $cliente = $pedido->nombre;
        }else{
            $cliente = $pedido->cliente->apellidopaterno." ".$pedido->cliente->apellidomaterno." ".$pedido->cliente->nombres;
        }
        $entidad             = 'Pedido';
        $cboTipoDocumento        = Tipodocumento::pluck('nombre', 'id')->all();
        $cboSucursal = Sucursal::pluck('nombre','id')->all();
        $formData            = array('pedido.update', $id);
        $formData            = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        $boton               = 'Modificar';
        //$cuenta = Cuenta::where('movimiento_id','=',$compra->id)->orderBy('id','ASC')->first();
        //$fechapago =  Date::createFromFormat('Y-m-d', $cuenta->fecha)->format('d/m/Y');
        $detalles = Detallepedido::where('pedido_id', '=', $pedido->id)->get();
        //$numerocuotas = count($cuentas);
        return view($this->folderview . '.mantView')->with(compact('cliente','cboSucursal','pedido', 'formData', 'entidad', 'boton', 'listar', 'cboTipoDocumento', 'detalles'));
   
     }

     public function viewUpdate(Request $request,$id){
        $existe = Libreria::verificarExistencia($id, 'pedido');
        if ($existe !== true) {
            return $existe;
        }
        $listar              = Libreria::getParam($request->input('listar'), 'NO');
        $pedido = Pedido::find($id);
        $cliente = '';
        if($pedido->nombre ){
            $cliente = $pedido->nombre;
        }else{
            $cliente = $pedido->cliente->apellidopaterno." ".$pedido->cliente->apellidomaterno." ".$pedido->cliente->nombres;
        }
        $entidad             = 'Pedido';
        $cboTipoDocumento        = Tipodocumento::pluck('nombre', 'id')->all();
        $cboSucursal = Sucursal::pluck('nombre','id')->all();
        $formData            = array('pedido.update', $id);
        $formData            = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        $boton               = 'Modificar';
        $conf_codigobarra = CODIGO_BARRAS;
        $cboModoPago = ["EFECTIVO" =>'Efectivo','TARJETA'=>'Tarjeta','YAPE'=>'Yape','DEPOSITO'=>'Deposito'];
        $cboTarjetas = ["VISA" =>'Visa','MASTERCARD'=>'Mastercard'];
        
        //$cuenta = Cuenta::where('movimiento_id','=',$compra->id)->orderBy('id','ASC')->first();
        //$fechapago =  Date::createFromFormat('Y-m-d', $cuenta->fecha)->format('d/m/Y');
        $detalles = Detallepedido::where('pedido_id', '=', $pedido->id)->get();
        //$numerocuotas = count($cuentas);
        return view($this->folderview . '.mantViewUpdate')->with(compact('conf_codigobarra','cboModoPago','cboTarjetas','cliente','cboSucursal','pedido', 'formData', 'entidad', 'boton', 'listar', 'cboTipoDocumento', 'detalles'));
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
        $listar     = Libreria::getParam($request->input('listar'), 'NO');
        $reglas     = array(
            'telefono'   => 'required',
            'direccion'   => 'required',
        );
        $mensajes = array(
            'sucursal_id.required' => 'Debe seleccionar una sucursal.'
        );
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $user = Auth::user();
        $dat = array();

        $caja_sesion_id     = session('caja_sesion_id', '0');
        $caja_sesion        = Caja::where('id', $caja_sesion_id)->first();
        $estado_caja        = $caja_sesion->estado;

        try {
            if ($estado_caja == 'CERRADA') {
                $dat[0] = array("respuesta" => "ERROR", "msg" => "CAJA CERRADA");
                throw new \Exception(json_encode($dat));
            }
            $error = DB::transaction(function () use ($request, $user, &$dat , $id) {
                //-------------------EDITAR PEDIDO------------------------
                    $pedido       = Pedido::find($id);
                    /*PARA PEDIDOS WEB
                        if($request->input('nombre_cliente')){
                            $Pedido->nombre = $request->input('nombre_cliente');
                        }
                        if($request->input('dni_cliente')){
                            $Pedido->dni = $request->input('dni_cliente');
                        }
                        if($request->input('ruc_cliente')){
                            $Pedido->ruc = $request->input('ruc_cliente');
                        }
                    */
                    $pedido->telefono = Libreria::getParam($request->input('telefono'));
                    $pedido->direccion = Libreria::getParam($request->input('direccion'));
                    $pedido->referencia = Libreria::getParam($request->input('referencia'));
                    $pedido->detalle = Libreria::getParam($request->input('detalle'));
                    $pedido->modopago = $request->input('modopago');
                    if($request->input('modopago')=='TARJETA'){
                        $pedido->tarjeta =$request->input('tarjeta');
                    }
                    $pedido->delivery = $request->input('delivery');
                    if(($request->input('delivery')== 'N') && ($pedido->estado =='A')){
                        $pedido->estado = 'PR';
                    }
                    if(($request->input('delivery')== 'S') && ($pedido->estado =='PR')){
                        $pedido->estado = 'A';
                    }
                    $pedido->responsable_id = $user->person_id;
                    if ($request->tipodocumento_id == "4" || $request->tipodocumento_id == "3") { //FACTURA O BOLETA
                        $pedido->subtotal = round($request->input('total') / 1.18, 2); //82%
                        $pedido->igv = round($request->input('total') - $pedido->subtotal, 2); //18%
                    } else { //TICKET
                        $pedido->subtotal = $request->input('total');
                        $pedido->igv = 0;
                    }
                    $pedido->total = str_replace(",", "", $request->input('total')); 
                    $pedido->save();
                //---------------------FIN EDITAR PEDIDO------------------------

                //---------------ELIMINAR TODOS LOS DETALLES
                    $lst = Detallepedido::where('pedido_id', '=', $id)->get();
                    foreach ($lst as $key => $Detalle) {
                        if ($Detalle->producto_id && $Detalle->producto_id != null ) {
                            $detalleproducto = Detalleproducto::where('producto_id', '=', $Detalle->producto_id)->get();
                            if (count($detalleproducto) > 0) {
                                foreach ($detalleproducto as $key => $value) {
                                    $stock = Stockproducto::where('producto_id', '=', $value->presentacion_id)->where('sucursal_id', $pedido->sucursal_id)->first();
                                    if (count($stock) > 0) {
                                        $stock->cantidad = $stock->cantidad +($Detalle->cantidad * $value->cantidad);
                                        $stock->save();
                                    } else {
                                        $stock = new Stockproducto();
                                        $stock->sucursal_id = $pedido->sucursal_id;
                                        $stock->producto_id = $value->presentacion_id;
                                        $stock->cantidad = $Detalle->cantidad * $value->cantidad;
                                        $stock->save();
                                    }
                                }
                            } else {
                                $stock = Stockproducto::where('producto_id', '=', $Detalle->producto_id)->where('sucursal_id', $pedido->sucursal_id)->first();
                                if (count($stock) > 0) {
                                    $stock->cantidad = $stock->cantidad + $Detalle->cantidad;
                                    $stock->save();
                                } else {
                                    $stock = new Stockproducto();
                                    $stock->sucursal_id = $pedido->sucursal_id;
                                    $stock->producto_id = $Detalle->producto_id;
                                    $stock->cantidad = $Detalle->cantidad;
                                    $stock->save();
                                }
                            }
                        } else {
                            $detallespromocion = Detallepromocion::where('promocion_id', $Detalle->promocion_id)->get();
                            foreach($detallespromocion as $key => $detallepromo){
                                $presentaciones = Detalleproducto::where('producto_id', '=', $detallepromo->producto_id)->get();
                                if(count($presentaciones)>0){
                                    foreach($presentaciones as $key => $presentacion){
                                        $stock = Stockproducto::where('producto_id', $presentacion->presentacion_id)->where('sucursal_id',$pedido->sucursal_id)->first();
                                        if($stock){
                                            $stock->cantidad = $stock->cantidad + ($Detalle->cantidad * $detallepromo->cantidad * $presentacion->cantidad);
                                            $stock->save();
                                        }else{
                                            $stock = new StockProducto();
                                            $stock->producto_id = $presentacion->presentacion_id;
                                            $stock->sucursal_id = $pedido->sucursal_id;
                                            $stock->cantidad    = ($Detalle->cantidad * $detallepromo->cantidad * $presentacion->cantidad);
                                            $stock->save();
                                        }
                                    }
                                }else{
                                    $stock = StockProducto::where('producto_id',$detallepromo->producto_id)->where('sucursal_id',$pedido->sucursal_id)->first();
                                    if($stock){
                                        $stock->cantidad = $stock->cantidad + ($Detalle->cantidad * $detallepromo->cantidad);
                                        $stock->save();
                                    }else{
                                        $stock = new StockProducto();
                                        $stock->producto_id = $detallepromo->producto_id;
                                        $stock->sucursal_id = $pedido->sucursal_id;
                                        $stock->cantidad = $Detalle->cantidad * $detallepromo->cantidad;
                                        $stock->save();
                                    }
                                }
                            }

                        }
                        $Detalle->delete();
                    }
                //---------------FIN ELIMINAR TODOS LOS DETALLES
                //---------------------CREAR NUEVOS DETALLES PEDIDO------------------------------
                    $arr = explode(",", $request->input('listProducto'));
                    for ($c = 0; $c < count($arr); $c++) {
                        $Detalle = new Detallepedido();
                        $Detalle->pedido_id = $pedido->id;
                        if($request->input('txtTipo'.$arr[$c])=="P"){
                            $Detalle->producto_id=$request->input('txtIdProducto'.$arr[$c]);
                        }else{
                            $Detalle->promocion_id=$request->input('txtIdProducto'.$arr[$c]);
                        }
                        $Detalle->cantidad = $request->input('txtCantidad' . $arr[$c]);
                        $Detalle->precioventa = $request->input('txtPrecio' . $arr[$c]);
                        $Detalle->preciocompra = Libreria::getParam($request->input('txtPrecioCompra' . $arr[$c]), '0');
                        $Detalle->save();
                        
                        if ($request->input('txtTipo'.$arr[$c])=="P") {
                            //DISMINUIR STOCK DEL PRODUCTO
                                //SI ES UNA PRESENTACION
                                $detalleproducto = Detalleproducto::where('producto_id', '=', $Detalle->producto_id)->get();
                                if (count($detalleproducto) > 0) {
                                    //REDUCIR STOCK EN CADA UNO DE LOS PRODUCTOS DE LA PRESENTACION
                                    foreach ($detalleproducto as $key => $value) {
                                        $stock = Stockproducto::where('producto_id', '=', $value->presentacion_id)->first();
                                        //SI TIENE STOCK REGISTRADO
                                        if (count($stock) > 0) {
                                            //SI EL STOCK ES SUFICIENTE
                                            if ($stock->cantidad >= $Detalle->cantidad * $value->cantidad) {
                                                $stock->cantidad = $stock->cantidad - $Detalle->cantidad * $value->cantidad;
                                                $stock->save();
                                            }
                                            //SI NO HAY STOCK SUFICIENTE
                                            else {
                                                if (STOCK_NEGATIVO == 'S') {
                                                    $stock->cantidad = $stock->cantidad - $Detalle->cantidad * $value->cantidad;
                                                    $stock->save();
                                                } else {
                                                    $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                                    throw new \Exception(json_encode($dat));
                                                }
                                            }
                                        }
                                        //SI NO TIENE STOCK REGISTRADO
                                        else {
                                            $stock = new Stockproducto();
                                            $stock->sucursal_id = $request->input('sucursal_id');
                                            $stock->producto_id = $value->presentacion_id;
                                            if (STOCK_NEGATIVO == 'S') {
                                                $stock->cantidad = $Detalle->cantidad * (-1) * $value->cantidad;
                                                $stock->save();
                                            } else {
                                                $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                                throw new \Exception(json_encode($dat));
                                            }
                                            $stock->save();
                                        }
                                    }
                                }
                                //SI ES UN PRODUCTO
                                else {
                                    $stock = Stockproducto::where('producto_id', '=', $Detalle->producto_id)->first();
                                    // SI TIENE STOCK REGISTRADO
                                    if (count($stock) > 0) {
                                        if ($stock->cantidad >= $Detalle->cantidad) {
                                            $stock->cantidad = $stock->cantidad - $Detalle->cantidad;
                                        } else {
                                            if (STOCK_NEGATIVO == 'S') {
                                                $stock->cantidad = $stock->cantidad - $Detalle->cantidad;
                                            } else {
                                                $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                                throw new \Exception(json_encode($dat));
                                            }
                                        }
                                        $stock->save();
                                    }
                                    //SI NO TIENE STOCK REGISTRADO
                                    else {
                                        $stock = new Stockproducto();
                                        $stock->sucursal_id = $request->input('sucursal_id');
                                        $stock->producto_id = $Detalle->producto_id;
                                        if (STOCK_NEGATIVO == 'S') {
                                            $stock->cantidad = $Detalle->cantidad * (-1);
                                        } else {
                                            $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                            throw new \Exception(json_encode($dat));
                                        }
                                        $stock->save();
                                    }
                                }
                            //FIN DISMINUIR STOCK DEL PRODUCTO
                        }else{

                            //DISMINUIR STOCK DE CADA UNO DE LOS PRODUCTOS DE LA PROMOCION
                            $lista = Detallepromocion::where('promocion_id','=',$Detalle->promocion_id)->get();
                            foreach ($lista as $key => $detallepromo) {
                            //DISMINUIR STOCK DEL PRODUCTO
                                //SI ES UNA PRESENTACION
                                $presentaciones = Detalleproducto::where('producto_id', '=', $detallepromo->producto_id)->get();
                                if (count($presentaciones) > 0) {
                                    //REDUCIR STOCK EN CADA UNO DE LOS PRODUCTOS DE LA PRESENTACION
                                    foreach ($presentaciones as $key => $presentacion) {
                                        $stock = Stockproducto::where('producto_id', '=', $presentacion->presentacion_id)->first();
                                        //SI TIENE STOCK REGISTRADO
                                        if (count($stock) > 0) {
                                            //SI EL STOCK ES SUFICIENTE
                                            if ($stock->cantidad >= ($Detalle->cantidad * $presentacion->cantidad * $detallepromo->cantidad)) {
                                                $stock->cantidad = $stock->cantidad - ($Detalle->cantidad * $detallepromo->cantidad * $presentacion->cantidad);
                                                $stock->save();
                                            }
                                            //SI NO HAY STOCK SUFICIENTE
                                            else {
                                                if (STOCK_NEGATIVO == 'S') {
                                                    $stock->cantidad = $stock->cantidad - ($Detalle->cantidad *$detallepromo->cantidad* $presentacion->cantidad);
                                                    $stock->save();
                                                } else {
                                                    $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                                    throw new \Exception(json_encode($dat));
                                                }
                                            }
                                        }
                                        //SI NO TIENE STOCK REGISTRADO
                                        else {
                                            $stock = new Stockproducto();
                                            $stock->sucursal_id = $request->input('sucursal_id');
                                            $stock->producto_id = $presentacion->presentacion_id;
                                            if (STOCK_NEGATIVO == 'S') {
                                                $stock->cantidad =(-1)*$Detalle->cantidad *$detallepromo->cantidad * $presentacion->cantidad;
                                                $stock->save();
                                            } else {
                                                $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                                throw new \Exception(json_encode($dat));
                                            }
                                        }
                                    }
                                }
                                //SI ES UN PRODUCTO
                                else {
                                    $stock = Stockproducto::where('producto_id', '=', $detallepromo->producto_id)->first();
                                    // SI TIENE STOCK REGISTRADO
                                    if (count($stock) > 0) {
                                        if ($stock->cantidad >= $Detalle->cantidad*$detallepromo->cantidad) {
                                            $stock->cantidad = $stock->cantidad - $Detalle->cantidad*$detallepromo->cantidad;
                                        } else {
                                            if (STOCK_NEGATIVO == 'S') {
                                                $stock->cantidad = $stock->cantidad - $Detalle->cantidad*$detallepromo->cantidad;
                                            } else {
                                                $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                                throw new \Exception(json_encode($dat));
                                            }
                                        }
                                        $stock->save();
                                    }
                                    //SI NO TIENE STOCK REGISTRADO
                                    else {
                                        $stock = new Stockproducto();
                                        $stock->sucursal_id = $request->input('sucursal_id');
                                        $stock->producto_id = $detallepromo->producto_id;
                                        if (STOCK_NEGATIVO == 'S') {
                                            $stock->cantidad = $Detalle->cantidad *$detallepromo->cantidad*(-1);
                                        } else {
                                            $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                            throw new \Exception(json_encode($dat));
                                        }
                                        $stock->save();
                                    }
                                }
                            //FIN DISMINUIR STOCK DEL PRODUCTO
                            }
                        }
                    }
                //-----------------------FIN CREAR NUEVOS DETALLES PEDIDO------------------------------

                $dat[0] = array("respuesta" => "OK", "pedido_id" => $pedido->id, "tipodocumento_id" => $pedido->tipodocumento_id);
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
    public function eliminar($id, $listarLuego)
    {
        $existe = Libreria::verificarExistencia($id, 'pedido');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Pedido::find($id);
        $entidad  = 'Pedido';
        $formData = array('route' => array('pedido.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        $boton    = 'Anular';
        return view('app.confirmarEliminar')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    
     }

     public function siguienteEtapa($id , $listarLuego){
        $existe = Libreria::verificarExistencia($id, 'pedido');
        if ($existe !== true) {
            return $existe;
        }
        $listar = "NO";
        if (!is_null(Libreria::obtenerParametro($listarLuego))) {
            $listar = $listarLuego;
        }
        $modelo   = Pedido::find($id);
        $boton = '';
        $texto = '';
        $ruta='';
        $bg_class = '';
        
        if($modelo->delivery == 'S'){
            if($modelo->estado == 'N'){ //NUEVO
                $bg_class = 'primary';
                $ruta = 'aceptar';
                $boton = 'Aceptar';
                $texto = 'El pedido cambiará de estado a ACEPTADO, esto implica que se ha revisado que los datos proporcionados por el cliente son correctos.';
            }else if ($modelo->estado == 'A'){ //ACEPTADO
                $bg_class = 'olive';
                $ruta = 'enviar';
                $boton = 'Enviar';
                $texto = 'El pedido cambiará de estado a ENVIADO, esto implica que salió de tienda y será entregado en las próximas horas al cliente. Se registrará la venta.';
            }else if ($modelo->estado == 'E'){ // ENVIADO
                $bg_class = 'success';
                $ruta = 'finalizar';
                $boton = 'Finalizar';
                $texto = 'El pedido cambiará de estado a FINALIZADO, se registrará el ingreso a caja correspondiente.';
            }
        }else if ($modelo->delivery == 'N'){
            if($modelo->estado == 'PR'){ //POR RECOGER
                $bg_class = 'success';
                $ruta = 'enviaryfinalizar';
                $boton = 'Finalizar';
                $texto = 'El pedido cambiará de estado a FINALIZADO, se registrará la venta y el ingreso a caja correspondiente.';
            }
        }


        $entidad  = 'Pedido';
        $formData = array('route' => array('pedido.'.$ruta, $id), 'method' => 'get', 'class' => 'form-horizontal', 'id' => 'formMantenimiento' . $entidad, 'autocomplete' => 'off');
        return view($this->folderview.'.siguienteEtapa')->with(compact('bg_class','texto','modelo', 'formData', 'entidad', 'boton', 'listar'));
    
    
     }

     public function aceptar( $id){
        $existe = Libreria::verificarExistencia($id, 'pedido');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function () use ($id) {
            $pedido = Pedido::find($id);
            if($pedido->delivery == 'S'){
                $pedido->estado = 'A';
            }else{
                $pedido->estado = 'PR';
            }
            $pedido->fechaaceptado = new DateTime();
            $pedido->save();

            $detallespedido = Detallepedido::where('pedido_id',$pedido->id)->get();
            foreach($detallespedido as $Detalle) {
                
                if (!is_null($Detalle->producto_id)) {
                    //DISMINUIR STOCK DEL PRODUCTO
                        //SI ES UNA PRESENTACION
                        $detalleproducto = Detalleproducto::where('producto_id', '=', $Detalle->producto_id)->get();
                        if (count($detalleproducto) > 0) {
                            //REDUCIR STOCK EN CADA UNO DE LOS PRODUCTOS DE LA PRESENTACION
                            foreach ($detalleproducto as $key => $value) {
                                $stock = Stockproducto::where('producto_id', '=', $value->presentacion_id)->first();
                                //SI TIENE STOCK REGISTRADO
                                if (count($stock) > 0) {
                                    //SI EL STOCK ES SUFICIENTE
                                    if ($stock->cantidad >= $Detalle->cantidad * $value->cantidad) {
                                        $stock->cantidad = $stock->cantidad - $Detalle->cantidad * $value->cantidad;
                                        $stock->save();
                                    }
                                    //SI NO HAY STOCK SUFICIENTE
                                    else {
                                        if (STOCK_NEGATIVO == 'S') {
                                            $stock->cantidad = $stock->cantidad - $Detalle->cantidad * $value->cantidad;
                                            $stock->save();
                                        } else {
                                            $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                            throw new \Exception(json_encode($dat));
                                        }
                                    }
                                }
                                //SI NO TIENE STOCK REGISTRADO
                                else {
                                    $stock = new Stockproducto();
                                    $stock->sucursal_id = $pedido->sucursal_id;
                                    $stock->producto_id = $value->presentacion_id;
                                    if (STOCK_NEGATIVO == 'S') {
                                        $stock->cantidad = $Detalle->cantidad * (-1) * $value->cantidad;
                                        $stock->save();
                                    } else {
                                        $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                        throw new \Exception(json_encode($dat));
                                    }
                                    $stock->save();
                                }
                            }
                        }
                        //SI ES UN PRODUCTO
                        else {
                            $stock = Stockproducto::where('producto_id', '=', $Detalle->producto_id)->first();
                            // SI TIENE STOCK REGISTRADO
                            if (count($stock) > 0) {
                                if ($stock->cantidad >= $Detalle->cantidad) {
                                    $stock->cantidad = $stock->cantidad - $Detalle->cantidad;
                                } else {
                                    if (STOCK_NEGATIVO == 'S') {
                                        $stock->cantidad = $stock->cantidad - $Detalle->cantidad;
                                    } else {
                                        $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                        throw new \Exception(json_encode($dat));
                                    }
                                }
                                $stock->save();
                            }
                            //SI NO TIENE STOCK REGISTRADO
                            else {
                                $stock = new Stockproducto();
                                $stock->sucursal_id = $pedido->sucursal_id;
                                $stock->producto_id = $Detalle->producto_id;
                                if (STOCK_NEGATIVO == 'S') {
                                    $stock->cantidad = $Detalle->cantidad * (-1);
                                } else {
                                    $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                    throw new \Exception(json_encode($dat));
                                }
                                $stock->save();
                            }
                        }
                    //FIN DISMINUIR STOCK DEL PRODUCTO
                }else{

                    //DISMINUIR STOCK DE CADA UNO DE LOS PRODUCTOS DE LA PROMOCION
                    $lista = Detallepromocion::where('promocion_id','=',$Detalle->promocion_id)->get();
                    foreach ($lista as $key => $detallepromo) {
                    //DISMINUIR STOCK DEL PRODUCTO
                        //SI ES UNA PRESENTACION
                        $presentaciones = Detalleproducto::where('producto_id', '=', $detallepromo->producto_id)->get();
                        if (count($presentaciones) > 0) {
                            //REDUCIR STOCK EN CADA UNO DE LOS PRODUCTOS DE LA PRESENTACION
                            foreach ($presentaciones as $key => $presentacion) {
                                $stock = Stockproducto::where('producto_id', '=', $presentacion->presentacion_id)->first();
                                //SI TIENE STOCK REGISTRADO
                                if (count($stock) > 0) {
                                    //SI EL STOCK ES SUFICIENTE
                                    if ($stock->cantidad >= ($Detalle->cantidad * $presentacion->cantidad * $detallepromo->cantidad)) {
                                        $stock->cantidad = $stock->cantidad - ($Detalle->cantidad * $detallepromo->cantidad * $presentacion->cantidad);
                                        $stock->save();
                                    }
                                    //SI NO HAY STOCK SUFICIENTE
                                    else {
                                        if (STOCK_NEGATIVO == 'S') {
                                            $stock->cantidad = $stock->cantidad - ($Detalle->cantidad *$detallepromo->cantidad* $presentacion->cantidad);
                                            $stock->save();
                                        } else {
                                            $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                            throw new \Exception(json_encode($dat));
                                        }
                                    }
                                }
                                //SI NO TIENE STOCK REGISTRADO
                                else {
                                    $stock = new Stockproducto();
                                    $stock->sucursal_id = $pedido->sucursal_id;
                                    $stock->producto_id = $presentacion->presentacion_id;
                                    if (STOCK_NEGATIVO == 'S') {
                                        $stock->cantidad =(-1)*$Detalle->cantidad *$detallepromo->cantidad * $presentacion->cantidad;
                                        $stock->save();
                                    } else {
                                        $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                        throw new \Exception(json_encode($dat));
                                    }
                                }
                            }
                        }
                        //SI ES UN PRODUCTO
                        else {
                            $stock = Stockproducto::where('producto_id', '=', $detallepromo->producto_id)->first();
                            // SI TIENE STOCK REGISTRADO
                            if (count($stock) > 0) {
                                if ($stock->cantidad >= $Detalle->cantidad*$detallepromo->cantidad) {
                                    $stock->cantidad = $stock->cantidad - $Detalle->cantidad*$detallepromo->cantidad;
                                } else {
                                    if (STOCK_NEGATIVO == 'S') {
                                        $stock->cantidad = $stock->cantidad - $Detalle->cantidad*$detallepromo->cantidad;
                                    } else {
                                        $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                        throw new \Exception(json_encode($dat));
                                    }
                                }
                                $stock->save();
                            }
                            //SI NO TIENE STOCK REGISTRADO
                            else {
                                $stock = new Stockproducto();
                                $stock->sucursal_id = $pedido->sucursal_id;
                                $stock->producto_id = $detallepromo->producto_id;
                                if (STOCK_NEGATIVO == 'S') {
                                    $stock->cantidad = $Detalle->cantidad *$detallepromo->cantidad*(-1);
                                } else {
                                    $dat[0] = array("respuesta" => "ERROR", "msg" => "STOCK NEGATIVO: " . $stock->producto->nombre);
                                    throw new \Exception(json_encode($dat));
                                }
                                $stock->save();
                            }
                        }
                    //FIN DISMINUIR STOCK DEL PRODUCTO
                    }
                }
            }

        });
        return is_null($error) ? "OK" : $error;

     }  
     public function enviar( $id){
        $existe = Libreria::verificarExistencia($id, 'pedido');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function () use ($id) {
            $pedido = Pedido::find($id);
            $pedido->estado = 'E';
            $pedido->fechaenviado=new DateTime();
            $pedido->save();

            //GENERAR VENTA 
            $Venta       = new Movimiento();
            $Venta->fecha =  Date('Y-m-d');
            $Venta->numero = $this->generarNumero($pedido->tipodocumento_id);
            if ($pedido->tipodocumento_id == "4" || $pedido->tipodocumento_id == "3") { //FACTURA O BOLETA
                $Venta->subtotal = round($pedido->total / 1.18, 2); //82%
                $Venta->igv = round($pedido->total - $Venta->subtotal, 2); //18%
            } else { //TICKET
                $Venta->subtotal = $pedido->total;
                $Venta->igv = 0;
            }
            $Venta->total = $pedido->total;
            if($pedido->modopago == 'EFECTIVO'){
                $Venta->tarjeta = "";
                $Venta->totalpagado = $pedido->total;
            }else {
                $Venta->tarjeta = $pedido->total;
                $Venta->totalpagado = "0.00";
            }
            $Venta->tipomovimiento_id = 2; //VENTA
            $Venta->tipodocumento_id = $pedido->tipodocumento_id;
            $Venta->persona_id = $pedido->cliente_id?$pedido->cliente_id:'1';
            $Venta->situacion = 'P'; //Pendiente => P / Cobrado => C / Boleteado => B
            $Venta->voucher = '';
            $Venta->comentario = 'Venta generada a partir de un pedido';
            $Venta->responsable_id = $pedido->responsable_id;
            $Venta->pedido_id = $pedido->id;
            $Venta->sucursal_id = $pedido->sucursal_id;
            $Venta->save();

            foreach($pedido->detalles as $detalle){
                $detallemov = new Detallemovimiento();
                $detallemov->movimiento_id = $Venta->id;
                if($detalle->producto_id){
                    $detallemov->producto_id = $detalle->producto_id;
                }else{
                    $detallemov->promocion_id = $detalle->promocion_id;
                }
                $detallemov->cantidad = $detalle->cantidad;
                $detallemov->precioventa = $detalle->precioventa;
                $detallemov->preciocompra = $detalle->preciocompra;
                $detallemov->save();
            }

        });
        return is_null($error) ? "OK" : $error;

     }  
     public function finalizar( $id){
        $existe = Libreria::verificarExistencia($id, 'pedido');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function () use ($id) {
            $pedido = Pedido::find($id);
            $pedido->estado = 'F';
            $pedido->fechafinalizado = new DateTime();
            $pedido->save();

            $venta = Movimiento::where('pedido_id',$pedido->id)->first();
            $venta->situacion = 'C'; //Pendiente => P / Cobrado => C / Boleteado => B
            //----------------------CAJA--------------------------------
            $movimiento        = new Movimiento();
            $movimiento->fecha = date("Y-m-d");
            $movimiento->numero = Movimiento::NumeroSigue(4, 6);
            $movimiento->responsable_id =$venta->responsable_id;
            $movimiento->persona_id = $venta->persona_id;
            $movimiento->subtotal = 0;
            $movimiento->igv = 0;
            $movimiento->total = $venta->total;
            $movimiento->totalpagado = $venta->totalpagado;
            $movimiento->tarjeta = $venta->tarjeta;
            $movimiento->tipomovimiento_id = 4;
            $movimiento->tipodocumento_id = 6;
            $movimiento->concepto_id = 3;
            $movimiento->voucher = '';
            $movimiento->comentario = 'Pago de Documento de Venta ' . $venta->numero;
            $movimiento->situacion = 'N';
            $movimiento->movimiento_id = $venta->id;

            $movimiento->sucursal_id = $venta->sucursal_id;
            $movimiento->caja_id = session('caja_sesion_id', '');
            $movimiento->save();
        //------------------------FIN CAJA--------------------------------
        });
        return is_null($error) ? "OK" : $error;
     }

     public function enviaryfinalizar($id){
        $existe = Libreria::verificarExistencia($id, 'pedido');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function () use ($id) {
            $pedido = Pedido::find($id);
            $pedido->estado = 'F';
            $pedido->fechaenviado=new DateTime();
            $pedido->fechafinalizado=new DateTime();
            $pedido->save();

            //GENERAR VENTA 
            $Venta       = new Movimiento();
            $Venta->fecha =  Date('Y-m-d');
            $Venta->numero = $this->generarNumero($pedido->tipodocumento_id);
            if ($pedido->tipodocumento_id == "4" || $pedido->tipodocumento_id == "3") { //FACTURA O BOLETA
                $Venta->subtotal = round($pedido->total / 1.18, 2); //82%
                $Venta->igv = round($pedido->total - $Venta->subtotal, 2); //18%
            } else { //TICKET
                $Venta->subtotal = $pedido->total;
                $Venta->igv = 0;
            }
            $Venta->total = $pedido->total;
            if($pedido->modopago == 'EFECTIVO'){
                $Venta->tarjeta = "";
                $Venta->totalpagado = $pedido->total;
            }else {
                $Venta->tarjeta = $pedido->total;
                $Venta->totalpagado = "0.00";
            }
            $Venta->tipomovimiento_id = 2; //VENTA
            $Venta->tipodocumento_id = $pedido->tipodocumento_id;
            $Venta->persona_id = $pedido->cliente_id?$pedido->cliente_id:'1';
            $Venta->situacion = 'C'; //Pendiente => P / Cobrado => C / Boleteado => B
            $Venta->voucher = '';
            $Venta->comentario = 'Venta generada a partir de un pedido';
            $Venta->responsable_id = $pedido->responsable_id;
            $Venta->pedido_id = $pedido->id;
            $Venta->sucursal_id = $pedido->sucursal_id;
            $Venta->save();

            foreach($pedido->detalles as $detalle){
                $detallemov = new Detallemovimiento();
                $detallemov->movimiento_id = $Venta->id;
                if($detalle->producto_id){
                    $detallemov->producto_id = $detalle->producto_id;
                }else{
                    $detallemov->promocion_id = $detalle->promocion_id;
                }
                $detallemov->cantidad = $detalle->cantidad;
                $detallemov->precioventa = $detalle->precioventa;
                $detallemov->preciocompra = $detalle->preciocompra;
                $detallemov->save();
            }

            //----------------------CAJA--------------------------------
            $movimiento        = new Movimiento();
            $movimiento->fecha = date("Y-m-d");
            $movimiento->numero = Movimiento::NumeroSigue(4, 6);
            $movimiento->responsable_id =$Venta->responsable_id;
            $movimiento->persona_id = $Venta->persona_id;
            $movimiento->subtotal = 0;
            $movimiento->igv = 0;
            $movimiento->total = $Venta->total;
            $movimiento->totalpagado = $Venta->totalpagado;
            $movimiento->tarjeta = $Venta->tarjeta;
            $movimiento->tipomovimiento_id = 4;
            $movimiento->tipodocumento_id = 6;
            $movimiento->concepto_id = 3;
            $movimiento->voucher = '';
            $movimiento->comentario = 'Pago de Documento de Venta ' . $Venta->numero;
            $movimiento->situacion = 'N';
            $movimiento->movimiento_id = $Venta->id;

            $movimiento->sucursal_id = $Venta->sucursal_id;
            $movimiento->caja_id = session('caja_sesion_id', '');
            $movimiento->save();
        //------------------------FIN CAJA--------------------------------

        });
        return is_null($error) ? "OK" : $error;
     }
     
     public function generarNumero($tipodocumento_id)
    {
        $caja = Caja::find(session("caja_sesion_id"));
        if ($caja->serie == null) {
            $caja->serie = 1;
        }
        $serie = str_pad($caja->serie, 3, '0', STR_PAD_LEFT);
        $numeroventa = Movimiento::NumeroSigue(2, $tipodocumento_id);
        if ($tipodocumento_id == 3) {
            return "B" . $serie . "-" . $numeroventa;
        } elseif ($tipodocumento_id == 4) {
            return "F" . $serie . "-" . $numeroventa;
        } else {
            return "T" . $serie . "-" . $numeroventa;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
        $existe = Libreria::verificarExistencia($id, 'pedido');
        if ($existe !== true) {
            return $existe;
        }
        $error = DB::transaction(function () use ($id) {
            $pedido = Pedido::find($id);
            if($pedido->estado == 'E'){
                $venta = Movimiento::where('pedido_id',$pedido->id)->where('tipomovimiento_id',2)->first();
                $venta->situacion = 'A';
                $venta->save();
            }
            $pedido->estado = 'R';
            $pedido->fecharechazado = new DateTime();
            $pedido->save();
            $lst = Detallepedido::where('pedido_id', '=', $id)->get();
            foreach ($lst as $key => $Detalle) {
                if ($Detalle->producto_id && $Detalle->producto_id != null ) {
                    $detalleproducto = Detalleproducto::where('producto_id', '=', $Detalle->producto_id)->get();
                    if (count($detalleproducto) > 0) {
                        foreach ($detalleproducto as $key => $value) {
                            $stock = Stockproducto::where('producto_id', '=', $value->presentacion_id)->where('sucursal_id', $venta->sucursal_id)->first();
                            if (count($stock) > 0) {
                                $stock->cantidad = $stock->cantidad +($Detalle->cantidad * $value->cantidad);
                                $stock->save();
                            } else {
                                $stock = new Stockproducto();
                                $stock->sucursal_id = $pedido->sucursal_id;
                                $stock->producto_id = $value->presentacion_id;
                                $stock->cantidad = $Detalle->cantidad * $value->cantidad;
                                $stock->save();
                            }
                        }
                    } else {
                        $stock = Stockproducto::where('producto_id', '=', $Detalle->producto_id)->where('sucursal_id', $pedido->sucursal_id)->first();
                        if (count($stock) > 0) {
                            $stock->cantidad = $stock->cantidad + $Detalle->cantidad;
                            $stock->save();
                        } else {
                            $stock = new Stockproducto();
                            $stock->sucursal_id = $pedido->sucursal_id;
                            $stock->producto_id = $Detalle->producto_id;
                            $stock->cantidad = $Detalle->cantidad;
                            $stock->save();
                        }
                    }
                } else {
                    $detallespromocion = Detallepromocion::where('promocion_id', $Detalle->promocion_id)->get();
                    foreach($detallespromocion as $key => $detallepromo){
                        $presentaciones = Detalleproducto::where('producto_id', '=', $detallepromo->producto_id)->get();
                        if(count($presentaciones)>0){
                            foreach($presentaciones as $key => $presentacion){
                                $stock = Stockproducto::where('producto_id', $presentacion->presentacion_id)->where('sucursal_id',$pedido->sucursal_id)->first();
                                if($stock){
                                    $stock->cantidad = $stock->cantidad + ($Detalle->cantidad * $detallepromo->cantidad * $presentacion->cantidad);
                                    $stock->save();
                                }else{
                                    $stock = new StockProducto();
                                    $stock->producto_id = $presentacion->presentacion_id;
                                    $stock->sucursal_id = $pedido->sucursal_id;
                                    $stock->cantidad    = ($Detalle->cantidad * $detallepromo->cantidad * $presentacion->cantidad);
                                    $stock->save();
                                }
                            }
                        }else{
                            $stock = StockProducto::where('producto_id',$detallepromo->producto_id)->where('sucursal_id',$pedido->sucursal_id)->first();
                            if($stock){
                                $stock->cantidad = $stock->cantidad + ($Detalle->cantidad * $detallepromo->cantidad);
                                $stock->save();
                            }else{
                                $stock = new StockProducto();
                                $stock->producto_id = $detallepromo->producto_id;
                                $stock->sucursal_id = $pedido->sucursal_id;
                                $stock->cantidad = $Detalle->cantidad * $detallepromo->cantidad;
                                $stock->save();
                            }
                        }
                    }

                }
            }
        });
        return is_null($error) ? "OK" : $error;
     }

}
