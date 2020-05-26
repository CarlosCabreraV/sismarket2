<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Caja extends Model
{
    use SoftDeletes;
    protected $table = 'caja';
    protected $dates = ['deleted_at'];

    public function sucursal()
    {
        return $this->belongsTo('App\Sucursal', 'sucursal_id');
    }

    /**
	 * Método para listar las cajas
	 */
	public function scopelistar($query, $nombre, $sucursal_id)
    {
        return $query->where(function($subquery) use($nombre)
		            {
		            	if (!is_null($nombre)) {
		            		$subquery->where('nombre', 'LIKE', '%'.$nombre.'%');
		            	}
		            })
        			->where(function($subquery) use($sucursal_id)
		            {
		            	if (!is_null($sucursal_id)) {
		            		$subquery->where('sucursal_id', '=', $sucursal_id);
		            	}
		            })
        			->orderBy('sucursal_id', 'ASC')
        			->orderBy('nombre', 'ASC');
    }
}
