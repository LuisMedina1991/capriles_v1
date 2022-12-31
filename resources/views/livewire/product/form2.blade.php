<div wire:ignore.self class="modal fade" id="theModal2" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white">
                    <b>ACTUALIZAR</b> |  {{$componentName}} {{--nombre y titulo dinamico de componente--}}
                </h5>
                <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-4">
                        <div class="form-group">
                            <label>Categoria</label>
                            <!--directiva de livewire para relacionar el select con una propiedad publica-->
                            <select wire:model="cat_id" class="form-control">
                                <option value="Elegir">Elegir</option>
                                @foreach ($categories as $category) <!--iteracion para obtener todas las categorias-->
                                    <option value="{{$category->id}}">{{$category->name}}</option>  <!--se obtiene el nombre de las categorias a traves de su id-->
                                @endforeach
                            </select>
                            @error('cat_id')    <!--directiva de blade para capturar el error al validar la propiedad publica-->
                                <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="form-group">
                            <label>Subcategoria</label>
                            <!--directiva de livewire para relacionar el select con una propiedad publica-->
                            <select wire:model="sub_id" class="form-control">
                                <option value="Elegir" disabled>Elegir</option>
                                @foreach ($subcategories as $subcategory) <!--iteracion para obtener todas las categorias-->
                                    <option value="{{$subcategory->id}}">{{$subcategory->name}}</option>  <!--se obtiene el nombre de las categorias a traves de su id-->
                                @endforeach
                            </select>
                            @error('sub_id')    <!--directiva de blade para capturar el error al validar la propiedad publica-->
                                <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="form-group">
                            <label>Estado</label>
                            <!--directiva de livewire para relacionar el select con una propiedad publica-->
                            <select wire:model="status" class="form-control">
                                <option value="Elegir" disabled>Elegir</option>
                                @foreach ($statuses as $status) <!--iteracion para obtener todas las categorias-->
                                    <option value="{{$status->id}}">{{$status->name}}</option>  <!--se obtiene el nombre de las categorias a traves de su id-->
                                @endforeach
                            </select>
                            @error('status')    <!--directiva de blade para capturar el error al validar la propiedad publica-->
                                <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="form-group">
                            <label>Descripcion</label>
                            <!--directiva de livewire para relacionar el input con una propiedad publica para enviar la informacion al backend solo cuando se pierde el foco de este campo-->
                            <!--component-name es un metodo js creado en la vista para que al abrir el modal se seleccione automaticamente el campo name-->
                            <input type="text" wire:model.lazy="description" class="form-control component-name" placeholder="Descripcion del producto...">
                            @error('description')  <!--directiva de blade para capturar el error al validar la propiedad publica-->
                                <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="form-group">
                            <label>Codigo</label>
                            <!--directiva de livewire para relacionar el input con una propiedad publica para enviar la informacion al backend solo cuando se pierde el foco de este campo-->
                            <input type="text" wire:model.lazy="code" class="form-control" placeholder="Codigo del producto...">
                            @error('code')   <!--directiva de blade para capturar el error al validar la propiedad publica-->
                                <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="form-group">
                            <label>Marca</label>
                            <!--directiva de livewire para relacionar el input con una propiedad publica para enviar la informacion al backend solo cuando se pierde el foco de este campo-->
                            <input type="text" wire:model.lazy="brand" class="form-control" placeholder="Marca del producto...">
                            @error('brand')   <!--directiva de blade para capturar el error al validar la propiedad publica-->
                                <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
                            @enderror
                        </div>
                    </div>

                    @if($cat_id == 1)

                    <div class="col-sm-12 col-md-4">
                        <div class="form-group">
                            <label>Aro</label>
                            <!--directiva de livewire para relacionar el input con una propiedad publica para enviar la informacion al backend solo cuando se pierde el foco de este campo-->
                            <input type="text" wire:model.lazy="ring" class="form-control" placeholder="Aro del producto...">
                            @error('ring')   <!--directiva de blade para capturar el error al validar la propiedad publica-->
                                <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="form-group">
                            <label>Trilla</label>
                            <!--directiva de livewire para relacionar el input con una propiedad publica para enviar la informacion al backend solo cuando se pierde el foco de este campo-->
                            <input type="text" wire:model.lazy="threshing" class="form-control" placeholder="Trilla del producto...">
                            @error('threshing')   <!--directiva de blade para capturar el error al validar la propiedad publica-->
                                <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="form-group">
                            <label>Lona</label>
                            <!--directiva de livewire para relacionar el input con una propiedad publica para enviar la informacion al backend solo cuando se pierde el foco de este campo-->
                            <input type="text" wire:model.lazy="tarp" class="form-control" placeholder="Lona del producto...">
                            @error('tarp')   <!--directiva de blade para capturar el error al validar la propiedad publica-->
                                <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
                            @enderror
                        </div>
                    </div>

                    @endif
                    
                    <div class="col-sm-12 col-md-4">
                        <div class="form-group">
                            <label>Comentario</label>
                            <!--directiva de livewire para relacionar el input con una propiedad publica para enviar la informacion al backend solo cuando se pierde el foco de este campo-->
                            <input type="text" wire:model.lazy="comment" class="form-control" placeholder="Informacion adicional...">
                            @error('comment')   <!--directiva de blade para capturar el error al validar la propiedad publica-->
                                <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <div class="form-group">
                            <label>Costo</label>
                            <!--directiva de livewire para relacionar el select con una propiedad publica-->
                            <select wire:model="value" class="form-control">
                                @foreach ($products as $product)
                                @foreach ($product->values as $value)
                                    <option value="{{$value->id}}">${{number_format($value->cost,2)}}</option>  <!--se obtiene el nombre de las categorias a traves de su id-->
                                @endforeach
                                @endforeach
                            </select>
                            @error('value')    <!--directiva de blade para capturar el error al validar la propiedad publica-->
                                <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
                            @enderror
                        </div>
                    </div>
                    {{--<div class="col-sm-12 col-md-4">
                        <div class="form-group">
                            <label>Precio</label>
                            <!--directiva de livewire para relacionar el select con una propiedad publica-->
                            <select wire:model="price2" class="form-control">
                                @foreach ($values as $val) <!--iteracion para obtener todas las categorias-->
                                    <option value="{{$val->id}}">{{$status->price}}</option>  <!--se obtiene el nombre de las categorias a traves de su id-->
                                @endforeach
                            </select>
                            @error('price2')    <!--directiva de blade para capturar el error al validar la propiedad publica-->
                                <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
                            @enderror
                        </div>
                    </div>--}}
                </div>
            </div>
            <div class="modal-footer">
            <!--directiva de livewire que hace llamado a metodo del controlador-->
            <button type="button" wire:click.prevent="resetUI()" class="btn btn-dark close-btn text-info" data-dismiss="modal">CERRAR</button>
                <!--directiva de livewire que hace llamado a metodo del controlador-->
                <button type="button" wire:click.prevent="Update()" class="btn btn-dark close-modal">ACTUALIZAR</button>
            </div>
        </div>
    </div>
</div>