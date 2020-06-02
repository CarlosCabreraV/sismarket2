<?php

namespace App\Exports;

use App\Movimiento;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CajaExport implements FromView
{
	protected $fechaini;
	protected $fechafin;

    public function __construct($fechaini, $fechafin , $caja_id)
    {
        $this->fechaini = $fechaini;
        $this->fechafin = $fechafin;
        $this->caja_id = $caja_id;
    }
    
    public function view(): View
    {
    	$resultado        = Movimiento::where('movimiento.tipomovimiento_id', '=', 4)
                            ->where('movimiento.concepto_id','=',1)
                            ->where('movimiento.fecha','>=',$this->fechaini)
                            ->where('movimiento.fecha','<=',$this->fechafin);

        if($this->caja_id  && $this->caja_id != ''){
            $resultado = $resultado->where('movimiento.caja_id',$this->caja_id);
        }
        $caja_id = ($this->caja_id)?$this->caja_id:'0';
        $lista1            = $resultado->get();
        return view('exports.caja')->with(compact('lista1','caja_id'));
    }

   
}
