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
                        placeholder="Nombre para el banco...">
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
                    <input type="text" wire:model.lazy="alias" class="form-control" placeholder="Alias para el banco...">
                </div>
                @error('alias')
                <span class="text-danger er">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-6">
            <label><b>Codigo*</b></label>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <span class="fas fa-edit"></span>
                        </span>
                    </div>
                    <input type="number" wire:model.lazy="entity_code" class="form-control" placeholder="Codigo de entidad...">
                </div>
                @error('entity_code')
                <span class="text-danger er">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

@include('common.modalFooter')