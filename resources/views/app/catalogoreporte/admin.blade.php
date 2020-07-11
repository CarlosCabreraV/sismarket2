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
							{!! Form::open(['route' => $ruta["store"], 'method' => 'POST' , 'class' => '', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad]) !!}
							{!! Form::hidden('page', 1, array('id' => 'page')) !!}
							{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
							
						  <div class="row">
                <div class="col-lg-12 col-md-12  form-group">
                    {!! Form::label('lblsucursal', 'Sucursal') !!}
                    {!! Form::select('sucursal',$sucursales,'',array('class'=>'form-control ','id'=>'sucursal'))!!}
                </div>
              </div>
              <div class="row ml-3 my-2">
                <div class="form-group">
                    <div class="custom-control custom-switch custom-switch-off-default custom-switch-on-success">
                      <input checked type="checkbox" class="custom-control-input" id="soloconstock" onchange="verificarChecked(this.id ,this.checked);" value='S'>
                      <label class="custom-control-label" for="soloconstock">SOLO PRODUCTOS CON STOCK</label>
                    </div>
                  </div>
              </div>
                          <div class="card">
                              <div class="card-header bg-navy">
                                  <h6 class="card-title">Datos incluidos en el reporte</h6> 
                                </div>
                              <div class="card-body">
                                <div class="row">
                                    <div class="col-md-11 ml-5">
                                      <div class="row">
                                          <div class="form-group">
                                              <div class="custom-control custom-switch custom-switch-off-default custom-switch-on-success">
                                                <input checked type="checkbox" class="custom-control-input" id="categoria" onchange="verificarChecked(this.id ,this.checked);" value='S'>
                                                <label class="custom-control-label" for="categoria">Categoria</label>
                                              </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                          <div class="form-group">
                                              <div class="custom-control custom-switch custom-switch-off-default custom-switch-on-success">
                                                <input checked type="checkbox" class="custom-control-input" id="subcategoria" onchange="verificarChecked(this.id ,this.checked);" value='S'>
                                                <label class="custom-control-label" for="subcategoria">SubCategoria</label>
                                              </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                          <div class="form-group">
                                              <div class="custom-control custom-switch custom-switch-off-default custom-switch-on-success">
                                                <input checked type="checkbox" class="custom-control-input" id="unidad" onchange="verificarChecked(this.id ,this.checked);" value='S'>
                                                <label class="custom-control-label" for="unidad">Unidad</label>
                                              </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                          <div class="form-group">
                                              <div class="custom-control custom-switch custom-switch-off-default custom-switch-on-success">
                                                <input checked type="checkbox" class="custom-control-input" id="marca" onchange="verificarChecked(this.id ,this.checked);" value='S'>
                                                <label class="custom-control-label" for="marca">Marca</label>
                                              </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                          <div class="form-group">
                                              <div class="custom-control custom-switch custom-switch-off-default custom-switch-on-success">
                                                <input checked type="checkbox" class="custom-control-input" id="codigo" onchange="verificarChecked(this.id ,this.checked);" value='S'>
                                                <label class="custom-control-label" for="codigo">Codigo</label>
                                              </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                          <div class="form-group">
                                              <div class="custom-control custom-switch custom-switch-off-default custom-switch-on-success">
                                                <input checked type="checkbox" class="custom-control-input" id="descripcion" onchange="verificarChecked(this.id ,this.checked);" value='S'>
                                                <label class="custom-control-label" for="descripcion">Descripcion</label>
                                              </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                          <div class="form-group">
                                              <div class="custom-control custom-switch custom-switch-off-default custom-switch-on-success">
                                                <input checked type="checkbox" class="custom-control-input" id="abreviatura" onchange="verificarChecked(this.id ,this.checked);" value='S'>
                                                <label class="custom-control-label" for="abreviatura">Abreviatura</label>
                                              </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                          <div class="form-group">
                                              <div class="custom-control custom-switch custom-switch-off-default custom-switch-on-success">
                                                <input checked type="checkbox" class="custom-control-input" id="precioventa" onchange="verificarChecked(this.id ,this.checked);" value='S'>
                                                <label class="custom-control-label" for="precioventa">Precio de venta</label>
                                              </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                          <div class="form-group">
                                              <div class="custom-control custom-switch custom-switch-off-default custom-switch-on-success">
                                                <input checked type="checkbox" class="custom-control-input" id="precioventaespecial" onchange="verificarChecked(this.id ,this.checked);" value='S'>
                                                <label class="custom-control-label" for="precioventaespecial">Precio de venta especial</label>
                                              </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                          <div class="form-group">
                                              <div class="custom-control custom-switch custom-switch-off-default custom-switch-on-success">
                                                <input checked type="checkbox" class="custom-control-input" id="precioventaespecial2" onchange="verificarChecked(this.id ,this.checked);" value='S'>
                                                <label class="custom-control-label" for="precioventaespecial2">Precio de venta especial 2</label>
                                              </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                          <div class="form-group">
                                              <div class="custom-control custom-switch custom-switch-off-default custom-switch-on-success">
                                                <input checked type="checkbox" class="custom-control-input" id="preciocompra" onchange="verificarChecked(this.id ,this.checked);" value='S'>
                                                <label class="custom-control-label" for="preciocompra">Precio de compra</label>
                                              </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                          <div class="form-group">
                                              <div class="custom-control custom-switch custom-switch-off-default custom-switch-on-success">
                                                <input checked type="checkbox" class="custom-control-input" id="ganancia" onchange="verificarChecked(this.id ,this.checked);" value='S'>
                                                <label class="custom-control-label" for="ganancia">Ganancia</label>
                                              </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                          <div class="form-group">
                                              <div class="custom-control custom-switch custom-switch-off-default custom-switch-on-success">
                                                <input checked type="checkbox" class="custom-control-input" id="afectoigv" onchange="verificarChecked(this.id ,this.checked);" value='S'>
                                                <label class="custom-control-label" for="afectoigv">Afecto IGV</label>
                                              </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                          <div class="form-group">
                                              <div class="custom-control custom-switch custom-switch-off-default custom-switch-on-success">
                                                <input checked type="checkbox" class="custom-control-input" id="stock" onchange="verificarChecked(this.id ,this.checked);" value='S'>
                                                <label class="custom-control-label" for="stock">Stock</label>
                                              </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              </div>
                          </div>
                          
                         
						  <div class="row">
							<div class="col-lg-12 col-md-12  form-group text-right">
								{!! Form::button('<i class="fa fa-file-excel"></i> EXCEL', array('class' => 'btn btn-success btn-sm  ', 'id' => 'btnDetalle', 'onclick' => 'imprimir();' ,'style'=>'width:200px;')) !!}   
							</div>
						  </div>
						{!! Form::close() !!}
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
		init(IDFORMBUSQUEDA+'{{ $entidad }}', 'B', '{{ $entidad }}');
	});

    function imprimir(){
        window.open("catalogoreporte/excelCatalogo?sucursal_id="+$('#sucursal').val()+"&categoria="+$('#categoria').val()+"&subcategoria="+$('#subcategoria').val()+"&marca="+$('#marca').val()+"&unidad="+$('#unidad').val()+"&precioventa="+$('#precioventa').val()+"&preciocompra="+$('#preciocompra').val()+"&stock="+$('#stock').val()+"&codigo="+$('#codigo').val()+"&descripcion="+$('#descripcion').val()+"&abreviatura="+$('#abreviatura').val()+"&precioventaespecial="+$('#precioventaespecial').val()+"&precioventaespecial2="+$('#precioventaespecial2').val()+"&afectoigv="+$('#afectoigv').val()+"&soloconstock="+$('#soloconstock').val()+"&ganancia="+$('#ganancia').val(),"_blank");
    }
/*
    function submitForm22 () {
        var categoria = $('#categoria').val();
        var subcategoria = $('#subcategoria').val();
        var unidad = $('#unidad').val();
        var marca = $('#marca').val();
        var precioventa = $('#precioventa').val();
        var stock = $('#stock').val();
        console.log(categoria);
        console.log(subcategoria);
        console.log(unidad);
        console.log(marca);
        console.log(precioventa);
        console.log(stock);
    }*/
    function verificarChecked(id,val){
        if(val){
            $('#'+id).val('S');
        }else{
            $('#'+id).val('N');
        }
    }

</script>