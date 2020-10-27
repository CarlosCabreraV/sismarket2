<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Detallenotacredito extends Model
{
	 use SoftDeletes;
    protected $table = 'detallenotacredito';
    protected $dates = ['deleted_at'];
    
    public function producto()
	{
		return $this->belongsTo('App\Producto', 'producto_id');
	}

	public function promocion()
	{
		return $this->belongsTo('App\Promocion', 'promocion_id');
	}

    public function notacredito()
	{
		return $this->belongsTo('App\NotaCredito', 'movimiento_id');
	}
}
