<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title text-uppercase">
                    <b>{{$pageTitle}} | {{$componentName}}</b>
                </h4>
                <ul class="tabs tab-pills">
                    <li>
                        <a href="javascript:void(0)" class="btn bg-dark" data-toggle="modal" data-target="#theModal">Agregar</a>
                    </li>
                </ul>
            </div>

            @include('common.searchbox')

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                <th class="table-th text-center text-white">nombre</th>
                                <th class="table-th text-center text-white">alias</th>
                                {{--<th class="table-th text-white text-center">cuentas asociadas</th>--}}
                                <th class="table-th text-white text-center">telefono</th>
                                <th class="table-th text-white text-center">email</th>
                                <th class="table-th text-white text-center">nit</th>
                                <th class="table-th text-center text-white text-center">opciones</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($companies as $company)

                            <tr>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$company->name}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$company->alias}}</h6>
                                </td>
                                {{--<td>
                                    <h6 class="text-center text-uppercase">{{$company->accounts_count}}</h6>
                                </td>--}}
                                <td>
                                    <h6 class="text-center text-uppercase">{{$company->phone}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center">{{$company->email}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$company->nit}}</h6>
                                </td>
                                <td class="text-center">
                                    @if($search_2 == 0)
                                        <a href="javascript:void(0)" wire:click="Edit({{$company->id}})"
                                            class="btn btn-dark" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0)"
                                            onclick="Confirm_1('{{$company->id}}')"
                                            class="btn btn-dark" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    @else
                                        <a href="javascript:void(0)"
                                            onclick="Confirm_2('{{$company->id}}')"
                                            class="btn btn-dark" title="Activar">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>

                            @endforeach

                        </tbody>
                    </table>
                    
                    {{$companies->links()}}

                </div>
            </div>
        </div>
    </div>

    @include('livewire.company.form')

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
        window.livewire.on('record-deleted', msg=>{
            noty(msg)
        });
        window.livewire.on('record-error', msg=>{
            noty(msg,2)
        });
        $('#theModal').on('shown.bs.modal', function(e){
            $('.component-name').focus()
        });
    });

    function Confirm(id,accounts_count){

        if(accounts_count > 0){

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

                window.livewire.emit('destroy',id,accounts_count)
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