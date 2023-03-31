<div wire:ignore.self id="details_modal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white text-uppercase">
                    <b>historial</b>
                </h5>
                <button class="close" data-dismiss="modal" type="button" aria-label="Close">
                    <span class="text-white">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                <th class="table-th text-center text-white">fecha</th>
                                <th class="table-th text-center text-white">detalles</th>
                                <th class="table-th text-center text-white">saldo previo</th>
                                <th class="table-th text-center text-white">monto</th>
                                <th class="table-th text-center text-white">saldo actual</th>
                                {{--<th class="table-th text-center text-white">ANULAR</th>--}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($details as $detail)
                                <tr>
                                    <td class="text-center">
                                        <h6>{{\Carbon\Carbon::parse($detail->created_at)->format('d-m-Y')}}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6>{{$detail->description}}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6>${{number_format($detail->previus_balance,2)}}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6>${{number_format($detail->amount,2)}}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6>${{number_format($detail->actual_balance,2)}}</h6>
                                    </td>
                                    {{--@if(Carbon\Carbon::parse($detail->created_at)->format('d-m-Y') == Carbon\Carbon::today()->format('d-m-Y'))
                                    @can('cancel_movement')
                                    <td class="text-center">
                                        <a href="javascript:void(0)" onclick="Confirm2('{{$detail->id}}')" class="btn btn-dark" title="Anular">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                    @endcan
                                    @endif--}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    document.addEventListener('DOMContentLoaded', function(){

        window.livewire.on('report-error', Msg => {
            noty(Msg,2)
        });

        window.livewire.on('cancel-detail', msg=>{
            $('#modal-details').modal('hide')
            noty(msg,2)
        });
    })

    function Confirm2(id){

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
                window.livewire.emit('cancel', id)
                swal.close()
            }
        })
    }

</script>