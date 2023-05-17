<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Bank;
use Livewire\WithPagination;

class Banks extends Component
{
    use WithPagination;

    public $pageTitle,$componentName,$search,$search_2,$selected_id;
    public $name,$alias,$entity_code;
    private $pagination = 20;

    public function mount(){

        $this->pageTitle = 'listado';
        $this->componentName = 'bancos';
        $this->search = '';
        $this->search_2 = 0;
        $this->selected_id = 0;
        $this->name = '';
        $this->alias = '';
        $this->entity_code = '';
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

                    $data = Bank::where('status_id',1)
                    ->where(function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                        $query->orWhere('alias', 'like', '%' . $this->search . '%');
                        $query->orWhere('entity_code', 'like', '%' . $this->search . '%');
                    })
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
        
                }else{
        
                    $data = Bank::where('status_id',1)
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
                }
                
            break;

            case 1:

                if(strlen($this->search) > 0){

                    $data = Bank::where('status_id',2)
                    ->where(function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                        $query->orWhere('alias', 'like', '%' . $this->search . '%');
                        $query->orWhere('entity_code', 'like', '%' . $this->search . '%');
                    })
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
        
                }else{
        
                    $data = Bank::where('status_id',2)
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

            'name' => 'required|min:3|max:45|unique:banks',
            'alias' => 'required|min:3|max:15|unique:banks',
            'entity_code' => 'required|digits_between:4,6|unique:banks',
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 45 caracteres',
            'name.unique' => 'Ya existe',
            'alias.required' => 'Campo requerido',
            'alias.min' => 'Minimo 3 caracteres',
            'alias.max' => 'Maximo 15 caracteres',
            'alias.unique' => 'Ya existe',
            'entity_code.required' => 'Campo requerido',
            'entity_code.digits_between' => 'Solo numeros enteros y positivos. De 4 a 6 digitos',
            'entity_code.unique' => 'Ya existe',
        ];

        $this->validate($rules, $messages);

        $bank = Bank::create([
            
            'name' => $this->name,
            'alias' => $this->alias,
            'entity_code' => $this->entity_code,
            'status_id' => 1
        ]);

        if($bank){

            $this->emit('record-added', 'Registrado correctamente');
            $this->mount();

        }else{

            $this->emit('record-error', 'Error al registrar');
            return;
        }
        
    }

    public function Edit(Bank $bank){

        $this->selected_id = $bank->id;
        $this->name = $bank->name;
        $this->alias = $bank->alias;
        $this->entity_code = $bank->entity_code;
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function Update(){

        $rules = [

            'name' => "required|min:3|max:45|unique:banks,name,{$this->selected_id}",
            'alias' => "required|min:3|max:15|unique:banks,alias,{$this->selected_id}",
            'entity_code' => "required|digits_between:4,6|unique:banks,entity_code,{$this->selected_id}",
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 45 caracteres',
            'name.unique' => 'Ya existe',
            'alias.required' => 'Campo requerido',
            'alias.min' => 'Minimo 3 caracteres',
            'alias.max' => 'Maximo 15 caracteres',
            'alias.unique' => 'Ya existe',
            'entity_code.required' => 'Campo requerido',
            'entity_code.digits_between' => 'Solo numeros enteros y positivos. De 4 a 6 digitos',
            'entity_code.unique' => 'Ya existe',
        ];

        $this->validate($rules, $messages);

        $bank = Bank::find($this->selected_id);

        $bank->update([

            'name' => $this->name,
            'alias' => $this->alias,
            'entity_code' => $this->entity_code
        ]);

        $this->emit('record-updated', 'Actualizado correctamente');
        $this->mount();
    }

    protected $listeners = [

        'activate' => 'Activate',
        'destroy' => 'Destroy'
    ];

    public function Activate(Bank $bank){

        $bank->update([

            'status_id' => 1

        ]);

        $this->emit('record-activated','Registro desbloqueado');
        $this->mount();
    }

    /*public function Destroy(Bank $bank,$accounts_count){

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

    }*/

    public function Destroy(Bank $bank){

        $bank->update([

            'status_id' => 2
        ]);

        $this->emit('record-deleted', 'Eliminado correctamente');
        $this->mount();
    }

    public function resetUI(){

        $this->mount();
    }
}
