<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($modelo, $formData) !!}
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
<div class="callout callout-info"><p class="text-{{$bg_class}}">{{$texto}}</p></div>
<div class="form-group">
	<div class="col-lg-12 col-md-12 col-sm-12 text-right">
		{!! Form::button('<i class="fa fa-check "></i> '.$boton, array('class' => 'btn btn-primary btn-sm bg-'.$bg_class, 'id' => 'btnGuardar', 'onclick' => 'siguienteEtapa(\''.$entidad.'\', this)')) !!}
		{!! Form::button('<i class="fa fa-undo "></i> Cancelar', array('class' => 'btn btn-default btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal((contadorModal - 1));')) !!}
	</div>
</div>
{!! Form::close() !!}
<script type="text/javascript">
	$(document).ready(function() {
		init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
		configurarAnchoModal('350');
	}); 

	function siguienteEtapa (entidad, idboton) {

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
                        cerrarModal();
                        buscarCompaginado('', 'Accion realizada correctamente', entidad, 'OK');
                    } else if(resp === 'ERROR') {
                        toastr.error(dat[0].msg , 'Error');
                    } else {
                        mostrarErrores(respuesta, idformulario, entidad);
                    }
                }
            });
        
    }

</script>