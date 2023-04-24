<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Status;
use Livewire\WithPagination;

class Statuses extends Component
{
    use WithPagination;

    public $pageTitle,$componentName,$search;
    private $pagination = 10;

    public function mount(){

        $this->pageTitle = 'listado';
        $this->componentName = 'estados';
        $this->search = '';
    }

    public function paginationView(){

        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        if(strlen($this->search) > 0){

            $data = Status::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('type', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'asc')
            ->paginate($this->pagination);

        }else{

            $data = Status::orderBy('id', 'asc')
            ->paginate($this->pagination);

        }

        return view('livewire.status.statuses', ['statuses' => $data])
        ->extends('layouts.theme.app')
        ->section('content');
    }
    

    public function resetUI(){

        $this->mount();
    }
}
