<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Supplier;
use App\Models\DebtsWithSupplier;
use Livewire\WithPagination;

class DebtsWithSuppliers extends Component
{
    use WithPagination;

    public $search,$search_2,$selected_id,$pageTitle,$componentName;
    public $description,$amount,$income_id,$supplier_id,$details;
    private $pagination = 20;

    public function mount(){

        $this->pageTitle = 'deudas con';
        $this->componentName = 'proveedores';
        $this->search = '';
        $this->search_2 = 0;
        $this->selected_id = 0;
        $this->description = '';
        $this->amount = '';
        $this->income_id = 'elegir';
        $this->supplier_id = 'elegir';
        $this->details = [];
        $this->resetValidation();
        $this->resetPage();
    }

    public function paginationView(){

        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {

        switch ($this->search_2) {

            case 0:

                if(strlen($this->search) > 0){

                    $data = DebtsWithSupplier::with(['status','income','supplier'])
                    ->where('status_id',1)
                    ->whereHas('income', function ($query) {
                        $query->where('file_number', 'like', '%' . $this->search . '%');
                        $query->orWhereHas('supplier', function($query){
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                        $query->orWhereHas('debt', function($query){
                            $query->where('description', 'like', '%' . $this->search . '%');
                        });
                    })
                    ->orderBy(Supplier::select('name')->whereColumn('suppliers.id','debts_with_suppliers.supplier_id'))
                    ->paginate($this->pagination);
        
                }else{
        
                    $data = DebtsWithSupplier::with(['status','income','supplier'])
                    ->where('status_id',1)
                    ->orderBy(Supplier::select('name')->whereColumn('suppliers.id','debts_with_suppliers.supplier_id'))
                    ->paginate($this->pagination);
        
                }

            break;

            case 1:

                if(strlen($this->search) > 0){

                    $data = DebtsWithSupplier::with(['status','income','supplier'])
                    ->where('status_id',2)
                    ->whereHas('income', function ($query) {
                        $query->where('file_number', 'like', '%' . $this->search . '%');
                        $query->orWhereHas('supplier', function($query){
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                        $query->orWhereHas('debt', function($query){
                            $query->where('description', 'like', '%' . $this->search . '%');
                        });
                    })
                    ->orderBy(Supplier::select('name')->whereColumn('suppliers.id','debts_with_suppliers.supplier_id'))
                    ->paginate($this->pagination);
        
                }else{
        
                    $data = DebtsWithSupplier::with(['status','income','supplier'])
                    ->where('status_id',2)
                    ->orderBy(Supplier::select('name')->whereColumn('suppliers.id','debts_with_suppliers.supplier_id'))
                    ->paginate($this->pagination);
        
                }

            break;

        }
        

        return view('livewire.debts_with_suppliers.debts-with-suppliers', ['debts' => $data])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function Edit(DebtsWithSupplier $debt){

        $this->selected_id = $debt->id;
        $this->description = $debt->description;
        $this->amount = $debt->amount;
        $this->supplier_id = $debt->supplier_id;
        $this->income_id = $debt->income_id;
        $this->emit('show-modal', 'Mostrando modal');
    }

    public function Update(){


        $rules = [

            'name' => 'required|min:3|max:100',
            'email' => 'max:100',
            'phone' => 'max:12',
            'fax' => 'max:12',
            'nit' => "max:12|unique:suppliers,nit,{$this->selected_id}",
            'city' => 'max:45',
            'country' => 'max:45',
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 100 caracteres',
            'email.max' => 'Maximo 100 caracteres',
            'phone.max' => 'Maximo 12 caracteres',
            'fax.max' => 'Maximo 12 caracteres',
            'nit.max' => 'Maximo 12 caracteres',
            'nit.unique' => 'Ya existe',
            'city.max' => 'Maximo 45 caracteres',
            'country.max' => 'Maximo 45 caracteres',
        ];

        $this->validate($rules, $messages);

        $supplier = Supplier::find($this->selected_id);

        $supplier->update([

            'name' => $this->name,
            'phone' => $this->phone,
            'fax' => $this->fax,
            'email' => $this->email,
            'nit' => $this->nit,
            'city' => $this->city,
            'country' => $this->country
        ]);

        $this->emit('item-updated', 'Actualizado correctamente');
        $this->mount();
    }

    public function Details(DebtsWithSupplier $debt){

        $this->details = $debt->details->where('status_id',1);
        //dd($this->details);
        $this->emit('show-detail-modal', 'Mostrando modal');
    }

    protected $listeners = [

        'destroy' => 'Destroy'
    ];

    public function Destroy(Supplier $supplier,$incomes_count,$debts_count){

        if ($incomes_count > 0 || $debts_count > 0) {

            $this->emit('item-error', 'No se puede eliminar debido a relacion');
            return;

        } else {

            $supplier->delete();

            $this->mount();
            $this->emit('item-deleted', 'Eliminado correctamente');
        }

    }

    public function resetUI(){

        $this->mount();
    }
}
