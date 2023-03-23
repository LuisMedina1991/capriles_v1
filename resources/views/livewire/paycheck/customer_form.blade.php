<div wire:ignore.self class="modal fade" id="customer_modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white text-uppercase">
                    <b>crear | cliente</b>
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
                                    placeholder="Nombre del cliente...">
                            </div>
                            @error('name')
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
                                    placeholder="Telefono del cliente...">
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
                                    placeholder="Fax del cliente...">
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
                                    placeholder="Email del cliente...">
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
                                    placeholder="NIT del cliente...">
                            </div>
                            @error('nit')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Ciudad</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <span class="fas fa-edit"></span>
                                    </span>
                                </div>
                                <input type="text" wire:model.lazy="city" class="form-control"
                                    placeholder="Direccion del cliente...">
                            </div>
                            @error('city')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Pais</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <span class="fas fa-edit"></span>
                                    </span>
                                </div>
                                <input type="text" wire:model.lazy="country" class="form-control"
                                    placeholder="Pais del cliente...">
                            </div>
                            @error('country')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="StoreCustomer()"
                    class="btn btn-dark close-modal">GUARDAR</button>
                <button type="button" wire:click.prevent="CloseCustomerModal()"
                    class="btn btn-dark close-btn text-info" data-dismiss="modal">CERRAR</button>
            </div>
        </div>
    </div>
</div>