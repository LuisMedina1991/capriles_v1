<div wire:ignore.self class="modal fade" id="values_modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white text-uppercase">
                    <b>gestionar | valores</b>
                </h5>
                <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                <th class="table-th text-center text-white">costo</th>
                                <th class="table-th text-center text-white">precio</th>
                                <th class="table-th text-center text-white">stock activo</th>
                                <th class="table-th text-center text-white">opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productValues as $index => $productValue)
                                <tr>
                                    @if($productValue['is_saved'])
                                        <td class="text-center">
                                            <h6 class="text-uppercase"><b>{{number_format($productValue['cost'],2)}}</b></h6>
                                            @error('productValues.' . $index . '.cost')
                                            <span class="text-danger er">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td class="text-center">
                                            <h6 class="text-uppercase"><b>{{number_format($productValue['price'],2)}}</b></h6>
                                            @error('productValues.' . $index . '.price')
                                            <span class="text-danger er">{{ $message }}</span>
                                            @enderror
                                        </td>
                                    @else
                                        <td class="text-center">
                                            <input type="number" wire:model.lazy="productValues.{{$index}}.cost" class="form-control" placeholder="0.00">
                                            @error('productValues.' . $index . '.cost')
                                            <span class="text-danger er">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td class="text-center">
                                            <input type="number" wire:model.lazy="productValues.{{$index}}.price" class="form-control" placeholder="0.00">
                                            @error('productValues.' . $index . '.price')
                                            <span class="text-danger er">{{ $message }}</span>
                                            @enderror
                                        </td>
                                    @endif
                                        {{--AÑADIR ALERTA DE STOCK--}}
                                        <td class="text-center">
                                            <h6 class="text-uppercase"><b>{{$productValue['stock']}} uds.</b></h6>
                                        </td>
                                        <td class="text-center">
                                            @if($productValue['is_saved'])
                                                <a href="javascript:void(0)" wire:click="EditValues({{$index}})" class="btn btn-warning" title="Editar Fila">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($productValue['stock'] > 0)
                                                <a href="javascript:void(0)" wire:click="ShowStockDetailsModal({{$selected_id}})" class="btn btn-info" title="Detalles de Stock">
                                                    <i class="fas fa-list"></i>
                                                </a>
                                                @endif
                                            @else
                                                <a href="javascript:void(0)" wire:click="SaveValues({{$index}})" class="btn btn-success" title="Guardar Fila">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a href="javascript:void(0)" wire:click="RemoveValues({{$index}})" class="btn btn-danger" title="Eliminar Fila">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            @endif
                                        </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="AddValues()" class="btn btn-success text-uppercase">+ añadir fila</button>
                @if ($selected_id < 1)
                <button type="button" wire:click.prevent="StoreValues()" class="btn btn-dark close-modal text-uppercase">guardar</button>
                @else
                <button type="button" wire:click.prevent="StoreValues()" class="btn btn-dark close-modal text-uppercase">actualizar</button>
                @endif
                <button type="button" wire:click.prevent="resetUI()" class="btn btn-dark close-btn text-info text-uppercase" data-dismiss="modal">cerrar</button>
            </div>
        </div>
    </div>
</div>