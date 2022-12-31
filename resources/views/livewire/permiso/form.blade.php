<div wire:ignore.self class="modal fade" id="theModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white">
                <!--nombre dinamico de componente-->
                <b>{{$componentName}}</b> | {{ $selected_id > 0 ? 'EDITAR' : 'CREAR'}}
                </h5>
                <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <span class="fas fa-edit">
                
                                    </span>
                                </span>
                            </div>
                            <!--directiva de livewire para relacionar el input con una propiedad publica para enviar la informacion al backend solo cuando se pierde el foco de este campo-->
                            <!--component-name es un metodo js creado en la vista para que al abrir el modal se seleccione automaticamente el campo name-->
                            <input type="text" wire:model.lazy="permissionName" class="form-control component-name" placeholder="Nombre de permiso..." maxlength="255">
                        </div>
                        @error('permissionName')    <!--directiva de blade para capturar el error al validar la propiedad publica-->
                            <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
                        @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!--directiva de livewire que hace llamado a metodo del controlador-->
                <button type="button" wire:click.prevent="resetUI()" class="btn btn-dark close-btn text-info" data-dismiss="modal">CERRAR</button>
                @if ($selected_id < 1)  <!--validar si registro ya existe para definir que boton mostrar-->
                    <!--directiva de livewire que hace llamado a metodo del controlador-->
                    <button type="button" wire:click.prevent="CreatePermission()" class="btn btn-dark close-modal">GUARDAR</button>
                @else
                    <!--directiva de livewire que hace llamado a metodo del controlador-->
                    <button type="button" wire:click.prevent="UpdatePermission()" class="btn btn-dark close-modal">ACTUALIZAR</button>
                @endif
            </div>
        </div>
    </div>
</div>