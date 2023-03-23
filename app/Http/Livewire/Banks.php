<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Bank;
use App\Models\Status;
use Livewire\WithPagination;

class Banks extends Component
{
    use WithPagination;

    public $search,$search_2,$selected_id,$pageTitle,$componentName;
    public $name,$alias,$entity_code,$statusId;
    private $pagination = 20;

    public function mount(){

        $this->pageTitle = 'listado';
        $this->componentName = 'bancos';
        $this->name = '';
        $this->alias = '';
        $this->entity_code = '';
        $this->statusId = 'Elegir';
        $this->search = '';
        $this->search_2 = 0;
        $this->selected_id = 0;
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

                    $data = Bank::with('status')
                    ->withCount('accounts')
                    ->where('status_id',1)
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('alias', 'like', '%' . $this->search . '%')
                    ->orWhere('entity_code', 'like', '%' . $this->search . '%')
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
        
                }else{
        
                    $data = Bank::with('status')
                    ->withCount('accounts')
                    ->where('status_id',1)
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
        
                }
                
            break;

            case 1:

                if(strlen($this->search) > 0){

                    $data = Bank::with('status')
                    ->withCount('accounts')
                    ->where('status_id',2)
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('alias', 'like', '%' . $this->search . '%')
                    ->orWhere('entity_code', 'like', '%' . $this->search . '%')
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
        
                }else{
        
                    $data = Bank::with('status')
                    ->withCount('accounts')
                    ->where('status_id',2)
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
        
                }
                
            break;

        }

        return view('livewire.bank.banks', ['banks' => $data])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function Store(){

        $rules = [

            'name' => 'required|unique:banks|min:3|max:100',
            'alias' => 'required|unique:banks|min:3|max:45',
            'entity_code' => 'required|digits_between:4,6|unique:banks',
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.unique' => 'Ya existe',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 100 caracteres',
            'alias.required' => 'Campo requerido',
            'alias.unique' => 'Ya existe',
            'alias.min' => 'Minimo 3 caracteres',
            'alias.max' => 'Maximo 45 caracteres',
            'entity_code.required' => 'Campo requerido',
            'entity_code.unique' => 'Ya existe',
            'entity_code.digits_between' => 'Solo numeros enteros positivos entre 4 y 6 digitos',
        ];

        $this->validate($rules, $messages);

        Bank::create([
            
            'name' => $this->name,
            'alias' => $this->alias,
            'entity_code' => $this->entity_code,
            'status_id' => 1
        ]);

        $this->mount();
        $this->emit('item-added', 'Registrado correctamente');
    }

    public function Edit(Bank $bank){

        $this->selected_id = $bank->id;
        $this->name = $bank->name;
        $this->alias = $bank->alias;
        $this->entity_code = $bank->entity_code;
        $this->statusId = $bank->status_id;
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function Update(){

        $rules = [

            'name' => "required|min:3|max:100|unique:banks,name,{$this->selected_id}",
            'alias' => "required|min:3|max:15|unique:banks,alias,{$this->selected_id}",
            'entity_code' => "required|digits_between:4,6|unique:banks,entity_code,{$this->selected_id}",
            'statusId' => 'not_in:Elegir',
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.unique' => 'Ya existe',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 100 caracteres',
            'alias.required' => 'Campo requerido',
            'alias.unique' => 'Ya existe',
            'alias.min' => 'Minimo 3 caracteres',
            'alias.max' => 'Maximo 15 caracteres',
            'entity_code.required' => 'Campo requerido',
            'entity_code.unique' => 'Ya existe',
            'entity_code.digits_between' => 'Solo numeros enteros positivos entre 4 y 6 digitos',
            'statusId.not_in' => 'Seleccione una opcion'
        ];

        $this->validate($rules, $messages);

        $bank = Bank::find($this->selected_id);

        $bank->update([

            'name' => $this->name,
            'alias' => $this->alias,
            'entity_code' => $this->entity_code,
            'status_id' => $this->statusId
        ]);

        $this->mount();
        $this->emit('item-updated', 'Actualizado correctamente');
    }

    protected $listeners = [

        'destroy' => 'Destroy'
    ];

    public function Destroy(Bank $bank,$accounts_count){

        if ($accounts_count > 0) {

            $this->emit('item-error', 'No se puede eliminar debido a relacion');
            return;

        } else {

            $bank->update([

                'status_id' => 2
            ]);

            $this->mount();
            $this->emit('item-deleted', 'Eliminado correctamente');
        }

    }

    public function resetUI(){

        $this->mount();
    }
}
