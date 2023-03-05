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
                            data-target="#theModal" title="Nuevo Contenedor">Agregar</a>
                    </li>
                </ul>
            </div>

            <div class="row mb-4">
                <div class="col-sm-3">
                    <select id="search" wire:model="search" class="form-control text-uppercase">
                        <option value="Elegir">filtro por categoria</option>
                        @foreach ($categories as $categorie)
                        <option value="{{$categorie->id}}">{{$categorie->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3">
                    <select id="search_2" wire:model="search_2" class="form-control text-uppercase {{$search_2 == 0 ? 'badge-success' : 'badge-danger'}}">
                        <option value="0">contenedores activos</option>
                        <option value="1">contenedores bloqueados</option>
                    </select>
                </div>
            </div>

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                <th class="table-th text-white text-center">estado</th>
                                <th class="table-th text-white text-center">prefijo</th>
                                <th class="table-th text-white text-center">productos relacionados</th>
                                <th class="table-th text-white text-center">categoria</th>
                                <th class="table-th text-white text-center">subcategoria</th>
                                <th class="table-th text-white text-center">presentacion</th>
                                <th class="table-th text-white text-center">comentarios</th>
                                <th class="table-th text-white text-center">opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($containers as $container)
                            <tr>
                                <td>
                                    <h6 class="text-center text-uppercase">
                                        <span
                                            class="badge text-uppercase {{$container->status->name == 'activo' ? 'badge-success' : 'badge-danger'}}">
                                            {{$container->status->name}}
                                        </span>
                                    </h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{ $container->prefix }}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{ $container->active_products_count }}</h6>
                                </td>
                                <td class="text-center text-uppercase">
                                    <h6 class="text-center text-uppercase">{{ $container->subcategory->category->name }}</h6>
                                </td>
                                <td class="text-center text-uppercase">
                                    <h6 class="text-center text-uppercase">{{ $container->subcategory->name }}</h6>
                                </td>
                                <td class="text-center text-uppercase">
                                    <h6 class="text-center text-uppercase">{{ $container->presentation->name }}</h6>
                                </td>
                                <td class="text-center text-uppercase">
                                    <h6 class="text-center text-uppercase">{{ $container->additional_info }}</h6>
                                </td>
                                <td class="text-center">
                                    <a href="javascript:void(0)"
                                        wire:click="Edit({{$container->id}})"
                                        class="btn btn-dark mtmobile" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0)"
                                        onclick="Confirm('{{$container->id}}','{{$container->active_products_count}}')"
                                        class="btn btn-dark" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{$containers->links()}}

                </div>
            </div>
        </div>
    </div>

    @include('livewire.container.form')
    @include('livewire.container.form_2')
    @include('livewire.container.category_form')
    @include('livewire.container.presentation_form')
    @include('livewire.container.subcategory_form')

</div>


<script>
    document.addEventListener('DOMContentLoaded', function(){

        window.livewire.on('show-modal', msg=>{
            $('#theModal').modal('show')
        });
        window.livewire.on('item-added', msg=>{
            $('#theModal').modal('hide')
            noty(msg)
        });
        window.livewire.on('show-edit-modal', msg=>{
            $('#edit_modal').modal('show')
        });
        window.livewire.on('item-updated', msg=>{
            $('#edit_modal').modal('hide')
            noty(msg)
        });
        window.livewire.on('show-modal-2', msg=>{
            $('#theModal').modal('hide')
            $('#category_modal').modal('show')
        });
        $('#category_modal').on('shown.bs.modal', function(e){
            $('.component-name').focus()
        });
        window.livewire.on('item-added-2', msg=>{
            $('#category_modal').modal('hide')
            noty(msg)
        });
        window.livewire.on('show-modal-3', msg=>{
            $('#theModal').modal('hide')
            $('#subcategory_modal').modal('show')
        });
        $('#subcategory_form').on('shown.bs.modal', function(e){
            $('.component-name').focus()
        });
        window.livewire.on('item-added-3', msg=>{
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
        window.livewire.on('item-added-4', msg=>{
            $('#presentation_modal').modal('hide')
            $('#theModal').modal('show')
            noty(msg)
        });
        window.livewire.on('show-modal-5', msg=>{
            $('#subcategory_modal').modal('hide')
            $('#category_modal').modal('show')
        });
        window.livewire.on('item-deleted', msg=>{
            noty(msg)
        });
        window.livewire.on('item-error', msg=>{
            noty(msg,2)
        });        
    });

    function Confirm(container_id,active_products_count){

        if(active_products_count > 0){

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

                window.livewire.emit('destroy', container_id,active_products_count)
                swal.close()
            }
        })
    }

</script>