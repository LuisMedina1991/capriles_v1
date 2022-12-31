@include('common.modalHead')    <!--inclucion del header del modal-->

<div class="row">
    <div class="col-sm-12 col-md-6">
        <label>Nombre</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-edit"></span>
                </span>
            </div>
            <!--directiva de livewire para relacionar el input con una propiedad publica para enviar la informacion al backend solo cuando se pierde el foco de este campo-->
            <!--component-name es un metodo js creado en la vista para que al abrir el modal se seleccione automaticamente el campo name-->
            <input type="text" wire:model.lazy="name" class="form-control component-name" placeholder="Nombre del estado...">
        </div>
        @error('name')  <!--directiva de blade para capturar el error al validar la propiedad publica-->
            <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
        @enderror
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Tipo</label>
            <!--directiva de livewire para relacionar el select con una propiedad publica-->
            <select wire:model="type" class="form-control text-uppercase">
                <option value="Elegir">Elegir</option>  <!--opcion por defecto en el select-->
                <option value="product">productos</option>    <!--opciones del select-->
                <option value="record">registros</option>  <!--opciones del select-->
                <option value="sale">ventas</option>  <!--opciones del select-->
            </select>
            @error('type')  <!--directiva de blade para capturar el error al validar la propiedad publica-->
                <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
            @enderror
        </div>
    </div>
</div>

@include('common.modalFooter')  <!--inclucion del footer del modal-->