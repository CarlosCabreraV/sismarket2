<?php

namespace App\Exports;

use App\Movimiento;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CajaExport implements FromView
{
	protected $fechaini;
	protected $fechafin;

    public function __construct($fechaini, $fechafin)
    {
        $this->fechaini = $fechaini;
        $this->fechafin = $fechafin;
    }
    
    public function view(): View
    {
    	$resultado        = Movimiento::where('movimiento.tipomovimiento_id', '=', 4)
                            ->where('movimiento.concepto_id','=',1)
                            ->where('movimiento.fecha','>=',$this->fechaini)
                            ->where('movimiento.fecha','<=',$this->fechafin);
        $resultado        = $resultado->select('movimiento.*');
        $lista1            = $resultado->get();
        return view('exports.caja')->with(compact('lista1'));
    }

   
}
