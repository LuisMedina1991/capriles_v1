<div wire:ignore.self id="stock-details" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white">
                    <b>Detalle de Stock</b>
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
                                <th class="table-th text-center text-white">costo/precio</th>
                                <th class="table-th text-center text-white">sucursal</th>
                                <th class="table-th text-center text-white">stock</th>
                                <th class="table-th text-center text-white">opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($stock_details as $detail)
                                <tr>
                                    <td class="text-center">
                                        <h6>${{number_format($detail->value->cost, 2)}}/${{number_format($detail->value->price, 2)}}</h6>
                                    </td>
                                    <td class="text-center text-uppercase">
                                        <h6>{{$detail->office->name}}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6>{{$detail->stock}}</h6>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:void(0)" wire:click="ShowIncomeModal({{$detail->id}})" class="btn btn-dark mtmobile" title="Ingreso">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0)" wire:click="ShowTransferModal({{$detail->id}})" class="btn btn-dark mtmobile" title="Traspaso">
                                            <i class="fas fa-truck"></i>
                                        </a>
                                        <a href="javascript:void(0)" wire:click="ShowSaleModal({{$detail->id}})" class="btn btn-dark mtmobile" title="Venta">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="10">
                                    <h6 class="text-center text-muted">Sin resultados</h6>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <td></td>
                            <td class="text-center"><h5 class="text-uppercase"><b>total: </b></h5></td>
                            <td class="text-center">
                                @if($stock_details)
                                    <h5><b>{{$stock_details->sum('stock')}}</b></h5>
                                @endif
                            </td>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>