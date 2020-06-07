<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DNS1D;

class Producto extends Model
{
    use SoftDeletes;
    protected $table = 'producto';
    protected $dates = ['deleted_at'];

    public function marca()
    {
        return $this->belongsTo('App\Marca', 'marca_id');
    }

    public function unidad()
    {
        return $this->belongsTo('App\Unidad', 'unidad_id');
    }

    public function categoria()
    {
        return $this->belongsTo('App\Categoria', 'categoria_id');
    }


    public function scopelistar($query, $category, $subcategory, $marca, $producto_id = null)
    {
        return $query->join("categoria", "categoria.id", "=", "producto.categoria_id")
            ->where(function ($subquery) use ($category) {
                if (!is_null($category) && strlen($category) > 0 && $category != "0") {
                    $subquery->where('categoria.categoria_id', '=', $category);
                }
            })
            ->where(function ($subquery) use ($subcategory) {
                if (!is_null($subcategory) && strlen($subcategory) > 0 && $subcategory != "0") {
                    $subquery->where('producto.categoria_id', '=', $subcategory);
                }
            })
            ->where(function ($subquery) use ($marca) {
                if (!is_null($marca) && strlen($marca) > 0 && $marca != "0") {
                    $subquery->where('producto.marca_id', '=', $marca);
                }
            })
            ->where(function ($subquery) use ($producto_id) {
                if (!is_null($producto_id) && strlen($producto_id) > 0 && $producto_id != "0") {
                    $subquery->where('producto.id', '=', $producto_id);
                }
            })
            ->select("producto.*")
            ->orderBy('nombre', 'ASC');
    }

    /**
     * Genera el codigo de barras de todos los productos que no tengan codigo
     *
     * @return void
     */
    public static function generarCodBarras()
    {
        $ultimocod = Producto::orderBy("codigobarra", "DESC")->first()->codigobarra;
        if ($ultimocod == null && strlen($ultimocod) == 0) {
            $ultimocod = 0;
        }
        $ultimocod++;
        $lista = Producto::where("codigobarra", "=", "")->orderBy("nombre", "ASC")->get();
        foreach ($lista as $key => $producto) {
            $producto->codigobarra = str_pad($ultimocod, 5, '0', STR_PAD_LEFT);
            $producto->barcode = DNS1D::getBarcodeHTML($producto->codigobarra, 'C128', 2, 40, 'black');
            $producto->save();
            $ultimocod++;
        }
    }
}
