@include('common.modalHead')    <!--inclucion del header del modal-->

<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Tipo</label>
            <!--directiva de livewire para relacionar el select con una propiedad publica-->
            <select wire:model="type" class="form-control">
                <option value="Elegir">Elegir</option>  <!--opcion por defecto en el select-->
                <option value="billete">billete</option>    <!--opciones del select-->
                <option value="moneda">moneda</option>  <!--opciones del select-->
                <option value="otro">otro</option>  <!--opciones del select-->
            </select>
            @error('type')  <!--directiva de blade para capturar el error al validar la propiedad publica-->
                <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <label>Valor</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-edit">

                    </span>
                </span>
            </div>
            <!--directiva de livewire para relacionar el input con una propiedad publica para enviar la informacion al backend solo cuando se pierde el foco de este campo-->
            <input type="text" wire:model.lazy="value" class="form-control" placeholder="Ingrese un valor..." maxlength="25">
        </div>
        @error('value') <!--directiva de blade para capturar el error al validar la propiedad publica-->
            <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
        @enderror
    </div>
    <div class="col-sm-12">
        <div class="form-group custom-file">
            <!--directiva de livewire para relacionar el input con una propiedad publica y especificando el tipo de archivos aceptados-->
            <input type="file" class="custom-file-input form-control" wire:model="image" accept="image/x-png, image/gif, image/jpeg">
            <label class="custom-file-label">IMAGEN {{ $image }}</label>
            @error('image') <!--directiva de blade para capturar el error al validar la propiedad publica-->
            <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
            @enderror
        </div>
    </div>
</div>

@include('common.modalFooter')  <!--inclucion del footer del modal-->