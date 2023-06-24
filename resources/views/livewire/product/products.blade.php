<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title text-uppercase">
                    <b>{{$pageTitle}} | {{$componentName}}</b>
                </h4>
                <div class="row">
                    {{--@foreach ($products as $product)
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
                        <a href="{{ url('stock_report/pdf' . '/' . $my_total . '/' . $search_2 . '/' . $search) }}" 
                        class="btn btn-dark btn-md {{count($products) < 1 ? 'disabled' : ''}}"
                            target="_blank" title="Inventario">Generar PDF</a>
                    </div>--}}
                    <div class="col-sm-4">
                        <h5 class="text-uppercase">valor total de inventario: </h5>
                    </div>
                    <div class="col-sm-4">
                        <a href="#" 
                        class="btn btn-dark btn-md {{count($products) < 1 ? 'disabled' : ''}}"
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

            @include('common.searchbox')

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                <th class="table-th text-white text-center">imagen</th>
                                <th class="table-th text-white text-center">codigo de producto</th>
                                <th class="table-th text-white text-center">marca</th>
                                <th class="table-th text-white text-center">contenedor</th>
                                <th class="table-th text-white text-center">informacion adicional</th>
                                {{--<th class="table-th text-white text-center">costo/precio</th>
                                <th class="table-th text-white text-center">stock por costo</th>
                                <th class="table-th text-white text-center">stock total</th>--}}
                                <th class="table-th text-white text-center">acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td class="text-center">
                                    {{--<span>
                                        <!--desplegar imagenes en el almacenamiento local de nuestro sistema con enlace simbolico-->
                                        <!--se obtiene el valor de la columna a traves del accesor imagen creado en el modelo-->
                                        <img src="{{ asset('storage/products/' . $product->image->url) }}"
                                            alt="imagen de ejemplo" height="70" width="80" class="rounded">
                                    </span>--}}
                                    @if($product->image != null)
                                        <img src="{{ asset('storage/products/' . $product->image->url) }}" alt="imagen de ejemplo" height="70" width="80" class="rounded">
                                    @else
                                        <img src="{{ asset('storage/noimg.jpg') }}" alt="imagen" height="70" width="80" class="rounded">
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($product->barcode_image != null)
                                        <img src="{{ asset($product->barcode_image) }}" height="50" width="200">
                                    @endif
                                    <h6 class="text-uppercase"><b>{{$product->code}}</b></h6>
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
                                            {{$product->comment}}
                                        </b>
                                    </h6>
                                </td>
                                {{--<td>
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
                                </td>--}}
                                <td class="text-center">
                                    @if($search_2 == 0)
                                        <a href="javascript:void(0)" wire:click="Stock_Detail({{$product->id}})"
                                            class="btn btn-dark mtmobile" title="Detalles del Stock">
                                            <i class="fas fa-list"></i>
                                        </a>
                                        <a href="javascript:void(0)" wire:click="Edit({{$product->id}})"
                                            class="btn btn-dark" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0)"
                                            onclick="Confirm_1('{{$product->id}}')"
                                            class="btn btn-dark" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    @else
                                        <a href="javascript:void(0)"
                                            onclick="Confirm_2('{{$product->id}}')"
                                            class="btn btn-dark" title="Activar">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    @endif
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
    {{--@include('livewire.product.stock_detail')
    @include('livewire.product.income_form')
    @include('livewire.product.transfer_form')
    @include('livewire.product.sale_form')
    @include('livewire.product.supplier_form')
    @include('livewire.product.customer_form')
    @include('livewire.product.account_form')
    @include('livewire.product.bank_form')
    @include('livewire.product.company_form')--}}

</div>


<script>
    document.addEventListener('DOMContentLoaded', function(){

        window.livewire.on('show-modal', msg=>{
            $('#theModal').modal('show')
        });
        window.livewire.on('code-focus', msg=>{
            $('.component-name').focus()
        });
        window.livewire.on('record-added', msg=>{
            $('#theModal').modal('hide')
            noty(msg)
        });
        window.livewire.on('record-updated', msg=>{
            $('#theModal').modal('hide')
            noty(msg)
        });
        window.livewire.on('record-deleted', msg=>{
            noty(msg)
        });
        window.livewire.on('record-error', msg=>{
            noty(msg,2)
        });
        window.livewire.on('show-brand-modal', msg=>{
            $('#theModal').modal('hide')
            $('#brand_modal').modal('show')
        });
        $('#brand_modal').on('shown.bs.modal', function(e){
            $('.component-name').focus()
        });
        window.livewire.on('brand-added', msg=>{
            $('#brand_modal').modal('hide')
            $('#theModal').modal('show')
            noty(msg)
        });
        window.livewire.on('show-account-modal-1', msg=>{
            $('#income_modal').modal('hide')
            $('#account_modal').modal('show')
        });
        window.livewire.on('show-account-modal-2', msg=>{
            $('#sale_modal').modal('hide')
            $('#account_modal').modal('show')
        });
        window.livewire.on('account-added', msg=>{
            $('#account_modal').modal('hide')
            noty(msg)
        });
        window.livewire.on('show-bank-modal-1', msg=>{
            $('#sale_modal').modal('hide')
            $('#bank_modal').modal('show')
        });
        window.livewire.on('show-bank-modal-2', msg=>{
            $('#account_modal').modal('hide')
            $('#bank_modal').modal('show')
        });
        $('#bank_modal').on('shown.bs.modal', function(e){
            $('.component-name').focus()
        });
        window.livewire.on('bank-added', msg=>{
            $('#bank_modal').modal('hide')
            noty(msg)
        });
        window.livewire.on('show-company-modal', msg=>{
            $('#account_modal').modal('hide')
            $('#company_modal').modal('show')
        });
        $('#company_modal').on('shown.bs.modal', function(e){
            $('.component-name').focus()
        });
        window.livewire.on('company-added', msg=>{
            $('#company_modal').modal('hide')
            $('#account_modal').modal('show')
            noty(msg)
        });
        window.livewire.on('show-supplier-modal', msg=>{
            $('#income_modal').modal('hide')
            $('#supplier_modal').modal('show')
        });
        $('#supplier_modal').on('shown.bs.modal', function(e){
            $('.component-name').focus()
        });
        window.livewire.on('supplier-added', msg=>{
            $('#supplier_modal').modal('hide')
            $('#income_modal').modal('show')
            noty(msg)
        });
        window.livewire.on('show-customer-modal', msg=>{
            $('#sale_modal').modal('hide')
            $('#customer_modal').modal('show')
        });
        $('#customer_modal').on('shown.bs.modal', function(e){
            $('.component-name').focus()
        });
        window.livewire.on('customer-added', msg=>{
            $('#customer_modal').modal('hide')
            $('#sale_modal').modal('show')
            noty(msg)
        });
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
        window.livewire.on('show-stock-detail', msg=>{
            $('#stock-details').modal('show')
        });
    });

    function Confirm_1(id){

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

    function Confirm_2(id){

        swal({

            title: 'CONFIRMAR',
            text: '¿CONFIRMA ACTIVAR EL REGISTRO?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'CERRAR',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'ACEPTAR'

        }).then(function(result){

            if(result.value){
                
                window.livewire.emit('activate',id)
                swal.close()
            }
        })

    }

</script>