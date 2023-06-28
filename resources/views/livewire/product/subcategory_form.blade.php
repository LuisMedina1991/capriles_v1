<div wire:ignore.self class="modal fade" id="subcategory_modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white text-uppercase">
                    <b>crear | subcategorias</b>
                </h5>
                <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
            </div>
            <div class="modal-body">
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
                                <input type="text" wire:model.lazy="subcategory_name" class="form-control component-name"
                                    placeholder="Nombre para la subcategoria...">
                            </div>
                            @error('subcategory_name')
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
                                <select wire:model="categoryId" class="form-control text-uppercase">
                                    <option value="elegir">elegir</option>
                                    @foreach ($allCategories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                                @error('categoryId')
                                <span class="text-danger er">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-3">
                            <div class="form-group">
                                <button type="button" wire:click.prevent="ShowCategoryModal({{$modal_id}})" class="btn btn-success" title="Nueva Categoria">Añadir Nuevo</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="StoreSubcategory()"
                    class="btn btn-dark close-modal text-uppercase">guardar</button>
                <button type="button" wire:click.prevent="CloseSubcategoryModal()"
                    class="btn btn-dark close-btn text-info text-uppercase" data-dismiss="modal">cerrar</button>
            </div>
        </div>
    </div>
</div>