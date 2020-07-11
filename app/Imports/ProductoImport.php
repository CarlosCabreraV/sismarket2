<?php

namespace App\Imports;

use App\Producto;
use App\Categoria;
use App\Category;
use App\Unidad;
use App\Marca;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Librerias\Libreria;

class ProductoImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
    $x=0;
        foreach ($rows as $row) 
        {
        if($x>0 && trim($row[0])!=""){
            $i = 0;
            $category = Category::where(DB::raw("upper(nombre)"), "=",strtoupper(trim($row[$i])))->first();
            if($category == null){
                $category = new Category();
                $category->nombre = trim($row[$i]);
                $category->save();
            }
            $i++;

            $categoria = Categoria::where(DB::raw("upper(nombre)"), "=",strtoupper(trim($row[$i])))->first();
            if($categoria == null){
                $categoria = new Categoria();
                $categoria->nombre = trim($row[$i]);
                $categoria->categoria_id = $category->id;
                $categoria->save();
            }
            $i++;

            $unidad = Unidad::where(DB::raw("upper(nombre)"), "=",strtoupper(trim($row[$i])))->first();
            if($unidad == null){
                $unidad = new Unidad();
                $unidad->nombre = trim($row[$i]);
                $unidad->save();
            }
            $i++;

            $marca = Marca::where(DB::raw("upper(nombre)"), "=",strtoupper(trim($row[$i])))->first();
            if($marca == null){
                $marca = new Marca();
                $marca->nombre = trim($row[$i]);
                $marca->save();
            }
            $i++;
	    $codigo = Libreria::getParam(trim($row[$i]),'');$i++;
	    $nombre = Libreria::getParam(trim($row[$i]),'');$i++;
	    if($codigo!="" && $codigo!="0"){
	            $producto = Producto::where(function($sql) use($nombre,$codigo){
				$sql->where(DB::raw('upper(nombre)'),'like',strtoupper($nombre))
				    ->orWhere('codigobarra','like',$codigo);
				})->first();
	    }else{
	            $producto = Producto::where(function($sql) use($nombre,$codigo){
				$sql->where(DB::raw('upper(nombre)'),'like',strtoupper($nombre));
				})->first();
            }
	    
	    if(is_null($producto)){
	            $producto = new Producto();
        	    $producto->codigobarra = $codigo;
	            $producto->nombre = $nombre;
	    }
	    $producto->codigobarra = $codigo;
            $producto->abreviatura = Libreria::getParam($row[$i],'');$i++;
            $producto->unidad_id = $unidad->id;
            $producto->marca_id = $marca->id;
            $producto->categoria_id = $categoria->id;
            $producto->preciocompra =  Libreria::getParam($row[$i], '0.00');$i++;
            $producto->precioventa = Libreria::getParam($row[$i], '0.00');$i++;
            $producto->precioventaespecial = Libreria::getParam($row[$i], '0.00');$i++;
            $producto->precioventaespecial2 = Libreria::getParam($row[$i], '0.00');$i++;
            $producto->ganancia =  '0.00';
            $producto->stockminimo = Libreria::getParam(isset($row[$i])?$row[$i]:'0', '0.00');$i++;
            $producto->consumo = '';
            $producto->igv = 'S';
            $producto->save();

        }
        $x=$x+1;
        }
    }
}
