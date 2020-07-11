<?php

namespace App\Exports;

use App\Producto;
use App\Sucursal;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class CatalogoExport implements FromView
{
    protected $sucursal;
    protected $categoria;
    protected $subcategoria;
    protected $unidad;
    protected $marca;
    protected $precioventa;
    protected $preciocompra;
    protected $stock;
    protected $codigo;
    protected $descripcion;
    protected $abreviatura;
    protected $precioventaespecial;
    protected $precioventaespecial2;
    protected $afectoigv;
    protected $soloconstock;
    protected $ganancia;

    public function __construct($sucursal=null, $categoria, $subcategoria, $marca , $unidad ,$precioventa,$preciocompra,$stock ,$codigo,$descripcion ,$abreviatura , $precioventaespecial , $precioventaespecial2 , $afectoigv,$soloconstock , $ganancia)
    {
       
        $this->sucursal = $sucursal;
        $this->categoria = $categoria;
        $this->subcategoria = $subcategoria;
        $this->marca = $marca;
        $this->unidad = $unidad;
        $this->precioventa = $precioventa;
        $this->preciocompra = $preciocompra;
        $this->stock = $stock;
        $this->codigo = $codigo;
        $this->descripcion = $descripcion;
        $this->abreviatura = $abreviatura;
        $this->precioventaespecial = $precioventaespecial;
        $this->precioventaespecial2 = $precioventaespecial2;
        $this->afectoigv = $afectoigv;
        $this->soloconstock = $soloconstock;
        $this->ganancia = $ganancia;

    }

    public function view(): View
    {
        $sucursal_id = $this->sucursal;
        $categoria = $this->categoria;
        $subcategoria = $this->subcategoria;
        $marca = $this->marca;
        $unidad = $this->unidad;
        $precioventa = $this->precioventa;
        $preciocompra = $this->preciocompra;
        $stock = $this->stock;
        $codigo = $this->codigo;
        $descripcion = $this->descripcion;
        $abreviatura = $this->abreviatura;
        $precioventaespecial = $this->precioventaespecial;
        $precioventaespecial2 = $this->precioventaespecial2;
        $afectoigv = $this->afectoigv;
        $soloconstock = $this->soloconstock;
        $ganancia = $this->ganancia;
        $resultado        = Producto::join('marca','marca.id','=','producto.marca_id')
                                ->join('unidad','unidad.id','=','producto.unidad_id')
                                ->join('categoria','categoria.id','=','producto.categoria_id')
                                ->join('category','categoria.categoria_id','=','category.id')
                                ->leftjoin('stockproducto',function($subquery) use ($sucursal_id){
                                    $subquery->whereRaw('stockproducto.producto_id = producto.id')->where("stockproducto.sucursal_id", "=", $sucursal_id);
                                });
        if($soloconstock == 'S'){
            $resultado = $resultado->where('stockproducto.cantidad','>','0');
        }
        $resultado = $resultado->orderBy('producto.nombre','asc')
                            ->select('producto.*','category.nombre as categoria','categoria.nombre as subcategoria','marca.nombre as marca','unidad.nombre as unidad','stockproducto.cantidad as stock');
         $lista1           = $resultado->get();
        return view('exports.catalogo')->with(compact('lista1','categoria','subcategoria','marca','unidad','stock','precioventa','preciocompra','codigo','descripcion','abreviatura','precioventaespecial','precioventaespecial2','afectoigv','ganancia'));
    }
}
