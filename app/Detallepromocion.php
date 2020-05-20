<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Detallepromocion extends Model
{
	 use SoftDeletes;
    protected $table = 'detallepromocion';
    protected $dates = ['deleted_at'];
    
    public function producto()
	{
		return $this->belongsTo('App\Producto', 'producto_id');
	}

    public function promocion()
	{
		return $this->belongsTo('App\Promocion', 'promocion_id');
	}
}
