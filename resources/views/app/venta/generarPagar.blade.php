<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($modelo, $formData) !!}
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body bg-light" >
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Total </label>
                    <div class="col-md-9">
                        {!! Form::text('total', number_format($modelo->total, 2), array('class' => 'form-control input-xs', 'id' => 'total', 'size' => 3, 'readonly' => 'false', 'style' => 'font-size:20px;color:white; background:#3d9970;')) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Dinero </label>
                    <div class="col-md-9">
                        {!! Form::text('dinero', null, array('class' => 'form-control input-xs', 'id' => 'dinero', 'size' => 3, 'style' => 'font-size:20px; color:GREEN;', 'onkeyup' => 'calcularVuelto();')) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Vuelto </label>
                    <div class="col-md-9">
                        {!! Form::text('vuelto', null, array('class' => 'form-control input-xs', 'id' => 'vuelto', 'size' => 3, 'readonly' => 'true', 'style' => 'font-size:20px;color:darkblue;')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-gray">
                <h5 class="card-title">MÉTODO</h5>
            </div>
            <div class="card-body bg-light">
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Efectivo </label>
                    <div class="col-md-9">
                        {!! Form::text('totalpagado',number_format($modelo->total,2) , array('class' => 'form-control input-xs', 'id' => 'totalpagado', 'size' => 3, 'readonly' => 'true', 'style' => 'font-size:30px;color:green;')) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Tarjeta </label>
                    <div class="col-md-9">
                        {!! Form::text('tarjeta', 0, array('class' => 'form-control input-xs', 'id' => 'tarjeta', 'size' => 3, 'style' => 'font-size:30px;color:blue;' , 'onkeyup' => 'calcularTarjeta();')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
	<div class="col-lg-12 col-md-12 col-sm-12 text-right">
		{!! Form::button('<i class="fa fa-hand-holding-usd fa-lg mr-1"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar')) !!}
		{!! Form::button('<i class="fa fa-undo fa-lg"></i> Cancelar', array('class' => 'btn btn-default btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal((contadorModal - 1));')) !!}
	</div>
</div>
{!! Form::close() !!}
<script type="text/javascript">
	$(document).ready(function() {
		init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
		configurarAnchoModal('700');
        $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="total"]').inputmask('decimal', { radixPoint: ".", autoGroup: true, groupSeparator: "", groupSize: 3, digits: 2 });
        $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="dinero"]').inputmask('decimal', { radixPoint: ".", autoGroup: true, groupSeparator: "", groupSize: 3, digits: 2 });
        $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="vuelto"]').inputmask('decimal', { radixPoint: ".", autoGroup: true, groupSeparator: "", groupSize: 3, digits: 2 });
        $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="tarjeta"]').inputmask('decimal', { radixPoint: ".", autoGroup: true, groupSeparator: "", groupSize: 3, digits: 2 });
    
        $('#btnGuardar').on('click',function(){
            if(parseFloat($("#tarjeta").val()) > parseFloat($('#total').val())){
                toastr.error('El monto de la tarjeta no debe superar al total','Corrige el siguiente error');
            }else{
                guardar('{{$entidad}}',this);
            }
        });
    }); 
    function calcularVuelto(){
        var tot=$("#total").val()?parseFloat($("#total").val()):0;
        var din=$("#dinero").val()?parseFloat($("#dinero").val()):0;
        var vue=Math.round((din - tot) * 100) / 100;
        $("#vuelto").val(vue);
    }
    function calcularTarjeta(){
        var tar = parseFloat(($("#tarjeta").val())?$("#tarjeta").val():0);
        var tot = parseFloat($("#total").val());
        var efe = Math.round((tot - tar)*100)/100;
        $("#totalpagado").val(efe);
    }
</script>