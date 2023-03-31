@include('common.modalHead')

<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label><b>Descripcion</b></label>
            <textarea wire:model.lazy="description" class="form-control component-name" placeholder="Descripcion de la transaccion..." cols="30" rows="3"></textarea>
            @error('description')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label><b>Accion</b></label>
            <select wire:model="action" class="form-control text-uppercase">
                <option value="elegir">elegir</option>
                <option value="ingreso">ingreso</option>
                <option value="egreso">egreso</option>
            </select>
            @error('action')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    @switch($action)

        @case('ingreso')

            <div class="col-sm-4">
                <div class="form-group">
                    <label><b>Tipo de transaccion</b></label>
                    <select wire:model="relation" class="form-control text-uppercase">
                        <option value="elegir">elegir</option>
                        <option value="caja general">variada</option>
                        <option value="deudas de clientes">clientes</option>
                        <option value="cuentas bancarias">cuentas bancarias</option>
                    </select>
                    @error('relation')
                        <span class="text-danger er">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            @switch($relation)

                @case('deudas de clientes')

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Cliente</b></label>
                            <select wire:model="CustomerId" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @foreach ($customers as $customer)
                                <option value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                            </select>
                            @error('CustomerId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if($CustomerId != 'elegir')

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label><b>Detalle de la deuda</b></label>
                            <select wire:model="CustomerDebtId" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @foreach ($customer_debt_detail as $detail)
                                <option value="{{$detail->id}}">{{$detail->description}}</option>
                                @endforeach
                            </select>
                            @error('CustomerDebtId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @endif

                    @if($CustomerId != 'elegir' && $CustomerDebtId != 'elegir')

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Saldo de la deuda</b></label>
                            <input type="text" wire:model.lazy="customer_debt_balance" class="form-control" disabled>
                            @error('customer_debt_balance')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @endif

                @break

                @case('cuentas bancarias')
                    
                    @if($AccountId != 'elegir')

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Saldo</b></label>
                            <input type="text" wire:model.lazy="bank_account_balance" class="form-control" disabled>
                            @error('bank_account_balance')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @endif

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Cuenta Bancaria</b></label>
                            <select wire:model="AccountId" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @foreach ($bank_accounts as $account)
                                <option value="{{$account->id}}">{{$account->bank->alias}}-{{$account->company->alias}}-{{$account->number}}</option>
                                @endforeach
                            </select>
                            @error('AccountId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                @break
                    
            @endswitch

            <div class="col-sm-4">
                <div class="form-group">
                    <label><b>Monto</b></label>
                    <input type="number" wire:model.lazy="income_amount" class="form-control" placeholder="0.00">
                    @error('income_amount')
                    <span class="text-danger er">{{ $message }}</span>
                    @enderror
                </div>
            </div>

        @break

        @case('egreso')

            <div class="col-sm-4">
                <div class="form-group">
                    <label><b>Tipo de transaccion</b></label>
                    <select wire:model="relation" class="form-control text-uppercase">
                        <option value="elegir">elegir</option>
                        <option value="caja general">variada</option>
                        <option value="deudas con proveedores">proveedores</option>
                        <option value="cuentas bancarias">cuentas bancarias</option>
                    </select>
                    @error('relation')
                        <span class="text-danger er">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            @switch($relation)

                @case('deudas con proveedores')

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Proveedor</b></label>
                            <select wire:model="SupplierId" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @foreach ($suppliers as $supplier)
                                <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                @endforeach
                            </select>
                            @error('SupplierId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if($SupplierId != 'elegir')

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label><b>Detalle de la deuda</b></label>
                            <select wire:model="DebtWithSupplierId" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @foreach ($debt_with_supplier_detail as $detail)
                                <option value="{{$detail->id}}">{{$detail->description}}</option>
                                @endforeach
                            </select>
                            @error('DebtWithSupplierId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @endif

                    @if($SupplierId != 'elegir' && $DebtWithSupplierId != 'elegir')

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Saldo de la deuda</b></label>
                            <input type="text" wire:model.lazy="debt_with_supplier_balance" class="form-control" disabled>
                            @error('debt_with_supplier_balance')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @endif

                @break

                @case('cuentas bancarias')
                    
                    @if($AccountId != 'elegir')

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Saldo</b></label>
                            <input type="text" wire:model.lazy="bank_account_balance" class="form-control" disabled>
                            @error('bank_account_balance')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @endif

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Cuenta Bancaria</b></label>
                            <select wire:model="AccountId" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @foreach ($bank_accounts as $account)
                                <option value="{{$account->id}}">{{$account->bank->alias}}-{{$account->company->alias}}-{{$account->number}}</option>
                                @endforeach
                            </select>
                            @error('AccountId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                @break
                    
            @endswitch

            <div class="col-sm-4">
                <div class="form-group">
                    <label><b>Monto</b></label>
                    <input type="number" wire:model.lazy="discharge_amount" class="form-control" placeholder="0.00">
                    @error('discharge_amount')
                    <span class="text-danger er">{{ $message }}</span>
                    @enderror
                </div>
            </div>

        @break

    @endswitch

</div>

@include('common.modalFooter')