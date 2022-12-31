@include('common.modalHead')    <!--inclucion del header del modal-->

<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Producto</label>
            <!--directiva de livewire para relacionar el select con una propiedad publica-->
            <select wire:model="id_product" class="form-control">
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
            <label>Sucursal</label>
            <!--directiva de livewire para relacionar el select con una propiedad publica-->
            <select wire:model="id_office" class="form-control">
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
            <label>Inv. Minimo</label>
            <!--directiva de livewire para relacionar el input con una propiedad publica para enviar la informacion al backend solo cuando se pierde el foco de este campo-->
            <input type="number" wire:model.lazy="alerts" class="form-control" placeholder="0">
            @error('alerts')    <!--directiva de blade para capturar el error al validar la propiedad publica-->
                <span class="text-danger er">{{ $message }}</span>  <!--mensaje recibido desde el controlador-->
            @enderror
        </div>
    </div>
</div>

@include('common.modalFooter')  <!--inclucion del footer del modal-->