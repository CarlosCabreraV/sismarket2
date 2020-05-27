<?php

namespace App\Exports;

use App\Producto;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class KardexExport implements FromView
{
    protected $fechaini;
	protected $fechafin;
	protected $categoria;
	protected $subcategoria;
	protected $producto;

    public function __construct($fechaini, $fechafin,$categoria,$subcategoria,$producto)
    {
        $this->fechaini = $fechaini;
        $this->fechafin = $fechafin;
        $this->categoria = $categoria;
        $this->subcategoria = $subcategoria;
        $this->producto = $producto;
    }
    
    public function view(): View
    {
    	$resultado        = Producto::listar($this->categoria,$this->subcategoria,null,$this->producto);
        $lista1           = $resultado->get();
        $fechaini 		  = $this->fechaini;
        $fechafin 		  = $this->fechafin;
        return view('exports.kardex')->with(compact('lista1','fechaini','fechafin'));
    }
}
