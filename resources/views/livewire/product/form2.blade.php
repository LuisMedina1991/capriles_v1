<div wire:ignore.self class="modal fade" id="theModal2" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white">
                    <b>CREAR</b> | {{$componentName}}
                </h5>
                <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
            </div>
        <div class="modal-body">
            <div class="mb-2">
                <label>Prefijo</label>
                <div class="d-flex">
                    <div class="w-75">
                        <select wire:model="prefixId" class="form-control text-uppercase">
                            <option value="Elegir">Elegir</option>
                            @foreach ($allPrefixes as $prefix)
                            <option value="{{$prefix->id}}">{{$prefix->name}}</option>
                            @endforeach
                        </select>
                        @error('prefixId')
                        <span class="text-danger er">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="ml-auto">
                        <button type="button" wire:click.prevent="ShowPrefixModal()" class="btn btn-success">Añadir Prefijo</button>
                    </div>
                </div>
            </div>
            <div class="mb-2">
                <label>Marca</label>
                <div class="d-flex">
                    <div class="w-75">
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
                    <div class="ml-auto">
                        <button type="button" wire:click.prevent="ShowBrandModal()" class="btn btn-success">Añadir Marca</button>
                    </div>
                </div>
            </div>
            <div class="mb-2">
                <label>Contenedor</label>
                <div class="d-flex">
                    <div class="w-75">
                        <select wire:model="containerId" class="form-control text-uppercase">
                            <option value="Elegir">Elegir</option>
                            @foreach ($allContainers as $container)
                            <option value="{{$container->id}}">{{ $container->category }} - {{ $container->subcategory }} - {{ $container->presentation }}</option>
                            @endforeach
                        </select>
                        @error('containerId')
                        <span class="text-danger er">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="ml-auto">
                        <button type="button" wire:click.prevent="ShowBrandModal()" class="btn btn-success">Añadir Contenedor</button>
                    </div>
                </div>
            </div>
            <div class="mb-2">
                <div class="d-flex">
                    <div class="w-25">
                        <label>Estado</label>
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
                    <div class="w-75 ml-4">
                        <label>Comentarios</label>
                        <input type="text" wire:model.lazy="comment" class="form-control" placeholder="Informacion adicional...">
                        @error('comment')
                            <span class="text-danger er">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            @foreach($productValues as $index => $productValue)
            <div class="mb-2">
                <div class="d-flex">
                    <div class="w-25">
                        <label>Costo</label>
                        <input type="text" wire:model.lazy="productValues.{{$index}}.cost" class="form-control" placeholder="0.00">
                        @error('productValues.' . $index . '.cost')
                            <span class="text-danger er">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-25 ml-5">
                        <label>Precio</label>
                        <input type="text" wire:model.lazy="productValues.{{$index}}.price" class="form-control" placeholder="0.00">
                        @error('productValues.' . $index . '.price')
                            <span class="text-danger er">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-25 ml-5 mt-4">
                        <button type="button" wire:click.prevent="removeValue({{$index}})" class="btn btn-danger">- Quitar valores</button>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="mb-2">
                <div class="d-flex">
                    <div class="w-25">
                        <button type="button" wire:click.prevent="addValue" class="btn btn-success">+ Añadir valores</button>
                    </div>
                </div>
            </div>
            {{--<div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="table-th text-black text-center">COSTO</th>
                                <th class="table-th text-black text-center">PRECIO</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productValues as $index => $productValue)
                                <tr>
                                    <td>
                                        <input type="text" wire:model.lazy="productValues.{{$index}}.cost" class="form-control" placeholder="0.00">
                                        @error('productValues.' . $index . '.cost')
                                            <span class="text-danger er">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" wire:model.lazy="productValues.{{$index}}.price" class="form-control" placeholder="0.00">
                                        @error('productValues.' . $index . '.price')
                                            <span class="text-danger er">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td>
                                        <button type="button" wire:click.prevent="removeValue({{$index}})" class="btn btn-danger">- Quitar valores</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <div class="form-group">
                                <button type="button" wire:click.prevent="addValue" class="btn btn-success">+ Añadir valores</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>--}}
        </div>
        <div class="modal-footer">
            <button type="button" wire:click.prevent="Store()" class="btn btn-dark close-modal">GUARDAR</button>
            <button type="button" wire:click.prevent="resetUI()" class="btn btn-dark close-btn text-info" data-dismiss="modal">CERRAR</button>
        </div>
      </div>
    </div>
  </div>