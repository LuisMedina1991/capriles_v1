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
                        <option value="cuentas bancarias">cuentas bancarias</option>
                    </select>
                    @error('relation')
                        <span class="text-danger er">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            @switch($relation)

                @case('cuentas bancarias')
                    
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

        @break

        @case('egreso')

            <div class="col-sm-4">
                <div class="form-group">
                    <label><b>Tipo de transaccion</b></label>
                    <select wire:model="relation" class="form-control text-uppercase">
                        <option value="elegir">elegir</option>
                        <option value="cuentas bancarias">cuentas bancarias</option>
                    </select>
                    @error('relation')
                        <span class="text-danger er">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            @switch($relation)

                @case('cuentas bancarias')
                    
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

        @break

    @endswitch

    <div class="col-sm-4">
        <div class="form-group">
            <label><b>Monto</b></label>
            <input type="number" wire:model.lazy="amount" class="form-control" placeholder="0.00">
            @error('amount')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

@include('common.modalFooter')