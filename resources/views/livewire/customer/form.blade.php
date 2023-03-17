@include('common.modalHead')
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label><b>Cliente</b></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <span class="fas fa-edit"></span>
                    </span>
                </div>
                <input type="text" wire:model.lazy="name" class="form-control component-name"
                    placeholder="Nombre del cliente...">
            </div>
            @error('name')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label><b>Email</b></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <span class="fas fa-edit"></span>
                    </span>
                </div>
                <input type="text" wire:model.lazy="email" class="form-control" placeholder="Email del cliente...">
            </div>
            @error('email')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label><b>Telefono</b></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <span class="fas fa-edit"></span>
                    </span>
                </div>
                <input type="text" wire:model.lazy="phone" class="form-control" placeholder="Telefono del cliente...">
            </div>
            @error('phone')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label><b>Fax</b></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <span class="fas fa-edit"></span>
                    </span>
                </div>
                <input type="text" wire:model.lazy="fax" class="form-control" placeholder="Fax del cliente...">
            </div>
            @error('fax')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label><b>NIT</b></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <span class="fas fa-edit"></span>
                    </span>
                </div>
                <input type="text" wire:model.lazy="nit" class="form-control" placeholder="NIT del cliente...">
            </div>
            @error('nit')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label><b>Ciudad</b></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <span class="fas fa-edit"></span>
                    </span>
                </div>
                <input type="text" wire:model.lazy="city" class="form-control" placeholder="Ciudad del cliente...">
            </div>
            @error('city')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label><b>Pais</b></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <span class="fas fa-edit"></span>
                    </span>
                </div>
                <input type="text" wire:model.lazy="country" class="form-control" placeholder="Pais del cliente...">
            </div>
            @error('country')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>
@include('common.modalFooter')