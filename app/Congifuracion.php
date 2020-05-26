<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Congifuracion extends Model
{
    use SoftDeletes;
    protected $table = 'configuracion';
    protected $dates = ['deleted_at'];
}
