<div wire:ignore.self class="modal fade" id="subcategory_modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white text-uppercase">
                    <b>crear | subcategoria</b>
                </h5>
                <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label><b>Subcategoria</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <span class="fas fa-edit"></span>
                                    </span>
                                </div>
                                <input type="text" wire:model.lazy="subcategory_name" class="form-control component-name"
                                    placeholder="Nombre para la subcategoria...">
                            </div>
                        </div>
                        @error('subcategory_name')
                        <span class="text-danger er">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div>
                    <label><b>Categoria</b></label>
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
                            <button type="button" wire:click.prevent="ShowCategoryModal({{$modal_id}})" class="btn btn-success">Añadir Categoria</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="StoreSubcategory()"
                    class="btn btn-dark close-modal">GUARDAR</button>
                <button type="button" wire:click.prevent="CloseSubcategoryModal()"
                    class="btn btn-dark close-btn text-info" data-dismiss="modal">CERRAR</button>
            </div>
        </div>
    </div>
</div>