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
				  <div class="col-lg-8 col-md-8 col-offset-2">
					  <div class="card mt-4">
						<div class="card-body">
							{!! Form::open(['route' => $ruta["search"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => '', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad]) !!}
							{!! Form::hidden('page', 1, array('id' => 'page')) !!}
							{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
							
						  <div class="row">
							<div class="col-lg-12 col-md-12  form-group">
								{!! Form::label('fechainicio', 'Fecha inicio:') !!}
								{!! Form::date('fechainicio', date('Y-m-d'), array('class' => 'form-control input-xs', 'id' => 'fechainicio')) !!}
							 </div>
						  </div>
						  <div class="row">
							<div class="col-lg-12 col-md-12  form-group">
								{!! Form::label('fechafin', 'Fecha fin:') !!}
								{!! Form::date('fechafin', date('Y-m-d'), array('class' => 'form-control input-xs', 'id' => 'fechafin')) !!}
							</div>
						  </div>
						  <div class="row">
							<div class="col-lg-12 col-md-12  form-group">
								{!! Form::label('categoria', 'Categoria:') !!}
								{!! Form::select('categoria', $cboCategoria , null, array('class' => 'form-control input-xs', 'id' => 'categoria')) !!}
							</div>
						  </div>
						  <div class="row">
							<div class="col-lg-12 col-md-12  form-group">
								{!! Form::label('producto', 'Producto:') !!}
								{!! Form::hidden('producto_id', 0, array('id' => 'producto_id')) !!}
								{!! Form::text('producto', '', array('class' => 'form-control input-xs', 'id' => 'producto')) !!}
							</div>
						  </div>
						  <div class="row">
							<div class="col-lg-12 col-md-12  form-group text-right">
								{!! Form::button('GENERAR <i class="fa fa-file-excel ml-2"></i> ', array('class' => 'btn btn-success btn-sm  ', 'id' => 'btnDetalle', 'onclick' => 'imprimir();' ,'style'=>'width:200px;')) !!}   
							</div>
						  </div>
						{!! Form::close() !!}
						</div>
					  </div>
					  
							<!-- /.card-header -->
							<div class="card-body table-responsive px-3">
								<div id="listado{{ $entidad }}">
								</div>
							</div>
							<!-- /.card-body -->
							  
				  </div>
			  </div>
		  </div>
		  <!-- /.row -->
		</div><!-- /.container-fluid -->
	  </div>
	  <!-- /.content -->
	
  </div>
<script>
	$(document).ready(function () {
		init(IDFORMBUSQUEDA+'{{ $entidad }}', 'B', '{{ $entidad }}');
	});

    var producto2 = new Bloodhound({
		datumTokenizer: function (d) {
			return Bloodhound.tokenizers.whitespace(d.value);
		},
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: {
			url: 'promocion/productoautocompletar/%QUERY',
			filter: function (producto2) {
				return $.map(producto2, function (movie) {
					return {
						value: movie.value,
						id: movie.id,
					};
				});
			}
		}
	});
	producto2.initialize();
	$(IDFORMBUSQUEDA + '{!! $entidad !!} :input[id="producto"]').typeahead(null,{
		displayKey: 'value',
		source: producto2.ttAdapter()
	}).on('typeahead:selected', function (object, datum) {
		$("#producto_id").val(datum.id);
		$("#producto").val(datum.value);
	});

    function imprimir(){
    	//if($("#producto_id").val()!=""){
        	window.open("kardexreporte/excelKardex?fechainicio="+$("#fechainicio").val()+"&fechafin="+$("#fechafin").val()+"&producto="+$("#producto_id").val()+"&producto2="+$("#producto").val()+"&categoria="+$("#categoria").val(),"_blank");
        //}
    }
</script>