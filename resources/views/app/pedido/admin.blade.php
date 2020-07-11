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
				<li class="breadcrumb-item"><a href="#">Pedidos</a></li>
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
                                    {!! Form::label('fechainicio', 'Fecha inicio') !!}
                                    {!! Form::date('fechainicio', date('Y-m-d'), array('class' => 'form-control input-xs', 'id' => 'fechainicio')) !!}
                                </div>
                                <div class="col-lg-3 col-md-3  form-group">
                                    {!! Form::label('fechafin', 'Fecha fin') !!}
                                    {!! Form::date('fechafin', '', array('class' => 'form-control input-xs', 'id' => 'fechafin')) !!}
                                </div>
								<div class="col-lg-3 col-md-3  form-group">
									{!! Form::label('cliente', 'Cliente') !!}
									{!! Form::text('cliente', '', array('class' => 'form-control input-xs', 'id' => 'cliente')) !!}
                                </div>
                                <div class="col-lg-3 col-md-3  form-group">
									{!! Form::label('tipodocumento_id', 'Tipo documento') !!}
									{!! Form::select('tipodocumento_id', $cboTipoDocumento, '', array('class' => 'form-control input-xs', 'id' => 'tipodocumento_id')) !!}
								</div>
							</div>
							<div class="row w-100">
                                
								<div class="col-lg-3 col-md-3  form-group">
									{!! Form::label('lblestado', 'Estado') !!}
									{!! Form::select('estado',$cboEstados , '', array('class' => 'form-control input-xs', 'id' => 'estado')) !!}
								</div>
								<div class="col-lg-3 col-md-3  form-group">
									{!! Form::label('lblestado', 'Tipo') !!}
									{!! Form::select('tipo', [''=>'Todos','S'=>'Delivery','N'=>'Recoger en tienda'] , '', array('class' => 'form-control input-xs', 'id' => 'tipo')) !!}
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
							  <div class="card-tools">
								@if(!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin())
									{!! Form::button(' <i class="fa fa-plus fa-fw"></i> Agregar', array('class' => 'btn  btn-outline-primary', 'id' => 'btnNuevo', 'onclick' => 'modal (\''.URL::route($ruta["create"], array('listar'=>'SI')).'\', \''.$titulo_registrar.'\', this);')) !!}
								@endif 
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
  <script>
	$(document).ready(function () {
		buscar('{{ $entidad }}');
		init(IDFORMBUSQUEDA+'{{ $entidad }}', 'B', '{{ $entidad }}');
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="cliente"]').keyup(function (e) {
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
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="estado"]').change(function (e) {
				buscar('{{ $entidad }}');
		});
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="tipo"]').change(function (e) {
				buscar('{{ $entidad }}');
		});
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="tipodocumento_id"]').change(function (e) {
				buscar('{{ $entidad }}');
		});
	});
</script>