<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Office;
use App\Models\Value;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class Offices extends Component
{
    use WithPagination;

    public $pageTitle,$componentName,$search,$search_2,$selected_id;
    public $name,$alias,$phone,$address;
    private $pagination = 20;

    public function mount()
    {
        $this->pageTitle = 'listado';
        $this->componentName = 'sucursales';
        $this->search = '';
        $this->search_2 = 0;
        $this->selected_id = 0;
        $this->name = '';
        $this->alias = '';
        $this->phone = '';
        $this->address = '';
        $this->resetValidation();
        $this->resetPage();
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {

        switch ($this->search_2){

            case 0:

                if (strlen($this->search) > 0){

                    $data = Office::where('status_id',1)
                    ->where(function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                        $query->orWhere('alias', 'like', '%' . $this->search . '%');
                        $query->orWhere('phone', 'like', '%' . $this->search . '%');
                        $query->orWhere('address', 'like', '%' . $this->search . '%');
                    })
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
        
                }else{
        
                    $data = Office::where('status_id',1)
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
                }

            break;

            case 1:

                if (strlen($this->search) > 0){

                    $data = Office::where('status_id',2)
                    ->where(function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                        $query->orWhere('alias', 'like', '%' . $this->search . '%');
                        $query->orWhere('phone', 'like', '%' . $this->search . '%');
                        $query->orWhere('address', 'like', '%' . $this->search . '%');
                    })
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
        
                }else{
        
                    $data = Office::where('status_id',2)
                    ->orderBy('name', 'asc')
                    ->paginate($this->pagination);
                }

            break;

        }

        return view('livewire.office.offices', ['offices' => $data])
            ->extends('layouts.theme.app')
            ->section('content');
    }


    public function Store()
    {
        $rules = [

            'name' => 'required|min:3|max:45|unique:offices',
            'alias' => 'required|min:3|max:15|unique:offices',
            'phone' => 'digits_between:7,12',
            'address' => 'max:255',
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
            'address.max' => 'Maximo 255 caracteres',
        ];

        $this->validate($rules, $messages);

        $office = Office::create([

            'name' => $this->name,
            'alias' => $this->alias,
            'phone' => $this->phone,
            'address' => $this->address,
            'status_id' => 1
        ]);

        /*$stocks = [];

        foreach($this->values as $value){

            $stocks[$value->id] = ['alerts' => 1, 'stock' => 0];
            
        }

        $office->values()->attach($stocks);*/

        if($office){

            $this->emit('record-added', 'Registrado correctamente');
            $this->mount();

        }else{

            $this->emit('record-error', 'Error al registrar');
            return;
        }

    }

    public function Edit(Office $office)
    {
        $this->selected_id = $office->id;
        $this->name = $office->name;
        $this->alias = $office->alias;
        $this->phone = $office->phone;
        $this->address = $office->address;
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function Update()
    {
        $rules = [

            'name' => "required|min:3|max:45|unique:offices,name,{$this->selected_id}",
            'alias' => "required|min:3|max:15|unique:offices,alias,{$this->selected_id}",
            'phone' => 'exclude_if:phone,null|digits_between:7,12',
            'address' => 'max:255',
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
            'address.max' => 'Maximo 255 caracteres',
        ];

        $this->validate($rules, $messages);

        $office = Office::find($this->selected_id);

        $office->update([

            'name' => $this->name,
            'alias' => $this->alias,
            'phone' => $this->phone,
            'address' => $this->address
        ]);

        $this->emit('record-updated', 'Actualizado correctamente');
        $this->mount();
    }

    protected $listeners = [

        'activate' => 'Activate',
        'destroy' => 'Destroy'
    ];

    public function Activate(Office $office){

        $office->update([

            'status_id' => 1

        ]);

        $this->emit('record-activated','Registro desbloqueado');
        $this->mount();
    }

    /*public function Destroy(Office $office,$values_count,$active_values_count)
    {   
        
        if ($active_values_count > 0) {

            $this->emit('item-error', 'No se puede eliminar debido a relacion');
            return;

        } else {

            DB::beginTransaction();

            try {

                if($values_count > 0){

                    $office->values()->detach();
                    $office->delete();

                }else{

                    $office->delete();
                }

                DB::commit();
                $this->emit('item-deleted', 'Eliminado correctamente');
                $this->mount();

            } catch (\Throwable $th) {

                DB::rollback();
                throw $th;
            }
        }

    }*/

    public function Destroy(Office $office)
    {   
        $office->update([

            'status_id' => 2

        ]);

        $this->emit('record-deleted', 'Eliminado correctamente');
        $this->mount();
    }

    public function resetUI()
    {
        $this->mount();
    }
}
