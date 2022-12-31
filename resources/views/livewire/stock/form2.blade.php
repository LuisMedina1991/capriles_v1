<div wire:ignore.self class="modal fade" id="theModal2" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h5 class="modal-title text-white">
            <b>{{$componentName}}</b> | {{ $formTitle}}  {{--nombre y titulo dinamico de componente--}}
          </h5>
          <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <label>Producto</label>
                        <!--directiva de livewire para relacionar el select con una propiedad publica-->
                        <select wire:model="id_product" class="form-control" disabled>
                            <option value="Elegir">Elegir</option>
                            @foreach ($products as $product) <!--iteracion para obtener todas las categorias-->
                                <option value="{{$product->id}}">{{$product->code}}</option>  <!--se obtiene el nombre de las categorias a traves de su id-->
                            @endforeach
                        </select>
                        @error('id_product')    <!--directiva de blade para capturar el error al validar la propiedad publica-->
                            <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
                        @enderror
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <label>Sucursal Origen</label>
                        <!--directiva de livewire para relacionar el select con una propiedad publica-->
                        <select wire:model="id_office" class="form-control" disabled>
                            <option value="Elegir">Elegir</option>
                            @foreach ($offices as $office) <!--iteracion para obtener todas las categorias-->
                                <option value="{{$office->id}}">{{$office->name}}</option>  <!--se obtiene el nombre de las categorias a traves de su id-->
                            @endforeach
                        </select>
                        @error('id_office')    <!--directiva de blade para capturar el error al validar la propiedad publica-->
                            <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
                        @enderror
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <label>Sucursal Destino</label>
                        <!--directiva de livewire para relacionar el select con una propiedad publica-->
                        <select wire:model="id_office2" class="form-control">
                            <option value="Elegir">Elegir</option>
                            @foreach ($offices2 as $office) <!--iteracion para obtener todas las categorias-->
                                <option value="{{$office->id}}">{{$office->name}}</option>  <!--se obtiene el nombre de las categorias a traves de su id-->
                            @endforeach
                        </select>
                        @error('id_office2')    <!--directiva de blade para capturar el error al validar la propiedad publica-->
                            <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
                        @enderror
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <label>STOCK</label>
                        <!--directiva de livewire para relacionar el input con una propiedad publica para enviar la informacion al backend solo cuando se pierde el foco de este campo-->
                        <input type="text" wire:model.lazy="cant" class="form-control" placeholder="Ingrese el stock actual...">
                    </div>
                    @error('cant')  <!--directiva de blade para capturar el error al validar la propiedad publica-->
                        <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
                    @enderror
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        <label>Cant. Traspaso</label>
                        <!--directiva de livewire para relacionar el input con una propiedad publica para enviar la informacion al backend solo cuando se pierde el foco de este campo-->
                        <input type="number" wire:model.lazy="cant2" class="form-control" placeholder="0">
                        @error('cant2')    <!--directiva de blade para capturar el error al validar la propiedad publica-->
                            <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
                        @enderror
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <!--directiva de livewire que hace llamado a metodo del controlador-->
            <button type="button" wire:click.prevent="resetUI()" class="btn btn-dark close-btn text-info" data-dismiss="modal">CERRAR</button>
            <!--directiva de livewire que hace llamado a metodo del controlador-->
            <button type="button" wire:click.prevent="Transfer()" class="btn btn-dark close-modal">GUARDAR</button>
        </div>
    </div>
</div>
</div>