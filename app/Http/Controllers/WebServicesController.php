<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;
use App\Category;
use App\Producto;
use App\Promocion;
use App\Detallemovimiento;
use App\Pedido;
use App\DetallePedido;
use Illuminate\Support\Facades\DB;
use App\Librerias\Libreria;
use DateTime;

class WebServicesController extends Controller
{
    public function cargarMegaMenu(){
    	$categorias = Category::join("categoria","categoria.categoria_id","=","category.id")->whereNull("categoria.deleted_at")->orderBy("category.nombre","ASC")->select("category.*")->distinct("category.id")->get();
    	foreach ($categorias as $key => $value) {
    		$value->categorias = Categoria::join("producto","categoria.id","=","producto.categoria_id")->where("categoria.categoria_id","=",$value->id)->whereNull("producto.deleted_at")->select("categoria.*")->distinct("categoria.id")->get();
            foreach ($value->categorias as $key => $value2) {
                $value2->productos;
            }
    	}
    	return json_encode(array("categorias"=>$categorias));
    }

    public function principal(){
    	$detalles = Detallemovimiento::join("movimiento","movimiento.id","=","detallemovimiento.movimiento_id")->join("producto","producto.id","=","detallemovimiento.producto_id")->whereNull("producto.deleted_at")->where("movimiento.tipomovimiento_id","=","2")->whereNotIn('movimiento.situacion',['A'])->groupBy("detallemovimiento.producto_id")->select("detallemovimiento.producto_id",DB::raw('sum(detallemovimiento.cantidad) as sumcantidad'))->orderBy("sumcantidad","DESC")->LIMIT(10)->get();
    	foreach ($detalles as $key => $value) {
    		$value->producto = Producto::find($value->producto_id);
    		//$value->producto->marca;
    		//$value->producto->unidad;
    	}

        $detalles2 = Category::whereNotNull("orderweb")->orderBy("orderweb","ASC")->get();
        foreach ($detalles2 as $key => $value) {
         $value->categoria = Category::find($value->id);
        }
    	//return json_encode($detalles2);
    	return json_encode(array("productos"=>$detalles,"categorias"=>$detalles2));
    }

    public function catalogo(Request $request){
        $categorias = Category::join("categoria","categoria.categoria_id","=","category.id")->join("producto","categoria.id","=","producto.categoria_id")->whereNull("producto.deleted_at")->whereNull("categoria.deleted_at")->groupBy("category.id")->select("category.id", "category.nombre", DB::raw('count(category.id) as cantidad'))->orderBy("category.nombre","ASC")->get();
        foreach ($categorias as $key => $value) {
            $value->categorias =  Categoria::join("producto","categoria.id","=","producto.categoria_id")->where("categoria.categoria_id","=",$value->id)->whereNull("producto.deleted_at")->groupBy("categoria.id")->select("categoria.id", "categoria.nombre", DB::raw('count(categoria.id) as cantidad'))->orderBy("categoria.nombre","ASC")->get();
        }
    	return json_encode(array("categorias"=>$categorias));
    }

    public function buscarProducto(Request $request){
    	$categorias_id = explode(",", $request->input("categorias"));
    	$pagina = $request->input("page");

    	$resultado = Producto::orderBy("producto.nombre","ASC");
    	if($request->input("categorias")!= null){
    		$resultado = $resultado->whereIn("producto.categoria_id",$categorias_id);
    	}
    	$productos = $resultado->get();

    	$filas = "12";

    	$clsLibreria     = new Libreria();
        $paramPaginacion = $clsLibreria->generarPaginacion($productos, $pagina, $filas, "PRODUCTO");
        $paginacion      = $paramPaginacion['cadenapaginacion'];
        $paginaactual    = $paramPaginacion['nuevapagina'];
        $productos       = $resultado->paginate($filas);
        //$request->replace(array('page' => $paginaactual));

    	return json_encode(array("productos"=>$productos,"paginacion"=>$paginacion,"paginaactual"=>$paginaactual));
    }

    public function productoautocompletar(Request $request)
    {
    	$searching = $request->input("query");
        $resultado        = Producto::
        	where(function ($sql) use ($searching) {
                $sql->where("producto.nombre", 'LIKE', '%' . strtoupper($searching) . '%');
            })
            ->whereNull('producto.deleted_at')->orderBy('nombre', 'ASC');
        $list = $resultado->select('producto.*')->get();
        $data = array();
        foreach ($list as $key => $value) {
            $data[] = array(
                'label' => trim($value->nombre),
                'id'    => $value->id,
                'name' => trim($value->nombre),
                'precio'   => number_format($value->precioventa,2),
                'descripcion'   => "",
                'imagen'   => $value->archivo,
            );
        }
        return json_encode($data);
    }

    public function producto(Request $request)
    {
    	$producto_id = $request->input("producto_id");
        $producto = Producto::find($producto_id);
        $producto->marca;
        $producto->unidad;
        $producto->categoria;
        $producto->categoria->categoriapadre;
        $producto->precioventa = number_format($producto->precioventa,2);

        $productos = Producto::where("categoria_id","=",$producto->categoria_id)->where("id","<>",$producto_id)->orderBy("producto.nombre")->limit(10)->get();
        return json_encode(array("producto"=>$producto,"productos"=>$productos));
    }

    public function registrarPedido(Request $request){
        
        $dat = array();

        try {
            $error = DB::transaction(function () use ($request,  &$dat) {
                //-------------------CREAR PEDIDO------------------------
                    $Pedido       = new Pedido();
                    $Pedido->cliente_id = 1;
                    $Pedido->nombre = Libreria::getParam($request->input('nombre'));
                    $Pedido->ruc = Libreria::getParam($request->input('ruc'));
                    $Pedido->dni = Libreria::getParam($request->input('dni'));
                    $Pedido->telefono = Libreria::getParam($request->input('telefono'));
                    $Pedido->direccion = Libreria::getParam($request->input('direccion'));
                    $Pedido->referencia = Libreria::getParam($request->input('referencia'));
                    $Pedido->detalle = Libreria::getParam($request->input('detalle'));
                    $Pedido->tipodocumento_id = $request->input('tipodocumento');
                    $Pedido->delivery = $request->input('delivery');
                    $Pedido->modopago = $request->input('modopago');
                    $Pedido->cantidadpago = $request->input('cantidadpago');
                    if($request->input('modopago')=='TARJETA'){
                        $Pedido->tarjeta =$request->input('tarjeta');
                    }
                    $Pedido->estado = "N";
                    
                    $Pedido->responsable_id = null;
                    $Pedido->sucursal_id = $request->input('sucursal_id');
                    //$Pedido->fechaaceptado =new DateTime();
                    $Pedido->subtotal = 0;
                    $Pedido->igv = 0;
                    $Pedido->total = 0; 
                    $Pedido->save();
                //---------------------FIN CREAR PEDIDO------------------------

                //---------------------DETALLES VENTA------------------------------
                    $detalles = json_decode($request->input('listProducto'));
                    $total = 0;
                    //dd($request);
                    foreach ($detalles as $detalle) {
                        $Detalle = new DetallePedido();
                        $Detalle->pedido_id = $Pedido->id;
                        $precioventa = 0;
                        if($detalle->tipo =="P"){
                            $productoOb = Producto::find($detalle->producto_id);
                            $precioventa = $productoOb->precioventa;
                            $Detalle->producto_id=$detalle->producto_id;
                        }else{
                            $productoOb = Promocion::find($detalle->producto_id);
                            $precioventa = $productoOb->precioventa;
                            $Detalle->promocion_id=$detalle->producto_id;
                        }
                        $Detalle->cantidad = $detalle->cantidad;
                        // $Detalle->precioventa = $detalle->precio;
                        $Detalle->precioventa = $precioventa;
                        $total = $total + $precioventa * $detalle->cantidad;
                        $Detalle->preciocompra = 0;
                        $Detalle->save();
                    }
                //-----------------------FIN DETALLES VENTA------------------------------

                    if ($request->input('tipodocumento') == "4" || $request->input('tipodocumento') == "3") { //FACTURA O BOLETA
                        $Pedido->subtotal = round($total / 1.18, 2); //82%
                        $Pedido->igv = round($total - $Pedido->subtotal, 2); //18%
                    } else { //TICKET
                        $Pedido->subtotal = $total;
                        $Pedido->igv = 0;
                    }
                    $Pedido->total = $total; 

                $dat[0] = array("respuesta" => "OK");
            });
        } catch (\Exception $e) {
            return json_encode(array("respuesta"=>"ERROR", "msg"=>$e->getMessage()));
        }
        return is_null($error) ? json_encode($dat[0]) : $error;
    }
}
