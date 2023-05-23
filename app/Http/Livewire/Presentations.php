<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Presentation;
use Livewire\WithPagination;

class Presentations extends Component
{
    use WithPagination;

    public $pageTitle,$componentName,$search,$search_2,$selected_id;
    public $name;
    private $pagination = 20;

    public function mount(){

        $this->pageTitle = 'listado';
        $this->componentName = 'presentaciones';
        $this->search = '';
        $this->search_2 = 0;
        $this->selected_id = 0;
        $this->name = '';
        $this->resetValidation();
        $this->resetPage();
    }

    public function paginationView(){

        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        switch ($this->search_2){

            case 0:

                if(strlen($this->search) > 0){

                    $data = Presentation::where('status_id',1)
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
        
                }else{
        
                    $data = Presentation::where('status_id',1)
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
                }

            break;

            case 1:

                if(strlen($this->search) > 0){

                    $data = Presentation::where('status_id',2)
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
        
                }else{
        
                    $data = Presentation::where('status_id',2)
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
                }

            break;

        }

        return view('livewire.presentation.presentations', ['presentations' => $data])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function Store(){

        $rules = [

            'name' => 'required|min:3|max:45|unique:presentations',
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 45 caracteres',
            'name.unique' => 'Ya existe',
        ];

        $this->validate($rules, $messages);

        $presentation = Presentation::create([
            
            'name' => $this->name,
            'status_id' => 1
        ]);

        if($presentation){

            $this->emit('record-added', 'Registrado correctamente');
            $this->mount();

        }else{

            $this->emit('record-error', 'Error al registrar');
            return;
        }

    }

    public function Edit(Presentation $presentation){

        $this->name = $presentation->name;
        $this->selected_id = $presentation->id;
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function Update(){

        $rules = [

            'name' => "required|min:3|max:45|unique:presentations,name,{$this->selected_id}",
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 45 caracteres',
            'name.unique' => 'Ya existe',
        ];

        $this->validate($rules, $messages);

        $presentation = Presentation::find($this->selected_id);

        $presentation->update([

            'name' => $this->name
        ]);

        $this->emit('record-updated', 'Actualizado correctamente');
        $this->mount();
    }

    protected $listeners = [

        'activate' => 'Activate',
        'destroy' => 'Destroy'
    ];

    public function Activate(Presentation $presentation){

        $presentation->update([

            'status_id' => 1

        ]);

        $this->emit('record-activated','Registro desbloqueado');
        $this->mount();
    }

    /*public function Destroy(Presentation $presentation,$subcategories_count){

        if ($subcategories_count > 0) {

            $this->emit('item-error', 'No se puede eliminar debido a relacion');
            return;

        } else {

            $presentation->delete();
            $this->mount();
            $this->emit('item-deleted', 'Eliminado correctamente');
        }

    }*/

    public function Destroy(Presentation $presentation){

        $presentation->update([

            'status_id' => 2
        ]);

        $this->emit('record-deleted', 'Eliminado correctamente');
        $this->mount();

    }

    public function resetUI(){

        $this->mount();
    }
}
