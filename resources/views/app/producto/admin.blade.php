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
				  <div class="col-12">
					  <div class="card mt-4">
						<div class="card-body w-100 d-flex">
							{!! Form::open(['route' => $ruta["search"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => 'w-100', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad]) !!}
								{!! Form::hidden('page', 1, array('id' => 'page')) !!}
								{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
								
							<div class="row w-100 d-flex">
								
								<div class="col-lg-2 col-md-2 form-group">
									{!! Form::label('codigobarra', 'Cod. Barra:') !!}
									{!! Form::text('codigobarra', '', array('class' => 'form-control input-xs', 'id' => 'codigobarra')) !!}
								</div>
								<div class="col-lg-3 col-md-3 form-group">
									{!! Form::label('nombre', 'Nombre:') !!}
									{!! Form::text('nombre', '', array('class' => 'form-control input-xs', 'id' => 'nombre')) !!}
								</div>
								
								<div class="col-lg-2 col-md-2 form-group">
									{!! Form::label('categoria', 'Categoria') !!}
									{!! Form::select('categoria', $cboCategoria, '', array('class' => 'form-control input-xs', 'id' => 'categoria' ,'onchange'=>'buscar(\''.$entidad.'\')')) !!}
								</div>
								<div class="col-lg-3 col-md-3 form-group">
									{!! Form::label('lblsubcategoria', 'Subcategoria') !!}
									{!! Form::select('subcategoria', $cboSubcategoria,'', array('class' => 'form-control input-xs', 'id' => 'subcategoria')) !!}
								</div>
								<div class="col-lg-2 col-md-2 form-group">
									{!! Form::label('marca', 'Marca:') !!}
									{!! Form::select('marca', $cboMarca, '', array('class' => 'form-control input-xs', 'id' => 'marca' ,'onchange'=>'buscar(\''.$entidad.'\')')) !!}
								</div>
							</div>
							<div class="row w-100 d-flex">
								<div class="col-lg-3 col-md-3 form-group">
									{!! Form::label('precio', 'Opciones precio') !!}
									{!! Form::select('precio', ['S'=>'Sin precio' , 'C'=>'Con precio'], 'C', array('class' => 'form-control input-xs', 'id' => 'precio', 'onChange'=>'buscar(\''.$entidad.'\')')) !!}
								</div>
								<div class="col-lg-3 col-md-3 form-group">
									{!! Form::label('lblsucursal', 'Sucursal') !!}
									{!! Form::select('sucursal_id', $cboSucursal, '', array('class' => 'form-control input-xs', 'id' => 'sucursal_id', 'onChange'=>'buscar(\''.$entidad.'\')')) !!}
								</div>
								<div class="col-lg-2 col-md-2 form-group" style="min-width: 150px;">
									{!! Form::label('nombre', 'Filas a mostrar') !!}
									{!! Form::selectRange('filas', 1, 30, 10, array('class' => 'form-control input-xs', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
								</div>
							</div>
							{!! Form::close() !!}
						</div>
					  </div>
					  <div class="row mt-2" >
						<div class="col-md-12">
						  <div class="card">
							<div class="card-header">
							  <h3 class="card-title">{{$title}}</h3>
							  <div class="card-tools">
								{!! Form::button(' <i class="fa fa-plus fa-fw"></i> Agregar', array('class' => 'btn  btn-outline-primary', 'id' => 'btnNuevo', 'onclick' => 'modal (\''.URL::route($ruta["create"], array('listar'=>'SI')).'\', \''.$titulo_registrar.'\', this);')) !!}
								{!! Form::button(' <i class="fa fa-file fa-fw"></i> Importar', array('class' => 'btn  btn-outline-success', 'id' => 'btnNuevo', 'onclick' => 'modal (\''.URL::route($ruta["import"], array('listar'=>'SI')).'\', \'Importar Productos\', this);')) !!}
								{!! Form::button(' <i class="fa fa-file-pdf fa-fw"></i> Exportar', array('class' => 'btn  btn-outline-danger', 'id' => 'btnNuevo', 'onclick' => 'imprimir();')) !!}
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
  <style>
	  .select2-container--default .select2-selection--single {
				border: 1px solid #ced4da;
				padding: .46875rem .75rem;
				height: calc(2.25rem + 2px);
		}
		</style>	
<script>
	$(document).ready(function () {
		buscar('{{ $entidad }}');
		init(IDFORMBUSQUEDA+'{{ $entidad }}', 'B', '{{ $entidad }}');
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="nombre"]').keyup(function (e) {
			var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				buscar('{{ $entidad }}');
			}
		});
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="codigobarra"]').keyup(function (e) {
			var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				buscar('{{ $entidad }}');
			}
		});

		$('#categoria').select2({
			ajax: {
				url: "promocion/categoriaautocompletar",
				dataType: 'json',
				delay: 250,
				data: function(params){
					return{
						q: $.trim(params.term),
					};
				},
				processResults: function(data){
					return{
						results: data
					};
				}
				
			}
		});
		$('#subcategoria').select2({
			ajax: {
				url: "promocion/subcategoriaautocompletar",
				dataType: 'json',
				delay: 250,
				data: function(params){
					return{
						q: $.trim(params.term),
						idcat: ($('#categoria').val())?($('#categoria').val()):'0',
					};
				},
				processResults: function(data){
					return{
						results: data
					};
				}
				
			}
		});
		$('#subcategoria').on('change',function(){
			buscar('{{ $entidad }}');
		});

		$('#categoria').on('change',function(){
			$('#subcategoria').val(null).trigger('change');
			buscar('{{ $entidad }}');
		});
	});
	function excel(){
        window.open("producto/excel?nombre="+$("#nombre").val()+"&marca="+$("#marca").val()+"&categoria="+$("#categoria").val(),"_blank");
    }

	function imprimir(){
        window.open("producto/export","_blank");
    }
</script>