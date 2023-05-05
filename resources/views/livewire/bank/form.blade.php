@include('common.modalHead')
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label><b>Nombre</b></label>
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
    <div class="col-sm-6">
        <div class="form-group">
            <label><b>Alias</b></label>
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
    <div class="col-sm-6">
        <div class="form-group">
            <label><b>Codigo de Entidad</b></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <span class="fas fa-edit"></span>
                    </span>
                </div>
                <input type="number" wire:model.lazy="entity_code" class="form-control" placeholder="1234">
            </div>
            @error('entity_code')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    @if($selected_id > 0 && $search_2 > 0)

        <div class="col-sm-6">
            <div class="form-group">
                <label><b>Estado del registro</b></label>
                <select wire:model="status_id" class="form-control text-uppercase">
                    <option value="elegir">elegir</option>
                    <option value="1">activo</option>
                    <option value="2">bloqueado</option>
                </select>
                @error('status_id')
                <span class="text-danger er">{{ $message }}</span>
                @enderror
            </div>
        </div>

    @endif

</div>
@include('common.modalFooter')