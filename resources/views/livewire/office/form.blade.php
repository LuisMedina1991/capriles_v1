@include('common.modalHead')

    <div class="row">

        <div class="col-sm-12 col-md-12 col-lg-6">
            <label><b>Nombre*</b></label>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <span class="fas fa-edit"></span>
                        </span>
                    </div>
                    <input type="text" wire:model.lazy="name" class="form-control component-name"
                        placeholder="Nombre para la sucursal...">
                </div>
                @error('name')
                <span class="text-danger er">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-lg-6">
            <label><b>Alias*</b></label>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <span class="fas fa-edit"></span>
                        </span>
                    </div>
                    <input type="text" wire:model.lazy="alias" class="form-control"
                        placeholder="Alias para la sucursal...">
                </div>
                @error('alias')
                <span class="text-danger er">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-lg-6">
            <label><b>Telefono</b></label>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <span class="fas fa-edit"></span>
                        </span>
                    </div>
                    <input type="number" wire:model.lazy="phone" class="form-control"
                        placeholder="Telefono para la sucursal...">
                </div>
                @error('phone')
                <span class="text-danger er">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-sm-12">
            <label><b>Direccion</b></label>
            <div class="form-group">
                <textarea wire:model.lazy="address" class="form-control" placeholder="Direccion para la sucursal..." cols="30" rows="2"></textarea>
                @error('address')
                    <span class="text-danger er">{{ $message }}</span>
                @enderror
            </div>
        </div>

    </div>

@include('common.modalFooter')