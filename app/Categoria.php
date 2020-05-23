<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
	 use SoftDeletes;
    protected $table = 'categoria';
    protected $dates = ['deleted_at'];
    
    public function categoriapadre()
	{
		return $this->belongsTo('App\Category', 'categoria_id');
	}
}
