@include('common.modalHead')    <!--inclucion del header del modal-->

<div class="row">
    <div class="col-sm-12">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <span class="fas fa-edit"></span>
                </span>
            </div>
            <!--directiva de livewire para relacionar el input con una propiedad publica para enviar la informacion al backend solo cuando se pierde el foco de este campo-->
            <!--component-name es un metodo js creado en la vista para que al abrir el modal se seleccione automaticamente el campo name-->
            <input type="text" wire:model.lazy="name" class="form-control component-name" placeholder="Nombre de la categoria...">
        </div>
        @error('name')  <!--directiva de blade para capturar el error al validar la propiedad publica-->
            <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
        @enderror
    </div>
</div>

@include('common.modalFooter')  <!--inclucion del footer del modal-->