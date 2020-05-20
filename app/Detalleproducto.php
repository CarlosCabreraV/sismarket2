<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Detalleproducto extends Model
{
	 use SoftDeletes;
    protected $table = 'detalleproducto';
    protected $dates = ['deleted_at'];
    
    public function producto()
	{
		return $this->belongsTo('App\Producto', 'producto_id');
	}

    public function presentacion()
	{
		return $this->belongsTo('App\Producto', 'presentacion_id');
	}
}
