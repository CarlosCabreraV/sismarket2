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
							{!! Form::open(['route' => $ruta["index"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => '', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad]) !!}
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
								{!! Form::label('sucursal', 'Sucursal:') !!}
								{!! Form::select('sucursal', $sucursal, '', array('class' => 'form-control input-xs slc2', 'id' => 'sucursal', 'style'=>'width: 100%!important','onchange'=>'' )) !!}
							</div>
						  </div>
						  <div class="row">
							<div class="col-lg-12 col-md-12  form-group">
								{!! Form::label('category', 'Categoría:') !!}
								{!! Form::select('category', $category, '', array('class' => 'form-control input-xs slc2', 'id' => 'category', 'style'=>'width: 100%!important','onchange'=>'cambiarsubcategoria()' )) !!}
							</div>
						  </div>
						  <div class="row">
							<div class="col-lg-12 col-md-12  form-group">
								{!! Form::label('categoria', 'Subcategoría:') !!}
								{!! Form::select('categoria', $cboCategoria, '', array('class' => 'form-control input-xs slc2', 'id' => 'categoria', 'style'=>'width: 100%!important','onchange'=>'cambiarproducto()' )) !!}
							</div>
						  </div>
						  <div class="row">
							<div class="col-lg-12 col-md-12  form-group">
								{!! Form::label('marca', 'Marca:') !!}
								{!! Form::select('marca', $cboMarca, '', array('class' => 'form-control slc2', 'id' => 'marca','style'=>'width: 100%!important','onchange'=>'cambiarproducto()')) !!}
							</div>
						  </div>
						  <div class="row">
							<div class="col-lg-12 col-md-12  form-group">
								{!! Form::label('producto', 'Producto:') !!}
								{!! Form::select('producto', $producto, '', array('class' => 'form-control input-xs slc2', 'id' => 'producto', 'style'=>'width: 100%!important' )) !!}
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

  
<!-- /.content -->	
<script>
	$(document).ready(function () {
		init(IDFORMBUSQUEDA+'{{ $entidad }}', 'B', '{{ $entidad }}');
		$('.slc2').select2();
	});

    function imprimir(){
        window.open("detallereporte/excelDetalle?sucursal="+$("#sucursal").val()+"&fechainicio="+$("#fechainicio").val()+"&fechafin="+$("#fechafin").val()+"&marca="+$("#marca").val()+"&categoria="+$("#categoria").val()+"&category="+$("#category").val()+"&producto="+$("#producto").val(),"_blank");
    }

	function cambiarsubcategoria() {
	    var idcategory = $(IDFORMBUSQUEDA + '{{ $entidad }}' + " :input[id='category']").val();
		var idmarca = $(IDFORMBUSQUEDA + '{{ $entidad }}' + " :input[id='marca']").val();	 
        var ruta = 'detallereporte/cambiarcategoria?category='+idcategory+"&marca="+idmarca;
        var respuesta = '';
        var data = sendRuta(ruta);
        data.done(function(msg) {
            respuesta = msg;
        }).fail(function(xhr, textStatus, errorThrown) {
            
        }).always(function() {
            data = JSON.parse(respuesta);
            $(IDFORMBUSQUEDA + '{{ $entidad }}' + " :input[id='categoria']").html("'<option value=''>TODOS</option>");
            $(IDFORMBUSQUEDA + '{{ $entidad }}' + " :input[id='categoria']").append(data.categorias);
            $(IDFORMBUSQUEDA + '{{ $entidad }}' + " :input[id='producto']").html("'<option value=''>TODOS</option>");
            $(IDFORMBUSQUEDA + '{{ $entidad }}' + " :input[id='producto']").append(data.productos);
        });
	    
	 }
    function cambiarproducto() {
	    var idcategoria = $(IDFORMBUSQUEDA + '{{ $entidad }}' + " :input[id='categoria']").val();
		var idmarca = $(IDFORMBUSQUEDA + '{{ $entidad }}' + " :input[id='marca']").val();	
        var ruta = 'detallereporte/cambiarproducto?categoria='+idcategoria+"&marca="+idmarca;
        var respuesta = '';
        var data = sendRuta(ruta);
        data.done(function(msg) {
            respuesta = msg;
        }).fail(function(xhr, textStatus, errorThrown) {
            
        }).always(function() {
            data = JSON.parse(respuesta);
            $(IDFORMBUSQUEDA + '{{ $entidad }}' + " :input[id='producto']").html("'<option value=''>TODOS</option>");
            $(IDFORMBUSQUEDA + '{{ $entidad }}' + " :input[id='producto']").append(data.productos);
        });
	    
	 }

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