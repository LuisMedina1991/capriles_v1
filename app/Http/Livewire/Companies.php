<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Company;
use Livewire\WithPagination;

class Companies extends Component
{
    use WithPagination;

    public $pageTitle,$componentName,$search,$search_2,$selected_id;
    public $name,$alias,$phone,$email,$nit,$status_id;
    private $pagination = 20;

    public function mount(){

        $this->pageTitle = 'listado';
        $this->componentName = 'empresas';
        $this->search = '';
        $this->search_2 = 0;
        $this->selected_id = 0;
        $this->name = '';
        $this->alias = '';
        $this->phone = '';
        $this->email = '';
        $this->nit = '';
        $this->status_id = 'elegir';
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

                    $data = Company:://withCount('bank_accounts')
                    where('status_id',1)
                    ->where(function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                        $query->orWhere('alias', 'like', '%' . $this->search . '%');
                        $query->orWhere('phone', 'like', '%' . $this->search . '%');
                        $query->orWhere('email', 'like', '%' . $this->search . '%');
                        $query->orWhere('nit', 'like', '%' . $this->search . '%');
                    })
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
        
                }else{
        
                    $data = Company:://withCount('bank_accounts')
                    where('status_id',1)
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
        
                }

            break;

            case 1:

                if(strlen($this->search) > 0){

                    $data = Company:://withCount('bank_accounts')
                    where('status_id',2)
                    ->where(function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                        $query->orWhere('alias', 'like', '%' . $this->search . '%');
                        $query->orWhere('phone', 'like', '%' . $this->search . '%');
                        $query->orWhere('email', 'like', '%' . $this->search . '%');
                        $query->orWhere('nit', 'like', '%' . $this->search . '%');
                    })
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
        
                }else{
        
                    $data = Company:://withCount('bank_accounts')
                    where('status_id',2)
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
        
                }

            break;

        }   

        return view('livewire.company.companies', ['companies' => $data])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function Store(){

        $rules = [

            'name' => 'required|min:3|max:45|unique:companies',
            'alias' => 'required|min:3|max:15|unique:companies',
            'phone' => 'digits_between:7,12',
            'email' => 'max:100',
            'nit' => 'required|digits_between:13,16|unique:companies',
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
            'phone.digits_between' => 'Solo digitos enteros y positivos. De 7 a 12 digitos',
            'email.max' => 'Maximo 100 caracteres',
            'nit.required' => 'Campo requerido',
            'nit.digits_between' => 'Solo digitos enteros y positivos. De 13 a 16 digitos',
            'nit.unique' => 'Ya existe',
        ];

        $this->validate($rules, $messages);

        $company = Company::create([
            
            'name' => $this->name,
            'alias' => $this->alias,
            'phone' => $this->phone,
            'email' => $this->email,
            'nit' => $this->nit,
            'status_id' => 1
        ]);

        if($company){

            $this->emit('item-added', 'Registrado correctamente');
            $this->mount();

        }else{

            $this->emit('item-error', 'Error al registrar');
            return;
        }

    }

    public function Edit(Company $company){
        
        $this->selected_id = $company->id;
        $this->name = $company->name;
        $this->alias = $company->alias;
        $this->phone = $company->phone;
        $this->email = $company->email;
        $this->nit = $company->nit;
        $this->status_id = $company->status_id;
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function Update(){

        $rules = [

            'name' => "required|min:3|max:45|unique:companies,name,{$this->selected_id}",
            'alias' => "required|min:3|max:15|unique:companies,alias,{$this->selected_id}",
            'phone' => 'digits_between:7,12',
            'email' => 'max:100',
            'nit' => "required|digits_between:13,16|unique:companies,nit,{$this->selected_id}",
            'status_id' => 'not_in:elegir',
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
            'phone.digits_between' => 'Solo digitos enteros y positivos. De 7 a 12 digitos',
            'email.max' => 'Maximo 100 caracteres',
            'nit.required' => 'Campo requerido',
            'nit.digits_between' => 'Solo digitos enteros y positivos. De 13 a 16 digitos',
            'nit.unique' => 'Ya existe',
            'status_id.not_in' => 'Seleccione una opcion',
        ];

        $this->validate($rules, $messages);

        $company = Company::find($this->selected_id);

        $company->update([

            'name' => $this->name,
            'alias' => $this->alias,
            'phone' => $this->phone,
            'email' => $this->email,
            'nit' => $this->nit,
            'status_id' => $this->status_id
        ]);

        $this->mount();
        $this->emit('item-updated', 'Actualizado correctamente');
    }

    protected $listeners = [

        'destroy' => 'Destroy'
    ];

    /*public function Destroy(Company $company,$accounts_count){

        if ($accounts_count > 0) {

            $this->emit('item-error', 'No se puede eliminar debido a relacion');
            return;

        } else {

            $company->update([

                'status_id' => 2
            ]);

            $this->mount();
            $this->emit('item-deleted', 'Eliminado correctamente');
        }

    }*/

    public function Destroy(Company $company){
        
        $company->update([

            'status_id' => 2
        ]);

        $this->mount();
        $this->emit('item-deleted', 'Eliminado correctamente');
    }

    public function resetUI(){

        $this->mount();
    }
}
