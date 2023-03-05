@include('common.modalHead')

<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label><b>Tipo</b></label>
            <select wire:model="type" class="form-control">
                <option value="Elegir">Elegir</option>
                <option value="billete">billete</option>
                <option value="moneda">moneda</option>
                <option value="otro">otro</option>
            </select>
            @error('type')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <label><b>Valor</b></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-edit">

                    </span>
                </span>
            </div>
            <input type="text" wire:model.lazy="value" class="form-control" placeholder="Ingrese un valor..."
                maxlength="25">
        </div>
        @error('value')
        <span class="text-danger er">{{ $message }}</span>
        @enderror
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