<div wire:ignore.self class="modal fade" id="theModal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white text-uppercase">
                    <b>{{ $selected_id > 0 ? 'editar' : 'crear'}} | {{$componentName}}</b>
                </h5>
                <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-9 col-md-9 col-lg-5">
                        <label><b>Contenedor*</b></label>
                        <div class="form-group">
                            <select wire:model="containerId" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @foreach ($allContainers as $container)
                                    <option value="{{$container->id}}">{{ $container->category_name }}-{{
                                        $container->subcategory_name }}-{{ $container->presentation_name }}</option>
                                @endforeach
                            </select>
                            @error('containerId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-3 col-md-3 col-lg-2">
                        <br>
                        <div class="form-group">
                            <button type="button" wire:click.prevent="ShowContainerModal()" class="btn btn-success" title="Nuevo Contenedor">Añadir Nuevo</button>
                        </div>
                    </div>
                    <div class="col-sm-9 col-md-9 col-lg-3">
                        <label><b>Marca*</b></label>
                        <div class="form-group">
                            <select wire:model="brandId" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @foreach ($allBrands as $brand)
                                <option value="{{$brand->id}}">{{$brand->name}}</option>
                                @endforeach
                            </select>
                            @error('brandId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-3 col-md-3 col-lg-2">
                        <br>
                        <div class="form-group">
                            <button type="button" wire:click.prevent="ShowBrandModal()" class="btn btn-success" title="Nueva Marca">Añadir Nuevo</button>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label><b>Detalles</b></label>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <span class="fas fa-edit"></span>
                                    </span>
                                </div>
                                <input type="text" wire:model.lazy="additional_info" class="form-control" placeholder="Informacion adicional...">
                            </div>
                            @error('additional_info')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label><b>Imagen</b></label>
                        <div class="form-group">
                            <input type="file" wire:model="image" id="{{$random_variable}}" class="custom-file form-control">
                            @error('image')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-4">
                        <label><b>Codigo de Barras*</b></label>
                        <div class="form-group">
                            <select wire:model="GenerateBarcode" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                <option value="0">no</option>
                                <option value="1">si</option>
                            </select>
                            @error('GenerateBarcode')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-4">
                        <label><b>Opciones de Codigo*</b></label>
                        <div class="form-group">
                            <select wire:model="CodeOptions" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                <option value="0">generar</option>
                                <option value="1">escanear</option>
                            </select>
                            @error('CodeOptions')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    @if(($CodeOptions == 1 && $selected_id < 1) || ($CodeOptions == 1 && $selected_id > 0))
                        <div class="col-sm-6 col-md-6 col-lg-4">
                            <label><b>Codigo de Producto*</b></label>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <span class="fas fa-edit"></span>
                                        </span>
                                    </div>
                                    <input type="text" wire:model.lazy="code" class="form-control component-name">
                                </div>
                                @error('code')
                                <span class="text-danger er">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                @if ($selected_id < 1) 
                    <button type="button" wire:click.prevent="Store()" class="btn btn-dark close-modal text-uppercase">guardar</button>
                @else
                    <button type="button" wire:click.prevent="Update()" class="btn btn-dark close-modal text-uppercase">editar</button>
                @endif
                <button type="button" wire:click.prevent="resetUI()" class="btn btn-dark close-btn text-info text-uppercase" data-dismiss="modal">cerrar</button>
            </div>
        </div>
    </div>
</div>