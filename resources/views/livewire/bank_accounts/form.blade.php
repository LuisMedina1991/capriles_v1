@include('common.modalHead')
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label><b>Propietario</b></label>
            <select wire:model="company_id" class="form-control text-uppercase">
                <option value="Elegir">Elegir</option>
                @foreach ($companies as $company)
                <option value="{{$company->id}}">{{$company->alias}}</option>
                @endforeach
            </select>
            @error('company_id')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-2 mt-4">
        <div class="form-group">
            <button type="button" wire:click.prevent="ShowCompanyModal()" class="btn btn-success"
                title="Nueva Empresa">Añadir Nuevo</button>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label><b>Banco</b></label>
            <select wire:model="bank_id" class="form-control text-uppercase">
                <option value="Elegir">Elegir</option>
                @foreach ($banks as $bank)
                <option value="{{$bank->id}}">{{$bank->alias}}</option>
                @endforeach
            </select>
            @error('bank_id')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-2 mt-4">
        <div class="form-group">
            <button type="button" wire:click.prevent="ShowBankModal()" class="btn btn-success"
                title="Nuevo Banco">Añadir Nuevo</button>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label><b>Tipo de Cuenta</b></label>
            <select wire:model="type" class="form-control text-uppercase">
                <option value="Elegir">Elegir</option>
                <option value="caja de ahorros">caja de ahorros</option>
                <option value="cuenta corriente">cuenta corriente</option>
            </select>
            @error('type')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label><b>Moneda de la Cuenta</b></label>
            <select wire:model="currency" class="form-control text-uppercase">
                <option value="Elegir">Elegir</option>
                <option value="bolivianos">bolivianos</option>
                <option value="dolares">dolares</option>
            </select>
            @error('currency')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label><b>Numero</b></label>
            <input type="text" wire:model.lazy="number" class="form-control"
                placeholder="Numero de la cuenta...">
            @error('number')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label><b>Saldo</b></label>
            <input type="text" wire:model.lazy="balance" class="form-control"
                placeholder="0.00">
            @error('balance')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    @if($selected_id > 0 && $search_2 > 0)
    <div class="col-sm-6">
        <div class="form-group">
            <label><b>Estado de la Cuenta</b></label>
            <select wire:model="status_id" class="form-control text-uppercase">
                <option value="Elegir">Elegir</option>
                <option value="1">activa</option>
                <option value="2">bloqueada</option>
            </select>
            @error('status_id')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    @endif
</div>
@include('common.modalFooter')