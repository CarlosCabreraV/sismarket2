<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use SoftDeletes;
    protected $table = 'person';
    protected $dates = ['deleted_at'];

/**
     * Funcón que retorna true si es personal
     *
     * @return boolean
     */
    public function isPersonal()
    {
        $rolpersonal = Rolpersona::where('rol_id',1)->where('person_id',$this->id)->first();
        if($rolpersonal){
            return true;
        }else{
            return false;

        }
    }

    /**
     * MÃ©todo para listar
     * @param  model $query modelo
     * @param  string $name  nombre
     * @return sql        sql
     */
    public function scopelistar($query, $name, $type)
    {
        return $query->where(function($subquery) use($name)
		            {
		            	if (!is_null($name)) {
		            		$subquery->where('name', 'LIKE', '%'.$name.'%');
		            	}
		            })
        			->where(function($subquery) use($type)
		            {
		            	if (!is_null($type)) {
		            		$subquery->where('type', '=', $type);
		            	}
		            })
        			->orderBy('firstname', 'ASC');
    }

}
