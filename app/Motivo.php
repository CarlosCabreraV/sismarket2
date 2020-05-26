<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Motivo extends Model
{
    use SoftDeletes;
    protected $table = 'motivo';
    protected $dates = ['deleted_at'];

    
    /**
	 * Método para listar las cajas
	 */
	public function scopelistar($query, $nombre)
    {
        return $query->where(function($subquery) use($nombre)
		            {
		            	if (!is_null($nombre)) {
		            		$subquery->where('nombre', 'LIKE', '%'.$nombre.'%');
		            	}
		            })
        			->where(function($subquery) use($tipo)
		            {
		            	if (!is_null($tipo)) {
		            		$subquery->where('tipo', 'LIKE', '%'.$tipo.'%');
		            	}
		            })
        			->orderBy('tipo', 'ASC')
        			->orderBy('nombre', 'ASC');
    }
}
