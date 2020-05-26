<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use SoftDeletes;
    protected $table = 'caja';
    protected $dates = ['deleted_at'];

    public function sucursal()
    {
        return $this->belongsTo('App\Sucursal', 'sucursal_id');
    }
}
