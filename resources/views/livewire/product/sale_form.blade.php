<div wire:ignore.self class="modal fade" id="sale_modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white text-uppercase">
                    <b>vender | producto</b>
                </h5>
                <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Codigo de Producto</b></label>
                            <input type="text" wire:model="product_code" class="form-control text-uppercase" disabled>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Sucursal</b></label>
                            <input type="text" wire:model="office_name" class="form-control text-uppercase" disabled>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Stock Actual</b></label>
                            <input type="text" wire:model="stock" class="form-control" disabled>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Costo</b></label>
                            <input type="text" wire:model.lazy="cost" class="form-control" placeholder="0" disabled>
                        </div>
                    </div>
                    
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Tipo de Pago</b></label>
                            <select wire:model="PaymentType" class="form-control text-uppercase">
                                <option value="Elegir">Elegir</option>
                                <option value="efectivo">efectivo</option>
                                <option value="deposito">deposito</option>
                                <option value="cheque">cheque</option>
                            </select>
                            @error('PaymentType')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Estado del Pago</b></label>
                            <select wire:model="statusId" class="form-control text-uppercase">
                                <option value="Elegir">Elegir</option>
                                @if($PaymentType != 'Elegir')
                                <option value="3">realizado</option>
                                @if($PaymentType == 'efectivo' || $PaymentType == 'cheque')
                                <option value="4">pendiente</option>
                                @endif
                                @endif
                            </select>
                            @error('statusId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if($PaymentType == 'deposito')

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Saldo</b></label>
                            <input type="text" wire:model.lazy="balance" class="form-control" disabled>
                            @error('balance')
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

                    @if($PaymentType == 'cheque')

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>N° Cheque</b></label>
                            <input type="text" wire:model.lazy="number" class="form-control" placeholder="1234">
                            @error('number')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Banco</b></label>
                            <select wire:model="bankId" class="form-control text-uppercase">
                                <option value="Elegir">Elegir</option>
                                @foreach ($allBanks as $bank)
                                <option value="{{$bank->id}}">{{$bank->name}}</option>
                                @endforeach
                            </select>
                            @error('bankId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-2 mt-4">
                        <div class="form-group">
                            <button type="button" wire:click.prevent="ShowBankModal({{$modal_id}})" class="btn btn-success"
                                title="Nuevo Banco">Añadir Nuevo</button>
                        </div>
                    </div>

                    @endif

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Cantidad a Vender</b></label>
                            <input type="number" wire:model.lazy="quantity" class="form-control">
                            @error('quantity')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Cliente</b></label>
                            <select wire:model="customerId" class="form-control text-uppercase">
                                <option value="Elegir">Elegir</option>
                                @foreach ($allCustomers as $customer)
                                <option value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                            </select>
                            @error('customerId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-2 mt-4">
                        <div class="form-group">
                            <button type="button" wire:click.prevent="ShowCustomerModal()" class="btn btn-success"
                                title="Nuevo Cliente">Añadir Nuevo</button>
                        </div>
                    </div>
                    
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Pago con Impuesto</b></label>
                            <select wire:model="tax_option" class="form-control text-uppercase">
                                <option value="Elegir">Elegir</option>
                                <option value="0">no</option>
                                <option value="1">si</option>
                            </select>
                            @error('tax_option')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if($tax_option == 1)

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Porcentaje de Impuesto</b></label>
                            <input type="number" wire:model.lazy="tax" class="form-control" placeholder="0.13">
                            @error('tax')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @endif

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Precio de Venta</b></label>
                            <input type="number" wire:model.lazy="price" class="form-control" placeholder="0.00">
                            @error('price')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{--<div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Total Impuesto</b></label>
                            <input type="text" wire:model="total_sale_tax" class="form-control" disabled>
                            @error('total_sale_tax')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Total Venta</b></label>
                            <input type="text" wire:model="total_sale" class="form-control" disabled>
                            @error('total_sale')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>--}}
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="Sale()" class="btn btn-dark close-modal text-uppercase">guardar</button>
                <button type="button" wire:click.prevent="CloseSaleModal()" class="btn btn-dark close-btn text-info text-uppercase" data-dismiss="modal">cerrar</button>
            </div>
        </div>
    </div>
</div>