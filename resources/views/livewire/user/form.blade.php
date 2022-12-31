@include('common.modalHead')

<div class="row">
    <div class="col-sm-12 col-md-8">
        <div class="form-group">
            <label>Nombre</label>
            <!--directiva de livewire para relacionar el input con una propiedad publica para enviar la informacion al backend solo cuando se pierde el foco de este campo-->
            <!--component-name es un metodo js creado en la vista para que al abrir el modal se seleccione automaticamente el campo name-->
            <input type="text" wire:model.lazy="name" class="form-control component-name" placeholder="Nombre de usuario...">
            @error('name')  <!--directiva de blade para capturar el error al validar la propiedad publica-->
                <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Telefono</label>
            <!--directiva de livewire para relacionar el input con una propiedad publica para enviar la informacion al backend solo cuando se pierde el foco de este campo-->
            <input type="text" wire:model.lazy="phone" class="form-control" maxlength="8">
            @error('phone') <!--directiva de blade para capturar el error al validar la propiedad publica-->
                <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Email</label>
            <!--directiva de livewire para relacionar el input con una propiedad publica para enviar la informacion al backend solo cuando se pierde el foco de este campo-->
            <input type="text" wire:model.lazy="email" class="form-control" placeholder="correo@ejemplo.com">
            @error('email') <!--directiva de blade para capturar el error al validar la propiedad publica-->
                <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Contraseña</label>
            <!--directiva de livewire para relacionar el input con una propiedad publica para enviar la informacion al backend solo cuando se pierde el foco de este campo-->
            <input type="password" wire:model.lazy="password" class="form-control" placeholder="********">
            @error('password')  <!--directiva de blade para capturar el error al validar la propiedad publica-->
                <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Estado</label>
            <!--directiva de livewire para relacionar el select con una propiedad publica para enviar la informacion al backend solo cuando se pierde el foco de este campo-->
            <select wire:model.lazy="status" class="form-control">
                <option value="Elegir" selected>Elegir</option> <!--opcion por defecto-->
                <option value="active">Activo</option>
                <option value="locked">Bloqueado</option>
            </select>
            @error('status')    <!--directiva de blade para capturar el error al validar la propiedad publica-->
                <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Rol</label>
            <!--directiva de livewire para relacionar el select con una propiedad publica para enviar la informacion al backend solo cuando se pierde el foco de este campo-->
            <select wire:model.lazy="profile" class="form-control">
                <option value="Elegir" selected>Elegir</option> <!--opcion por defecto-->
                @foreach ($roles as $role)  <!--iteracion para obtener todos los roles-->
                    <option value="{{$role->name}}">{{$role->name}}</option>
                @endforeach
            </select>
            @error('profile')   <!--directiva de blade para capturar el error al validar la propiedad publica-->
                <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Imagen</label>
            <!--directiva de livewire para relacionar el input con una propiedad publica y especificando el tipo de archivos aceptados-->
            <input type="file" wire:model="image" accept="image/x-png, image/jpeg, image/gif" class="form-control">
            @error('image') <!--directiva de blade para capturar el error al validar la propiedad publica-->
                <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
            @enderror
        </div>
    </div>
</div>

@include('common.modalFooter')  <!--inclucion del footer del modal-->