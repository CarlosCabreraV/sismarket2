<?php 
$nombrepersona = NULL;
$esCajero = "false";
if (!is_null($usuario)) {
	$persona = $usuario->person;
	$nombrepersona = trim($persona->lastname.' '.$persona->apellidopaterno.' '.$persona->apellidomaterno.', '.trim($persona->firstname.' '.$persona->nombres));
	if($usuario->usertype_id == 2){
		$esCajero = "true";
	}
}
?>
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($usuario, $formData) !!}
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
<div class="form-group">
	{!! Form::label('sucursal_id', 'Sucursal:', array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label')) !!}
	<div class=	"col-lg-12 col-md-12 col-sm-12">
		{!! Form::select('sucursal_id', $cboSucursal, null, array('class' => 'form-control input-xs', 'id' => 'sucursal_id','onchange'=>'cambiarcaja();')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('usertype_id', 'Tipo de usuario:', array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label')) !!}
	<div class=	"col-lg-12 col-md-12 col-sm-12">
		{!! Form::select('usertype_id', $cboTipousuario, null, array('class' => 'form-control input-xs', 'id' => 'usertype_id','onchange'=>'changetipousuario();')) !!}
	</div>
</div>
<div id="caja">
	<div class="form-group">
		{!! Form::label('caja_id', 'Caja:', array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label')) !!}
		<div class=	"col-lg-12 col-md-12 col-sm-12">
			{!! Form::select('caja_id', $cboCaja, null, array('class' => 'form-control input-xs', 'id' => 'caja_id')) !!}
		</div>
	</div>
</div>
<div class="form-group">
	{!! Form::label('nombrepersona', 'Persona:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	{!! Form::hidden('person_id', null, array('id' => 'person_id')) !!}
	<div class="col-lg-12 col-md-12 col-sm-12">
		@if(!is_null($usuario))
		{!! Form::text('nombrepersona', $nombrepersona, array('class' => 'form-control input-xs', 'id' => 'nombrepersona', 'placeholder' => 'Seleccione persona')) !!}
		@else
		{!! Form::text('nombrepersona', $nombrepersona, array('class' => 'form-control input-xs', 'id' => 'nombrepersona', 'placeholder' => 'Seleccione persona')) !!}
		@endif
	</div>
</div>
@if (is_null($usuario))
	<div class="form-group">
		{!! Form::label('login', 'Usuario:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::text('login', null, array('class' => 'form-control input-xs', 'id' => 'login', 'placeholder' => 'Ingrese login')) !!}
		</div>
	</div>
@else
	<div class="form-group">
		{!! Form::label('login', 'Usuario:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! Form::text('login', null, array('class' => 'form-control input-xs', 'id' => 'login', 'placeholder' => 'Ingrese login','readonly'=>"true")) !!}
		</div>
	</div>
@endif
<div class="form-group">
	{!! Form::label('password', 'Contraseña:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-12 col-md-12 col-sm-12">
		{!! Form::password('password', array('class' => 'form-control input-xs', 'id' => 'password', 'placeholder' => 'Ingrese contraseña')) !!}
	</div>
</div>
<div class="form-group">
	<div class="col-lg-12 col-md-12 col-sm-12 text-right">
		{!! Form::button('<i class="fa fa-check "></i> '.$boton, array('class' => 'btn btn-primary btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
		{!! Form::button('<i class="fa fa-undo "></i> Cancelar', array('class' => 'btn btn-default btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
</div>
{!! Form::close() !!}
<script type="text/javascript">
	$(document).ready(function() {
		init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="usertype_id"]').focus();
		configurarAnchoModal('500');
		$("#caja").hide();
		if({{ $esCajero  }}){
			$("#caja").show();
		}
		var personas = new Bloodhound({
			datumTokenizer: function (d) {
				return Bloodhound.tokenizers.whitespace(d.value);
			},
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: {
				url: 'usuario/personautocompletar/%QUERY',
				filter: function (personas) {
					return $.map(personas, function (movie) {
						return {
							value: movie.value,
							id: movie.id
						};
					});
				}
			}
		});
		personas.initialize();
		$('#nombrepersona').typeahead(null,{
			displayKey: 'value',
			source: personas.ttAdapter()
		}).on('typeahead:selected', function (object, datum) {
			$('#person_id').val(datum.id);
		});

	}); 

	function changetipousuario(){
		var tipousuario = $("#usertype_id").val();
		$("#caja").hide();
		if(tipousuario=='2'){
			$("#caja").show();
		}
	}

	function cambiarcaja() {
	    var idsucursal = $(IDFORMMANTENIMIENTO + '{{ $entidad }}' + " :input[id='sucursal_id']").val();
	    if(idsucursal != ""){
	        var ruta = 'usuario/cambiarcaja?sucursal_id='+idsucursal;
	        var respuesta = '';
	        var data = sendRuta(ruta);
	        data.done(function(msg) {
	            respuesta = msg;
	        }).fail(function(xhr, textStatus, errorThrown) {
	            
	        }).always(function() {
	            data = JSON.parse(respuesta);
	            $(IDFORMMANTENIMIENTO + '{{ $entidad }}' + " :input[id='caja_id']").html("'<option value=''>SELECCIONE</option>");
	            $(IDFORMMANTENIMIENTO + '{{ $entidad }}' + " :input[id='caja_id']").append(data.cajas);
	        });
	    }
	    
	 }

</script>