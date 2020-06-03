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
								{!! Form::label('fechainicio', 'Fecha inicio') !!}
								{!! Form::date('fechainicio', date('Y-m-d'), array('class' => 'form-control input-xs', 'id' => 'fechainicio')) !!}
							 </div>
						  </div>
						  <div class="row">
							<div class="col-lg-12 col-md-12  form-group">
								{!! Form::label('fechafin', 'Fecha fin') !!}
								{!! Form::date('fechafin', date('Y-m-d'), array('class' => 'form-control input-xs', 'id' => 'fechafin')) !!}
							</div>
						  </div>
						  <div class="row">
							<div class="col-lg-12 col-md-12  form-group">
								{!! Form::label('sucursal', 'Sucursal') !!}
								{!! Form::select('sucursal', $cboSucursal , null, array('class' => 'form-control input-xs', 'id' => 'sucursal', 'style'=>'width:100%;')) !!}
							</div>
						  </div>
						  <div class="row">
							<div class="col-lg-12 col-md-12  form-group">
								{!! Form::label('lblcategoria', 'Categoria') !!}
								{!! Form::select('categoria', $cboCategoria , null, array('class' => 'form-control input-xs', 'id' => 'categoria', 'style'=>'width:100%;')) !!}
							</div>
						  </div>
						  <div class="row">
							<div class="col-lg-12 col-md-12  form-group">
								{!! Form::label('lblsubcategoria', 'Subcategoria') !!}
								{!! Form::select('subcategoria', $cboCategoria , null, array('class' => 'form-control input-xs', 'id' => 'subcategoria', 'style'=>'width:100%;')) !!}
							</div>
						  </div>
						  <div class="row">
							<div class="col-lg-12 col-md-12 w-100 d-inline-block">
								{!! Form::label('producto', 'Producto') !!}
								<div class="form-group">
									{!! Form::hidden('producto_id', 0, array('id' => 'producto_id')) !!}
									{!! Form::select('producto', $cboProductos, null, array('class' => 'form-control input-md', 'id'=> 'producto' , 'style'=>'width:100%;')) !!}
								</div>
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
  <style>
		.select2-container--default .select2-selection--single {
				border: 1px solid #ced4da;
				padding: .46875rem .75rem;
				height: calc(2.25rem + 2px);
		}
		
  </style>
<script>
	$(document).ready(function () {
		init(IDFORMBUSQUEDA+'{{ $entidad }}', 'B', '{{ $entidad }}');
		$('#producto').select2({
			ajax: {
				url: "promocion/productoautocompletar2",
				dataType: 'json',
				delay: 250,
				data: function(params){
					return{
						q: $.trim(params.term),
						idcat: ($('#categoria').val())?($('#categoria').val()):'0',
						idsub: ($('#subcategoria').val())?($('#subcategoria').val()):'0'
					};
				},
				processResults: function(data){
					return{
						results: data
					};
				}
				
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
		$('#sucursal').select2({
			ajax: {
				url: "promocion/sucursalautocompletar",
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

		$('#producto').on('change',function(){
				var idproducto = this.value;
				$('#producto_id').val(idproducto);
		});
		
		$('#subcategoria').on('change',function(){
			$('#producto').val(null).trigger('change');
			$('#producto_id').val('0');
		});

		$('#categoria').on('change',function(){
			$('#subcategoria').val(null).trigger('change');
			$('#producto').val(null).trigger('change');
			$('#producto_id').val('0');
		});
	});
	/*
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
	*/

    function imprimir(){
    	//if($("#producto_id").val()!=""){
        	window.open("kardexreporte/excelKardex?sucursal="+$("#sucursal").val()+"&fechainicio="+$("#fechainicio").val()+"&fechafin="+$("#fechafin").val()+"&producto="+$("#producto_id").val()+"&producto2="+$("#producto").val()+"&categoria="+$("#categoria").val(),"_blank");
        //}
    }
</script>