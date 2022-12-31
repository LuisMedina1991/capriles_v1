@include('common.modalHead')    <!--inclucion del header del modal-->

<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label>Nombre</label>
            <!--directiva de livewire para relacionar el input con una propiedad publica para enviar la informacion al backend solo cuando se pierde el foco de este campo-->
            <!--component-name es un metodo js creado en la vista para que al abrir el modal se seleccione automaticamente el campo name-->
            <input type="text" wire:model.lazy="name" class="form-control component-name" placeholder="Nombre de la sucursal...">
        </div>
        @error('name')  <!--directiva de blade para capturar el error al validar la propiedad publica-->
            <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
        @enderror
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label>DIRECCION</label>
            <!--directiva de livewire para relacionar el input con una propiedad publica para enviar la informacion al backend solo cuando se pierde el foco de este campo-->
            <input type="text" wire:model.lazy="address" class="form-control" placeholder="Direccion de la sucursal...">
        </div>
        @error('address')  <!--directiva de blade para capturar el error al validar la propiedad publica-->
            <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
        @enderror
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label>TELEFONO</label>
            <!--directiva de livewire para relacionar el input con una propiedad publica para enviar la informacion al backend solo cuando se pierde el foco de este campo-->
            <input type="text" wire:model.lazy="phone" class="form-control" placeholder="Telefono de la sucursal...">
        </div>
        @error('phone')  <!--directiva de blade para capturar el error al validar la propiedad publica-->
            <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
        @enderror
    </div>
</div>

@include('common.modalFooter')  <!--inclucion del footer del modal-->