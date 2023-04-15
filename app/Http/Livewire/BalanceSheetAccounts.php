<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BalanceSheetAccount;
use App\Models\Type;
use App\Models\Subtype;

class BalanceSheetAccounts extends Component
{
    use WithPagination;

    public $search, $search_2, $selected_id, $pageTitle, $componentName;
    public $types;
    private $pagination = 20;

    public function mount()
    {

        $this->pageTitle = 'listado';
        $this->componentName = 'cuentas';
        $this->search = 'elegir';
        $this->search_2 = 0;
        $this->selected_id = 0;
        $this->types = Type::all();
        $this->resetValidation();
        $this->resetPage();
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        switch ($this->search_2) {

            case 0:

                if ($this->search != 'elegir') {

                    $data = BalanceSheetAccount::where('status_id', 1)
                        ->with('subtype.type')
                        ->WhereHas('subtype', function ($query) {
                            $query->where('type_id', $this->search);
                        })
                        ->orderBy(Subtype::select('name')->whereColumn('subtypes.id','balance_sheet_accounts.subtype_id'))
                        ->paginate($this->pagination);

                } else {

                    $data = BalanceSheetAccount::where('status_id', 1)
                    ->with('subtype.type')
                    ->orderBy(Subtype::select('name')->whereColumn('subtypes.id','balance_sheet_accounts.subtype_id'))
                    ->paginate($this->pagination);
                }

            break;

            case 1:

                if ($this->search != 'elegir') {

                    $data = BalanceSheetAccount::where('status_id', 2)
                        ->with('subtype.type')
                        ->WhereHas('subtype', function ($query) {
                            $query->where('type_id', $this->search);
                        })
                        ->orderBy(Subtype::select('name')->whereColumn('subtypes.id','balance_sheet_accounts.subtype_id'))
                        ->paginate($this->pagination);

                } else {

                    $data = BalanceSheetAccount::where('status_id', 2)
                        ->with('subtype.type')
                        ->orderBy(Subtype::select('name')->whereColumn('subtypes.id','balance_sheet_accounts.subtype_id'))
                        ->paginate($this->pagination);
                }

            break;
        }


        return view('livewire.balance_sheet_accounts.balance-sheet-accounts', ['accounts' => $data])
            ->extends('layouts.theme.app')
            ->section('content');
    }


    public function resetUI()
    {
        $this->mount();
    }
}
