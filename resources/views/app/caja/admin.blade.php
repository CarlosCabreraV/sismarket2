<div class="content-wrapper p-2 ml-0 " id="container"  >
	<!-- Content Header (Page header) -->
	
	  <div class="content-header mb-none">
		<div class="container-fluid">
		  <div class="row mb-2">
			<div class="col-sm-6">
			  <h1 class="m-0 text-dark">{{$title}}</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
			  <ol class="breadcrumb float-sm-right">
				<li class="breadcrumb-item"><a href="#">Administracion</a></li>
				<li class="breadcrumb-item active">{{$entidad}}</li>
			  </ol>
			</div><!-- /.col -->
		  </div><!-- /.row -->
		</div><!-- /.container-fluid -->
	  </div>
	  <!-- /.content-header -->
  
	  <!-- Main content -->
	  <div class="content ">
		<div class="container-fluid">
		  <div class="">
			  <div class="row justify-content-center">
				  <div class="col-lg-12 col-md-12">
					  <div class="card mt-4">
						<div class="card-body">
						  <div class="row">
							{!! Form::open(['route' => $ruta["search"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => 'w-100 d-md-flex d-lg-flex d-sm-inline-block', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad]) !!}
							{!! Form::hidden('page', 1, array('id' => 'page')) !!}
							{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
							<div class="col-lg-4 col-md-4  form-group">
								{!! Form::label('lblcaja_id', 'Caja') !!}
							<select id="caja_id" name='caja_id' class="form-control input-xs" onchange="buscar('{{$entidad}}')">
									@if($sucursales)
										@foreach($sucursales as $sucursal)
										<optgroup label="{{$sucursal->nombre}}">
												@foreach($sucursal->cajas as $caja)
													<option  value={{$caja->id}}>{{$caja->nombre}}</option>
												@endforeach
										</optgroup>
										@endforeach
									@else
									<option value={{$caja->id}} selected>{{$caja->nombre}}</option>
									@endif
								</select>
							</div>
							<div class="col-lg-2 col-md-2  form-group" style="min-width: 150px;">
								{!! Form::label('nombre', 'Filas a mostrar') !!}
								{!! Form::selectRange('filas', 1, 30, 10, array('class' => 'form-control input-xs', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
							</div>
							{!! Form::close() !!}
						  </div>
						</div>
					  </div>
					  <div class="row mt-2" >
						<div class="col-md-12">
						  <div class="card">
							<div class="card-header">
							  <h3 class="card-title">{{$title}}</h3>
							  <div class="card-tools">
							  </div>
							</div>
							<!-- /.card-header -->
							<div class="card-body table-responsive px-3">
								<div id="listado{{ $entidad }}">
								</div>
							</div>
							<!-- /.card-body -->
							   
			  
						  </div>
						  <!-- /.card -->
						</div>
					  </div>
					  
				  </div>
			  </div>
		  </div>
		  <!-- /.row -->
		</div><!-- /.container-fluid -->
	  </div>
	  <!-- /.content -->
	
  </div>

  
<!-- /.content -->	
<script>
	$(document).ready(function () {
		buscar('{{ $entidad }}');
		init(IDFORMBUSQUEDA+'{{ $entidad }}', 'B', '{{ $entidad }}');
	});

    function imprimir(){
        window.open("caja/pdfCierre?caja_id="+$("#caja_id").val(),"_blank");
    }

    function imprimirDetalle(){
        window.open("caja/pdfDetalleCierre","_blank");
    }
    
    function modalCaja (controlador, titulo) {
    	var idContenedor = "divModal" + contadorModal;
    	var divmodal     = "<div id=\"" + idContenedor + "\"></div>";
    	var box          = bootbox.dialog({
    		message: divmodal,
    		className: 'modal' +  contadorModal,
    		title: titulo,
    		closeButton: false
    	});
    	box.prop('id', 'modal'+contadorModal);
    	/*$('#modal'+contadorModal).draggable({
    		handle: ".modal-header"
    	});*/
    	modales[contadorModal] = box;
    	contadorModal          = contadorModal + 1;
    	setTimeout(function(){
    		cargarRuta(controlador+"&saldo="+$( '#saldo').val(), idContenedor);
    	},400);
    }

</script>