@include('common.modalHead')

<div class="row">
    <div class="col-sm-12 col-md-6">
        <label><b>Nombre</b></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-edit"></span>
                </span>
            </div>
            <input type="text" wire:model.lazy="name" class="form-control component-name" placeholder="Nombre del estado...">
        </div>
        @error('name')
            <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label><b>Tipo</b></label>
            <select wire:model="type" class="form-control text-uppercase">
                <option value="Elegir">Elegir</option>
                <option value="registro">registro</option>
                <option value="transaccion">transaccion</option>
            </select>
            @error('type')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

@include('common.modalFooter')