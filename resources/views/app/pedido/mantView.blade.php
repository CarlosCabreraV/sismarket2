<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($pedido, $formData) !!}	
    {!! Form::hidden('listar', $listar, array('id' => 'listar' , 'class'=>'form-horizontal')) !!}
    <div class="row">
        <div class="col-md-5 ">
        <!--DATOS PEDIDO -->
            <div class="card bg-light">
                <div class="card-body">
                    <div class="card-text">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row mr-2">
                                    <label class="col-md-4 col-form-label">Fecha:</label>
                                    <div class="col-md-8 ">
                                        {!! Form::date('fecha', date('Y-m-d'), array('class' => 'form-control form-control-sm pr-0', 'id' => 'fecha', 'readonly' => 'true')) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label ">Doc :</label>
                                    <div class="col-md-9">
                                        {!! Form::select('tipodocumento',$cboTipoDocumento, $pedido->tipodocumento_id, array('class' => 'form-control form-control-sm', 'id' => 'tipodocumento' , 'readonly'=>'true', 'disabled'=>'true')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                   
                                <div class="form-group row ">
                                        <label class="col-md-3 col-form-label">Sucursal :</label>
                                        <div class="col-md-9">
                                                {!! Form::select('sucursal_id', $cboSucursal, $pedido->sucursal_id, array('class' => 'form-control form-control-sm', 'id' => 'sucursal_id', 'readonly'=>'true', 'disabled'=>'true')) !!}
                                        </div>
                                </div>
                        
                        <div class="form-group row">
                                <label class="col-md-3 col-form-label">Cliente:</label>
                                <div class="col-md-9 ">
                                    
                                    {!! Form::text('persona', $cliente, array('class' => 'form-control form-control-sm ', 'id' => 'persona', 'placeholder' => ' Busca un cliente...', 'readonly'=>'true', 'disabled'=>'true')) !!}
                                </div>
                        </div>
                        <div class="form-group row">
                                <label class="col-md-3 col-form-label">Telefono :</label>
                                <div class="col-md-9">
                                        {!! Form::text('telefono', $pedido->telefono, array('class' => 'form-control form-control-sm', 'id' => 'telefono', 'readonly'=>'true', 'disabled'=>'true')) !!}
                                </div>
                        </div>
                        <div class="form-group row">
                                <label class="col-md-3 col-form-label">Direccion :</label>
                                <div class="col-md-9">
                                        {!! Form::text('direccion',$pedido->direccion, array('class' => 'form-control form-control-sm', 'id' => 'direccion', 'readonly'=>'true', 'disabled'=>'true')) !!}
                                </div>
                        </div>
                        <div class="form-group row">
                                <label class="col-md-3 col-form-label">Ref. :</label>
                                <div class="col-md-9">
                                        {!! Form::text('referencia', $pedido->referencia, array('class' => 'form-control form-control-sm', 'id' => 'referencia', 'readonly'=>'true', 'disabled'=>'true')) !!}
                                </div>
                        </div>
                        <div class="form-group row">
                                <label class="col-md-3 col-form-label">Detalle :</label>
                                <div class="col-md-9">
                                        {!! Form::textarea('detalle', $pedido->detalle, array('class' => 'form-control form-control-sm', 'id' => 'detalle' , 'rows'=>2 , 'style'=>'resize:none;', 'readonly'=>'true', 'disabled'=>'true')) !!}
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        <!--FIN DATOS PEDIDO -->

        <!--TOTAL -->
            <div class="row">
                
                <div class="col-md-6 ">
                    <div class="card bg-light">
                        <div class="card-header bg-success">
                            <h6 class="card-title">Servicio delivery</h6>
                        </div>
                        <div class="card-body justify-content-center align-items-center d-flex pb-0">
                            <div class="form-group row">
                                <div class="custom-control custom-switch custom-switch-off-default custom-switch-on-success">
                                    <input type="checkbox" disabled class="custom-control-input" id="delivery" style="height: 100px;" value="S" {{($pedido->delivery=='S')?'checked':''}} >
                                    <label class="custom-control-label" for="delivery">Delivery</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body ">
                                <div class="row form-group">
                                            <label class="col-md-5 col-form-label" style="font-size:20px; font-weight:bold;">TOTAL :</label>
                                        <div class="col-md-7">
                                                {!! Form::text('total', $pedido->total, array('class' => 'form-control form-control-lg bg-dark', 'id' => 'total' , 'readonly'=>true , 'style'=>'font-size:23px; font-weight:bold; ')) !!}
                                        </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            
       <!-- FIN TOTAL -->
        </div>
        <!--DATOS AGREGAR PRODUCTO -->
        <div class="col-md-7 ">
            <div class="row ">
                <div class="col-md-12">
                    <div class="card bg-light">
                        <div class="card-body">
                            <div class="row mb-2">
                                    <!-- /.Fecha nuevo -->
                                    <div class="col-md-4 ">
                                      <div class="color-palette-set">
                                        <div class="bg-gray color-palette text-center"><span>Nuevo</span></div>
                                      <div class="bg-white disabled color-palette text-center"><span>{{$pedido->created_at?$pedido->created_at:'-'}}</span></div>
                                      </div>
                                    </div>
                                    <!-- /.Fecha aceptado-->
                                    <div class="col-md-4 ">
                                      <div class="color-palette-set">
                                        <div class="bg-primary color-palette text-center"><span>Aceptado</span></div>
                                        <div class="bg-white disabled color-palette text-center"><span>{{$pedido->fechaaceptado?$pedido->fechaaceptado:'-'}}</span></div>
                                      </div>
                                    </div>
                                    <!-- /.Fecha enviado -->
                                    <div class="col-md-4 ">
                                      <div class="color-palette-set">
                                        <div class="bg-olive color-palette text-center"><span>Enviado</span></div>
                                        <div class="bg-white disabled color-palette text-center"><span>{{$pedido->fechaenviado?$pedido->fechaenviado:'-'}}</span></div>
                                      </div>
                                    </div>
                            </div>
                            <div class="row">
                                    <!-- /.Fecha finalizado -->
                                    <div class="col-md-4 ">
                                      <div class="color-palette-set">
                                        <div class="bg-success color-palette text-center"><span>Finalizado</span></div>
                                        <div class="bg-white disabled color-palette text-center"><span>{{$pedido->fechafinalizado?$pedido->fechafinalizado:'-'}}</span></div>
                                      </div>
                                    </div>
                                    <!-- /.Fecha rechazado -->
                                    <div class="col-md-4">
                                      <div class="color-palette-set">
                                        <div class="bg-danger color-palette text-center"><span>Rechazado</span></div>
                                        <div class="bg-white disabled color-palette text-center"><span>{{$pedido->fecharechazado?$pedido->fecharechazado:'-'}}</span></div>
                                      </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card bg-light ">
                        <div class="card-header bg-gray">
                            <h5 class="card-title ">Detalles</h5>
                        </div>
                        <div class="card-body">
                            <div class="card-text">
                                <!--FIN RESULTADOS BUSQUEDA -->
                                <!-- DETALLES PEDIDO-->
                                <div class="row mt-3 " id ='divDetalles'>
                                    <div class="table-responsive">
                                        <table class=" table table-sm table-striped table-hover table-borderless " width='100%' >
                                            <thead class="thead-dark">
                                                    <th class="text-center">Cant.</th>
                                                    <th class="text-center">Producto</th>
                                                    <th class="text-center">Precio</th>
                                                    <th class="text-center">Subtotal</th>
                                            </thead>
                                            <tbody id="tbDetalle">
                                                @foreach ($detalles as $detalle )
                                                    <tr>
                                                    <td class="text-center">{{$detalle->cantidad}}</td>
                                                        <td class="text-center">{{($detalle->producto_id)?$detalle->producto->nombre: $detalle->promocion->nombre}}</td>
                                                        <td class="text-center">{{$detalle->precioventa}}</td>
                                                        <td class="text-center">{{number_format($detalle->precioventa*$detalle->cantidad,2)}}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr></tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--PAGO -->
                
            <!-- FIN PAGO -->
            <div class="form-group">
                <div class="col-lg-12 col-md-12 col-sm-12 text-right">
                    {!! Form::button('<i class="fa fa-undo fa-lg"></i> Cancelar', array('class' => 'btn btn-default btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
                </div>
            </div>
        </div>
        <!--FIN DATOS AGREGAR PRODUCTO -->

    </div>
    

{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('1300');
    
}); 
</script>