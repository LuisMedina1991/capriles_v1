@include('common.modalHead')

    <div class="row">
        <div class="col-sm-12">
            <label><b>Nombre*</b></label>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <span class="fas fa-edit"></span>
                        </span>
                    </div>
                    <input type="text" wire:model.lazy="name" class="form-control component-name"
                        placeholder="Nombre para la subcategoria...">
                </div>
                @error('name')
                <span class="text-danger er">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div>
        <label><b>Categoria*</b></label>
        <div class="row">
            <div class="col-sm-12 col-md-9 col-lg-9">
                <div class="form-group">
                    <select wire:model="category_id" class="form-control text-uppercase">
                        <option value="elegir">elegir</option>
                        @foreach ($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <span class="text-danger er">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-sm-12 col-md-3 col-lg-3">
                <div class="form-group">
                    <button type="button" wire:click.prevent="ShowCategoryModal()" class="btn btn-success" title="Nueva Categoria">Añadir Nuevo</button>
                </div>
            </div>
        </div>
    </div>

    {{--<div>
        <label><b>Categoria</b></label>
        <div class="d-flex">
            <div class="w-75">
                <select wire:model="category_id" class="form-control text-uppercase">
                    <option value="elegir">Elegir</option>
                    @foreach ($categories as $category)
                    <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>
                @error('category_id')
                <span class="text-danger er">{{ $message }}</span>
                @enderror
            </div>
            <div class="ml-auto">
                <button type="button" wire:click.prevent="ShowCategoryModal()" class="btn btn-success">Añadir Categoria</button>
            </div>
        </div>
    </div>--}}

@include('common.modalFooter')