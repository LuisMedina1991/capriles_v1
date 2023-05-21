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
                        placeholder="Nombre para la empresa...">
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
                        placeholder="Alias para la empresa...">
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
                    <input type="number" wire:model.lazy="phone" class="form-control" placeholder="Telefono para la empresa...">
                </div>
                @error('phone')
                <span class="text-danger er">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-lg-6">
            <label><b>Email</b></label>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <span class="fas fa-edit"></span>
                        </span>
                    </div>
                    <input type="text" wire:model.lazy="email" class="form-control" placeholder="Email para la empresa...">
                </div>
                @error('email')
                <span class="text-danger er">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-lg-6">
            <label><b>NIT*</b></label>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <span class="fas fa-edit"></span>
                        </span>
                    </div>
                    <input type="number" wire:model.lazy="nit" class="form-control" placeholder="NIT para la empresa...">
                </div>
                @error('nit')
                <span class="text-danger er">{{ $message }}</span>
                @enderror
            </div>
        </div>
        
    </div>

@include('common.modalFooter')