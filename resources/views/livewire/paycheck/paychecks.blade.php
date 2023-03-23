<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title text-uppercase">
                    <b>{{$pageTitle}} | {{$componentName}}</b>
                </h4>
                <div class="row mr-5">
                    <div class="col-sm-6">
                        <h5 class="text-uppercase">total cheques por cobrar:
                            ${{number_format($paychecks->sum('amount'),2)}}</h5>
                    </div>
                    <div class="col-sm-6">
                        <a href="{{ url('paychecks_report/pdf' . '/' . $paychecks->sum('amount') . '/' . $search_2 . '/' . $search) }}" 
                        class="btn btn-dark btn-block {{count($paychecks) < 1 ? 'disabled' : ''}}"
                            target="_blank" title="Reporte">Generar PDF</a>
                    </div>
                </div>
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
                                <th class="table-th text-center text-white">fecha</th>
                                <th class="table-th text-center text-white">n° cheque</th>
                                <th class="table-th text-center text-white">n° recibo</th>
                                <th class="table-th text-center text-white">cliente</th>
                                <th class="table-th text-center text-white">banco</th>
                                <th class="table-th text-center text-white">cobro</th>
                                <th class="table-th text-center text-white">descripcion</th>
                                <th class="table-th text-center text-white">saldo</th>
                                <th class="table-th text-center text-white">opciones</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($paychecks as $paycheck)

                            <tr>
                                <td class="text-center">
                                    <h6>{{Carbon\Carbon::parse($paycheck->created_at)->format('d-m-Y')}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$paycheck->number}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$paycheck->sale->file_number}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$paycheck->customer->name}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$paycheck->bank->alias}}</h6>
                                </td>
                                <td class="text-center">
                                    <h6>
                                        @switch($paycheck->status->name)
                                        @case('realizado')
                                        <span class="text-uppercase badge badge-success">
                                            {{$paycheck->status->name}}
                                        </span>
                                        @break
                                        @case('pendiente')
                                        <span class="text-uppercase badge badge-warning">
                                            {{$paycheck->status->name}}
                                        </span>
                                        @break
                                        @case('anulado')
                                        <span class="text-uppercase badge badge-danger">
                                            {{$paycheck->status->name}}
                                        </span>
                                        @break
                                        @endswitch
                                    </h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$paycheck->description}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">${{number_format($paycheck->amount,2)}}</h6>
                                </td>
                                <td class="text-center">
                                    @if($search_2 == 0)
                                    <a href="javascript:void(0)" wire:click="Edit({{$paycheck->id}})" class="btn btn-dark mtmobile" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                    <a href="javascript:void(0)" 
                                        class="btn btn-dark mtmobile" title="Detalles">
                                        <i class="fas fa-list"></i>
                                    </a>
                                </td>
                            </tr>

                            @endforeach

                        </tbody>
                    </table>
                    
                    {{$paychecks->links()}}

                </div>
            </div>
        </div>
    </div>

    @include('livewire.paycheck.form')
    @include('livewire.paycheck.bank_form')
    @include('livewire.paycheck.customer_form')

</div>


<script> 
    document.addEventListener('DOMContentLoaded', function(){

        window.livewire.on('show-modal', msg=>{
            $('#theModal').modal('show')
        });
        $('#theModal').on('shown.bs.modal', function(e){
            $('.component-name').focus()
        });
        window.livewire.on('item-added', msg=>{
            $('#theModal').modal('hide')
            noty(msg)
        });
        window.livewire.on('item-updated', msg=>{
            $('#theModal').modal('hide')
            noty(msg)
        });
        window.livewire.on('item-deleted', msg=>{
            noty(msg)
        });
        window.livewire.on('show-customer-modal', msg=>{
            $('#theModal').modal('hide')
            $('#customer_modal').modal('show')
        });
        $('#customer_modal').on('shown.bs.modal', function(e){
            $('.component-name').focus()
        });
        window.livewire.on('customer-added', msg=>{
            $('#customer_modal').modal('hide')
            $('#theModal').modal('show')
            noty(msg)
        });
        window.livewire.on('show-bank-modal', msg=>{
            $('#theModal').modal('hide')
            $('#bank_modal').modal('show')
        });
        $('#bank_modal').on('shown.bs.modal', function(e){
            $('.component-name').focus()
        });
        window.livewire.on('bank-added', msg=>{
            $('#bank_modal').modal('hide')
            $('#theModal').modal('show')
            noty(msg)
        });
        window.livewire.on('item-error', msg=>{
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

                window.livewire.emit('destroy',id)
                swal.close()
            }
        })
    }

</script>