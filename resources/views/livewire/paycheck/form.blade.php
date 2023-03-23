@include('common.modalHead')
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label><b>Descripcion</b></label>
            <input type="text" wire:model.lazy="description" class="form-control" disabled>
            @error('description')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label><b>Saldo</b></label>
            <input type="text" wire:model.lazy="amount" class="form-control" disabled>
            @error('amount')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-6">
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
            <label><b>Cliente</b></label>
            <select wire:model="customer_id" class="form-control text-uppercase">
                <option value="Elegir">Elegir</option>
                @foreach ($customers as $customer)
                <option value="{{$customer->id}}">{{$customer->name}}</option>
                @endforeach
            </select>
            @error('customer_id')
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
</div>
@include('common.modalFooter')