<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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


    public function scopelistar($query, $category,$subcategory,$marca)
    {
        return $query->join("categoria","categoria.id","=","producto.categoria_id")
                    ->where(function($subquery) use($category)
                    {
                        if (!is_null($category) && strlen($category)>0) {
                            $subquery->where('categoria.categoria_id', '=', $category);
                        }
                    })
                    ->where(function($subquery) use($subcategory)
                    {
                        if (!is_null($subcategory) && strlen($subcategory)>0) {
                            $subquery->where('producto.categoria_id', '=', $subcategory);
                        }
                    })
                    ->where(function($subquery) use($marca)
                    {
                        if (!is_null($marca) && strlen($marca)>0) {
                            $subquery->where('producto.marca_id', '=', $marca);
                        }
                    })
                    ->select("producto.*")
                    ->orderBy('nombre', 'ASC');
    }
}
