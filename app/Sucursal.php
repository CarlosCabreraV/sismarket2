<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use SoftDeletes;
    protected $table = 'sucursal';
    protected $dates = ['deleted_at'];

    public function empresa()
    {
        return $this->belongsTo('App\Empresa', 'empresa_id');
    }
}
