<style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }

        table {
            font-size: x-small;
        }

        @page {
            margin: 0cm 0cm;
         /*   font-family: Arial; */

        }

        body {
            margin: 0.5cm;

        }

    </style>

<table width='100%' border="1" style="padding:10px; margin:5px;border-collapse: collapse; ">
    <thead>
        <tr>
            <th style="height: 20px; width: 35%" >PRODUCTO</th>
            <th style="height: 20px; width: 20%">CATEGORIA</th>
            <th style="height: 20px; width: 15%">CODIGO BARRA</th>
            <th style="height: 20px; width: 30%"></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($lista as $value)
            <tr>
                <td style="height: 20px; text-align:center;" > {{$value->nombre}} </td>
                @if ($value->categoria != null)
                    <td style="height: 20px; text-align:center;" > {{$value->categoria->nombre}} </td>
                @else
                    <td style="height: 20px; text-align:center;" > - </td>
                @endif
                <td style="height: 20px; text-align:center;" >{{$value->codigobarra}}</td>
                {{-- <td style="height: 20px; text-align:center;" >{{$value->codigobarra}}</td> --}}
                <td style="height: 20px; text-align:center; padding:5px;" > <div style="margin-left: 0px"> <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($value->codigobarra, 'C128', 2, 40)}}" alt="barcode" /> </div> </td>
            </tr>
        @endforeach
    </tbody>
</table>