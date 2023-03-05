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

    public $name, $address, $phone, $search, $selected_id, $pageTitle, $componentName,$values;
    private $pagination = 20;

    public function mount()
    {
        $this->pageTitle = 'listado';
        $this->componentName = 'sucursales';
        $this->name = '';
        $this->address = '';
        $this->phone = '';
        $this->selected_id = 0;
        $this->search = '';
        $this->values = Value::all();
        $this->resetValidation();
        $this->resetPage();
    }

    public function paginationView()
    {

        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {

        if (strlen($this->search) > 0)

            $data = Office::withCount('values','activeValues')
                ->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('address', 'like', '%' . $this->search . '%')
                ->orWhere('phone', 'like', '%' . $this->search . '%')
                ->paginate($this->pagination);

        else

            $data = Office::withCount('values','activeValues')
            ->orderBy('name', 'asc')
            ->paginate($this->pagination);


        return view('livewire.office.offices', ['offices' => $data])
            ->extends('layouts.theme.app')
            ->section('content');
    }


    public function Store()
    {

        $rules = [

            'name' => 'required|unique:offices|min:3|max:100',
            'address' => 'max:500',
            'phone' => 'max:12',
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.unique' => 'Ya existe',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 100 caracteres',
            'address.max' => 'Maximo 500 caracteres',
            //'phone.numeric' => 'Solo numeros',
            'phone.max' => 'Maximo 12 caracteres',
        ];

        $this->validate($rules, $messages);

        $office = Office::create([

            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone
        ]);

        $stocks = [];

        foreach($this->values as $value){

            $stocks[$value->id] = ['alerts' => 1, 'stock' => 0];
            
        }

        $office->values()->attach($stocks);

        $this->emit('item-added', 'Registrado correctamente');
        $this->mount();
    }

    public function Edit(Office $office)
    {
        $this->selected_id = $office->id;
        $this->name = $office->name;
        $this->address = $office->address;
        $this->phone = $office->phone;
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function Update()
    {

        $rules = [

            'name' => "required|min:3|max:100|unique:offices,name,{$this->selected_id}",
            'address' => 'max:500',
            'phone' => 'max:12',
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.unique' => 'Ya existe',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 100 caracteres',
            'address.max' => 'Maximo 500 caracteres',
            //'phone.numeric' => 'Solo numeros',
            'phone.max' => 'Maximo 12 caracteres',
        ];

        $this->validate($rules, $messages);

        $office = Office::find($this->selected_id);

        $office->update([

            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone
        ]);

        $this->emit('item-updated', 'Actualizado correctamente');
        $this->mount();
    }

    protected $listeners = [

        'destroy' => 'Destroy'
    ];

    public function Destroy(Office $office,$values_count,$active_values_count)
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

    }

    public function resetUI()
    {
        $this->mount();
    }
}
