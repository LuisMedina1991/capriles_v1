<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title text-uppercase">
                    <b>{{$pageTitle}} | {{$componentName}}</b>
                </h4>
                <ul class="tabs tab-pills">
                    <li>
                        <a href="javascript:void(0)" class="btn bg-dark" data-toggle="modal"
                            data-target="#theModal">Agregar</a>
                    </li>
                </ul>
            </div>

            @include('common.searchbox')
            {{--<div class="row">
                <div class="col-sm-3">
                    <h6><b>Filtro de busqueda</b></h6>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text input-gp">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                            <input type="text" wire:model="search" placeholder="BUSCAR..." class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <h6><b>Estado del registro</b></h6>
                    <div class="form-group">
                        <select wire:model="search_2" class="form-control">
                            <option value="0">Activo</option>
                            <option value="1">Bloqueado</option>
                        </select>
                    </div>
                </div>
            </div>--}}

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                <th class="table-th text-white text-center">categoria</th>
                                <th class="table-th text-white text-center">subcategoria</th>
                                {{--<th class="table-th text-white text-center">contenedores relacionados</th>--}}
                                <th class="table-th text-white text-center">opciones</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($subcategories as $subcategory)

                            <tr>
                                <td>
                                    <h6 class="text-center text-uppercase">{{ $subcategory->category->name }}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{ $subcategory->name }}</h6>
                                </td>
                                {{--<td>
                                    <h6 class="text-center text-uppercase">{{ $subcategory->presentations_count }}</h6>
                                </td>--}}
                                <td class="text-center">
                                    @if($search_2 == 0)
                                        <a href="javascript:void(0)" wire:click="Edit({{$subcategory->id}})"
                                            class="btn btn-dark" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0)"
                                            onclick="Confirm_1('{{$subcategory->id}}')"
                                            class="btn btn-dark" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    @else
                                        <a href="javascript:void(0)"
                                            onclick="Confirm_2('{{$subcategory->id}}','{{$subcategory->category_id}}')"
                                            class="btn btn-dark" title="Activar">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>

                            @endforeach

                        </tbody>
                    </table>

                    {{$subcategories->links()}}

                </div>
            </div>
        </div>
    </div>

    @include('livewire.subcategory.form')
    {{--@include('livewire.category.form')--}}
    @include('livewire.subcategory.category_form')

</div>


<script>
    document.addEventListener('DOMContentLoaded', function(){

        window.livewire.on('show-modal', msg=>{
            $('#theModal').modal('show')
        });
        window.livewire.on('show-modal-2', msg=>{
            $('#theModal').modal('hide')
            $('#category_modal').modal('show')
        });
        window.livewire.on('record-added', msg=>{
            $('#theModal').modal('hide')
            noty(msg)
        });
        window.livewire.on('record-added-2', msg=>{
            $('#category_modal').modal('hide')
            noty(msg)
        });
        window.livewire.on('record-updated', msg=>{
            $('#theModal').modal('hide')
            noty(msg)
        });
        window.livewire.on('record-activated', msg=>{
            noty(msg)
        });
        window.livewire.on('record-deleted', msg=>{
            noty(msg)
        });
        $('#theModal').on('shown.bs.modal', function(e){
            $('.component-name').focus()
        });
        $('#category_modal').on('shown.bs.modal', function(e){
            $('.component-name').focus()
        });
        window.livewire.on('record-error', msg=>{
            noty(msg,2)
        });
    });

    function Confirm_1(id,presentations_count){

        if(presentations_count > 0){

            swal('NO SE PUEDE ELIMINAR DEBIDO A RELACION')
            return;
        }

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

                window.livewire.emit('destroy',id,presentations_count)
                swal.close()
            }
        })
    }

    function Confirm_2(id,category_id){

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
                
                window.livewire.emit('activate',id,category_id)
                swal.close()
            }
        })
    }

</script>