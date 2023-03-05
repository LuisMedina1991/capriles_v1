@include('common.modalHead')
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label><b>Nombre</b></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <span class="fas fa-edit"></span>
                    </span>
                </div>
                <input type="text" wire:model.lazy="name" class="form-control component-name"
                    placeholder="Nombre para la sucursal...">
            </div>
        </div>
        @error('name')
        <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-6">
        <div class="form-gropu">
            <label><b>Telefono</b></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <span class="fas fa-edit"></span>
                    </span>
                </div>
                <input type="text" wire:model.lazy="phone" class="form-control"
                    placeholder="Telefono para la sucursal...">
            </div>
        </div>
        @error('phone')
        <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label><b>Direccion</b></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <span class="fas fa-edit"></span>
                    </span>
                </div>
                <input type="text" wire:model.lazy="address" class="form-control"
                    placeholder="Direccion para la sucursal...">
            </div>
        </div>
        @error('address')
        <span class="text-danger er">{{ $message }}</span>
        @enderror
    </div>
</div>
@include('common.modalFooter')