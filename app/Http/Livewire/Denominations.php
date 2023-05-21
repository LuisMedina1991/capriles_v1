<?php

namespace App\Http\Livewire;

use App\Models\Denomination;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Denominations extends Component
{

    use WithFileUploads;
    use WithPagination;

    public $pageTitle,$componentName,$selected_id,$search,$type,$value,$image;
    private $pagination = 20;

    public function mount()
    {

        $this->pageTitle = 'listado';
        $this->componentName = 'denominaciones';
        $this->selected_id = 0;
        $this->search = '';
        $this->type = 'elegir';
        $this->value = '';
        $this->image = null;
        $this->resetValidation();
        $this->resetPage();
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        if (strlen($this->search) > 0){

            $data = Denomination::with('image')
            ->where('type', 'like', '%' . $this->search . '%')
            ->orWhere('value', 'like', '%' . $this->search . '%')
            ->orderBy('value', 'asc')
            ->paginate($this->pagination);

        }else{

            $data = Denomination::with('image')
            ->orderBy('value', 'asc')
            ->paginate($this->pagination);

        }

        return view('livewire.denomination.denominations', ['denominations' => $data])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function Store()
    {
        $rules = [

            'type' => 'not_in:elegir',
            'value' => 'required|min:1|max:10|unique:denominations',
            'image' => 'exclude_if:image,null|mimes:jpg,png'
        ];

        $messages = [

            'type.not_in' => 'Seleccione una opcion',
            'value.required' => 'Campo requerido',
            'value.min' => 'Minimo 1 digito',
            'value.max' => 'Maximo 10 digitos',
            'value.unique' => 'Ya existe',
            'image.mimes' => 'Solo formatos jpg o png'
        ];

        $this->validate($rules, $messages);

        $denomination = Denomination::create([

            'type' => $this->type,
            'value' => $this->value
        ]);

        if ($denomination) {

            if ($this->image) {

                $customFileName = uniqid() . '_.' . $this->image->extension();
                $this->image->storeAs('public/denominations', $customFileName);
                $denomination->image()->create(['url' => $customFileName]);
            }

            $this->emit('record-added', 'Registrado correctamente');
            $this->mount();

        }else{

            $this->emit('record-error', 'Error al registrar');
            return;
        }
    }

    public function Edit(Denomination $denomination)
    {
        $this->type = $denomination->type;
        $this->value = $denomination->value;
        $this->selected_id = $denomination->id;
        $this->image = null;
        $this->emit('show-modal', 'Mostrar modal!');
    }

    public function Update()
    {
        $rules = [

            'type' => 'not_in:elegir',
            'value' => "required|min:1|max:10|unique:denominations,value,{$this->selected_id}",
            'image' => 'exclude_if:image,null|mimes:jpg,png'
        ];

        $messages = [

            'type.not_in' => 'Seleccione una opcion',
            'value.required' => 'Campo requerido',
            'value.min' => 'Minimo 1 digito',
            'value.max' => 'Maximo 10 digitos',
            'value.unique' => 'Ya existe',
            'image.mimes' => 'Solo formatos jpg o png'
        ];

        $this->validate($rules, $messages);

        $denomination = Denomination::find($this->selected_id);

        $denomination->update([

            'type' => $this->type,
            'value' => $this->value
        ]);

        if ($this->image) {

            $customFileName = uniqid() . '_.' . $this->image->extension();

            $this->image->storeAs('public/denominations', $customFileName);

            if ($denomination->image != null) {

                $imageTemp = $denomination->image->url;
                $denomination->image()->update(['url' => $customFileName]);

                if ($imageTemp != null) {

                    if (file_exists('storage/denominations/' . $imageTemp)) {

                        unlink('storage/denominations/' . $imageTemp);
                    }
                }

            } else {

                $denomination->image()->create(['url' => $customFileName]);
            }
        }

        $this->emit('record-updated', 'Actualizado correctamente');
        $this->mount();
    }

    protected $listeners = [

        'destroy' => 'Destroy'
    ];

    public function Destroy(Denomination $denomination)
    {
        if ($denomination->image != null) {

            $imageTemp = $denomination->image->url;
            $denomination->image()->delete();

            if (file_exists('storage/denominations/' . $imageTemp)) {

                unlink('storage/denominations/' . $imageTemp);
            }

        }

        $denomination->delete();

        $this->emit('record-deleted', 'Eliminado correctamente');
        $this->mount();
    }

    public function resetUI()
    {
        $this->mount();
    }
}
