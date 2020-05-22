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
use App\Promocion;
use App\Detallepromocion;
use App\Detalleproducto;
use App\Stockproducto;
use App\Detallemovimiento;
use App\Person;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;

class VentaController extends Controller
{
    protected $folderview      = 'app.venta';
    protected $tituloAdmin     = 'Venta';
    protected $tituloRegistrar = 'Registrar venta';
    protected $tituloModificar = 'Modificar venta';
    protected $tituloEliminar  = 'Anular venta';
    protected $tituloVer       = 'Ver Venta';
    protected $rutas           = array('create' => 'venta.create', 
            'edit'   => 'venta.edit',
            'show'   => 'venta.show', 
            'delete' => 'venta.eliminar',
            'search' => 'venta.buscar',
            'index'  => 'venta.index',
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
        $entidad          = 'Venta';
        $nombre             = Libreria::getParam($request->input('cliente'));
        $resultado        = Movimiento::join('person','person.id','=','movimiento.persona_id')
                                ->join('person as responsable','responsable.id','=','movimiento.responsable_id')
                                ->where('tipomovimiento_id','=',2);
        if($request->input('fechainicio')!=""){
            $resultado = $resultado->where('fecha','>=',$request->input('fechainicio'));
        }
        if($request->input('fechafin')!=""){
            $resultado = $resultado->where('fecha','<=',$request->input('fechafin'));
        }
        if($request->input('numero')!=""){
            $resultado = $resultado->where('numero','like','%'.$request->input('numero').'%');
        }
        if($request->input('tipodocumento_id')!=""){
            $resultado = $resultado->where('movimiento.tipodocumento_id','=',$request->input('tipodocumento_id'));
        }
        $lista            = $resultado->select('movimiento.*',DB::raw('concat(person.apellidopaterno,\' \',person.apellidomaterno,\' \',person.nombres) as cliente'),DB::raw('responsable.nombres as responsable2'))->orderBy('movimiento.id', 'desc')->orderBy('fecha', 'desc')->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Hora', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Tipo Doc.', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nro', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Cliente', 'numero' => '1');
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
            return view($this->folderview.'.list')->with(compact('lista', 'paginacion', 'inicio', 'fin', 'entidad', 'cabecera', 'titulo_modificar', 'titulo_eliminar', 'ruta', 'titulo_ver'));
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
        $entidad          = 'Venta';
        $title            = $this->tituloAdmin;
        $titulo_registrar = $this->tituloRegistrar;
        $ruta             = $this->rutas;
        $cboTipoDocumento = array('' => 'Todos');
        $tipodocumento = Tipodocumento::where('tipomovimiento_id','=',2)->orderBy('nombre','asc')->get();
        foreach($tipodocumento as $k=>$v){
            $cboTipoDocumento = $cboTipoDocumento + array($v->id => $v->nombre);
        }
        return view($this->folderview.'.admin')->with(compact('entidad', 'title', 'titulo_registrar', 'ruta', 'cboTipoDocumento'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $entidad  = 'Venta';
        $movimiento = null;
        $cboTipoDocumento = array();
        $tipodocumento = Tipodocumento::where('tipomovimiento_id','=',2)->orderBy('nombre','asc')->get();
        foreach($tipodocumento as $k=>$v){
            $cboTipoDocumento = $cboTipoDocumento + array($v->id => $v->nombre);
        }        
        $formData = array('venta.store');
        $formData = array('route' => $formData, 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Registrar'; 
        return view($this->folderview.'.mant')->with(compact('movimiento', 'formData', 'entidad', 'boton', 'listar', 'cboTipoDocumento'));
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
        $reglas     = array('persona' => 'required|max:500');
        $mensajes = array(
            'nombre.required'         => 'Debe ingresar un cliente'
            );
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        }
        $user = Auth::user();
        $dat=array();
        $rst  = Movimiento::where('tipomovimiento_id','=',4)->orderBy('movimiento.id','DESC')->limit(1)->first();
        if(count($rst)==0){
            $conceptopago_id=2;
        }else{
            $conceptopago_id=$rst->conceptopago_id;
        }
        if($conceptopago_id==2){
            $dat[0]=array("respuesta"=>"ERROR","msg"=>"Caja cerrada");
            return json_encode($dat);
        }
        $error = DB::transaction(function() use($request,$user,&$dat){
            $Venta       = new Movimiento();
            $Venta->fecha = $request->input('fecha');
            $Venta->numero = $request->input('numero');
            if($request->input('tipodocumento')=="4"){//FACTURA
                $Venta->subtotal = round($request->input('total')/1.18,2);
                $Venta->igv = round($request->input('total') - $Venta->subtotal,2);
            }else{
                $Venta->subtotal = round($request->input('total')/1.18,2);
                $Venta->igv = round($request->input('total') - $Venta->subtotal,2);
            }
            $Venta->total = str_replace(",","",$request->input('total')); 
            $Venta->totalpagado=str_replace(",","",$request->input('totalpagado')); 
            $Venta->tarjeta=str_replace(",","",$request->input('tarjeta')); 
            $Venta->tipomovimiento_id=2;//VENTA
            $Venta->tipodocumento_id=$request->input('tipodocumento');
            $Venta->persona_id = $request->input('persona_id')=="0"?1:$request->input('persona_id');
            $Venta->situacion='C';//Pendiente => P / Cobrado => C / Boleteado => B
            $Venta->comentario = '';
            $Venta->responsable_id=$user->person_id;
            $Venta->save();
            $arr=explode(",",$request->input('listProducto'));
            for($c=0;$c<count($arr);$c++){
                $Detalle = new Detallemovimiento();
                $Detalle->movimiento_id=$Venta->id;
                if($request->input('txtTipo'.$arr[$c])=="P"){
                    $Detalle->producto_id=$request->input('txtIdProducto'.$arr[$c]);
                }else{
                    $Detalle->promocion_id=$request->input('txtIdProducto'.$arr[$c]);
                }
                $Detalle->cantidad=$request->input('txtCantidad'.$arr[$c]);
                $Detalle->precioventa=$request->input('txtPrecio'.$arr[$c]);
                $Detalle->preciocompra=$request->input('txtPrecioCompra'.$arr[$c]);
                $Detalle->save();

                if($request->input('txtTipo'.$arr[$c])=="P"){
                    $detalleproducto = Detalleproducto::where('producto_id','=',$Detalle->producto_id)->get();
                    if(count($detalleproducto)>0){
                        foreach ($detalleproducto as $key => $value){
                            $stock = Stockproducto::where('producto_id','=',$value->presentacion_id)->first();
                            if(count($stock)>0){
                                $stock->cantidad = $stock->cantidad - $Detalle->cantidad*$value->cantidad;
                                $stock->save();
                            }else{
                                $stock = new Stockproducto();
                                $stock->producto_id = $value->presentacion_id;
                                $stock->cantidad = $Detalle->cantidad*(-1)*$value->cantidad;
                                $stock->save();
                            }
                        }
                    }else{
                        $stock = Stockproducto::where('producto_id','=',$Detalle->producto_id)->first();
                        if(count($stock)>0){
                            $stock->cantidad = $stock->cantidad - $Detalle->cantidad;
                            $stock->save();
                        }else{
                            $stock = new Stockproducto();
                            $stock->producto_id = $Detalle->producto_id;
                            $stock->cantidad = $Detalle->cantidad*(-1);
                            $stock->save();
                        }
                    }
                }else{
                    $lista = Detallepromocion::where('promocion_id','=',$Detalle->promocion_id)->get();
                    foreach ($lista as $key => $value) {
                        $stock = Stockproducto::where('producto_id','=',$value->producto_id)->first();
                        if(count($stock)>0){
                            $stock->cantidad = $stock->cantidad - $value->cantidad*$Detalle->cantidad;
                            $stock->save();
                        }else{
                            $stock = new Stockproducto();
                            $stock->producto_id = $value->producto_id;
                            $stock->cantidad = $value->cantidad*$Detalle->cantidad*(-1);
                            $stock->save();
                        }   
                    }
                }
            }
            $movimiento        = new Movimiento();
            $movimiento->fecha = date("Y-m-d");
            $movimiento->numero= Movimiento::NumeroSigue(4,6);
            $movimiento->responsable_id=$user->person_id;
            $movimiento->persona_id=$request->input('persona_id')=="0"?1:$request->input('persona_id'); 
            $movimiento->subtotal=0;
            $movimiento->igv=0;
            $movimiento->total=str_replace(",","",$request->input('total')); 
            $movimiento->totalpagado=str_replace(",","",$request->input('totalpagado')); 
            $movimiento->tarjeta=str_replace(",","",$request->input('tarjeta')); 
            $movimiento->tipomovimiento_id=4;
            $movimiento->tipodocumento_id=6;
            $movimiento->concepto_id=3;
            $movimiento->comentario='Pago de Documento de Venta '.$Venta->numero;
            $movimiento->situacion='N';
            $movimiento->movimiento_id=$Venta->id;
            $movimiento->save();
            $dat[0]=array("respuesta"=>"OK","venta_id"=>$Venta->id,"tipodocumento_id"=>$Venta->tipodocumento_id);
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
        $venta = Movimiento::find($id);
        $entidad             = 'Venta';
        $cboTipoDocumento        = Tipodocumento::lists('nombre', 'id')->all();
        $formData            = array('venta.update', $id);
        $formData            = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton               = 'Modificar';
        //$cuenta = Cuenta::where('movimiento_id','=',$compra->id)->orderBy('id','ASC')->first();
        //$fechapago =  Date::createFromFormat('Y-m-d', $cuenta->fecha)->format('d/m/Y');
        $detalles = Detallemovimiento::where('movimiento_id','=',$venta->id)->get();
        //$numerocuotas = count($cuentas);
        return view($this->folderview.'.mantView')->with(compact('venta', 'formData', 'entidad', 'boton', 'listar','cboTipoDocumento','detalles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $existe = Libreria::verificarExistencia($id, 'seccion');
        if ($existe !== true) {
            return $existe;
        }
        $listar   = Libreria::getParam($request->input('listar'), 'NO');
        $seccion = Seccion::find($id);
        $cboEspecialidad = array();
        $especialidad = Especialidad::orderBy('nombre','asc')->get();
        foreach($especialidad as $k=>$v){
            $cboEspecialidad = $cboEspecialidad + array($v->id => $v->nombre);
        }
        $cboCiclo = array();
        $ciclo = Grado::orderBy('nombre','asc')->get();
        foreach($ciclo as $k=>$v){
            $cboCiclo = $cboCiclo + array($v->id => $v->nombre);
        }
        
        $entidad  = 'Seccion';
        $formData = array('seccion.update', $id);
        $formData = array('route' => $formData, 'method' => 'PUT', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Modificar';
        return view($this->folderview.'.mant')->with(compact('seccion', 'formData', 'entidad', 'boton', 'listar', 'cboEspecialidad', 'cboCiclo'));
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
        $existe = Libreria::verificarExistencia($id, 'seccion');
        if ($existe !== true) {
            return $existe;
        }
        $reglas     = array('nombre' => 'required|max:50');
        $mensajes = array(
            'nombre.required'         => 'Debe ingresar un nombre'
            );
        $validacion = Validator::make($request->all(), $reglas, $mensajes);
        if ($validacion->fails()) {
            return $validacion->messages()->toJson();
        } 
        $error = DB::transaction(function() use($request, $id){
            $anio = Anio::where('situacion','like','A')->first();
            $seccion = Seccion::find($id);
            $seccion->nombre = strtoupper($request->input('nombre'));
            $seccion->grado_id = $request->input('grado_id');
            $seccion->especialidad_id = $request->input('especialidad_id');
            $seccion->anio_id = $anio->id;
            $seccion->save();
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
        $error = DB::transaction(function() use($id){
            $venta = Movimiento::find($id);
            $venta->situacion='A';
            $venta->save();
            $lst = Detallemovimiento::where('movimiento_id','=',$id)->get();
            foreach ($lst as $key => $Detalle) {
                if(!is_null($Detalle->producto_id) && $Detalle->producto_id>0){
                    $detalleproducto = Detalleproducto::where('producto_id','=',$Detalle->producto_id)->get();
                    if(count($detalleproducto)>0){
                        foreach ($detalleproducto as $key => $value){
                            $stock = Stockproducto::where('producto_id','=',$value->presentacion_id)->first();
                            if(count($stock)>0){
                                $stock->cantidad = $stock->cantidad + $Detalle->cantidad*$value->cantidad;
                                $stock->save();
                            }else{
                                $stock = new Stockproducto();
                                $stock->producto_id = $value->presentacion_id;
                                $stock->cantidad = $Detalle->cantidad*$value->cantidad;
                                $stock->save();
                            }
                        }
                    }else{
                        $stock = Stockproducto::where('producto_id','=',$Detalle->producto_id)->first();
                        if(count($stock)>0){
                            $stock->cantidad = $stock->cantidad + $Detalle->cantidad;
                            $stock->save();
                        }else{
                            $stock = new Stockproducto();
                            $stock->producto_id = $Detalle->producto_id;
                            $stock->cantidad = $Detalle->cantidad;
                            $stock->save();
                        } 
                    }       
                }else{
                    $lista = Detallepromocion::where('promocion_id','=',$Detalle->promocion_id)->get();
                    foreach ($lista as $key1 => $value) {
                        $stock = Stockproducto::where('producto_id','=',$value->producto_id)->first();
                        if(count($stock)>0){
                            $stock->cantidad = $stock->cantidad + $value->cantidad*$Detalle->cantidad;
                            $stock->save();
                        }else{
                            $stock = new Stockproducto();
                            $stock->producto_id = $value->producto_id;
                            $stock->cantidad = $value->cantidad*$Detalle->cantidad;
                            $stock->save();
                        }  
                    }
                }
            }
            $caja = Movimiento::where('movimiento_id','=',$venta->id)->where('tipomovimiento_id','=','4')->first();
            $caja->situacion='A';
            $caja->save();
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
        $entidad  = 'Venta';
        $formData = array('route' => array('venta.destroy', $id), 'method' => 'DELETE', 'class' => 'form-horizontal', 'id' => 'formMantenimiento'.$entidad, 'autocomplete' => 'off');
        $boton    = 'Anular';
        return view('app.confirmarAnular')->with(compact('modelo', 'formData', 'entidad', 'boton', 'listar'));
    }
    
    public function buscarproducto(Request $request)
    {
        $descripcion = $request->input("descripcion");
        $resultado = Producto::leftjoin('stockproducto','stockproducto.producto_id','=','producto.id')->where('nombre','like','%'.strtoupper($descripcion).'%')->select('producto.*','stockproducto.cantidad')->get();
        $c=0;$data=array();
        if(count($resultado)>0){
            foreach ($resultado as $key => $value){
                $data[$c] = array(
                        'producto' => $value->nombre,
                        'codigobarra' => $value->codigobarra,
                        'precioventa' => $value->precioventa,
                        'preciocompra' => $value->preciocompra,
                        'idproducto' => $value->id,
                        'tipo' => 'P',
                        'stock' => round($value->cantidad,2),
                    );
                $c++;                
            }
        }
        $resultado = Promocion::where('nombre','like','%'.strtoupper($descripcion).'%')->get();
        if(count($resultado)>0){
            foreach ($resultado as $key => $value){
                $data[$c] = array(
                        'producto' => $value->nombre,
                        'codigobarra' => '',
                        'precioventa' => $value->precioventa,
                        'preciocompra' => 0,
                        'idproducto' => $value->id,
                        'tipo' => 'C',
                        'stock' => 0,
                    );
                $c++;                
            }
        }
        return json_encode($data);
    }
    
    public function buscarproductobarra(Request $request)
    {
        $codigobarra = $request->input("codigobarra");
        $resultado = Producto::leftjoin('stockproducto','stockproducto.producto_id','=','producto.id')->where(DB::raw('trim(codigobarra)'),'like',trim($codigobarra))->select('producto.*','stockproducto.cantidad')->get();
        $c=0;$data=array();
        if(count($resultado)>0){
            foreach ($resultado as $key => $value){
                $data[$c] = array(
                        'producto' => $value->nombre,
                        'codigobarra' => $value->codigobarra,
                        'precioventa' => $value->precioventa,
                        'preciocompra' => $value->preciocompra,
                        'idproducto' => $value->id,
                        'tipo' => 'P',
                        'stock' => round($value->cantidad,2),
                    );
                $c++;                
            }
        }else{         
            $data = array();
        }
        return json_encode($data);
    }
    
    public function generarNumero(Request $request){
        $numeroventa = Movimiento::NumeroSigue(2,$request->input('tipodocumento'));
        if($request->input('tipodocumento')==3){
            echo "B001-".$numeroventa;
        }elseif($request->input('tipodocumento')==4){
            echo "F001-".$numeroventa;
        }else{
            echo "T001-".$numeroventa;
        }
    }

    public function personautocompletar($searching){
        $resultado        = Person::join('rolpersona','rolpersona.person_id','=','person.id')->where('rolpersona.rol_id','=',3)
                            ->where(function($sql) use($searching){
                                $sql->where(DB::raw('CONCAT(apellidopaterno," ",apellidomaterno," ",nombres)'), 'LIKE', '%'.strtoupper($searching).'%')->orWhere('bussinesname', 'LIKE', '%'.strtoupper($searching).'%');
                            })
                            ->whereNull('person.deleted_at')->whereNull('rolpersona.deleted_at')->orderBy('apellidopaterno', 'ASC');
        $list      = $resultado->select('person.*')->get();
        $data = array();
        foreach ($list as $key => $value) {
            $name = '';
            if ($value->bussinesname != null) {
                $name = $value->bussinesname;
            }else{
                $name = $value->apellidopaterno." ".$value->apellidomaterno." ".$value->nombres;
            }
            $data[] = array(
                            'label' => trim($name),
                            'id'    => $value->id,
                            'value' => trim($name),
                            'ruc' => $value->ruc,
                        );
        }
        return json_encode($data);
    }

    public function imprimirVenta(Request $request){
        $venta = Movimiento::find($request->input('id'));
        $connector = new WindowsPrintConnector("CAJA");
        $printer = new Printer($connector);
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        //$printer -> bitImage($tux,Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT);
        $printer -> text("MINIMARKET LEONELA II");
        $printer -> feed();
        $printer -> text("DE: CARRERA BURGA SARA");
        $printer -> feed();
        $printer -> text("AV. PACHACUTECT 1003");
        $printer -> feed();
        $printer -> text("LA VICTORIA-CHICLAYO-LAMBAYEQUE");
        $printer -> feed();
        $printer -> text("RUC:10403745991");
        $printer -> feed();
        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        if($venta->tipodocumento_id=="3"){//BOLETA
            $printer -> text("Boleta Electronica: ".substr($venta->numero,0,13));
            $printer -> feed();
            $num = "03-".$venta->numero;
        }elseif($venta->tipodocumento_id=="4"){//FACTURA
            $printer -> text("Factura Electronica: ".substr($venta->numero,0,13));
            $printer -> feed();
            $num = "01-".$venta->numero;
        }else{
            $printer -> text("Ticket: ".substr($venta->numero,0,13));
            $printer -> feed();
            $num = "07-".$venta->numero;
        }
        $printer -> text("Fecha: ".substr($venta->fecha,0,10));
        $printer -> feed();
        if($venta->nombres!="VARIOS"){
            $printer -> text("Cliente: ".$venta->persona->apellidopaterno." ".$venta->persona->apellidomaterno." ".$venta->persona->nombres);
            $printer -> feed();
            $printer -> text("Dir.: ".$venta->persona->direccion);
            $printer -> feed();
            if($venta->idtipodocumento=="3"){
                $printer -> text("RUC/DNI: 0");
            }else{
                $printer -> text("RUC/DNI: ".$venta->persona->ruc." ".$venta->persona->dni);
            }
            $printer -> feed();
        }else{
            $printer -> text("Cliente: ");
            $printer -> feed();
            $printer -> text("Dir.: SIN DOMICILIO");
            $printer -> feed();
            $printer -> text("RUC/DNI: 0");
            $printer -> feed();
        }
        $printer -> text("----------------------------------------"."\n");
        $printer -> text("Cant.  Producto                 Importe");
        $printer -> feed();
        $printer -> text("----------------------------------------"."\n");
        
        $lst = Detallemovimiento::where('movimiento_id','=',$request->input('id'))->get();
        $exonerada = 0;
        foreach ($lst as $key => $Detalle) {
            if(!is_null($Detalle->producto_id) && $Detalle->producto_id>0){
                $printer -> text(number_format($Detalle->cantidad,0,'.','')."  ".str_pad(($Detalle->producto->nombre),30," ")." ".number_format($Detalle->cantidad*$Detalle->precioventa,2,'.',' ')."\n");
                if($Detalle->producto->igv!="S"){
                    $exonerada = $Detalle->cantidad*$Detalle->precioventa;
                }
            }else{
                $printer -> text(number_format($Detalle->cantidad,0,'.','')."  ".str_pad(($Detalle->promocion->nombre),30," ")." ".number_format($Detalle->cantidad*$Detalle->precioventa,2,'.',' ')."\n");
            }
        }
        if($exonerada>0){
            $venta->subtotal = round(($venta->total - $exonerada)/1.18,2);
            $venta->igv = round($venta->total - $exonerada - $venta->subtotal,2);
        }
        $printer -> text("----------------------------------------"."\n");
        $printer -> text(str_pad("Op. Gravada:",32," "));
        $printer -> text(number_format($venta->subtotal,2,'.',' ')."\n");
        $printer -> text(str_pad("I.G.V. (18%)",32," "));
        $printer -> text(number_format($venta->igv,2,'.',' ')."\n");
        $printer -> text(str_pad("Op. Inafecta:",32," "));
        $printer -> text(number_format(0,2,'.',' ')."\n");
        $printer -> text(str_pad("Op. Exonerada:",32," "));
        $printer -> text(number_format($exonerada,2,'.',' ')."\n");
        $printer -> text(str_pad("TOTAL S/ ",32," "));
        $printer -> text(number_format($venta->total,2,'.',' ')."\n");
        $printer -> text("----------------------------------------"."\n");
        //CODIGO QR
        //print_r(__DIR__."../../../../../htdocs/clifacturacion/ficheros/10403745991-".$num.".png");die();
        if(file_exists(__DIR__."../../../../../htdocs/clifacturacion/ficheros/10403745991-".$num.".png")){
            $tux = EscposImage::load(__DIR__."../../../../../htdocs/clifacturacion/ficheros/10403745991-".$num.".png",true);
            $printer -> setJustification(Printer::JUSTIFY_CENTER);
            $printer -> bitImage($tux,Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT);
            $printer -> text("---------------------------------------"."\n");
        }
        //CODIGO QR
        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> text("Hora: ".date("H:i:s")."\n");
        $printer -> text("\n");
        $printer -> text(("Representación impresa del Comprobante Electrónico, consulte en https://facturae-garzasoft.com"));
        $printer -> text("\n");
        $printer -> text("\n");
        $printer -> text("           GRACIAS POR SU PREFERENCIA"."\n");
        $printer -> text("\n");
        $printer -> feed();
        $printer -> feed();
        $printer -> cut();
        $printer -> pulse();
        
        /* Close printer */
        $printer -> close();       
    }

    public function declarar(Request $request){
        $lista = Movimiento::where('tipomovimiento_id','=',2)->get();
        $dato="";
        foreach ($lista as $key => $value) {
            $dato.=$value->id."|".$value->tipodocumento_id."@";
        }
        echo substr($dato, 0, strlen($dato) - 1);
    }
}
