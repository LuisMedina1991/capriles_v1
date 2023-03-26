<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title text-uppercase">
                    <b>{{$pageTitle}} | {{$componentName}}</b>
                </h4>
                <div class="row">
                    <div class="col-sm-6">
                        <a href="{{ url('bank_accounts_report/pdf' . '/' .  $search_2 . '/' . $search) }}" 
                        class="btn btn-dark btn-md {{count($accounts) < 1 ? 'disabled' : ''}}"
                            target="_blank" title="Reporte">Generar PDF</a>
                    </div>
                    <div class="col-sm-6">
                        <a href="javascript:void(0)" class="btn btn-dark btn-md" data-toggle="modal" data-target="#theModal" title="Nueva Cuenta">Agregar</a>
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
                        <option value="0">cuentas activas</option>
                        <option value="1">cuentas bloqueadas</option>
                    </select>
                </div>
            </div>

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                <th class="table-th text-white text-center">n° cuenta</th>
                                <th class="table-th text-center text-white">propietario</th>
                                <th class="table-th text-white text-center">banco</th>
                                <th class="table-th text-white text-center">tipo de cuenta</th>
                                <th class="table-th text-white text-center">moneda</th>
                                <th class="table-th text-white text-center">saldo</th>
                                <th class="table-th text-center text-white">opciones</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($accounts as $account)

                            <tr>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$account->number}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$account->company->alias}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$account->bank->alias}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$account->type}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">{{$account->currency}}</h6>
                                </td>
                                <td>
                                    <h6 class="text-center text-uppercase">${{number_format($account->balance,2)}}</h6>
                                </td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" wire:click="Edit({{$account->id}})" class="btn btn-dark mtmobile" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0)" onclick="Confirm('{{$account->id}}')" class="btn btn-dark" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            @endforeach

                        </tbody>
                    </table>
                    
                    {{$accounts->links()}}

                </div>
            </div>
        </div>
    </div>

    @include('livewire.bank_accounts.form')
    @include('livewire.bank_accounts.company_form')
    @include('livewire.bank_accounts.bank_form')

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
        window.livewire.on('show-company-modal', msg=>{
            $('#theModal').modal('hide')
            $('#company_modal').modal('show')
        });
        $('#company_modal').on('shown.bs.modal', function(e){
            $('.component-name').focus()
        });
        window.livewire.on('company-added', msg=>{
            $('#company_modal').modal('hide')
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