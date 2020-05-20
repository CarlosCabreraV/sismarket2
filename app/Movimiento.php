<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Movimiento extends Model
{
	 use SoftDeletes;
    protected $table = 'movimiento';
    protected $dates = ['deleted_at'];
    
    public function concepto()
	{
		return $this->belongsTo('App\Concepto', 'concepto_id');
	}

    public function tipomovimiento()
	{
		return $this->belongsTo('App\Tipomovimiento', 'tipomovimiento_id');
	}

    public function tipodocumento()
	{
		return $this->belongsTo('App\Tipodocumento', 'tipodocumento_id');
	}
    
    public function persona()
    {
        return $this->belongsTo('App\Person', 'persona_id');
    }

    public function resoonsable()
    {
        return $this->belongsTo('App\Person', 'responsable_id');
    }

    public function scopeNumeroSigue($query,$tipomovimiento_id,$tipodocumento_id=0){
        if($tipodocumento_id==0){
            $rs=$query->where('tipomovimiento_id','=',$tipomovimiento_id)->select(DB::raw("max((CASE WHEN numero IS NULL THEN 0 ELSE convert(substr(numero,5,8),SIGNED integer) END)*1) AS maximo"))->first();
        }else{
            $rs=$query->where('tipomovimiento_id','=',$tipomovimiento_id)->where('tipodocumento_id','=',$tipodocumento_id)->select(DB::raw("max((CASE WHEN numero IS NULL THEN 0 ELSE convert(substr(numero,6,8),SIGNED  integer) END)*1) AS maximo"))->first();
        }
        return str_pad($rs->maximo+1,8,'0',STR_PAD_LEFT);    
    }
}
