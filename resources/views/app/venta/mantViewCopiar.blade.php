<style>
    .tr_hover{
        color:red;
    }
    .form-group{
        margin-bottom: 8px !important;
    }
    
    </style>
    <div id="divMensajeError{!! $entidad !!}"></div>
    {!! Form::model($venta, $formData) !!}	
        {!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
        {!! Form::hidden('idventaparcial', "0", array('id' => 'idventaparcial')) !!}
        {!! Form::hidden('listProducto', null, array('id' => 'listProducto')) !!}
    
        <div class="row">
            <div class="col-lg-5 col-md-5">
                <!--DATOS VENTA -->
                <div class="row ">
                    <div class="col-md-6 col-lg-6 ">
                        <div class="form-group">
                            {!! Form::label('fecha', 'Fecha', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                {!! Form::date('fecha',date('Y-m-d'), array('class' => 'form-control input-xs', 'id' => 'fecha', 'readonly' => 'true')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 ">
                        <div class="form-group">
                            {!! Form::label('numero', 'Numero', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                {!! Form::text('numero', '', array('class' => 'form-control input-xs', 'id' => 'numero', 'readonly' => 'true')) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row   py-2 px-1 my-2">
                    <div class="col-md-6 col-lg-6 ">
                        <div class="form-group">
                            {!! Form::label('lblsucursal_id', 'Sucursal', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                {!! Form::select('sucursal_id',$cboSucursal, $venta->sucursal_id, array('class' => 'form-control input-xs', 'id' => 'sucursal_id')) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 ">
                        <div class="form-group">
                            {!! Form::label('lbltipodoc', 'Tipo doc.', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                {!! Form::select('tipodocumento',$cboTipoDocumento, $venta->tipodocumento_id, array('class' => 'form-control input-xs ', 'id' => 'tipodocumento','onchange' => 'generarNumero();')) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <!--DATOS VENTA -->
                <!--DATOS CLIENTE -->
                <div class="row   py-2 px-1 my-2">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <div class="form-group ">
                            {!! Form::label('persona', 'Cliente', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
                            @if ($venta->persona != null)
                                <div class="col-lg-12 col-md-12 col-sm-12 input-group">
                                    {!! Form::hidden('persona_id', $venta->persona->id, array('id' => 'persona_id')) !!}
                                    {!! Form::hidden('dni', $venta->persona->dni?$venta->persona->dni:'', array('id' => 'dni')) !!}
                                    {!! Form::hidden('ruc', $venta->persona->dni?$venta->persona->ruc:'', array('id' => 'ruc')) !!}
                                    {!! Form::hidden('ispersonal', $venta->persona->isPersonal()?'S':'N', array('id' => 'ispersonal')) !!}
                                    <div class="col-lg-9 col-sm-9 col-md-9 pr-0">
                                        {!! Form::text('persona', $cliente, array('class' => 'form-control input-xs', 'id' => 'persona', 'placeholder' => 'Ingrese Cliente')) !!}
                                    </div>
                                    <div class="col-lg-3 col-sm-3 col-md-3 pl-0">
                                        <span class="input-group-append">
                                            {!! Form::button('<i class="fas fa-plus fa-fw"></i> Agregar', array('class' => 'btn btn-info btn-flat ', 'onclick' => 'modal (\''.URL::route('persona.create', array('listar'=>'SI','modo'=>'popup')).'\', \'Nueva Person\', this);', 'title' => 'Nueva Persona')) !!}
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div class="col-lg-12 col-md-12 col-sm-12 input-group">
                                    {!! Form::hidden('persona_id', 0, array('id' => 'persona_id')) !!}
                                    {!! Form::hidden('dni', '', array('id' => 'dni')) !!}
                                    {!! Form::hidden('ruc', '', array('id' => 'ruc')) !!}
                                    {!! Form::hidden('ispersonal', 'N', array('id' => 'ispersonal')) !!}
                                    <div class="col-lg-9 col-sm-9 col-md-9 pr-0">
                                        {!! Form::text('persona', 'VARIOS', array('class' => 'form-control input-xs', 'id' => 'persona', 'placeholder' => 'Ingrese Cliente')) !!}
                                    </div>
                                    <div class="col-lg-3 col-sm-3 col-md-3 pl-0">
                                        <span class="input-group-append">
                                            {!! Form::button('<i class="fas fa-plus fa-fw"></i> Agregar', array('class' => 'btn btn-info btn-flat ', 'onclick' => 'modal (\''.URL::route('persona.create', array('listar'=>'SI','modo'=>'popup')).'\', \'Nueva Person\', this);', 'title' => 'Nueva Persona')) !!}
                                        </span>
                                    </div>
                                </div>
                            @endif
                            
                        </div>
                    </div>
                </div>
                <!--DATOS CLIENTE -->
                <!--TIPO DE PAGO -->
                <div class="form-group py-2 mx-2 my-2 row">
                    <label class="col-md-1 col-form-label ">Tipo</label>
                    <div class="col-md-5">
                        {!! Form::select('tipoventa',['CONTADO'=>'Contado','CREDITO'=>'Credito'], $venta->tipoventa, array('class' => 'form-control input-xs', 'id' => 'tipoventa','onchange'=>'cambioTipo(this.value);')) !!}
                    </div>
                </div>
                <!--TIPO DE PAGO -->
            </div>
            <div class="col-lg-7 col-md-7 ">
                <!--DATOS PRODUCTO -->
                <div class="row ">
                    @if ($conf_codigobarra=="S")
                    <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                {!! Form::label('codigo', 'Cod. Barra:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    {!! Form::text('codigobarra', null, array('class' => 'form-control input-xs', 'id' => 'codigobarra')) !!}
                                </div>
                            </div>
                    </div>
                    @endif
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="form-group">
                            {!! Form::label('descripcion', 'Producto:', array('class' => 'col-lg-12 col-md-12 col-sm-12 control-label')) !!}
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                {!! Form::text('descripcion', null, array('class' => 'form-control input-xs', 'id' => 'descripcion', 'onkeypress' => '')) !!}
                            </div>
                        </div>
                        
                    </div>
                </div>
                <!--DATOS PRODUCTO -->
                <div class="form-group col-lg-12 col-md-12 col-sm-12" id="divBusqueda">
                </div>
            </div>
        </div>
    
       
            <div class="row   py-2 px-1 my-2">
                <table class="table table-sm table-condensed table-striped" id="tbDetalle">
                    <thead class="bg-navy">
                        <th class="text-center">Cant.</th>
                        @if ($conf_codigobarra=="S")
                        <th class="text-center">Cod. Barra</th>
                        @endif
                        <th class="text-center">Producto</th>
                        <th class="text-center">Precio</th>
                        <th class="text-center">Subtotal</th>
                        <th class="text-center">Quitar</th>
                    </thead>
                    <tbody>
                    </tbody>
                    
                </table>
            </div>
        @if ($venta->persona)
            <table class="table table-sm table-condensed table-striped {{$venta->persona->isPersonal()?'':'d-none'}}" id='divDescuento' >
                    {!! Form::hidden('descuento', '', array('id' => 'descuento')) !!}
                    <tbody>
                        <tr >
                            <td width='80' class="pl-3" bgcolor="#0cfa7f" style="font-style:italic;"> SE TE APLICAR&Aacute; UN DESCUENTO DEL 10%</td>
                            <td width='20' bgcolor="#0cfa7f" style="font-style:italic;"> DESCUENTO: <b id="lblDescuento"></b></td>
                        </tr>
                    </tbody>
                    
                </table>
            
        @endif
        <div id='divPago' class="{{$venta->tipoventa=='CREDITO'?'d-none':''}}">
                <!--TOTAL , DINERO , VUELTO-->
    
                <div class="row   py-2 px-1 my-2" >
                    <div class="col-md-3 col-lg-3 form-group row">
                            {!! Form::label('lbltotal', 'TOTAL', array('class' => ' col-form-label col-lg-3 col-md-3 col-sm-3  bold' )) !!}
                        <div class="col-md-9 col-lg-9">
                            {!! Form::text('total', $venta->total, array('class' => 'form-control input-xs', 'id' => 'total', 'size' => 3, 'readonly' => 'false', 'style' => 'font-size:20px;color:white; background:#00028f;')) !!}
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 form-group row">
                        {!! Form::label('lbldinero', 'DINERO', array('class' => 'col-form-label col-lg-3 col-md-2 col-sm-3  bold')) !!}
                        <div class="col-md-9 col-lg-9">
                            {!! Form::text('dinero', null, array('class' => 'form-control input-xs', 'id' => 'dinero', 'size' => 3, 'style' => 'font-size:20px; color:GREEN;', 'onkeyup' => 'calcularVuelto();')) !!}
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 form-group row">
                        {!! Form::label('lblvuelto', 'VUELTO', array('class' => 'col-form-label col-lg-3 col-md-3 col-sm-12 bold')) !!}
                        <div class="col-md-9 col-lg-9">
                            {!! Form::text('vuelto', null, array('class' => 'form-control input-xs', 'id' => 'vuelto', 'size' => 3, 'readonly' => 'true', 'style' => 'font-size:20px;color:darkblue;')) !!}
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 form-group d-none">
                        <input type="hidden" name="acuenta" id="acuenta" value="N">
                        {!! Form::label('lblcuenta', 'A CUENTA', array('class' => 'col-form-label col-lg-4 col-md-4 col-sm-4 bold')) !!}
                        <div class="col-md-8 col-lg-8 mt-2">
                            <input type="checkbox"  onclick="aCuenta(this.checked);" />
                        </div>
                    </div>
                </div>
                <!--TOTAL , DINERO , VUELTO-->
                   <!--EFECTIVO , VISA-->
                   <div class="row   py-2 px-1 my-2">
                        <div class="col-md-3 col-lg-3 form-group row">
                            {!! Form::label('lblefectivo', 'EFECTIVO', array('class' => 'col-form-label col-lg-3 col-md-2 col-sm-3  bold')) !!}
                            <div class="col-md-9 col-lg-9">
                                {!! Form::text('totalpagado', $venta->totalpagado, array('class' => 'form-control input-xs', 'id' => 'totalpagado', 'size' => 3, 'readonly' => 'true', 'style' => 'font-size:30px;color:green;')) !!}
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 form-group row">
                            {!! Form::label('lblvisa', 'TARJETA', array('class' => 'col-form-label col-lg-3 col-md-2 col-sm-3  bold')) !!}
                            <div class="col-md-9 col-lg-9">
                                {!! Form::text('tarjeta', $venta->tarjeta, array('class' => 'form-control input-xs', 'id' => 'tarjeta', 'size' => 3, 'style' => 'font-size:30px;color:blue;' , 'onkeyup' => 'calcularTarjeta();')) !!}
                            </div>
                        </div>
                   </div>
                 
               <!--EFECTIVO, VISA-->
            </div>
         <div class="form-group">
            <div class="col-lg-12 col-md-12 col-sm-12 text-right">
                {!! Form::button('<i class="fa fa-edit fa-lg"></i> Venta Parcial', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnGuardar', 'onclick' => '$(\'#listProducto\').val(carro);guardarPagoParcial(\''.$entidad.'\', this);')) !!}
                {!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-primary btn-sm', 'id' => 'btnGuardar', 'onclick' => '$(\'#listProducto\').val(carro);guardarPago(\''.$entidad.'\', this);')) !!}
                {!! Form::button('<i class="fa fa-undo fa-lg"></i> Cancelar', array('class' => 'btn btn-default btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
            </div>
        </div>
    {!! Form::close() !!}
    <style>
        .dataTables_scrollBody{
            overflow-x: scroll;
            border-radius: 0.25rem;
            border: 2px solid #001f3f;
            /* border-right:none; */
            cursor: pointer;
        }
        .dataTables_scrollBody::-webkit-scrollbar{ 
            width: 10px;
        background-color: #001f3f;
    }
    
        .dataTables_scrollBody::-webkit-scrollbar-thumb{ 
            border-radius: 5px;
        background-color: #e9ecef;
        border-left: 0.5px solid #001f3f;
        /* border-right: 0.5px solid #001f3f; */
    }
        .mostrarBarra{
            overflow-y: scroll;
        }
    
    </style>
  <script type="text/javascript">
    var valorbusqueda="";
    $(document).ready(function() {
        configurarAnchoModal('1300');
        init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'B', '{!! $entidad !!}');
        $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="total"]').inputmask('decimal', { radixPoint: ".", autoGroup: true, groupSeparator: "", groupSize: 3, digits: 2 });
        $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="dinero"]').inputmask('decimal', { radixPoint: ".", autoGroup: true, groupSeparator: "", groupSize: 3, digits: 2 });
        $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="vuelto"]').inputmask('decimal', { radixPoint: ".", autoGroup: true, groupSeparator: "", groupSize: 3, digits: 2 });
        generarNumero();
        var personas2 = new Bloodhound({
            datumTokenizer: function (d) {
                return Bloodhound.tokenizers.whitespace(d.value);
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: 'venta/personautocompletar/%QUERY',
                filter: function (personas2) {
                    return $.map(personas2, function (movie) {
                        return {
                            value: movie.value,
                            id: movie.id,
                            ruc: movie.ruc,
                            ispersonal: movie.ispersonal,
                        };
                    });
                }
            }
        });
        personas2.initialize();
        $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="persona"]').typeahead(null,{
            displayKey: 'value',
            source: personas2.ttAdapter()
        }).on('typeahead:selected', function (object, datum) {
            $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="persona"]').val(datum.value);
            $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="ruc"]').val(datum.ruc);
            $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="persona_id"]').val(datum.id);
            $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="ispersonal"]').val(datum.ispersonal);
            if(datum.ispersonal == 'S'){
                $('#divDescuento').removeClass('d-none');
            }else if(datum.ispersonal == 'N'){
                $('#divDescuento').addClass('d-none');
            }
            calcularTotal();
        });
    
        
        $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="descripcion"]').focus();
    
        $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="sucursal_id"]').change(function (e) {
            buscarProducto();
        });
        $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="descripcion"]').on( 'keydown', function () {
            var e = window.event; 
            var keyc = e.keyCode || e.which;
            if(this.value.length>1 && keyc == 13){
                buscarProducto(this.value);
                valorbusqueda=this.value;
                this.focus();
                return false;
            }
            if(keyc == 38 || keyc == 40 || keyc == 13) {
                var tabladiv='tablaProducto';
                var child = document.getElementById(tabladiv).rows;
                var indice = -1;
                var i=0;
                $('#tablaProducto tr').each(function(index, elemento) {
                    if($(elemento).hasClass("tr_hover")) {
                        $(elemento).removeClass("par");
                        $(elemento).removeClass("impar");								
                        indice = i;
                    }
                    if(i % 2==0){
                        $(elemento).removeClass("tr_hover");
                        $(elemento).addClass("impar");
                    }else{
                        $(elemento).removeClass("tr_hover");								
                        $(elemento).addClass('par');
                    }
                    i++;
                });		 
                // return
                if(keyc == 13) {        				
                     if(indice != -1){
                        var seleccionado = '';			 
                        if(child[indice].id) {
                           seleccionado = child[indice].id;
                        } else {
                           seleccionado = child[indice].id;
                        }		 		
                        seleccionarProducto(seleccionado);
                    }
                } else {
                    // abajo
                    if(keyc == 40) {
                        if(indice == (child.length - 1)) {
                           indice = 1;
                        } else {
                           if(indice==-1) indice=0;
                           indice=indice+1;
                        } 
                    // arriba
                    } else if(keyc == 38) {
                        indice = indice - 1;
                        if(indice==0) indice=-1;
                        if(indice < 0) {
                            indice = (child.length - 1);
                        }
                    }	 
                    child[indice].className = child[indice].className+' tr_hover';
                }
            }
        });
        
        $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="codigobarra"]').on( 'keydown', function () {
            var e = window.event; 
            var keyc = e.keyCode || e.which;
            if(this.value.length>1 && keyc == 13){
                buscarProductoBarra(this.value);
                this.value='';
            }
        });
    
    }); 
    
    function guardarHistoria (entidad, idboton) {
        var idformulario = IDFORMMANTENIMIENTO + entidad;
        var data         = submitForm(idformulario);
        var respuesta    = '';
        var btn = $(idboton);
        btn.button('loading');
        data.done(function(msg) {
            respuesta = msg;
        }).fail(function(xhr, textStatus, errorThrown) {
            respuesta = 'ERROR';
        }).always(function() {
            btn.button('reset');
            if(respuesta === 'ERROR'){
            }else{
              //alert(respuesta);
                var dat = JSON.parse(respuesta);
                if (dat[0]!==undefined && (dat[0].respuesta=== 'OK')) {
                    cerrarModal();
                    $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="historia_id"]').val(dat[0].id);
                    $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="numero_historia"]').val(dat[0].historia);
                    $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="person_id"]').val(dat[0].person_id);
                    $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="tipopaciente"]').val(dat[0].tipopaciente);
                    alert('Historia Generada');
                    window.open("historia/pdfhistoria?id="+dat[0].id,"_blank");
                } else {
                    mostrarErrores(respuesta, idformulario, entidad);
                }
            }
        });
    }
    
    var contador=0;
    function guardarPago (entidad, idboton) {
        var band=true;
        var msg="";
    
        if($("#person_id").val()==""){
            band = false;
            msg += " *No se selecciono un cliente \n";    
        }
        if(carro.length==0){
            band = false;
            msg += " *No se agreg� ning�n producto \n";    
        }
        if(parseFloat($("#total").val())>700 && $("#tipodocumento").val()=="3"){//BOLETA
            if($("#dni").val().trim().length!=8){
                band = false;
                msg += " *El cliente debe tener DNI correcto \n";
            }
        }   
        if($("#tipodocumento").val()=="4"){//FACTURA
            var ruc = $("#ruc").val();
            ruc = ruc.replace("_"," ");
            console.log(ruc);
            if(ruc.trim().length<11){
                band = false;
                msg += " *Debe registrar un correcto RUC \n";   
            }
        }
        if(parseFloat($("#tarjeta").val()) > parseFloat($('#total').val()) && carro.length != 0){
            band = false;
            msg += " *El monto de la tarjeta no debe superar al total \n";
        }
        if(band && contador==0){
            contador=1;
            var idformulario = IDFORMMANTENIMIENTO + entidad;
            var data         = submitForm(idformulario);
            var respuesta    = '';
            var error = '';
            var btn = $(idboton);
            btn.button('loading');
            data.done(function(msg) {
                respuesta = msg;
            }).fail(function(xhr, textStatus, errorThrown) {
                respuesta = 'ERROR';
                contador=0;
            }).always(function() {
                btn.button('reset');
                contador=0;
                if(respuesta === 'ERROR'){
                    console.log(error);
                }else{
                  //alert(respuesta);
                    var dat = JSON.parse(respuesta);
                    if(dat[0]!==undefined){
                        resp=dat[0].respuesta;    
                    }else{
                        resp='VALIDACION';
                    }
                    
                    if (resp === 'OK') {
                        if(dat[0].tipodocumento_id!="5"){
                            console.log('DECLARAR');
                            declarar(dat[0].venta_id,dat[0].tipodocumento_id);
                        }
                        cerrarModal();
                        buscarCompaginado('', 'Accion realizada correctamente', entidad, 'OK');
                        //window.open('/juanpablo/ticket/pdfComprobante3?ticket_id='+dat[0].ticket_id,'_blank')
                    } else if(resp === 'ERROR') {
                        toastr.error(dat[0].msg , 'Error');
                    } else {
                        mostrarErrores(respuesta, idformulario, entidad);
                    }
                }
            });
        }else{
            toastr.error(msg , "Corrige los siguientes errores");
        }
    }

    function guardarPagoParcial (entidad, idboton) {
        var band=true;
        var msg="";
    
        if($("#person_id").val()==""){
            band = false;
            msg += " *No se selecciono un cliente \n";    
        }
        if(carro.length==0){
            band = false;
            msg += " *No se agreg� ning�n producto \n";    
        }
        // if(parseFloat($("#total").val())>700 && $("#tipodocumento").val()=="3"){//BOLETA
        //     if($("#dni").val().trim().length!=8){
        //         band = false;
        //         msg += " *El cliente debe tener DNI correcto \n";
        //     }
        // }   
        // if($("#tipodocumento").val()=="4"){//FACTURA
        //     var ruc = $("#ruc").val();
        //     ruc = ruc.replace("_"," ");
        //     console.log(ruc);
        //     if(ruc.trim().length<11){
        //         band = false;
        //         msg += " *Debe registrar un correcto RUC \n";   
        //     }
        // }
        if(parseFloat($("#tarjeta").val()) > parseFloat($('#total').val()) && carro.length != 0){
            band = false;
            msg += " *El monto de la tarjeta no debe superar al total \n";
        }
        if(band && contador==0){
            contador=1;
            var idformulario = IDFORMMANTENIMIENTO + entidad;
            var data         = $.ajax({
                url : "venta/guardarParcial/1",
                data: new FormData($(idformulario)[0]),
                type: "POST",
                contentType: false,
                processData: false
            });
            var respuesta    = '';
            var error = '';
            var btn = $(idboton);
            btn.button('loading');
            data.done(function(msg) {
                respuesta = msg;
            }).fail(function(xhr, textStatus, errorThrown) {
                respuesta = 'ERROR';
                contador=0;
            }).always(function() {
                btn.button('reset');
                contador=0;
                if(respuesta === 'ERROR'){
                    console.log(error);
                }else{
                  //alert(respuesta);
                    var dat = JSON.parse(respuesta);
                    if(dat[0]!==undefined){
                        resp=dat[0].respuesta;    
                    }else{
                        resp='VALIDACION';
                    }
                    
                    if (resp === 'OK') {
                        cerrarModal();
                        buscarCompaginado('', 'Accion realizada correctamente', entidad, 'OK');
                        //window.open('/juanpablo/ticket/pdfComprobante3?ticket_id='+dat[0].ticket_id,'_blank')
                    } else if(resp === 'ERROR') {
                        toastr.error(dat[0].msg , 'Error');
                    } else {
                        mostrarErrores(respuesta, idformulario, entidad);
                    }
                }
            });
        }else{
            toastr.error(msg , "Corrige los siguientes errores");
        }
    }
    
    function declarar(idventa,idtipodocumento){
        if(idtipodocumento==3){
            var funcion="enviarBoleta";
        }else{
            var funcion="enviarFactura";
        }
        $.ajax({
            type: "GET",
            url: "../clifacturacion/controlador/contComprobante.php?funcion="+funcion,
            data: "idventa="+idventa+"&_token="+$(IDFORMBUSQUEDA + '{!! $entidad !!} :input[name="_token"]').val(),
            success: function(a) {
                console.log(a);
            }
        }); 
    }
    
    function buscarProductoBarra(barra){
        if($(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="sucursal_id"]').val() == ""){
            toastr.warning("Debe seleccionar una sucursal", 'Error:');
        }else{
            $.ajax({
            type: "POST",
            url: "venta/buscarproductobarra",
            data: "sucursal_id="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="sucursal_id"]').val()+"&codigobarra="+barra+"&_token="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="_token"]').val(),
            success: function(a) {
                datos=JSON.parse(a);
                if(datos.length > 0){
                    seleccionarProducto(datos[0].idproducto,datos[0].codigobarra,datos[0].producto,datos[0].preciocompra,datos[0].precioventa,datos[0].stock,datos[0].tipo);
                }
            }
        });
        }
    }
    
    
    var valorinicial="";
    function buscarProducto(valor){
        if($(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="sucursal_id"]').val() == ""){
            toastr.warning("Debe seleccionar una sucursal", 'Error:');
        }else{
                $.ajax({
                    type: "POST",
                    url: "venta/buscarproducto",
                    data:"sucursal_id="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="sucursal_id"]').val()+"&descripcion="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="descripcion"]').val()+"&_token="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="_token"]').val(),
                    success: function(a) {
                        datos=JSON.parse(a);
                        var strTable = "<table class='table table-striped table-bordered table-sm table-condensed table-hover' border='1' id='tablaProducto'><thead class='bg-navy'><tr>";
                        @if($conf_codigobarra=="S")
                            strTable = strTable + "<th class='text-center'>Cod. Barra</th>";
                        @endif   
                        strTable = strTable + "<th class='text-center'>Producto</th><th class='text-center'>Stock</th><th class='text-center'>P. Unit.</th></tr></thead><tbody id='tbodyProducto'></tbody></table>";
                        $("#divBusqueda").html(strTable);
                        
                        var pag=parseInt($("#pag").val());
                        var d=0;
                        for(c=0; c < datos.length; c++){
                            var a="<tr id='"+datos[c].idproducto+"' onclick=\"seleccionarProducto('"+datos[c].idproducto+"','"+datos[c].codigobarra+"','"+datos[c].producto+"','"+datos[c].preciocompra+"','"+datos[c].precioventa+"','"+datos[c].stock+"','"+datos[c].tipo+"')\">";
                            @if ($conf_codigobarra=="S")
                                a = a + "<td align='center'>"+datos[c].codigobarra+"</td>";
                            @endif 
                            a = a + "<td>"+datos[c].producto+"</td><td align='right'>"+datos[c].stock+"</td><td align='right'>"+datos[c].precioventa+"</td></tr>";
                            $("#tbodyProducto").append(a);           
                        }
                        
                        $('#tablaProducto').DataTable({
                            "scrollY":        "200px",
                            "scrollCollapse": true,
                            "paging":         false
                        });
                            
                           
                        $('#tablaProducto_filter').css('display','none');
                        $("#tablaProducto_info").css("display","none");
                    }
                });
        }
    }
    
    var carro = new Array();
    var carroDoc = new Array();
    var copia = new Array();
    var idant = 0;
    function seleccionarProducto(idproducto,codigobarra,descripcion,preciocompra,precioventa,stock, tipo , cantidad = 1){
        var band=true;
        var id=idproducto;
        idproducto = idproducto+'-'+tipo;
        for(c=0; c < carro.length; c++){
            if(carro[c]==idproducto){
                band=false;
            }      
        }
        if(band){
            var strDetalle = "<tr id='tr"+idproducto+"'><td><input type='hidden' id='txtIdProducto"+idproducto+"' name='txtIdProducto"+idproducto+"' value='"+id+"' /><input type='hidden' id='txtTipo"+idproducto+"' name='txtTipo"+idproducto+"' value='"+tipo+"' /><input type='text' data='numero3' style='width: 80px;' class='form-control input-xs' id='txtCantidad"+idproducto+"' name='txtCantidad"+idproducto+"' value='1' size='3' onkeydown=\"if(event.keyCode==13){calcularTotalItem('"+idproducto+"')}\" onblur=\"calcularTotalItem('"+idproducto+"')\" /></td>";
            @if ($conf_codigobarra=="S")
                strDetalle = strDetalle + "<td align='left'>"+codigobarra+"</td>";
            @endif
    
            strDetalle = strDetalle + "<td align='left'>"+descripcion+"</td>" + 
            "<td align='center'><input type='hidden' id='txtPrecioVenta"+idproducto+"' name='txtPrecioVenta"+idproducto+"' value='"+precioventa+"' /><input type='hidden' id='txtPrecioCompra"+idproducto+"' name='txtPrecioCompra"+idproducto+"' value='"+preciocompra+"' /><input readonly type='text' size='5' class='form-control input-xs' data='numero' id='txtPrecio"+idproducto+"' style='width: 80px;' name='txtPrecio"+idproducto+"' value='"+precioventa+"' onkeydown=\"if(event.keyCode==13){calcularTotalItem('"+idproducto+"')}\" onblur=\"calcularTotalItem('"+idproducto+"')\" /></td>"+
            "<td align='center'><input type='text' readonly='' data='numero' class='form-control input-xs' size='5' name='txtTotal"+idproducto+"' style='width: 80px;' id='txtTotal"+idproducto+"' value='"+precioventa+"' /></td>"+
            "<td><a href='#' onclick=\"quitarProducto('"+idproducto+"')\"><i class='fa fa-minus-circle' title='Quitar' width='20px' height='20px'></i></td></tr>";
           
            $("#tbDetalle").append(strDetalle);
             carro.push(idproducto);
            if(idant>0){
                $("#tdDescripcion"+idant).css('font-size','');
                $("#tdDescripcion"+idant).css('color','');
                $("#tdDescripcion"+idant).css('font-weight','');
            }
            $("#tdDescripcion"+idproducto).css('font-size','14px');
            $("#tdDescripcion"+idproducto).css('color','');
            $("#tdDescripcion"+idproducto).css('font-weight','');
            idant=idproducto;
            if(cantidad == 1){
            $('#txtCantidad'+idproducto).val("1");
            }else{
            $('#txtCantidad'+idproducto).val(cantidad);
            }
            $('#txtCantidad'+idproducto).keyup(function (e) {
                var key = window.event ? e.keyCode : e.which;
                if (key == '13') {
                    $('#descripcion').val("");
                    $('#descripcion').focus();
                }
            });
            $(':input[data="numero"]').inputmask('decimal', { radixPoint: ".", autoGroup: true, groupSeparator: "", groupSize: 3, digits: 2 });
            $(':input[data="numero3"]').inputmask('decimal', { radixPoint: ".", autoGroup: true, groupSeparator: "", groupSize: 3, digits: 3 });

            $('#txtCantidad'+idproducto).select();
            calcularTotal();
        }else{
            if(idant>0){
                $("#tdDescripcion"+idant).css('font-size','');
                $("#tdDescripcion"+idant).css('color','');
                $("#tdDescripcion"+idant).css('font-weight','');
            }
            $("#tdDescripcion"+idproducto).css('font-size','14px');
            $("#tdDescripcion"+idproducto).css('color','');
            $("#tdDescripcion"+idproducto).css('font-weight','');
            idant=idproducto;
            var cant = parseInt($('#txtCantidad'+idproducto).val())+1; 
            $('#txtCantidad'+idproducto).val(cant);
            $('#txtCantidad'+idproducto).select();
            $('#txtCantidad'+idproducto).keyup(function (e) {
                var key = window.event ? e.keyCode : e.which;
                if (key == '13') {
                    $('#descripcion').val("");
                    $('#descripcion').focus();
                }
            });
            calcularTotalItem(idproducto);
        }
    }
    
    function calcularTotal(){
        var total2=0;
        for(c=0; c < carro.length; c++){
            var tot=parseFloat($("#txtTotal"+carro[c]).val());
            total2=Math.round((total2+tot) * 100) / 100;        
        }
        if($('#ispersonal').val() == 'S'){
            $("#total").val(Math.round((total2) * 90) / 100); 
            $("#totalpagado").val(Math.round((total2) * 90) / 100);
            $("#descuento").val(Math.round((total2) * 10) / 100);
            $("#lblDescuento").html(' S/ '+Math.round(total2 * 10) / 100);
        }else if($('#ispersonal').val() == 'N'){
            $("#total").val(total2); 
            $("#totalpagado").val(total2);
            $("#descuento").val('');
            $("#lblDescuento").html('0.0');
        }
    }
    
    function calcularTotalItem(id){
        var cant=parseFloat($("#txtCantidad"+id).val());
        var pv=parseFloat($("#txtPrecio"+id).val());
        var total=Math.round((pv*cant) * 100) / 100;
        $("#txtTotal"+id).val(total);
        calcularTotal();
    }
    
    function quitarProducto(id){
        $("#tr"+id).remove();
        for(c=0; c < carro.length; c++){
            if(carro[c] == id) {
                carro.splice(c,1);
            }
        }
        calcularTotal();
    }
    
    function generarNumero(){
        $.ajax({
            type: "POST",
            url: "venta/generarNumero",
            data: "tipodocumento="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="tipodocumento"]').val()+"&_token="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="_token"]').val(),
            success: function(a) {
                $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="numero"]').val(a);
            }
        });
    }
    
    function agregarDetalle(id){
        $.ajax({
            type: "POST",
            url: "ticket/agregardetalle",
            data: "id="+id+"&_token="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="_token"]').val(),
            success: function(a) {
                datos=JSON.parse(a);
                for(d=0;d < datos.length; d++){
                    if(datos[d].idservicio>0){
                        datos[d].id=datos[d].idservicio;
                    }else{
                        datos[d].id="00"+Math.round(Math.random()*100);
                    }
                    //console.log(datos[d].idservicio);
                    datos[d].idservicio="01"+Math.round(Math.random()*100)+datos[d].idservicio;
                    $("#tbDetalle").append("<tr id='tr"+datos[d].idservicio+"'><td><input type='hidden' id='txtIdTipoServicio"+datos[d].idservicio+"' name='txtIdTipoServicio"+datos[d].idservicio+"' value='"+datos[d].idtiposervicio+"' /><input type='hidden' id='txtIdServicio"+datos[d].idservicio+"' name='txtIdServicio"+datos[d].idservicio+"' value='"+datos[d].id+"' /><input type='text' data='numero' style='width: 40px;' class='form-control input-xs' id='txtCantidad"+datos[d].idservicio+"' name='txtCantidad"+datos[d].idservicio+"' value='"+datos[d].cantidad+"' size='3' onkeydown=\"if(event.keyCode==13){calcularTotal()}\" onblur=\"calcularTotalItem('"+datos[d].idservicio+"')\" /></td>"+
                        "<td><input type='checkbox' id='chkCopiar"+datos[d].idservicio+"' onclick=\"checkMedico(this.checked,'"+datos[d].idservicio+"')\" /></td>"+
                        "<td><input type='text' class='form-control input-xs' id='txtMedico"+datos[d].idservicio+"' name='txtMedico"+datos[d].idservicio+"' value='"+datos[d].medico+"' /><input type='hidden' id='txtIdMedico"+datos[d].idservicio+"' name='txtIdMedico"+datos[d].idservicio+"' value='"+datos[d].idmedico+"' /></td>"+
                        "<td align='left'>"+datos[d].tiposervicio+"</td><td>"+datos[d].servicio+"</td>"+
                        "<td><input type='hidden' id='txtPrecio2"+datos[d].idservicio+"' name='txtPrecio2"+datos[d].idservicio+"' value='0' /><input type='text' size='5' class='form-control input-xs' style='width: 60px;' data='numero' id='txtPrecio"+datos[d].idservicio+"' name='txtPrecio"+datos[d].idservicio+"' value='0' onkeydown=\"if(event.keyCode==13){calcularTotalItem2('"+datos[d].idservicio+"')}\" onblur=\"calcularTotalItem2('"+datos[d].idservicio+"')\" /></td>"+
                        "<td><input type='text' size='5' style='width: 60px;' class='form-control input-xs' data='numero' id='txtDescuento"+datos[d].idservicio+"' name='txtDescuento"+datos[d].idservicio+"' value='0' onkeydown=\"if(event.keyCode==13){calcularTotalItem2('"+datos[d].idservicio+"')}\" onblur=\"calcularTotalItem2('"+datos[d].idservicio+"')\" style='width:50%' /></td>"+
                        "<td><input type='hidden' id='txtPrecioHospital2"+datos[d].idservicio+"' name='txtPrecioHospital2"+datos[d].idservicio+"' value='0' /><input type='text' size='5' style='width: 60px;' class='form-control input-xs' data='numero'  id='txtPrecioHospital"+datos[d].idservicio+"' name='txtPrecioHospital"+datos[d].idservicio+"' value='0' onblur=\"calcularTotalItem2("+datos[d].idservicio+")\" /></td>"+
                        "<td><input type='hidden' id='txtPrecioMedico2"+datos[d].idservicio+"' name='txtPrecioMedico2"+datos[d].idservicio+"' value='0' /><input type='text' size='5' class='form-control input-xs' data='numero'  id='txtPrecioMedico"+datos[d].idservicio+"' name='txtPrecioMedico"+datos[d].idservicio+"' value='0' style='width: 60px;' /></td>"+
                        "<td><input type='text' style='width: 60px;' readonly='' data='numero' class='form-control input-xs' size='5' name='txtTotal"+datos[d].idservicio+"' id='txtTotal"+datos[d].idservicio+"' value=0' /></td>"+
                        "<td><a href='#' id='Quitar"+datos[d].idservicio+"' onclick=\"quitarServicio('"+datos[d].idservicio+"')\"><i class='fa fa-minus-circle' title='Quitar' width='20px' height='20px'></i></td></tr>");
                    if(datos[d].situacionentrega!="A"){
                        carro.push(datos[d].idservicio);
                    }else{
                        $("#Quitar"+datos[d].idservicio).css('display','none');
                    }
                    calcularTotalItem(datos[d].idservicio);
                    $(':input[data="numero"]').inputmask('decimal', { radixPoint: ".", autoGroup: true, groupSeparator: "", groupSize: 3, digits: 2 });
                    eval("var planes"+datos[d].idservicio+" = new Bloodhound({"+
                        "datumTokenizer: function (d) {"+
                            "return Bloodhound.tokenizers.whitespace(d.value);"+
                        "},"+
                        "limit: 10,"+
                        "queryTokenizer: Bloodhound.tokenizers.whitespace,"+
                        "remote: {"+
                            "url: 'medico/medicoautocompletar/%QUERY',"+
                            "filter: function (planes"+datos[d].idservicio+") {"+
                                "return $.map(planes"+datos[d].idservicio+", function (movie) {"+
                                    "return {"+
                                        "value: movie.value,"+
                                        "id: movie.id,"+
                                    "};"+
                                "});"+
                            "}"+
                        "}"+
                    "});"+
                    "planes"+datos[d].idservicio+".initialize();"+
                    "$('#txtMedico"+datos[d].idservicio+"').typeahead(null,{"+
                        "displayKey: 'value',"+
                        "source: planes"+datos[d].idservicio+".ttAdapter()"+
                    "}).on('typeahead:selected', function (object, datum) {"+
                        "$('#txtMedico"+datos[d].idservicio+"').val(datum.value);"+
                        "$('#txtIdMedico"+datos[d].idservicio+"').val(datum.id);"+
                        "copiarMedico('"+datos[d].idservicio+"');"+
                    "});");
                    $("#txtMedico"+datos[d].idservicio).focus(); 
    
                } 
                $(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="coa"]').attr("readonly","true");
                $(".datofactura").css("display","none");
            }
        });
    }
    
    function calcularVuelto(){
        var tot=parseFloat($("#total").val());
        var din=parseFloat($("#dinero").val());
        var vue=Math.round((din - tot) * 100) / 100;
        $("#vuelto").val(vue);
    }
    
    function aCuenta(check){
        if(check){
            $("#acuenta").val("S");
        }else{
            $("#acuenta").val("N");
        }
    }
    function cambioTipo(tipoventa){
        if(tipoventa=='CREDITO'){
            $('#divPago').addClass('d-none');
        }else if(tipoventa=='CONTADO'){
            $('#divPago').removeClass('d-none');
        }
    }
    
    function calcularTarjeta(){
    
        var tar = parseFloat(($("#tarjeta").val())?$("#tarjeta").val():0);
        var tot = parseFloat($("#total").val());
        var efe = Math.round((tot - tar)*100)/100;
        $("#totalpagado").val(efe);
    }
    
    </script>
    @foreach($detalles as $detalle)
    <?php 
        $idproducto = $detalle->producto_id?$detalle->producto_id:$detalle->promocion_id;
        $tipo = $detalle->producto_id?'P':'C';
        $codigobarra = ($detalle->producto_id)?$detalle->producto->codigobarra:'';
        $descripcion = ($detalle->producto_id)?$detalle->producto->nombre: $detalle->promocion->nombre;
        $preciocompra = $detalle->preciocompra;   
        $precioventa = $detalle->precioventa;  
        $cantidad = $detalle->cantidad; 
    ?>

    <script>
        seleccionarProducto('{{$idproducto}}','{{$codigobarra}}','{{$descripcion}}','{{$preciocompra}}','{{$precioventa}}',0, '{{$tipo}}','{{$cantidad}}');
    </script>
@endforeach
<script>
    calcularTotal();
</script>