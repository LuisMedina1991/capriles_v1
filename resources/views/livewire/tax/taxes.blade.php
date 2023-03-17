<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title text-uppercase">
                    <b>{{$pageTitle}} | {{$componentName}}</b>
                </h4>
                <div class="row mr-5">
                    <div class="col-sm-6">
                        <h5 class="text-uppercase">total de impuestos:
                            ${{number_format($taxes->sum('amount'),2)}}</h5>
                    </div>
                    <div class="col-sm-6">
                        <a href="{{ url('taxes_report/pdf' . '/' . $taxes->sum('amount') . '/' . $search_2 . '/' . $search) }}" 
                        class="btn btn-dark btn-block {{count($taxes) < 1 ? 'disabled' : ''}}"
                            target="_blank" title="Reporte">Generar PDF</a>
                    </div>
                </div>
            </div>

            {{--@include('common.searchbox')--}}

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
                <div class="col-sm-3">
                    <select id="search_2" wire:model="search_2"
                        class="form-control text-uppercase">
                        <option value="0">registros activos</option>
                        <option value="1">registros bloqueados</option>
                    </select>
                </div>
            </div>

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                {{--<th class="table-th text-center text-white">relacion</th>--}}
                                <th class="table-th text-white text-center">fecha</th>
                                <th class="table-th text-white text-center">n° de recibo</th>
                                <th class="table-th text-white text-center">detalle</th>
                                <th class="table-th text-white text-center">saldo</th>
                                <th class="table-th text-center text-white">opciones</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($taxes as $tax)

                            <tr>
                                {{--<td>
                                    <h6 class="text-center text-uppercase">{{$tax->taxable->name}}</h6>
                                </td>--}}
                                <td class="text-center">
                                    <h6>{{Carbon\Carbon::parse($tax->created_at)->format('d-m-Y')}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$tax->taxable->file_number}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$tax->description}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center">{{number_format($tax->amount,2)}}</h6>
                                </td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" wire:click="Edit({{$tax->id}})" class="btn btn-dark mtmobile" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0)" onclick="Confirm('{{$tax->id}}')" class="btn btn-dark" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            @endforeach

                        </tbody>
                    </table>
                    
                    {{$taxes->links()}}

                </div>
            </div>
        </div>
    </div>

    {{--@include('livewire.supplier.form')--}}

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
        window.livewire.on('item-deleted', msg=>{
            noty(msg)
        });
        window.livewire.on('item-updated', msg=>{
            $('#theModal').modal('hide')
            noty(msg)
        });
        window.livewire.on('item-error', msg=>{
            noty(msg,2)
        });
        $('#theModal').on('shown.bs.modal', function(e){
            $('.component-name').focus()
        });
    });

    function Confirm(id,incomes_count,debts_count){

        if(incomes_count > 0 || debts_count > 0){

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

                window.livewire.emit('destroy',id,incomes_count,debts_count)
                swal.close()
            }
        })
    }

</script>