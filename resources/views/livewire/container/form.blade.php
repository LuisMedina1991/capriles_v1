@include('common.modalHead')

<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label><b>Categoria</b></label>
            <select wire:model="categoryId" class="form-control text-uppercase">
                <option value="Elegir">Elegir</option>
                @foreach ($categories as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
            </select>
            @error('categoryId')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-2 mt-4">
        <button type="button" wire:click.prevent="ShowCategoryModal({{$modal_id}})" class="btn btn-success"
            title="Nueva Categoria">Añadir Nuevo</button>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label><b>Subcategoria</b></label>
            <select wire:model="subcategoryId" class="form-control text-uppercase">
                <option value="Elegir">Elegir</option>
                @if($categoryId != 'Elegir')
                @foreach ($subcategories as $subcategory)
                <option value="{{$subcategory->id}}">{{$subcategory->name}}</option>
                @endforeach
                @endif
            </select>
            @error('subcategoryId')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-2 mt-4">
        <button type="button" wire:click.prevent="ShowSubcategoryModal()" class="btn btn-success"
            title="Nueva Subcategoria">Añadir Nuevo</button>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label><b>Presentacion</b></label>
            <select wire:model="presentationId" class="form-control text-uppercase">
                <option value="Elegir">Elegir</option>
                @foreach ($presentations as $presentation)
                <option value="{{$presentation->id}}">{{$presentation->name}}</option>
                @endforeach
            </select>
            @error('presentationId')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-2 mt-4">
        <button type="button" wire:click.prevent="ShowPresentationModal()" class="btn btn-success"
            title="Nueva Presentacion">Añadir Nuevo</button>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label><b>Prefijo</b></label>
            <input type="text" wire:model.lazy="prefix" class="form-control"
                placeholder="Prefijo para el contenedor...">
            @error('prefix')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label><b>Informacion Adicional</b></label>
            <input type="text" wire:model.lazy="additional_info" class="form-control" placeholder="Otros detalles...">
            @error('additional_info')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

{{--<div class="mb-2">
    <label>Categoria</label>
    <div class="d-flex">
        <div class="w-75">
            <select wire:model="categoryId" class="form-control text-uppercase">
                <option value="Elegir">Elegir</option>
                @foreach ($categories as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
            </select>
            @error('categoryId')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
        <div class="ml-auto">
            <button type="button" id="1" value="1" wire:click.prevent="ShowCategoryModal( $('#1').val() )"
                class="btn btn-success">Añadir Categoria</button>
        </div>
    </div>
</div>
<div class="mb-2">
    <label>Subcategoria</label>
    <div class="d-flex">
        <div class="w-75">
            <select wire:model="subcategoryId" class="form-control text-uppercase">
                <option value="Elegir">Elegir</option>
                @if($categoryId != 'Elegir')
                @foreach ($subcategories as $subcategory)
                <option value="{{$subcategory->id}}">{{$subcategory->name}}</option>
                @endforeach
                @endif
            </select>
            @error('subcategoryId')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
        <div class="ml-auto">
            <button type="button" wire:click.prevent="ShowSubcategoryModal()" class="btn btn-success">Añadir
                Subcategoria</button>
        </div>
    </div>
</div>
<div class="mb-2">
    <label>Presentacion</label>
    <div class="d-flex">
        <div class="w-75">
            <select wire:model="presentationId" class="form-control text-uppercase">
                <option value="Elegir">Elegir</option>
                @foreach ($presentations as $presentation)
                <option value="{{$presentation->id}}">{{$presentation->name}}</option>
                @endforeach
            </select>
            @error('presentationId')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
        <div class="ml-auto">
            <button type="button" wire:click.prevent="ShowPresentationModal()" class="btn btn-success">Añadir
                Presentacion</button>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label>Informacion Adicional</label>
            <textarea wire:model.lazy="additional_info" class="form-control component-name"
                placeholder="Otros detalles del contenedor..." cols="30" rows="3"></textarea>
            @error('additional_info')
            <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>--}}

@include('common.modalFooter')