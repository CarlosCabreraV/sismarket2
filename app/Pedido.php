<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use SoftDeletes;
    protected $table = 'pedido';
    protected $dates = ['deleted_at'];
    
    

	public function detalles(){
		return $this->hasMany('App\DetallePedido', 'pedido_id');
	}
	public function documento()
    {
        return $this->belongsTo('App\Tipodocumento', 'tipodocumento_id');
    }
	public function cliente()
    {
        return $this->belongsTo('App\Person', 'cliente_id');
    }
	
}
