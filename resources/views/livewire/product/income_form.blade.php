<div wire:ignore.self class="modal fade" id="income_modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white text-uppercase">
                    <b>ingresar | producto</b>
                </h5>
                <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4">
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
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Sucursal</b></label>
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
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Stock Actual</b></label>
                            <input type="text" wire:model.lazy="cant_1" class="form-control" placeholder="0" disabled>
                        </div>
                        @error('cant_1')
                        <span class="text-danger er">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Pago con Impuesto</b></label>
                            <select wire:model="tax" class="form-control text-uppercase">
                                <option value="Elegir">Elegir</option>
                                <option value="0">no</option>
                                <option value="1">si</option>
                            </select>
                            @error('tax')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Estado del Pago</b></label>
                            <select wire:model="statusId" class="form-control text-uppercase">
                                <option value="Elegir">Elegir</option>
                                <option value="3">realizado</option>
                                <option value="4">pendiente</option>
                            </select>
                            @error('statusId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Tipo de Pago</b></label>
                            <select wire:model="payment_type" class="form-control text-uppercase">
                                <option value="Elegir">Elegir</option>
                                <option value="efectivo">efectivo</option>
                                <option value="deposito">deposito</option>
                            </select>
                            @error('payment_type')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Cantidad a Ingresar</b></label>
                            <input type="number" wire:model.lazy="cant_2" class="form-control" placeholder="0">
                            @error('cant_2')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Proveedor</b></label>
                            <select wire:model="supplierId" class="form-control text-uppercase">
                                <option value="Elegir">Elegir</option>
                                @foreach ($allSuppliers as $supplier)
                                <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                @endforeach
                            </select>
                            @error('supplierId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-2 mt-4">
                        <div class="form-group">
                            <button type="button" wire:click.prevent="ShowSupplierModal()" class="btn btn-success"
                                title="Nuevo Proveedor">Añadir Nuevo</button>
                        </div>
                    </div>
                    @if($payment_type == 'deposito')
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Saldo</b></label>
                            <select wire:model="accountId" class="form-control text-uppercase" disabled>
                                <option value="Elegir">$0.00</option>
                                @foreach ($allAccounts as $account)
                                <option value="{{$account->id}}">${{number_format($account->balance,2)}}</option>
                                @endforeach
                            </select>
                            @error('accountId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Cuenta de Banco</b></label>
                            <select wire:model="accountId" class="form-control text-uppercase">
                                <option value="Elegir">Elegir</option>
                                @foreach ($allAccounts as $account)
                                <option value="{{$account->id}}">{{$account->bank->alias}}-{{$account->company->alias}}-{{$account->number}}</option>
                                @endforeach
                            </select>
                            @error('accountId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-2 mt-4">
                        <div class="form-group">
                            <button type="button" wire:click.prevent="ShowAccountModal({{$modal_id_2}})" class="btn btn-success"
                                title="Nueva Cuenta">Añadir Nuevo</button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="Income()" class="btn btn-dark close-modal text-uppercase">guardar</button>
                <button type="button" wire:click.prevent="CloseIncomeModal()" class="btn btn-dark close-btn text-info text-uppercase" data-dismiss="modal">cerrar</button>
            </div>
        </div>
    </div>
</div>