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
        if($x>0){
            $i = 0;
            $category = Category::where(DB::raw("upper(nombre)"), "=",strtoupper($row[$i]))->first();
            if($category == null){
                $category = new Category();
                $category->nombre = $row[$i];
                $category->save();
            }
            $i++;

            $categoria = Categoria::where(DB::raw("upper(nombre)"), "=",strtoupper($row[$i]))->first();
            if($categoria == null){
                $categoria = new Categoria();
                $categoria->nombre = $row[$i];
                $categoria->categoria_id = $category->id;
                $categoria->save();
            }
            $i++;

            $unidad = Unidad::where(DB::raw("upper(nombre)"), "=",strtoupper($row[$i]))->first();
            if($unidad == null){
                $unidad = new Unidad();
                $unidad->nombre = $row[$i];
                $unidad->save();
            }
            $i++;

            $marca = Marca::where(DB::raw("upper(nombre)"), "=",strtoupper($row[$i]))->first();
            if($marca == null){
                $marca = new Marca();
                $marca->nombre = $row[$i];
                $marca->save();
            }
            $i++;
            
            $producto = new Producto();
            $producto->codigobarra = Libreria::getParam($row[$i],'');$i++;
            $producto->nombre = Libreria::getParam($row[$i],'');$i++;
            $producto->abreviatura = Libreria::getParam($row[$i],'');$i++;
            $producto->unidad_id = $unidad->id;
            $producto->marca_id = $marca->id;
            $producto->categoria_id = $categoria->id;
            $producto->preciocompra =  Libreria::getParam($row[$i], '0.00');$i++;
            $producto->precioventa = Libreria::getParam($row[$i], '0.00');$i++;
            $producto->precioventaespecial = Libreria::getParam($row[$i], '0.00');$i++;
            $producto->ganancia =  '0.00';
            $producto->stockminimo = Libreria::getParam($row[$i], '0.00');$i++;
            $producto->consumo = '';
            $producto->igv = 'S';
            $producto->save();

        }
        $x=$x+1;
        }
    }
}
