<div wire:ignore.self class="modal fade" id="edit_modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white text-uppercase">
                    <b>editar | {{$componentName}}</b>
                </h5>
                <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Categoria</b></label>
                            <select wire:model="categoryId" class="form-control text-uppercase" disabled>
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
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Subcategoria</b></label>
                            <select wire:model="subcategoryId" class="form-control text-uppercase" disabled>
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
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Presentacion</b></label>
                            <select wire:model="presentationId" class="form-control text-uppercase" disabled>
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
                @if($search_2 > 0)
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Estado</b></label>
                            <select wire:model="statusId" class="form-control text-uppercase">
                                <option value="Elegir">Elegir</option>
                                @foreach ($statuses as $status)
                                <option value="{{$status->id}}">{{$status->name}}</option>
                                @endforeach
                            </select>
                            @error('statusId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label><b>Informacion Adicional</b></label>
                            <input type="text" wire:model.lazy="additional_info" class="form-control"
                                placeholder="Otros detalles...">
                            @error('additional_info')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="Update()"
                    class="btn btn-dark close-modal text-uppercase">editar</button>
                <button type="button" wire:click.prevent="resetUI()"
                    class="btn btn-dark close-btn text-info text-uppercase" data-dismiss="modal">cerrar</button>
            </div>
        </div>
    </div>
</div>