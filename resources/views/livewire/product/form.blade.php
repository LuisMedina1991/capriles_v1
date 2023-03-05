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
                    @if($search_2 > 0 && $selected_id > 0)
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label><b>Contenedor</b></label>
                            <select wire:model="containerId" class="form-control text-uppercase">
                                <option value="Elegir">Elegir</option>
                                @foreach ($allContainers_2 as $container)
                                <option value="{{$container->id}}">{{ $container->subcategory->category->name }} - {{
                                    $container->subcategory->name }} - {{ $container->presentation->name }}</option>
                                @endforeach
                            </select>
                            @error('containerId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Prefijo</b></label>
                            <select wire:model="containerId" class="form-control text-uppercase" disabled>
                                <option value="Elegir">Elegir</option>
                                @foreach ($allContainers_2 as $container)
                                <option value="{{$container->id}}">{{ $container->prefix }}</option>
                                @endforeach
                            </select>
                            @error('containerId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    @else
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label><b>Contenedor</b></label>
                            <select wire:model="containerId" class="form-control text-uppercase">
                                <option value="Elegir">Elegir</option>
                                @foreach ($allContainers as $container)
                                <option value="{{$container->id}}">{{ $container->subcategory->category->name }} - {{
                                    $container->subcategory->name }} - {{ $container->presentation->name }}</option>
                                @endforeach
                            </select>
                            @error('containerId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Prefijo</b></label>
                            <select wire:model="containerId" class="form-control text-uppercase" disabled>
                                <option value="Elegir">Elegir</option>
                                @foreach ($allContainers as $container)
                                <option value="{{$container->id}}">{{ $container->prefix }}</option>
                                @endforeach
                            </select>
                            @error('containerId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label><b>Marca</b></label>
                            <select wire:model="brandId" class="form-control text-uppercase">
                                <option value="Elegir">Elegir</option>
                                @foreach ($allBrands as $brand)
                                <option value="{{$brand->id}}">{{$brand->name}}</option>
                                @endforeach
                            </select>
                            @error('brandId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-2 mt-4">
                        <div class="form-group">
                            <button type="button" wire:click.prevent="ShowBrandModal()" class="btn btn-success"
                                title="Nueva Marca">Añadir Nuevo</button>
                        </div>
                    </div>
                    @if($search_2 > 0 && $selected_id > 0)
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label><b>Estado</b></label>
                            <select wire:model="statusId" class="form-control text-uppercase">
                                <option value="Elegir">Elegir</option>
                                @foreach ($allStatuses as $status)
                                <option value="{{$status->id}}">{{$status->name}}</option>
                                @endforeach
                            </select>
                            @error('statusId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label><b>Comentarios</b></label>
                            <input type="text" wire:model.lazy="comment" class="form-control"
                                placeholder="Informacion adicional...">
                            @error('comment')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                @foreach($productValues as $index => $productValue)
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Costo</b></label>
                            <input type="text" wire:model.lazy="productValues.{{$index}}.cost" class="form-control"
                                placeholder="0.00">
                            @error('productValues.' . $index . '.cost')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Precio</b></label>
                            <input type="text" wire:model.lazy="productValues.{{$index}}.price" class="form-control"
                                placeholder="0.00">
                            @error('productValues.' . $index . '.price')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    @if($productValue['is_saved'])
                    <div class="col-sm-2 mt-4">
                        <div class="form-group">
                            <button type="button" wire:click.prevent="editValue({{$index}})" class="btn btn-info" title="Editar Fila">Editar</button>
                        </div>
                    </div>
                    @else
                    <div class="col-sm-2 mt-4">
                        <div class="form-group">
                            <button type="button" wire:click.prevent="saveValue({{$index}})" class="btn btn-success" title="Guardar Fila">Listo</button>
                        </div>
                    </div>
                    @endif
                    <div class="col-sm-2 mt-4">
                        <div class="form-group">
                            <button type="button" wire:click.prevent="removeValue({{$index}})" class="btn btn-danger" title="Eliminar Fila">Quitar</button>
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <button type="button" wire:click.prevent="addValue" class="btn btn-success">+ Añadir
                                valor</button>
                        </div>
                    </div>
                </div>
                {{--<div class="col-sm-12">
                    <div class="form-group custom-file">
                        <!--directiva de livewire para relacionar el input con una propiedad publica y especificando el tipo de archivos aceptados-->
                        <input type="file" class="custom-file-input form-control" wire:model="image"
                            accept="image/x-png, image/gif, image/jpeg">
                        <label class="custom-file-label">IMAGEN {{ $image }}</label>
                        @error('image')
                        <!--directiva de blade para capturar el error al validar la propiedad publica-->
                        <span class="text-danger er">{{ $message }}</span>
                        <!--mensaje recibido desde el controlador-->
                        @enderror
                    </div>
                </div>--}}
            </div>
            <div class="modal-footer">
                @if ($selected_id < 1) <button type="button" wire:click.prevent="Store()"
                    class="btn btn-dark close-modal text-uppercase">guardar</button>
                    @else
                    <button type="button" wire:click.prevent="Update()"
                        class="btn btn-dark close-modal text-uppercase">editar</button>
                    @endif
                    <button type="button" wire:click.prevent="resetUI()"
                        class="btn btn-dark close-btn text-info text-uppercase" data-dismiss="modal">cerrar</button>
            </div>
        </div>
    </div>
</div>