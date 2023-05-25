@include('common.modalHead')

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
                    <input type="text" wire:model.lazy="name" class="form-control component-name"
                        placeholder="Nombre para la categoria...">
                </div>
                @error('name')
                <span class="text-danger er">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

@include('common.modalFooter')

{{--<div wire:ignore.self class="modal fade" id="category_modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white text-uppercase">
                    <b>{{ $selected_id > 0 ? 'editar' : 'crear'}} | {{$componentName}}</b>
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
                @if ($selected_id < 1)
                <button type="button" wire:click.prevent="Store()" class="btn btn-dark close-modal text-uppercase">guardar</button>
                @else
                <button type="button" wire:click.prevent="Update()" class="btn btn-dark close-modal text-uppercase">editar</button>
                @endif
                <button type="button" wire:click.prevent="resetUI()" class="btn btn-dark close-btn text-info text-uppercase" data-dismiss="modal">cerrar</button>
            </div>
        </div>
    </div>
</div>--}}