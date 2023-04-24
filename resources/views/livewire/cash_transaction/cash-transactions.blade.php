<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title text-uppercase">
                    <b>{{$pageTitle}} | {{$componentName}}</b>
                </h4>
                <div class="row">
                    <div class="col-sm-4">
                        <h5 class="text-uppercase">saldo de caja: ${{number_format(($my_total),2)}}</h5>
                    </div>
                    <div class="col-sm-4">
                        <a href="{{ url('cash_transactions_report/pdf' . '/' . $my_total . '/' . $reportRange . '/' . $search_2 . '/' . $dateFrom . '/' . $dateTo . '/' . $search) }}" 
                        class="btn btn-dark btn-md {{count($transactions) < 1 ? 'disabled' : ''}}"
                            target="_blank" title="Reporte">Generar PDF</a>
                    </div>
                    <div class="col-sm-4">
                        <a href="javascript:void(0)" class="btn btn-dark btn-md {{$reportRange != 0 || $search_2 != 0 ? 'disabled' : ''}}" data-toggle="modal"
                            data-target="#theModal" title="Nuevo Registro">Agregar</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
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
                <div class="col-sm-2">
                    <h6><b>Estado de transaccion</b></h6>
                    <div class="form-group">
                        <select wire:model="search_2" class="form-control">
                            <option value="0">Activa</option>
                            <option value="1">Anulada</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <h6><b>Alcance del reporte</b></h6>
                    <div class="form-group">
                        <select wire:model="reportRange" class="form-control">
                            <option value="0">Del dia</option>
                            <option value="1">Por fecha</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-2">
                    <h6><b>Fecha Inicial</b></h6>
                    <div class="form-group">
                        <input type="text" wire:model="dateFrom" class="form-control flatpickr" placeholder="Click para elegir">
                    </div>
                </div>
                <div class="col-sm-2">
                    <h6><b>Fecha Final</b></h6>
                    <div class="form-group">
                        <input type="text" wire:model="dateTo" class="form-control flatpickr" placeholder="Click para elegir">
                    </div>
                </div>
            </div>
            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                @if($reportRange == 1)
                                <th class="table-th text-white text-center">fecha</th>
                                @endif
                                <th class="table-th text-white text-center">n° recibo</th>
                                <th class="table-th text-white text-center">accion</th>
                                <th class="table-th text-white text-center">tipo</th>
                                <th class="table-th text-white text-center">detalles</th>
                                <th class="table-th text-white text-center">monto</th>
                                @if($search_2 == 0 && $reportRange == 0)
                                <th class="table-th text-white text-center">acciones</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                            <tr>
                                @if($reportRange == 1)
                                <td class="text-center"><h6>{{\Carbon\Carbon::parse($transaction->created_at)->format('d-m-Y')}}</h6></td>
                                @endif
                                <td><h6 class="text-center text-uppercase">{{$transaction->file_number}}</h6></td>
                                <td><h6 class="text-center text-uppercase">{{$transaction->action}}</h6></td>
                                <td><h6 class="text-center text-uppercase">{{$transaction->type}}</h6></td>
                                <td><h6 class="text-center text-uppercase">{{$transaction->description}}</h6></td>  
                                <td><h6 class="text-center">${{number_format($transaction->amount,2)}}</h6></td>
                                @if($search_2 == 0 && $reportRange == 0)
                                <td class="text-center">
                                    <a href="javascript:void(0)" onclick="Confirm('{{$transaction->id}}')" class="btn btn-dark" title="Anular">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('livewire.cash_transaction.form')
</div>


<script>

    document.addEventListener('DOMContentLoaded', function(){

        flatpickr(document.getElementsByClassName('flatpickr'), {

            enableTime: false,
            dateFormat: 'd-m-Y',
            locale: {
                firstDayofWeek: 1,
                weekdays: {
                    shorthand: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
                    longhand: [
                    "Domingo",
                    "Lunes",
                    "Martes",
                    "Miércoles",
                    "Jueves",
                    "Viernes",
                    "Sábado",
                    ],
                },
                months: {
                    shorthand: [
                    "Ene",
                    "Feb",
                    "Mar",
                    "Abr",
                    "May",
                    "Jun",
                    "Jul",
                    "Ago",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dic",
                    ],
                    longhand: [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre",
                    ],
                },
            }
        })
        
        window.livewire.on('item-added', msg=>{
            $('#theModal').modal('hide')
            noty(msg)
        });
        window.livewire.on('item-deleted', msg=>{
            noty(msg,2)
        });
        window.livewire.on('show-modal', msg=>{
            $('#theModal').modal('show')
        });
        window.livewire.on('modal-hide', msg=>{
            $('#theModal').modal('hide')
        });
        $('#theModal').on('shown.bs.modal', function(e){
            $('.component-name').focus()
        });
        window.livewire.on('item-updated', msg=>{
            $('#theModal2').modal('hide')
            noty(msg)
        });
        window.livewire.on('item-error', msg => {
            noty(msg,2)
        });
    });

    function Confirm(id){

        swal({

            title: 'CONFIRMAR',
            text: '¿CONFIRMA ANULAR LA TRANSACCION?',
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

    function Confirm2(){

        swal({

            title: 'CONFIRMAR',
            text: 'SOLO SE PUEDE OBTENER LAS VENTAS UNA VEZ AL DIA ¿DESEA CONTINUAR?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'CERRAR',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'ACEPTAR'

        }).then(function(result){

            if(result.value){
                window.livewire.emit('collect', )
                swal.close()
            }
        })
    }

</script>