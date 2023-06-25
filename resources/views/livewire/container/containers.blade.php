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

            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-3">
                    <h6><b>Filtro por categoria</b></h6>
                    <div class="form-group">
                        <select id="search" wire:model="search" class="form-control text-uppercase">
                            <option value="elegir">elegir</option>
                            @foreach ($categories as $categorie)
                            <option value="{{$categorie->id}}">{{$categorie->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-3">
                    <h6><b>Estado del registro</b></h6>
                    <div class="form-group">
                        <select id="search_2" wire:model="search_2" class="form-control">
                            <option value="0">Activo</option>
                            <option value="1">Bloqueado</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                <th class="table-th text-white text-center">categoria</th>
                                <th class="table-th text-white text-center">subcategoria</th>
                                <th class="table-th text-white text-center">presentacion</th>
                                <th class="table-th text-white text-center">productos relacionados</th>
                                <th class="table-th text-white text-center">opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($containers as $container)
                            <tr>
                                <td class="text-center text-uppercase">
                                    <h6 class="text-center text-uppercase">{{ $container->category_name }}</h6>
                                </td>
                                <td class="text-center text-uppercase">
                                    <h6 class="text-center text-uppercase">{{ $container->subcategory_name }}</h6>
                                </td>
                                <td class="text-center text-uppercase">
                                    <h6 class="text-center text-uppercase">{{ $container->presentation_name }}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{ $container->products_count }}</h6>
                                </td>
                                <td class="text-center">
                                    @if($search_2 == 0)
                                        <a href="javascript:void(0)" wire:click="Edit({{$container->id}})"
                                            class="btn btn-dark" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0)"
                                            onclick="Confirm_1('{{$container->id}}','{{$container->products_count}}')"
                                            class="btn btn-dark" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    @else
                                        <a href="javascript:void(0)"
                                            onclick="Confirm_2('{{$container->id}}','{{$container->subcategory_status}}','{{$container->presentation_status}}')"
                                            class="btn btn-dark" title="Activar">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $containers->links() }}

                </div>
            </div>
        </div>
    </div>

    @include('livewire.container.form')
    @include('livewire.container.category_form')
    @include('livewire.container.presentation_form')
    @include('livewire.container.subcategory_form')

</div>


<script>
    document.addEventListener('DOMContentLoaded', function(){

        window.livewire.on('show-modal', msg=>{
            $('#theModal').modal('show')
        });
        window.livewire.on('record-added', msg=>{
            $('#theModal').modal('hide')
            noty(msg)
        });
        window.livewire.on('record-updated', msg=>{
            $('#theModal').modal('hide')
            noty(msg)
        });
        window.livewire.on('record-activated', msg=>{
            noty(msg)
        });
        window.livewire.on('show-modal-2', msg=>{
            $('#theModal').modal('hide')
            $('#category_modal').modal('show')
        });
        $('#category_modal').on('shown.bs.modal', function(e){
            $('.component-name').focus()
        });
        window.livewire.on('record-added-2', msg=>{
            $('#category_modal').modal('hide')
            noty(msg)
        });
        window.livewire.on('show-modal-3', msg=>{
            $('#theModal').modal('hide')
            $('#subcategory_modal').modal('show')
        });
        $('#subcategory_modal').on('shown.bs.modal', function(e){
            $('.component-name').focus()
        });
        window.livewire.on('record-added-3', msg=>{
            $('#subcategory_modal').modal('hide')
            $('#theModal').modal('show')
            noty(msg)
        });
        window.livewire.on('show-modal-4', msg=>{
            $('#theModal').modal('hide')
            $('#presentation_modal').modal('show')
        });
        $('#presentation_modal').on('shown.bs.modal', function(e){
            $('.component-name').focus()
        });
        window.livewire.on('record-added-4', msg=>{
            $('#presentation_modal').modal('hide')
            $('#theModal').modal('show')
            noty(msg)
        });
        window.livewire.on('show-modal-5', msg=>{
            $('#subcategory_modal').modal('hide')
            $('#category_modal').modal('show')
        });
        window.livewire.on('record-deleted', msg=>{
            noty(msg)
        });
        window.livewire.on('record-error', msg=>{
            noty(msg,2)
        });        
    });

    function Confirm_1(id,products_count){

        if(products_count > 0){

            swal({
                title: 'AVISO',
                text: 'NO SE PUEDE ELIMINAR DEBIDO A RELACION',
                type: 'error',
                confirmButtonColor: '#3B3F5C',
                confirmButtonText: 'ACEPTAR'
            })
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

                window.livewire.emit('destroy',id,products_count)
                swal.close()
            }
        })
    }

    function Confirm_2(id,subcategory_status,presentation_status){

        if(subcategory_status != 1){

            swal({
                title: 'AVISO',
                text: 'LA SUBCATEGORIA DE ESTE CONTENEDOR SE ENCUENTRA BLOQUEADA. DEBE ACTIVARLA PARA CONTINUAR.',
                type: 'error',
                confirmButtonColor: '#3B3F5C',
                confirmButtonText: 'ACEPTAR'
            })
            return;

        }

        if(presentation_status != 1){

            swal({
                title: 'AVISO',
                text: 'LA PRESENTACION DE ESTE CONTENEDOR SE ENCUENTRA BLOQUEADA. DEBE ACTIVARLA PARA CONTINUAR.',
                type: 'error',
                confirmButtonColor: '#3B3F5C',
                confirmButtonText: 'ACEPTAR'
            })
            return;

        }

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
                
                window.livewire.emit('activate',id,subcategory_status,presentation_status)
                swal.close()
            }
        })
    }

</script>