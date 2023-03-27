<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget">
            <div class="widget-heading">
                <h4 class="card-title text-center text-uppercase"><b>{{$componentName}}</b></h4>
            </div>
            <div class="widget-content">
                <div class="row">
                    <div class="col-sm-12 col-md-3">
                        <div class="col">
                            <div class="col-sm-12">
                                <h6>Estado de transaccion</h6>
                                <div class="form-group">
                                    <select wire:model="search_2" class="form-control">
                                        <option value="0">Vigente</option>
                                        <option value="1">Anulada</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <h6>Seleccione cuenta bancaria</h6>
                                <div class="form-group">
                                    <select wire:model="account_id" class="form-control">
                                        <option value="0">Todas</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{$account->id}}">{{$account->bank->alias}} - {{$account->company->alias}} - {{$account->number}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <h6>Elige el alcance del reporte</h6>
                                <div class="form-group">
                                    <select wire:model="reportRange" class="form-control">
                                        <option value="0">Transacciones del dia</option>
                                        <option value="1">Transacciones por fecha</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 mt-2">
                                <h6>Fecha inicial</h6>
                                <div class="form-group">
                                    <input type="text" wire:model="dateFrom" class="form-control flatpickr" placeholder="Click para elegir">
                                </div>
                            </div>
                            <div class="col-sm-12 mt-2">
                                <h6>Fecha final</h6>
                                <div class="form-group">
                                    <input type="text" wire:model="dateTo" class="form-control flatpickr" placeholder="Click para elegir">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <a href="{{ url('banking_transactions_report/pdf' . '/' . $account_id . '/' . $reportRange . '/' . $search_2 . '/' . $dateFrom . '/' . $dateTo) }}" 
                                class="btn btn-dark btn-block {{count($transactions) < 1 ? 'disabled' : ''}}" target="_blank">
                                    Generar PDF
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-9">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered mt-1">
                                <thead class="text-white" style="background: #3B3F5C">
                                    <tr>
                                        <th class="table-th text-white text-center">fecha</th>
                                        <th class="table-th text-white text-center">tipo de transaccion</th>
                                        <th class="table-th text-white text-center">n° recibo</th>
                                        <th class="table-th text-white text-center">saldo previo</th>
                                        <th class="table-th text-white text-center">monto</th>
                                        <th class="table-th text-white text-center">saldo actual</th>
                                        <th class="table-th text-white text-center">detalles</th>
                                        {{--@if($reportRange == 0)
                                        <th class="table-th text-white text-center">acciones</th>
                                        @endif--}}
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @if(count($transactions) < 1)
                                        <tr>
                                            <td colspan="10">
                                                <h6 class="text-center text-muted">Sin resultados</h6>
                                            </td>
                                        </tr>
                                    @endif
                                    @foreach ($transactions as $transaction)
                                    <tr>
                                        <td class="text-center"><h6>{{\Carbon\Carbon::parse($transaction->created_at)->format('d-m-Y')}}</h6></td>
                                        <td class="text-center"><h6>{{$transaction->action}}</h6></td>
                                        <td class="text-center"><h6>{{$transaction->relation_file_number}}</h6></td>
                                        <td class="text-center"><h6>${{number_format($transaction->previus_balance,2)}}</h6></td>
                                        <td class="text-center"><h6>${{number_format($transaction->amount,2)}}</h6></td>
                                        <td class="text-center"><h6>${{number_format($transaction->actual_balance,2)}}</h6></td>
                                        <td class="text-center"><h6>{{$transaction->description}}</h6></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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


        window.livewire.on('report-error', Msg => {
            noty(Msg,2)
        });

    })

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