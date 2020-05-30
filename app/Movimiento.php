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

	public function caja()
	{
		return $this->belongsTo('App\Caja', 'caja_id');
	}
	public function sucursal()
	{
		return $this->belongsTo('App\Sucursal', 'sucursal_id');
	}

	public function scopeNumeroSigue($query, $tipomovimiento_id, $tipodocumento_id = 0)
	{
		if ($tipodocumento_id == 0) {
			$rs = $query->where('tipomovimiento_id', '=', $tipomovimiento_id)->select(DB::raw("max((CASE WHEN numero IS NULL THEN 0 ELSE convert(substr(numero,5,8),SIGNED integer) END)*1) AS maximo"))->first();
		} else {
			$rs = $query->where('tipomovimiento_id', '=', $tipomovimiento_id)->where('tipodocumento_id', '=', $tipodocumento_id)->select(DB::raw("max((CASE WHEN numero IS NULL THEN 0 ELSE convert(substr(numero,6,8),SIGNED  integer) END)*1) AS maximo"))->first();
		}
		return str_pad($rs->maximo + 1, 8, '0', STR_PAD_LEFT);
	}

	/**
	 * Función para listar las compras 
	 *
	 * @param  $this $query
	 * @param  string $sucursal
	 * @param  string $fecinicio
	 * @param  string $fecfin
	 * @param  string $tipodocumento
	 * @param  string $numero
	 * @param  string $proveedor
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopelistarCompra($query, $sucursal, $fecinicio, $fecfin, $tipodocumento, $numero, $proveedor)
	{
		return $query->join('person', 'person.id', '=', 'movimiento.persona_id')
			->join('person as responsable', 'responsable.id', '=', 'movimiento.responsable_id')
			->where('tipomovimiento_id', '=', 1)
			->where(function ($subquery) use ($sucursal) {
				if (!is_null($sucursal) && strlen($sucursal) > 0) {
					$subquery->where('sucursal_id', '=', $sucursal);
				}
			})
			->where(function ($subquery) use ($fecinicio) {
				if (!is_null($fecinicio) && strlen($fecinicio) > 0) {
					$subquery->where('fecha', '>=', $fecinicio);
				}
			})
			->where(function ($subquery) use ($fecfin) {
				if (!is_null($fecfin) && strlen($fecfin) > 0) {
					$subquery->where('fecha', '<=', $fecfin);
				}
			})
			->where(function ($subquery) use ($tipodocumento) {
				if (!is_null($tipodocumento) && strlen($tipodocumento) > 0) {
					$subquery->where('tipodocumento_id', '=', $tipodocumento);
				}
			})
			->where(function ($subquery) use ($numero) {
				if (!is_null($numero) && strlen($numero) > 0) {
					$subquery->where('numero', 'LIKE', "%" . $numero . "%");
				}
			})
			->where(function ($subquery) use ($proveedor) {
				if (!is_null($proveedor) && strlen($proveedor) > 0) {
					$subquery->where(DB::raw('concat(person.apellidopaterno,\' \',person.apellidomaterno,\' \',person.nombres)'), 'LIKE', "%" . $proveedor . "%");
				}
			})
			->select('movimiento.*', DB::raw('concat(person.apellidopaterno,\' \',person.apellidomaterno,\' \',person.nombres) as cliente'), DB::raw('responsable.nombres as responsable2'))
			->orderBy('fecha', 'ASC');
	}
}
