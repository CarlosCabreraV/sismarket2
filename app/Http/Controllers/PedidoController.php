<?php

namespace App\Http\Controllers;

use App\Configuracion;
use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use App\Pedido;
use App\Sucursal;
use App\Tipodocumento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    protected $folderview      = 'app.pedido';
    protected $tituloAdmin     = 'Pedido';
    protected $tituloRegistrar = 'Registrar pedido';
    protected $tituloModificar = 'Modificar pedido';
    protected $tituloEliminar  = 'Anular pedido';
    protected $tituloVer       = 'Ver Pedido';
    protected $rutas           = array('create' => 'pedido.create', 
            'edit'   => 'pedido.edit',
            'show'   => 'pedido.show', 
            'delete' => 'pedido.eliminar',
            'search' => 'pedido.buscar',
            'index'  => 'pedido.index',
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
        $resultado        = Pedido::join('user as cliente','cliente.id','=','pedido.user_id')
                                ->join('user as responsable','responsable.id','=','pedido.responsable_id');
        if($request->input('fechainicio')!=""){
            $resultado = $resultado->where('created_at','>=',$request->input('fechainicio'));
        }
        if($request->input('fechafin')!=""){
            $resultado = $resultado->where('created_at','<=',$request->input('fechafin'));
        }
        if($request->input('estado')!=""){
            $resultado = $resultado->where('estado',$request->input('estado'));
        }
        if($request->input('cliente')!=""){
            $cliente =$request->input('cliente');
            $resultado = $resultado->where(function($query) use ($cliente){
                            $query->where('pedido.nombre', 'like', '%'.$cliente.'%')
                                ->orWhere('pedido.dni', 'like', '%'.$cliente.'%')
                                ->orWhere('pedido.ruc', 'like', '%'.$cliente.'%');
                        });
        }
        if($request->input('tipodocumento_id')!=""){
            $resultado = $resultado->where('pedido.tipodocumento_id','=',$request->input('tipodocumento_id'));
        }
        $lista            = $resultado->select('pedido.*','cliente.*','responsable.*')->orderBy('pedido.id', 'desc')->orderBy('fecha', 'desc')->get();
        $cabecera         = array();
        $cabecera[]       = array('valor' => '#', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Fecha', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Hora', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Tipo Doc.', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Nro', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Cliente', 'numero' => '1');
        $cabecera[]       = array('valor' => 'Total', 'numero' => '1');
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
        $cboTipoDocumento = Tipodocumento::where('tipomovimiento_id', '=', 2)->orderBy('nombre', 'asc')->pluck('nombre', 'id')->all();
        $cboSucursal = ["" => "SELECCIONE SUCURSAL"] + Sucursal::pluck('nombre', 'id')->all();
        if (!$current_user->isAdmin() && !$current_user->isSuperAdmin()) {
            $cboSucursal = Sucursal::where('id', '=', $current_user->sucursal_id)->pluck('nombre', 'id')->all();
        }
        $boton    = 'Registrar';
        return view($this->folderview . '.mant')->with(compact('pedido', 'formData', 'entidad', 'boton', 'listar', 'cboTipoDocumento', 'cboSucursal', 'conf_codigobarra'));
    }

    public function store(Request $request){
        return ;
    }

}
