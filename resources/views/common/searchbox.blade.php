{{--<div class="row justify-content-between">
    <div class="col-sm-3">
        <div class="input-group mb-4">
            <div class="input-group-prepend">
                <span class="input-group-text input-gp">
                    <i class="fas fa-search"></i>
                </span>
            </div>
            <input type="text" wire:model="search" placeholder="BUSCAR..." class="form-control">
        </div>
    </div>
</div>--}}

<div class="row">
    <div class="col-sm-12 col-md-3 col-lg-3">
        <h6><b>Filtro de busqueda</b></h6>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text input-gp">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
                <input type="text" wire:model="search" placeholder="BUSCAR..." class="form-control">
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-3 col-lg-3">
        <h6><b>Estado del registro</b></h6>
        <div class="form-group">
            <select wire:model="search_2" class="form-control">
                <option value="0">Activo</option>
                <option value="1">Bloqueado</option>
            </select>
        </div>
    </div>
</div>