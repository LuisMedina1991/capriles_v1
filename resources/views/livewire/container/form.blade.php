@include('common.modalHead')

    <div class="row">

        <div class="col-sm-9 col-md-9 col-lg-4">
            <label><b>Categoria*</b></label>
            <div class="form-group">
                <select wire:model="categoryId" class="form-control text-uppercase">
                    <option value="elegir">elegir</option>
                    @foreach ($categories as $category)
                    <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>
                @error('categoryId')
                <span class="text-danger er">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-sm-3 col-md-3 col-lg-2">
            <br>
            <div class="form-group">
                <button type="button" wire:click.prevent="ShowCategoryModal({{$modal_id}})" class="btn btn-success" title="Nueva Categoria">Añadir Nuevo</button>
            </div>
        </div>

        <div class="col-sm-9 col-md-9 col-lg-4">
            <label><b>Subcategoria*</b></label>
            <div class="form-group">
                <select wire:model="subcategoryId" class="form-control text-uppercase">
                    <option value="elegir">elegir</option>
                    @if($categoryId != 'elegir')
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

        <div class="col-sm-3 col-md-3 col-lg-2">
            <br>
            <div class="form-group">
                <button type="button" wire:click.prevent="ShowSubcategoryModal()" class="btn btn-success" title="Nueva Subcategoria">Añadir Nuevo</button>
            </div>
        </div>

        <div class="col-sm-9 col-md-9 col-lg-4">
            <label><b>Presentacion*</b></label>
            <div class="form-group">
                <select wire:model="presentationId" class="form-control text-uppercase">
                    <option value="elegir">elegir</option>
                    @foreach ($presentations as $presentation)
                    <option value="{{$presentation->id}}">{{$presentation->name}}</option>
                    @endforeach
                </select>
                @error('presentationId')
                <span class="text-danger er">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-sm-3 col-md-3 col-lg-2">
            <br>
            <div class="form-group">
                <button type="button" wire:click.prevent="ShowPresentationModal()" class="btn btn-success" title="Nueva Presentacion">Añadir Nuevo</button>
            </div>
        </div>

    </div>

@include('common.modalFooter')