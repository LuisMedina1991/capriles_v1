@include('common.modalHead')

    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-6">
            <label><b>Tipo de denominacion*</b></label>
            <div class="form-group">
                <select wire:model="type" class="form-control text-uppercase">
                    <option value="elegir">elegir</option>
                    <option value="billete">billete</option>
                    <option value="moneda">moneda</option>
                    <option value="otro">otro</option>
                </select>
                @error('type')
                <span class="text-danger er">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6">
            <label><b>Valor de la denominacion*</b></label>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <span class="fas fa-edit"></span>
                        </span>
                    </div>
                    <input type="number" wire:model.lazy="value" class="form-control" placeholder="0.00"
                        maxlength="25">
                </div>
                @error('value')
                <span class="text-danger er">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group custom-file">
                <input type="file" class="custom-file-input form-control" wire:model="image"
                    accept="image/x-png, image/gif, image/jpeg">
                <label class="custom-file-label">IMAGEN {{ $image }}</label>
                @error('image')
                <span class="text-danger er">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

@include('common.modalFooter')