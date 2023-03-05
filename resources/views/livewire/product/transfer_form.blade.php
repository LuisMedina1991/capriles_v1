<div wire:ignore.self class="modal fade" id="transfer_modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white text-uppercase">
                    <b>transferir | producto</b>
                </h5>
                <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Codigo de Producto</b></label>
                            <select wire:model="product_id" class="form-control text-uppercase" disabled>
                                <option value="Elegir">Elegir</option>
                                @foreach ($products as $product)
                                <option value="{{$product->id}}">{{$product->code}}</option>
                                @endforeach
                            </select>
                            @error('product_id')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Sucursal de Origen</b></label>
                            <select wire:model="office_id_1" class="form-control text-uppercase" disabled>
                                <option value="Elegir">Elegir</option>
                                @foreach ($allOffices as $office)
                                <option value="{{$office->id}}">{{$office->name}}</option>
                                @endforeach
                            </select>
                            @error('office_id')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Sucursal de Destino</b></label>
                            <select wire:model="office_id_2" class="form-control text-uppercase">
                                <option value="Elegir">Elegir</option>
                                @foreach ($allOffices as $office)
                                <option value="{{$office->id}}">{{$office->name}}</option>
                                @endforeach
                            </select>
                            @error('office_id_2')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Stock</b></label>
                            <input type="text" wire:model.lazy="cant_1" class="form-control" placeholder="0" disabled>
                        </div>
                        @error('cant_1')
                        <span class="text-danger er">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Cantidad a Transferir</b></label>
                            <input type="number" wire:model.lazy="cant_2" class="form-control" placeholder="0">
                            @error('cant_2')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="Transfer()" class="btn btn-dark close-modal text-uppercase">guardar</button>
                <button type="button" wire:click.prevent="CloseTransferModal()" class="btn btn-dark close-btn text-info text-uppercase" data-dismiss="modal">cerrar</button>
            </div>
        </div>
    </div>
</div>