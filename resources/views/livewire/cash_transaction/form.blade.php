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

    @if($action != 'elegir')

        <div class="col-sm-4">
            <div class="form-group">
                <label><b>Tipo de transaccion</b></label>
                <select wire:model="TypeId" class="form-control text-uppercase">
                    <option value="elegir">elegir</option>
                    @foreach($types as $type)
                    <option value="{{$type}}">{{$type}}</option>
                    @endforeach
                </select>
                @error('TypeId')
                    <span class="text-danger er">{{ $message }}</span>
                @enderror
            </div>
        </div>

    @endif

    @switch($action)

        @case('ingreso')

            @switch($TypeId)
                
                @case('clientes')

                    {{--<div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Cliente</b></label>
                            <select wire:model="Temp1" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @foreach ($TempArr1 as $debt)
                                <option value="{{$debt->customer_id}}">{{$debt->customer->name}}</option>
                                @endforeach
                            </select>
                            @error('Temp1')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if($Temp1 != 'elegir')

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><b>Detalles</b></label>
                                <select wire:model="Temp2" class="form-control text-uppercase">
                                    <option value="elegir">elegir</option>
                                    @foreach ($TempArr2 as $detail)
                                    <option value="{{$detail->id}}">{{$detail->description}}</option>
                                    @endforeach
                                </select>
                                @error('Temp2')
                                <span class="text-danger er">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    @endif

                    @if($Temp1 != 'elegir' && $Temp2 != 'elegir')

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Saldo</b></label>
                                <input type="text" wire:model.lazy="TempBal" class="form-control" disabled>
                                @error('TempBal')
                                <span class="text-danger er">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    @endif--}}

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Cliente</b></label>
                            <select wire:model="CustomerId" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @foreach ($customers_with_debts as $debt)
                                <option value="{{$debt->customer_id}}">{{$debt->customer->name}}</option>
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
                                <label><b>Detalles</b></label>
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
                                <label><b>Saldo</b></label>
                                <input type="text" wire:model.lazy="customer_debt_balance" class="form-control" disabled>
                                @error('customer_debt_balance')
                                <span class="text-danger er">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    @endif

                @break

                @case('cheques')

                    {{--<div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Cliente</b></label>
                            <select wire:model="Temp1" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @foreach ($TempArr1 as $check)
                                <option value="{{$check->customer_id}}">{{$check->customer->name}}</option>
                                @endforeach
                            </select>
                            @error('Temp1')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if($Temp1 != 'elegir')

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>N° Cheque</b></label>
                                <select wire:model="Temp2" class="form-control text-uppercase">
                                    <option value="elegir">elegir</option>
                                    @foreach ($TempArr2 as $detail)
                                    <option value="{{$detail->id}}">{{$detail->number}}</option>
                                    @endforeach
                                </select>
                                @error('Temp2')
                                <span class="text-danger er">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    @endif--}}

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Cliente</b></label>
                            <select wire:model="PaycheckCustomerId" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @foreach ($customers_with_paychecks as $debt)
                                <option value="{{$debt->customer_id}}">{{$debt->customer->name}}</option>
                                @endforeach
                            </select>
                            @error('PaycheckCustomerId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if($PaycheckCustomerId != 'elegir' && $PaycheckId != 'elegir')

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><b>Detalles</b></label>
                                <input type="text" wire:model.lazy="paycheck_description" class="form-control" disabled>
                                @error('paycheck_description')
                                <span class="text-danger er">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    @endif

                    @if($PaycheckCustomerId != 'elegir')

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>N° Cheque</b></label>
                                <select wire:model="PaycheckId" class="form-control text-uppercase">
                                    <option value="elegir">elegir</option>
                                    @foreach ($paycheck_detail as $detail)
                                    <option value="{{$detail->id}}">{{$detail->number}}</option>
                                    @endforeach
                                </select>
                                @error('PaycheckId')
                                <span class="text-danger er">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    @endif

                    @if($PaycheckCustomerId != 'elegir' && $PaycheckId != 'elegir')

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Saldo</b></label>
                                <input type="text" wire:model.lazy="paycheck_balance" class="form-control" disabled>
                                @error('paycheck_balance')
                                <span class="text-danger er">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    @endif

                @break

                @case('bancario')

                    {{--<div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Cuenta Bancaria</b></label>
                            <select wire:model="Temp1" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @foreach($TempArr1 as $bank_account)
                                <option value="{{$bank_account->id}}">{{$bank_account->bank->alias}}-{{$bank_account->company->alias}}-{{$bank_account->number}}</option>
                                @endforeach
                            </select>
                            @error('Temp1')
                                <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>--}}

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Cuenta Bancaria</b></label>
                            <select wire:model="BankAccountId" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @foreach($bank_accounts as $bank_account)
                                <option value="{{$bank_account->id}}">{{$bank_account->bank->alias}}-{{$bank_account->company->alias}}-{{$bank_account->number}}</option>
                                @endforeach
                            </select>
                            @error('BankAccountId')
                                <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if($BankAccountId != 'elegir')

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

                @break

                {{--@case('documentos')
                @break--}}

                @case('otros por pagar')

                    {{--<div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Relacion</b></label>
                            <select wire:model="Temp1" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @foreach ($TempArr1 as $relation)
                                <option value="{{$relation}}">{{$relation}}</option>
                                @endforeach
                            </select>
                            @error('Temp1')
                                <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>--}}

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Relacion</b></label>
                            <select wire:model="MPRELID" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @foreach ($MPREL as $relation)
                                <option value="{{$relation}}">{{$relation}}</option>
                                @endforeach
                            </select>
                            @error('MPRELID')
                                <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if($MPRELID != 'elegir')

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Referencia</b></label>
                                <input type="text" wire:model.lazy="IMPREF" class="form-control" placeholder="Referencia para la deuda...">
                                @error('IMPREF')
                                <span class="text-danger er">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    @endif

                @break

                @case('otros por cobrar')

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Relacion</b></label>
                            <select wire:model="MRRELID" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @foreach ($MRREL as $relation)
                                <option value="{{$relation}}">{{$relation}}</option>
                                @endforeach
                            </select>
                            @error('MRRELID')
                                <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if($MRRELID != 'elegir' && $MRREFID != 'elegir')

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><b>Detalles</b></label>
                                <select wire:model="MRDESCID" class="form-control text-uppercase">
                                    <option value="elegir">elegir</option>
                                    @foreach ($MRDESC as $detail)
                                    <option value="{{$detail->id}}">{{$detail->description}}</option>
                                    @endforeach
                                </select>
                                @error('MRDESCID')
                                <span class="text-danger er">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    @endif

                    @if($MRRELID != 'elegir')

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Referencia</b></label>
                                <select wire:model="MRREFID" class="form-control text-uppercase">
                                    <option value="elegir">elegir</option>
                                    @foreach($IMRREF as $reference)
                                    <option value="{{$reference->reference}}">{{$reference->reference}}</option>
                                    @endforeach
                                </select>
                                @error('MRREFID')
                                    <span class="text-danger er">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    @endif
                    
                    @if($MRRELID != 'elegir' && $MRREFID != 'elegir' && $MRDESCID != 'elegir')

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Saldo</b></label>
                                <input type="text" wire:model.lazy="MRBAL" class="form-control" disabled>
                                @error('MRBAL')
                                <span class="text-danger er">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    @endif

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

            @switch($TypeId)

                @case('bancario')

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Cuenta Bancaria</b></label>
                            <select wire:model="BankAccountId" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @foreach($bank_accounts as $bank_account)
                                <option value="{{$bank_account->id}}">{{$bank_account->bank->alias}}-{{$bank_account->company->alias}}-{{$bank_account->number}}</option>
                                @endforeach
                            </select>
                            @error('BankAccountId')
                                <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if($BankAccountId != 'elegir')

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

                @break

                @case('impuestos')

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>N° Recibo</b></label>
                            <select wire:model="TaxId" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @foreach ($active_taxes as $tax)
                                <option value="{{$tax->id}}">{{$tax->taxable->file_number}}</option>
                                @endforeach
                            </select>
                            @error('TaxId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if($TaxId != 'elegir')

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><b>Detalles</b></label>
                                <textarea wire:model.lazy="tax_description" class="form-control" cols="30" rows="2" disabled></textarea>
                                @error('tax_description')
                                <span class="text-danger er">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Saldo</b></label>
                                <input type="text" wire:model.lazy="tax_balance" class="form-control" disabled>
                                @error('tax_balance')
                                <span class="text-danger er">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    @endif

                @break
                
                {{--@case('inmuebles')
                @break

                @case('intangibles')
                @break

                @case('documentos')
                @break--}}

                @case('proveedores')

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Proveedor</b></label>
                            <select wire:model="SupplierId" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @foreach ($suppliers_with_debts as $debt)
                                <option value="{{$debt->supplier_id}}">{{$debt->supplier->name}}</option>
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
                                <label><b>Detalles</b></label>
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
                                <label><b>Saldo</b></label>
                                <input type="text" wire:model.lazy="debt_with_supplier_balance" class="form-control" disabled>
                                @error('debt_with_supplier_balance')
                                <span class="text-danger er">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    @endif

                @break

                @case('otros por pagar')

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Relacion</b></label>
                            <select wire:model="MPRELID" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @foreach ($MPREL as $relation)
                                <option value="{{$relation}}">{{$relation}}</option>
                                @endforeach
                            </select>
                            @error('MPRELID')
                                <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if($MPRELID != 'elegir' && $MPREFID != 'elegir')

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><b>Detalles</b></label>
                                <select wire:model="MPDESCID" class="form-control text-uppercase">
                                    <option value="elegir">elegir</option>
                                    @foreach ($MPDESC as $detail)
                                    <option value="{{$detail->id}}">{{$detail->description}}</option>
                                    @endforeach
                                </select>
                                @error('MPDESCID')
                                <span class="text-danger er">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    @endif

                    @if($MPRELID != 'elegir')

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Referencia</b></label>
                                <select wire:model="MPREFID" class="form-control text-uppercase">
                                    <option value="elegir">elegir</option>
                                    @foreach($DMPREF as $reference)
                                    <option value="{{$reference->reference}}">{{$reference->reference}}</option>
                                    @endforeach
                                </select>
                                @error('MPREFID')
                                    <span class="text-danger er">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    @endif
                    
                    @if($MPRELID != 'elegir' && $MPREFID != 'elegir' && $MPDESCID != 'elegir')

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Saldo</b></label>
                                <input type="text" wire:model.lazy="MPBAL" class="form-control" disabled>
                                @error('MPBAL')
                                <span class="text-danger er">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    @endif

                @break

                @case('otros por cobrar')

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Relacion</b></label>
                            <select wire:model="MRRELID" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @foreach ($MRREL as $relation)
                                <option value="{{$relation}}">{{$relation}}</option>
                                @endforeach
                            </select>
                            @error('MRRELID')
                                <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if($MRRELID != 'elegir')

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Referencia</b></label>
                                <input type="text" wire:model.lazy="DMRREF" class="form-control" placeholder="Referencia para la deuda...">
                                @error('DMRREF')
                                <span class="text-danger er">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    @endif

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