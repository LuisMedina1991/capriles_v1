<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Presentation;
use Livewire\WithPagination;

class Presentations extends Component
{
    use WithPagination;

    public $name,$search,$selected_id,$pageTitle,$componentName;
    private $pagination = 20;

    public function mount(){

        $this->pageTitle = 'listado';
        $this->componentName = 'presentaciones';
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

            $data = Presentation::withCount('subcategories')
            ->where('name', 'like', '%' . $this->search . '%')
            ->paginate($this->pagination);

        else

            $data = Presentation::withCount('subcategories')
            ->orderBy('name', 'asc')
            ->paginate($this->pagination);
            

        return view('livewire.presentation.presentations', ['presentations' => $data])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function Store(){

        $rules = [

            'name' => 'required|unique:presentations|min:3|max:100'
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.unique' => 'Ya existe',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 100 caracteres',
        ];

        $this->validate($rules, $messages);

        Presentation::create([
            
            'name' => $this->name   
        ]);

        $this->mount();
        $this->emit('item-added', 'Registrado correctamente');
    }

    public function Edit(Presentation $presentation){

        $this->name = $presentation->name;
        $this->selected_id = $presentation->id;
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function Update(){

        $rules = [

            'name' => "required|min:3|max:100|unique:presentations,name,{$this->selected_id}"
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.unique' => 'Ya existe',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 100 caracteres',
        ];

        $this->validate($rules, $messages);

        $presentation = Presentation::find($this->selected_id);

        $presentation->update([

            'name' => $this->name
        ]);

        $this->mount();
        $this->emit('item-updated', 'Actualizado correctamente');
    }

    protected $listeners = [

        'destroy' => 'Destroy'
    ];

    public function Destroy(Presentation $presentation,$subcategories_count){

        if ($subcategories_count > 0) {

            $this->emit('item-error', 'No se puede eliminar debido a relacion');
            return;

        } else {

            $presentation->delete();
            $this->mount();
            $this->emit('item-deleted', 'Eliminado correctamente');
        }

    }

    public function resetUI(){

        $this->mount();
    }
}
