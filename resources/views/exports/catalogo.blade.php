@if (count($lista1)>0)
    <table>
        <thead>
        <tr>
            <th colspan="7" style="text-align: center"><b>CATALOGO DE PRODUCTOS</b></th>
        </tr>
        <tr>
            @if($categoria=='S')
            <th style="width: 20px"><b>CATEGORIA</b></th>
            @endif
            @if($subcategoria=='S')
            <th style="width: 20px"><b>SUBCATEGORIA</b></th>

            @endif
            @if($marca=='S')
            <th style="width: 20px"><b>MARCA</b></th>
            @endif
            @if($unidad=='S')
            <th style="width: 20px"><b>UNIDAD</b></th>
            @endif
            @if($codigo=='S')
            <th style="width: 20px"><b>CODIGO</b></th>
            @endif
            @if($descripcion=='S')
            <th style="width: 20px"><b>DESCRIPCION</b></th>
            @endif
            @if($abreviatura=='S')
            <th style="width: 20px"><b>ABREVIATURA</b></th>
            @endif
            @if($preciocompra=='S')
            <th style="width: 20px"><b>P. COMPRA</b></th>
            @endif
            @if($precioventa=='S')
            <th style="width: 20px"><b>P. VENTA</b></th>
            @endif
            @if($ganancia=='S')
            <th style="width: 20px"><b>GANANCIA</b></th>
            @endif
            @if($precioventaespecial=='S')
            <th style="width: 20px"><b>P. VENTA ESPECIAL</b></th>
            @endif
            @if($precioventaespecial2=='S')
            <th style="width: 20px"><b>P. VENTA ESPECIAL 2</b></th>
            @endif
            @if($afectoigv=='S')
            <th style="width: 20px"><b>AFECTO IGV</b></th>
            @endif
            @if($stock=='S')
            <th style="width: 20px"><b>STOCK</b></th>
            @endif
            
        </tr>
        </thead>
        <tbody>
        
        @foreach($lista1 as $key => $value)
            <tr>
                @if($categoria=='S')
                <td>{{ $value->categoria }}</td>
                @endif
                @if($subcategoria=='S')
                <td>{{ $value->subcategoria }}</td>
                @endif
                @if($marca=='S')
                <td>{{ $value->marca }}</td>
                @endif
                @if($unidad=='S')
                <td>{{ $value->unidad }}</td>
                @endif
                @if($codigo=='S')
                <td>{{ "'".$value->codigobarra}}</td>
                @endif
                @if($descripcion=='S')
                <td>{{$value->nombre}}</td>
                @endif
                @if($abreviatura=='S')
                <td>{{$value->abreviatura}}</td>
                @endif
                @if($preciocompra=='S')
                <td>{{$value->preciocompra}}</td>
                @endif
                @if($precioventa=='S')
                <td>{{$value->precioventa}}</td>
                @endif
                @if($ganancia=='S')
                <td>{{($value->ganancia)}}</td>
                @endif
                @if($precioventaespecial=='S')
                <td>{{$value->precioventaespecial}}</td>
                @endif
                @if($precioventaespecial2=='S')
                <td>{{$value->precioventaespecial2}}</td>
                @endif
                
                @if($afectoigv=='S')
                <td align="right">{{$value->igv}}</td>
                @endif
                @if($stock=='S')
                <td>{{ $value->stock }}</td>
                @endif
                
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <table>
        <tr>
            <td>
                SIN RESULTADOS
            </td>
        </tr>
    </table>
@endif
