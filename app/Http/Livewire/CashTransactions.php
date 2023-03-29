<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\CashTransaction;
use App\Models\BankAccount;
use App\Models\Detail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
//use Livewire\WithPagination;

class CashTransactions extends Component
{
    //use WithPagination;

    public $pageTitle,$componentName,$selected_id,$search,$search_2,$total_income,$total_discharge,$my_total,$dateFrom,$dateTo,$reportRange,$transactions,$transactions_2;
    public $description,$action,$relation,$amount;
    public $bank_accounts,$details;
    public $AccountId;
    public $balance;
    //private $pagination = 30;

    public function paginationView(){

        return 'vendor.livewire.bootstrap';
    }

    public function mount(){

        $this->pageTitle = 'listado';
        $this->componentName = 'transacciones en efectivo';
        $this->selected_id = 0;
        $this->search = '';
        $this->search_2 = 0;
        $this->total_income = 0;
        $this->total_discharge = 0;
        $this->my_total = 0;
        $this->reportRange = 0;
        $this->transactions = [];
        $this->transactions_2 = CashTransaction::where('status_id',1)->get();
        $this->description = '';
        $this->action = 'elegir';
        $this->relation = 'elegir';
        $this->amount = '';
        $this->AccountId = 'elegir';
        $this->bank_accounts = BankAccount::where('status_id',1)->with(['bank','company'])->get();
        $this->details = Detail::where('status_id',1)->get();
        $this->balance = 0;
        $this->resetValidation();
        //$this->resetPage();
    }

    public function render()
    {   
        $this->ReportsByDate();

        return view('livewire.cash_transaction.cash-transactions')
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function ReportsByDate(){

        if($this->reportRange == 0){

            $fecha1 = Carbon::parse(Carbon::today())->format('Y-m-d') . ' 00:00:00';
            $fecha2 = Carbon::parse(Carbon::today())->format('Y-m-d') . ' 23:59:59';

            $this->total_income = $this->transactions_2->where('action','ingreso')->sum('amount');
            $this->total_discharge = $this->transactions_2->where('action','egreso')->sum('amount');

        }else{

            $fecha1 = Carbon::parse($this->dateFrom)->format('Y-m-d') . ' 00:00:00';
            $fecha2 = Carbon::parse($this->dateTo)->format('Y-m-d') . ' 23:59:59';

            $this->total_income = $this->transactions_2->where('action','ingreso')->where('created_at','<=',$fecha2)->sum('amount');
            $this->total_discharge = $this->transactions_2->where('action','egreso')->where('created_at','<=',$fecha2)->sum('amount');

        }

        if($this->reportRange == 1 && ($this->dateFrom == '' || $this->dateTo == '')){

            $this->emit('item-error', 'Seleccione fecha de inicio y fecha de fin');
            return;
        }

        switch ($this->search_2){

            case 0:

                if (strlen($this->search) > 0){

                    $this->transactions = CashTransaction::where('status_id',1)
                    ->with(['status','detail'])
                    ->where(function ($q1) {
                        $q1->where('file_number', 'like', '%' . $this->search . '%');
                        $q1->orWhere('action', 'like', '%' . $this->search . '%');
                        $q1->orWhere('description', 'like', '%' . $this->search . '%');
                    })
                    ->whereBetween('created_at', [$fecha1, $fecha2])
                    ->orderBy('file_number','asc')
                    ->get();

                }else{

                    $this->transactions = CashTransaction::where('status_id',1)
                    ->with(['status','detail'])
                    ->whereBetween('created_at', [$fecha1, $fecha2])
                    ->orderBy('file_number','asc')
                    ->get();

                }

                

            break;

            case 1:

                if (strlen($this->search) > 0){

                    $this->transactions = CashTransaction::where('status_id',2)
                    ->with(['status','detail'])
                    ->where(function ($q1) {
                        $q1->where('file_number', 'like', '%' . $this->search . '%');
                        $q1->orWhere('action', 'like', '%' . $this->search . '%');
                        $q1->orWhere('description', 'like', '%' . $this->search . '%');
                    })
                    ->whereBetween('created_at', [$fecha1, $fecha2])
                    ->orderBy('file_number','asc')
                    ->get();

                }else{

                    $this->transactions = CashTransaction::where('status_id',2)
                    ->with(['status','detail'])
                    ->whereBetween('created_at', [$fecha1, $fecha2])
                    ->orderBy('file_number','asc')
                    ->get();

                }

            break;

        }

        $this->my_total = $this->total_income - $this->total_discharge;

    }

    public function updatedAccountId()
    {
        if($this->AccountId != 'elegir'){
            
            $this->balance = number_format($this->bank_accounts->find($this->AccountId)->balance,2);

        }else{

            $this->balance = 0;
        }

    }

    public function Store(){

        $rules = [

            'description' => 'required|min:10|max:255',
            'action' => 'not_in:elegir',
            'relation' => 'not_in:elegir',
            'amount' => 'required|gt:0',
            'AccountId' => 'exclude_unless:relation,cuentas bancarias|not_in:elegir',
            'balance' => 'exclude_unless:relation,cuentas bancarias|exclude_if:action,egreso|gte:amount',
        ];

        $messages = [

            'description.required' => 'Campo requerido',
            'description.min' => 'Minimo 10 caracteres',
            'description.max' => 'Maximo 255 caracteres',
            'action.not_in' => 'Seleccione una opcion',
            'relation.not_in' => 'Seleccione una opcion',
            'amount.required' => 'Campo requerido',
            'amount.gt' => 'Numeros mayores a 0',
            'AccountId.not_in' => 'Seleccione una opcion',
            'balance.gte' => 'El saldo es menor al monto',
        ];
        
        $this->validate($rules, $messages);

        //$account = $this->bank_accounts->find($this->AccountId);
        //dd($account);

        DB::beginTransaction();

        try {

            $now = Carbon::parse(Carbon::now())->format('d-m-Y');

            switch ($this->action){

                case 'ingreso':

                    switch ($this->relation){

                        case 'cuentas bancarias':

                            $account = $this->bank_accounts->find($this->AccountId);

                            $transaction = $account->CashTransactions()->create([

                                'action' => $this->action,
                                'description' => 
                                    'retiro de cuenta bancaria' . ' ' .
                                    $account->bank->alias . '-' .
                                    $account->company->alias . '-' .
                                    $account->number . ' ' .
                                    'en fecha' . ' ' .
                                    $now . ' ' .
                                    $this->description,
                                'amount' => $this->amount,
                                'status_id' => 1
                            ]);

                            $detail = $account->details()->create([
                                'action' => 'egreso',
                                'relation_file_number' => $transaction->file_number,
                                'description' => 
                                    'retiro de cuenta bancaria' . ' ' .
                                    $account->bank->alias . '-' .
                                    $account->company->alias . '-' .
                                    $account->number . ' ' .
                                    'en fecha' . ' ' .
                                    $now . ' ' .
                                    $this->description,
                                'amount' => $transaction->amount,
                                'previus_balance' => $account->balance,
                                'actual_balance' => $account->balance - $transaction->amount,
                                'status_id' => 1
                            ]);
    
                            $account->Update([
    
                                'balance' => $account->balance - $transaction->amount
    
                            ]);

                            $transaction->Update([
    
                                'detail_id' => $detail->id
    
                            ]);

                        break;

                    }

                break;

                case 'egreso':

                    switch ($this->relation){

                        case 'cuentas bancarias':

                            $account = $this->bank_accounts->find($this->AccountId);

                            $transaction = $account->CashTransactions()->create([

                                'action' => $this->action,
                                'description' => 
                                    'deposito a cuenta bancaria' . ' ' .
                                    $account->bank->alias . '-' .
                                    $account->company->alias . '-' .
                                    $account->number . ' ' .
                                    'en fecha' . ' ' .
                                    $now . ' ' .
                                    $this->description,
                                'amount' => $this->amount,
                                'status_id' => 1
                            ]);

                            $detail = $account->details()->create([
                                'action' => 'ingreso',
                                'relation_file_number' => $transaction->file_number,
                                'description' => 
                                    'deposito a cuenta bancaria' . ' ' .
                                    $account->bank->alias . '-' .
                                    $account->company->alias . '-' .
                                    $account->number . ' ' .
                                    'en fecha' . ' ' .
                                    $now . ' ' .
                                    $this->description,
                                'amount' => $transaction->amount,
                                'previus_balance' => $account->balance,
                                'actual_balance' => $account->balance + $transaction->amount,
                                'status_id' => 1
                            ]);
    
                            $account->Update([
    
                                'balance' => $account->balance + $transaction->amount
    
                            ]);

                            $transaction->Update([
    
                                'detail_id' => $detail->id
    
                            ]);

                        break;

                    }

                break;

            }

            DB::commit();
            $this->emit('item-added','Transaccion Registrada');
            $this->mount();

        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }

    }

    protected $listeners = [

        'destroy' => 'Destroy'
    ];

    public function Destroy(CashTransaction $transaction)
    {
        DB::beginTransaction();

        try {
            
            switch ($transaction->action){

                case 'ingreso':
    
                    switch ($transaction->cashable_type){
    
                        case 'App\Models\BankAccount':
    
                            $account = $this->bank_accounts->find($transaction->cashable_id);
                            $detail = $this->details->find($transaction->detail_id);

                            if($account->balance == ($detail->previus_balance - $transaction->amount)){

                                $account->update([

                                    'balance' => $account->balance + $transaction->amount

                                ]);

                                $detail->update([

                                    'status_id' => 2

                                ]);

                            }else{

                                $this->emit('item-error','Se han realizado transacciones con la cuenta de destino. Anule esas transacciones primero');
                                return;

                            }
    
                        break;
    
                    }
    
                break;
    
                case 'egreso':
    
                    switch ($transaction->cashable_type){
    
                        case 'App\Models\BankAccount':
    
                            $account = $this->bank_accounts->find($transaction->cashable_id);
                            $detail = $this->details->find($transaction->detail_id);

                            if($account->balance == ($detail->previus_balance + $transaction->amount)){

                                $account->update([

                                    'balance' => $account->balance - $transaction->amount

                                ]);

                                $detail->update([

                                    'status_id' => 2

                                ]);

                            }else{

                                $this->emit('item-error','Se han realizado transacciones con la cuenta de destino. Anule esas transacciones primero');
                                return;

                            }
    
                        break;
    
                    }
    
                break;
    
            }
    
            $transaction->update([
    
                'status_id' => 2
    
            ]);
            
            DB::commit();
            $this->emit('item-deleted', 'Transaccion Anulada');
            $this->mount();

        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }

    }

    public function resetUI(){

        $this->mount();
    }
}
