<div wire:ignore.self class="modal fade" id="category_modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white text-uppercase">
                    <b>crear | categoria</b>
                </h5>
                <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <span class="fas fa-edit"></span>
                                </span>
                            </div>
                            <input type="text" wire:model.lazy="category_name" class="form-control component-name"
                                placeholder="Nombre para la categoria...">
                        </div>
                        @error('category_name')
                        <span class="text-danger er">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="StoreCategory({{$modal_id}})" class="btn btn-dark close-modal">GUARDAR</button>
                <button type="button" wire:click.prevent="CloseCategoryModal({{$modal_id}})" class="btn btn-dark close-btn text-info" data-dismiss="modal">CERRAR</button>
            </div>
        </div>
    </div>
</div>