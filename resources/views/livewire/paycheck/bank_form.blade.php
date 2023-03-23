<div wire:ignore.self class="modal fade" id="bank_modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white text-uppercase">
                    <b>crear | banco</b>
                </h5>
                <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Nombre</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <span class="fas fa-edit"></span>
                                    </span>
                                </div>
                                <input type="text" wire:model.lazy="name" class="form-control component-name"
                                    placeholder="Nombre del banco...">
                            </div>
                            @error('name')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Alias</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <span class="fas fa-edit"></span>
                                    </span>
                                </div>
                                <input type="text" wire:model.lazy="alias" class="form-control"
                                    placeholder="Alias para el banco...">
                            </div>
                            @error('alias')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Codigo de Entidad</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <span class="fas fa-edit"></span>
                                    </span>
                                </div>
                                <input type="text" wire:model.lazy="entity_code" class="form-control"
                                    placeholder="1234">
                            </div>
                            @error('entity_code')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="StoreBank()"
                    class="btn btn-dark close-modal">GUARDAR</button>
                <button type="button" wire:click.prevent="CloseBankModal()"
                    class="btn btn-dark close-btn text-info" data-dismiss="modal">CERRAR</button>
            </div>
        </div>
    </div>
</div>