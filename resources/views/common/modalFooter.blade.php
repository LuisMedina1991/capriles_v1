    </div>
      <div class="modal-footer">
        @if ($selected_id < 1)  <!--validar si registro ya existe para definir que boton mostrar-->
          <!--directiva de livewire que hace llamado a metodo del controlador-->
          <button type="button" wire:click.prevent="Store()" class="btn btn-dark close-modal text-uppercase">guardar</button>
        @else
          <!--directiva de livewire que hace llamado a metodo del controlador-->
          <button type="button" wire:click.prevent="Update()" class="btn btn-dark close-modal text-uppercase">editar</button>
        @endif
        <!--directiva de livewire que hace llamado a metodo del controlador-->
        <button type="button" wire:click.prevent="resetUI()" class="btn btn-dark close-btn text-info text-uppercase" data-dismiss="modal">cerrar</button>
      </div>
    </div>
  </div>
</div>