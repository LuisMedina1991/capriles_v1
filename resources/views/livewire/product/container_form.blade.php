<div wire:ignore.self class="modal fade" id="container_modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white text-uppercase">
                    <b>crear | contenedores</b>
                </h5>
                <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-sm-9 col-md-9 col-lg-4">
                        <label><b>Categoria*</b></label>
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
            
                    <div class="col-sm-3 col-md-3 col-lg-2">
                        <br>
                        <div class="form-group">
                            <button type="button" class="btn btn-success" title="Nueva Categoria">Añadir Nuevo</button>
                        </div>
                    </div>
            
                    <div class="col-sm-9 col-md-9 col-lg-4">
                        <label><b>Subcategoria*</b></label>
                        <div class="form-group">
                            <select wire:model="subcategoryId" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @if($categoryId != 'elegir')
                                @foreach ($allSubcategories as $subcategory)
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
                            <button type="button" class="btn btn-success" title="Nueva Subcategoria">Añadir Nuevo</button>
                        </div>
                    </div>
            
                    <div class="col-sm-9 col-md-9 col-lg-4">
                        <label><b>Presentacion*</b></label>
                        <div class="form-group">
                            <select wire:model="presentationId" class="form-control text-uppercase">
                                <option value="elegir">elegir</option>
                                @foreach ($allPresentations as $presentation)
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
                            <button type="button" class="btn btn-success" title="Nueva Presentacion">Añadir Nuevo</button>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="StoreContainer()" class="btn btn-dark close-modal text-uppercase">guardar</button>
                <button type="button" wire:click.prevent="CloseContainerModal()" class="btn btn-dark close-btn text-info text-uppercase" data-dismiss="modal">cerrar</button>
            </div>
        </div>
    </div>
</div>