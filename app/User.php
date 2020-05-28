<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function scopelistar($query, $login,$nombre = null,$sucursal_id = null, $caja_id = null )
    {
        return $query->join('person','person.id','=','user.person_id')
                        
                    ->where(function($subquery) use($login)
                    {
                        if (!is_null($login)) {
                            $subquery->where('login', 'LIKE', '%'.$login.'%');
                        }
                    })
                    ->where(function($subquery) use($nombre)
                    {
                        if (!is_null($nombre)) {
                            $subquery->where(DB::raw('concat(person.apellidopaterno,\' \',person.apellidomaterno,\' \',person.nombres)'), 'LIKE', '%'.$request->input('nombre').'%');
                        }
                    })
                    ->where(function($subquery) use($sucursal_id)
                    {
                        if (!is_null($sucursal_id)) {
                            $subquery->where('sucursal_id', '=', $sucursal_id);
                        }
                    })
                    ->where(function($subquery) use($caja_id)
                    {
                        if (!is_null($caja_id)) {
                            $subquery->where('caja_id', '=', $caja_id);
                        }
                    })
                    ->select('user.*','person.nombres','person.apellidopaterno','person.apellidomaterno')->orderBy('person.apellidopaterno','asc');
    }


    public function usertype()
    {
        return $this->belongsTo('App\Usertype', 'usertype_id');
    }

    public function person(){
        return $this->belongsTo('App\Person', 'person_id');
    }

    public function sucursal()
    {
        return $this->belongsTo('App\Sucursal', 'sucursal_id');
    }

    public function caja()
    {
        return $this->belongsTo('App\Caja', 'caja_id');
    }
}
