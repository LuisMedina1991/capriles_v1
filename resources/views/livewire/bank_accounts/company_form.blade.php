<div wire:ignore.self class="modal fade" id="company_modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white text-uppercase">
                    <b>crear | empresa</b>
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
                                    placeholder="Nombre de la empresa...">
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
                                    placeholder="Alias de la empresa...">
                            </div>
                            @error('alias')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Telefono</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <span class="fas fa-edit"></span>
                                    </span>
                                </div>
                                <input type="text" wire:model.lazy="phone" class="form-control"
                                    placeholder="Telefono de la empresa...">
                            </div>
                            @error('phone')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Fax</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <span class="fas fa-edit"></span>
                                    </span>
                                </div>
                                <input type="text" wire:model.lazy="fax" class="form-control"
                                    placeholder="Fax de la empresa...">
                            </div>
                            @error('fax')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Email</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <span class="fas fa-edit"></span>
                                    </span>
                                </div>
                                <input type="text" wire:model.lazy="email" class="form-control"
                                    placeholder="Email de la empresa...">
                            </div>
                            @error('email')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>NIT</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <span class="fas fa-edit"></span>
                                    </span>
                                </div>
                                <input type="text" wire:model.lazy="nit" class="form-control"
                                    placeholder="NIT de la empresa...">
                            </div>
                            @error('nit')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Direccion</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <span class="fas fa-edit"></span>
                                    </span>
                                </div>
                                <input type="text" wire:model.lazy="address" class="form-control"
                                    placeholder="Direccion de la empresa...">
                            </div>
                            @error('address')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="StoreCompany()"
                    class="btn btn-dark close-modal">GUARDAR</button>
                <button type="button" wire:click.prevent="CloseCompanyModal()"
                    class="btn btn-dark close-btn text-info" data-dismiss="modal">CERRAR</button>
            </div>
        </div>
    </div>
</div>