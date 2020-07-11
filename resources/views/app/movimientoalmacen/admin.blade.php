<div class="content-wrapper p-2 ml-0 " id="container"  >
	<!-- Content Header (Page header) -->
	
	  <div class="content-header mb-none">
		<div class="container-fluid">
		  <div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0 text-dark">{{$title}}</h1>
				<h5 class="m-2 text-dark">{{$sucursal}}</h5>
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
						<div class="card-body w-100 d-flex">
							{!! Form::open(['route' => $ruta["search"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => 'w-100 ', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad]) !!}
							{!! Form::hidden('page', 1, array('id' => 'page')) !!}
							{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
						  <div class="row w-100">
								<div class="col-lg-3 col-md-3  form-group">
									{!! Form::label('fechainicio', 'Fecha inicio:') !!}
									{!! Form::date('fechainicio', date('Y-m-d'), array('class' => 'form-control input-xs', 'id' => 'fechainicio')) !!}
								</div>
								<div class="col-lg-3 col-md-3  form-group">
									{!! Form::label('fechafin', 'Fecha fin:') !!}
									{!! Form::date('fechafin', '', array('class' => 'form-control input-xs', 'id' => 'fechafin')) !!}
								</div>
								<div class="col-lg-3 col-md-3  form-group">
									{!! Form::label('sucursal_id', 'Sucursal') !!}
									{!! Form::select('sucursal_id', $cboSucursal, '', array('class' => 'form-control input-xs', 'id' => 'sucursal_id')) !!}
								</div>
								<div class="col-lg-3 col-md-3  form-group">
									{!! Form::label('producto_id', 'Producto:') !!}
									{!! Form::select('producto_id', $cboProducto, '', array('class' => 'form-control input-xs', 'id' => 'producto_id')) !!}
								</div>
								{{-- <div class="col-lg-4 col-md-4  form-group">
									{!! Form::label('proveedor', 'Proveedor:') !!}
									{!! Form::text('proveedor', '', array('class' => 'form-control input-xs', 'id' => 'proveedor')) !!}
								</div> --}}
							</div>
							<div class="row w-100">
								
								<div class="col-lg-3 col-md-3  form-group">
									{!! Form::label('tipodocumento', 'Tipo Doc.:') !!}
									{!! Form::select('tipodocumento', $cboTipoDocumento, '', array('class' => 'form-control input-xs', 'id' => 'tipodocumento')) !!}
								</div>
								<div class="col-lg-3 col-md-3  form-group">
									{!! Form::label('numero', 'Nro:') !!}
									{!! Form::text('numero', '', array('class' => 'form-control input-xs', 'id' => 'numero')) !!}
								</div>
								<div class="col-lg-2 col-md-2  form-group" style="min-width: 150px;">
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
							  <?php 
								$user = Auth::User();
							  ?>
							  @if ($user->usertype_id != "2")
								<div class="card-tools">
									{!! Form::button(' <i class="fa fa-plus fa-fw"></i> Agregar', array('class' => 'btn  btn-outline-primary', 'id' => 'btnNuevo', 'onclick' => 'modal (\''.URL::route($ruta["create"], array('listar'=>'SI')).'\', \''.$titulo_registrar.'\', this);')) !!}
									{!! Form::button(' <i class="fa fa-file fa-fw"></i> Importar', array('class' => 'btn  btn-outline-success', 'id' => 'btnNuevo', 'onclick' => 'modal (\''.URL::route($ruta["import"], array('listar'=>'SI')).'\', \'Importar\', this);')) !!}
								</div>
							  @endif
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
<script>
	$(document).ready(function () {
		buscar('{{ $entidad }}');
		init(IDFORMBUSQUEDA+'{{ $entidad }}', 'B', '{{ $entidad }}');
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="numero"]').keyup(function (e) {
			var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				buscar('{{ $entidad }}');
			}
		});
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="fechainicio"]').change(function (e) {
				buscar('{{ $entidad }}');
		});
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="fechafin"]').change(function (e) {
				buscar('{{ $entidad }}');
		});
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="tipodocumento"]').change(function (e) {
            buscar('{{ $entidad }}');
		});
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="sucursal_id"]').change(function (e) {
            buscar('{{ $entidad }}');
        });
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="producto_id"]').change(function (e) {
            buscar('{{ $entidad }}');
        });
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="producto_id"]').select2();
	});
</script>
<style>

	.select2-container--default .select2-selection--single {
			border: 1px solid #ced4da;
			padding: .46875rem .75rem;
			height: calc(2.25rem + 2px);
	}
	.select2-selection__arrow{
		margin-top: 0.38rem
	}

</style>