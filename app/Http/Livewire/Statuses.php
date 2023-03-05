<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Status;
use Livewire\WithPagination;

class Statuses extends Component
{
    use WithPagination;

    public $name,$type,$search,$selected_id,$pageTitle,$componentName;
    private $pagination = 20;

    public function mount(){

        $this->pageTitle = 'listado';
        $this->componentName = 'estados';
        $this->type = 'Elegir';
        $this->name = '';
        $this->search = '';
        $this->selected_id = 0;
        $this->resetValidation();
        $this->resetPage();
    }

    public function paginationView(){

        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        if(strlen($this->search) > 0)

            $data = Status::withCount('users','products','containers','values')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('type', 'like', '%' . $this->search . '%')
            ->paginate($this->pagination);

        else

            $data = Status::withCount('users','products','containers','values')
            ->orderBy('id', 'asc')
            ->paginate($this->pagination);

        return view('livewire.status.statuses', ['statuses' => $data])
        ->extends('layouts.theme.app')
        ->section('content');
    }


    public function Store(){

        $rules = [

            'name' => 'required|unique:statuses|min:3|max:100',
            'type' => 'not_in:Elegir',
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.unique' => 'Ya existe',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 100 caracteres',
            'type.not_in' => 'Seleccione una opcion',
        ];

        $this->validate($rules, $messages);

        Status::create([

            'name' => $this->name,
            'type' => $this->type   
        ]);

        $this->mount();
        $this->emit('item-added', 'Registrado correctamente');
    }

    public function Edit(Status $status){

        $this->name = $status->name;
        $this->selected_id = $status->id;
        $this->type = $status->type;
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function Update(){

        $rules = [
           
          'name' => "required|min:3|max:100|unique:statuses,name,{$this->selected_id}",
          'type' => 'not_in:Elegir',
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.unique' => 'Ya existe',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 100 caracteres',
            'type.not_in' => 'Seleccione una opcion',
        ];

        $this->validate($rules, $messages);

        $category = Status::find($this->selected_id);

        $category->update([

            'name' => $this->name,
            'type' => $this->type
        ]);

        $this->mount();
        $this->emit('item-updated', 'Actualizado correctamente');
    }

    protected $listeners = [

        'destroy' => 'Destroy'
    ];

    public function Destroy(Status $status,$products_count,$users_count,$containers_count,$values_count){
        
        if ($products_count > 0 || $users_count > 0 || $containers_count > 0 || $values_count > 0) {

            $this->emit('item-error', 'No se puede eliminar debido a relacion');
            return;

        }else{

            $status->delete();
            $this->mount();
            $this->emit('item-deleted', 'Eliminado correctamente');
        }
        
    }

    public function resetUI(){

        $this->mount();
    }
}
