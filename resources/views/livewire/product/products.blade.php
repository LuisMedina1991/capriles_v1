<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title text-uppercase">
                    <b>{{$pageTitle}} | {{$componentName}}</b>
                </h4>
                <div class="row">
                    @foreach ($products as $product)
                    @foreach ($product->activeValues as $value)
                    @foreach ($value->offices as $office)
                    @php
                    $my_total += $value->cost * $office->pivot->stock;
                    @endphp
                    @endforeach
                    @endforeach
                    @endforeach
                    <div class="col-sm-4">
                        <h5 class="text-uppercase">valor total de inventario:
                            ${{number_format($my_total,2)}}</h5>
                    </div>
                    <div class="col-sm-4">
                        <a href="{{ url('report_stock/pdf' . '/' . $my_total) }}" class="btn btn-dark btn-md"
                            target="_blank" title="Inventario">Generar PDF</a>
                    </div>
                    <div class="col-sm-4">
                        <a href="javascript:void(0)" class="btn btn-dark btn-md" data-toggle="modal"
                            data-target="#theModal" title="Nuevo Producto">Agregar</a>
                    </div>
                </div>
                {{--<div class="container">
                    <div class="row row-cols-3">
                        @foreach ($products as $product)
                        @foreach ($product->activeValues as $value)
                        @foreach ($value->offices as $office)
                        @php
                        $my_total += $value->cost * $office->pivot->stock;
                        @endphp
                        @endforeach
                        @endforeach
                        @endforeach
                        <div class="col">
                            <h5 wire class="text-uppercase">valor total de inventario: ${{number_format($my_total,2)}}
                            </h5>
                        </div>
                        <div class="col">
                            <a href="#" class="btn btn-dark btn-md" target="_blank" title="Inventario">Generar PDF</a>
                        </div>
                        <div class="col">
                            <a href="javascript:void(0)" class="btn btn-dark btn-md" data-toggle="modal"
                                data-target="#theModal" title="Nuevo Producto">Agregar</a>
                        </div>
                    </div>
                </div>--}}
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <div class="input-group mb-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text input-gp">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <input type="text" wire:model="search" placeholder="BUSCAR..." class="form-control">
                    </div>
                </div>
                <div class="col-sm-2">
                    <select id="search_2" wire:model="search_2"
                        class="form-control text-uppercase {{$search_2 == 0 ? 'badge-success' : 'badge-danger'}}">
                        <option value="0">productos activos</option>
                        <option value="1">productos bloqueados</option>
                    </select>
                </div>
            </div>

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                <th class="table-th text-white text-center">codigo de producto</th>
                                <th class="table-th text-white text-center">marca</th>
                                <th class="table-th text-white text-center">contenedor</th>
                                <th class="table-th text-white text-center">comentarios</th>
                                <th class="table-th text-white text-center">costo/precio</th>
                                <th class="table-th text-white text-center">stock por costo</th>
                                <th class="table-th text-white text-center">stock total</th>
                                <th class="table-th text-white text-center">acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td>
                                    <h6 class="text-center text-uppercase"><b>{{$product->code}}</b></h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase"><b>{{$product->brand->name}}</b></h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">
                                        <b>
                                            {{$product->container->subcategory->category->name}}-{{$product->container->subcategory->name}}-{{$product->container->presentation->name}}
                                        </b>
                                    </h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">
                                        <b>
                                            {{$product->container->additional_info}}
                                            <br>
                                            {{$product->comment}}
                                        </b>
                                    </h6>
                                </td>
                                <td>
                                    <select wire:model="value" class="form-control text-uppercase">
                                        <option value="Elegir">Elegir</option>
                                        @foreach($product->activeValues as $value)
                                        <option value="{{$value->id}}">
                                            ${{number_format($value->cost,2)}}/${{number_format($value->price,2)}}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select wire:model="value" class="form-control text-uppercase" disabled>
                                        <option value="Elegir">Elegir</option>
                                        @foreach($product->activeValues as $value)
                                        <option value="{{$value->id}}">{{$value->offices->sum('pivot.stock')}} unidades
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase"><b>{{$product->activeStocks->sum('stock')}}
                                            unidades</b></h6>
                                </td>
                                {{--<td class="text-center">
                                    <span>
                                        <!--desplegar imagenes en el almacenamiento local de nuestro sistema con enlace simbolico-->
                                        <!--se obtiene el valor de la columna a traves del accesor imagen creado en el modelo-->
                                        <img src="{{ asset('storage/products/' . $product->image->url) }}"
                                            alt="imagen de ejemplo" height="70" width="80" class="rounded">
                                    </span>
                                </td>--}}
                                <td class="text-center">
                                    <a href="javascript:void(0)" wire:click="Stock_Detail({{$product->id}})"
                                        class="btn btn-dark mtmobile" title="Detalles del Stock">
                                        <i class="fas fa-list"></i>
                                    </a>
                                    <a href="javascript:void(0)" wire:click="Edit({{$product->id}})"
                                        class="btn btn-dark mtmobile" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0)" onclick="Confirm('{{$product->id}}')"
                                        class="btn btn-dark" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                                {{--<td>
                                    <select wire:model="value" class="form-control" disabled>
                                        <option value="Elegir">Elegir</option>
                                        @foreach($product->stocks as $pivot)
                                        @continue($pivot->office_id == 2)
                                        <option value="{{$pivot->value_id}}">{{$pivot->stock}} unidades</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select wire:model="value" class="form-control" disabled>
                                        <option value="Elegir">Elegir</option>
                                        @foreach($product->stocks as $pivot)
                                        @continue($pivot->office_id == 1)
                                        <option value="{{$pivot->value_id}}">{{$pivot->stock}} unidades</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select wire:model="value" class="form-control" disabled>
                                        <option value="Elegir">Elegir</option>
                                        @foreach($product->values as $value)
                                        <option value="{{$value->id}}">{{$value->offices->sum('pivot.stock')}} unidades
                                        </option>
                                        @endforeach
                                    </select>
                                </td>--}}
                                {{--<td>
                                    @foreach($product->values as $value)
                                    <h6 class="text-center text-uppercase">
                                        ${{number_format($value->cost,2)}}/${{number_format($value->price,2)}}</h6>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($product->stocks as $pivot)
                                    @continue($pivot->office_id == 2)
                                    <h6 class="text-center text-uppercase">{{$pivot->stock}}</h6>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($product->stocks as $pivot)
                                    @continue($pivot->office_id == 1)
                                    <h6 class="text-center text-uppercase">{{$pivot->stock}}</h6>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($product->values as $pivot)
                                    <h6 class="text-center text-uppercase">{{$pivot->offices->sum('pivot.stock')}}
                                        unidades</h6>
                                    @endforeach
                                </td>--}}
                                {{--<td>
                                    @foreach($product->stocks as $pivot)
                                    <select wire:model="value" class="form-control">
                                        <option value="Elegir">Elegir</option>
                                        @foreach($product->stocks as $pivot)
                                        <option value="{{$pivot->value_id}}">{{$pivot->stock}}</option>
                                        <option value="{{$pivot->id}}">
                                            ${{number_format($pivot->value->cost,2)}} -
                                            ${{number_format($pivot->value->price,2)}} - {{$pivot->office->name}} -
                                            {{$pivot->stock}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @endforeach
                                </td>--}}
                            </tr>
                            @endforeach
                            {{--@foreach($relations as $relation)
                            <tr>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$relation->product->code}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$relation->product->brand->name}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">${{number_format($relation->cost,2)}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">${{number_format($relation->price,2)}}</h6>
                                </td>
                                <td>
                                    @foreach($relation->offices as $pivot)
                                    <h6 class="text-center text-uppercase">{{$pivot->name}} = {{$pivot->pivot->stock}}
                                        unidades</h6>
                                    @endforeach
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$relation->offices->sum('pivot.stock')}}
                                        unidades</h6>
                                </td>
                            </tr>
                            @endforeach--}}
                        </tbody>
                    </table>
                    {{$products->links()}}
                </div>
            </div>
        </div>
    </div>
    @include('livewire.product.form')
    @include('livewire.product.brand_form')
    @include('livewire.product.stock_detail')
    @include('livewire.product.income_form')
    @include('livewire.product.transfer_form')
    @include('livewire.product.sale_form')

</div>


<script>
    document.addEventListener('DOMContentLoaded', function(){

        window.livewire.on('show-income-modal', msg=>{
            $('#stock-details').modal('hide')
            $('#income_modal').modal('show')
        });
        window.livewire.on('item-entered', msg=>{
            $('#income_modal').modal('hide')
            noty(msg)
        });
        window.livewire.on('show-transfer-modal', msg=>{
            $('#stock-details').modal('hide')
            $('#transfer_modal').modal('show')
        });
        window.livewire.on('item-transfered', msg=>{
            $('#transfer_modal').modal('hide')
            noty(msg)
        });
        window.livewire.on('show-sale-modal', msg=>{
            $('#stock-details').modal('hide')
            $('#sale_modal').modal('show')
        });
        window.livewire.on('item-saled', msg=>{
            $('#sale_modal').modal('hide')
            noty(msg)
        });
        window.livewire.on('show-modal', msg=>{
            $('#theModal').modal('show')
        });
        window.livewire.on('item-added', msg=>{
            $('#theModal').modal('hide')
            noty(msg)
        });
        window.livewire.on('show-stock-detail', msg=>{
            $('#stock-details').modal('show')
        });
        window.livewire.on('show-modal-2', msg=>{
            $('#theModal').modal('hide')
            $('#brand_modal').modal('show')
        });
        $('#brand_modal').on('shown.bs.modal', function(e){
            $('.component-name').focus()
        });
        window.livewire.on('item-added-2', msg=>{
            $('#brand_modal').modal('hide')
            $('#theModal').modal('show')
            noty(msg)
        });
        window.livewire.on('item-updated', msg=>{
            $('#theModal').modal('hide')
            noty(msg)
        });
        window.livewire.on('item-deleted', msg=>{
            noty(msg)
        });
        window.livewire.on('record-error', msg=>{
            noty(msg,2)
        });
    });

    function Confirm(id){

        swal({

            title: 'CONFIRMAR',
            text: '¿CONFIRMA ELIMINAR EL REGISTRO?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'CERRAR',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'ACEPTAR'

        }).then(function(result){

            if(result.value){

                window.livewire.emit('destroy', id)
                swal.close()
            }
        })
    }

</script>