<div wire:ignore.self class="modal fade" id="account_modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white text-uppercase">
                    <b>crear | cuenta bancaria</b>
                </h5>
                <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Propietario</b></label>
                            <select wire:model="companyId" class="form-control text-uppercase">
                                <option value="Elegir">Elegir</option>
                                @foreach ($allCompanies as $company)
                                <option value="{{$company->id}}">{{$company->alias}}</option>
                                @endforeach
                            </select>
                            @error('companyId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-2 mt-4">
                        <div class="form-group">
                            <button type="button" wire:click.prevent="ShowCompanyModal()" class="btn btn-success"
                                title="Nueva Empresa">Añadir Nuevo</button>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><b>Banco</b></label>
                            <select wire:model="bankId" class="form-control text-uppercase">
                                <option value="Elegir">Elegir</option>
                                @foreach ($allBanks as $bank)
                                <option value="{{$bank->id}}">{{$bank->alias}}</option>
                                @endforeach
                            </select>
                            @error('bankId')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-2 mt-4">
                        <div class="form-group">
                            <button type="button" wire:click.prevent="ShowBankModal({{$modal_id}})" class="btn btn-success"
                                title="Nuevo Banco">Añadir Nuevo</button>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Tipo de Cuenta</b></label>
                            <select wire:model="type" class="form-control text-uppercase">
                                <option value="Elegir">Elegir</option>
                                <option value="caja de ahorros">caja de ahorros</option>
                                <option value="cuenta corriente">cuenta corriente</option>
                            </select>
                            @error('type')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Moneda de la Cuenta</b></label>
                            <select wire:model="currency" class="form-control text-uppercase">
                                <option value="Elegir">Elegir</option>
                                <option value="bolivianos">bolivianos</option>
                                <option value="dolares">dolares</option>
                            </select>
                            @error('currency')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Numero</b></label>
                            <input type="text" wire:model.lazy="number" class="form-control"
                                placeholder="Numero de la cuenta...">
                            @error('number')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><b>Saldo</b></label>
                            <input type="text" wire:model.lazy="balance" class="form-control" placeholder="0.00">
                            @error('balance')
                            <span class="text-danger er">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="StoreAccount({{$modal_id_2}})"
                    class="btn btn-dark close-modal text-uppercase">guardar</button>
                <button type="button" wire:click.prevent="CloseAccountModal({{$modal_id_2}})"
                    class="btn btn-dark close-btn text-info text-uppercase" data-dismiss="modal">cerrar</button>
            </div>
        </div>
    </div>
</div>