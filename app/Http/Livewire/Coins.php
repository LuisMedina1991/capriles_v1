<?php

namespace App\Http\Livewire;

use App\Models\Denomination;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Coins extends Component
{

    use WithFileUploads;
    use WithPagination;

    public $type, $value, $search, $image, $selected_id, $pageTitle, $componentName;
    private $pagination = 20;

    public function mount()
    {

        $this->pageTitle = 'listado';
        $this->componentName = 'denominaciones';
        $this->type = 'Elegir';
        $this->image = null;
        $this->value = '';
        $this->search = '';
        $this->selected_id = 0;
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

            $data = Denomination::where('type', 'like', '%' . $this->search . '%')
            ->paginate($this->pagination);

        else

            $data = Denomination::with('image')->orderBy('id', 'asc')
            ->paginate($this->pagination);

        return view('livewire.denomination.coins', ['coins' => $data])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function Store()
    {

        $rules = [

            'type' => 'not_in:Elegir',
            'value' => 'required|unique:denominations|numeric',
        ];

        $messages = [

            'type.not_in' => 'Seleccione una opcion',
            'value.required' => 'Campo requerido',
            'value.unique' => 'Ya existe',
            //'value.max' => 'Maximo 45 digitos',
            'value.numeric' => 'Este campo solo admite numeros',
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
        }

        $this->mount();
        $this->emit('item-added', 'Registrado correctamente');
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

            'type' => 'not_in:Elegir',
            'value' => "required|unique:denominations,value,{$this->selected_id}|numeric"
        ];

        $messages = [

            'type.not_in' => 'Seleccione una opcion',
            'value.required' => 'Campo requerido',
            'value.unique' => 'Ya existe',
            //'value.max' => 'Maximo 45 digitos',
            'value.numeric' => 'Este campo solo admite numeros',
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

        $this->mount();
        $this->emit('item-updated', 'Actualizado correctamente');
    }

    protected $listeners = [

        'destroy' => 'Destroy'
    ];

    public function Destroy(Denomination $denomination)
    {

        $denomination->delete();

        if ($denomination->image != null) {

            $imageTemp = $denomination->image->url;
            $denomination->image()->delete();

            if ($imageTemp != null) {

                if (file_exists('storage/denominations/' . $imageTemp)) {

                    unlink('storage/denominations/' . $imageTemp);
                }
            }
        }

        $this->mount();
        $this->emit('item-deleted', 'Eliminado correctamente');
    }

    public function resetUI()
    {
        $this->mount();
    }
}
