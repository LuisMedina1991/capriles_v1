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
            </div>

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                <th class="table-th text-white text-center">tipo de denominacion</th>
                                <th class="table-th text-white text-center">valor</th>
                                <th class="table-th text-white text-center">imagen</th>
                                <th class="table-th text-white text-center">opciones</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($denominations as $denomination)

                            <tr>
                                <td>
                                    <h6 class="text-uppercase text-center">{{ $denomination->type }}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center">${{ number_format($denomination->value,2) }}</h6>
                                </td>
                                <td class="text-center">
                                    <span>
                                        @if($denomination->image != null)
                                        <img src="{{ asset('storage/denominations/' . $denomination->image->url) }}"
                                            alt="imagen de ejemplo" height="70" width="80" class="rounded">
                                        @else
                                        <img src="{{ asset('storage/noimg.jpg') }}" alt="imagen de ejemplo" height="70"
                                            width="80" class="rounded">
                                        @endif
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" wire:click="Edit({{$denomination->id}})"
                                        class="btn btn-dark" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0)" onclick="Confirm('{{$denomination->id}}')" class="btn btn-dark"
                                        title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            @endforeach

                        </tbody>
                    </table>

                    {{$denominations->links()}}

                </div>
            </div>
        </div>
    </div>

    @include('livewire.denomination.form')

</div>


<script>
    document.addEventListener('DOMContentLoaded', function(){
        
        window.livewire.on('show-modal', msg=>{
            $('#theModal').modal('show')
        });
        window.livewire.on('modal-hide', msg=>{
            $('#theModal').modal('hide')
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