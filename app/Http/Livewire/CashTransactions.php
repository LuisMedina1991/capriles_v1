<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\CashTransaction;
use App\Models\BankAccount;
use App\Models\Supplier;
use App\Models\DebtsWithSupplier;
use App\Models\Detail;
use App\Models\Customer;
use App\Models\CustomerDebt;
use App\Models\Paycheck;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
//use Livewire\WithPagination;

class CashTransactions extends Component
{
    //use WithPagination;

    public $pageTitle,$componentName,$selected_id,$search,$search_2,$total_income,$total_discharge,$my_total,$dateFrom,$dateTo,$reportRange,$transactions,$transactions_2;
    public $description,$action,$relation,$income_amount,$discharge_amount;
    public $bank_accounts,$suppliers,$active_debts_with_suppliers,$debts_with_suppliers,$customers,$active_customer_debts,$customer_debts;
    public $active_paychecks,$paychecks;
    public $AccountId,$SupplierId,$DebtWithSupplierId,$CustomerId,$CustomerDebtId,$PaycheckId,$PaycheckCustomerId;
    public $bank_account_balance,$debt_with_supplier_balance,$customer_debt_balance,$paycheck_balance;
    public $debt_with_supplier_detail,$customer_debt_detail,$paycheck_detail,$paycheck_description;
    public $details;
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
        $this->income_amount = '';
        $this->discharge_amount = '';
        $this->AccountId = 'elegir';
        $this->SupplierId = 'elegir';
        $this->CustomerId = 'elegir';
        $this->DebtWithSupplierId = 'elegir';
        $this->CustomerDebtId = 'elegir';
        $this->PaycheckId = '';
        $this->PaycheckCustomerId = 'elegir';
        $this->suppliers = Supplier::all();
        $this->customers = Customer::all();
        $this->bank_accounts = BankAccount::where('status_id',1)->with(['bank','company'])->get();
        $this->debts_with_suppliers = DebtsWithSupplier::with(['supplier','income'])->get();
        $this->active_debts_with_suppliers = DebtsWithSupplier::where('status_id',1)->with(['supplier','income'])->get();
        $this->customer_debts = CustomerDebt::with(['customer','sale'])->get();
        $this->active_customer_debts = CustomerDebt::where('status_id',1)->with(['customer','sale'])->get();
        $this->paychecks = Paycheck::with(['customer','sale','bank'])->get();
        $this->active_paychecks = Paycheck::where('status_id',1)->with(['customer','sale','bank'])->get();
        $this->details = Detail::where('status_id',1)->get();
        $this->debt_with_supplier_detail = [];
        $this->customer_debt_detail = [];
        $this->paycheck_detail = [];
        $this->paycheck_description = '';
        $this->bank_account_balance = 0;
        $this->debt_with_supplier_balance = 0;
        $this->customer_debt_balance = 0;
        $this->paycheck_balance = 0;
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

    public function updatedAccountId($relation_id)
    {
        if($this->bank_accounts->firstWhere('id',$relation_id) != null){
            
            $this->bank_account_balance = number_format($this->bank_accounts->firstWhere('id',$relation_id)->balance,2);

        }else{

            $this->bank_account_balance = 0;

        }

    }

    public function updatedSupplierId($relation_id)
    {
        $this->DebtWithSupplierId = 'elegir';

        $this->debt_with_supplier_detail = $this->active_debts_with_suppliers->where('supplier_id',$relation_id);

    }

    public function updatedCustomerId($relation_id)
    {
        $this->CustomerDebtId = 'elegir';

        $this->customer_debt_detail = $this->active_customer_debts->where('customer_id',$relation_id);
    }

    public function updatedPaycheckCustomerId($relation_id)
    {
        $this->PaycheckId = 'elegir';

        $this->paycheck_detail = $this->active_paychecks->where('customer_id',$relation_id);
    }

    public function updatedPaycheckId($detail_id){

        if($this->active_paychecks->firstWhere('id',$detail_id) != null){

            $this->paycheck_description = $this->active_paychecks->firstWhere('id',$detail_id)->description;
            $this->paycheck_balance = number_format($this->active_paychecks->firstWhere('id',$detail_id)->amount,2);

        }else{
            
            $this->paycheck_description = '';
            $this->paycheck_balance = 0;

        }

    }

    public function updatedDebtWithSupplierId($detail_id){

        if($this->active_debts_with_suppliers->firstWhere('id',$detail_id) != null){

            $this->debt_with_supplier_balance = number_format($this->active_debts_with_suppliers->firstWhere('id',$detail_id)->amount,2);

        }else{

            $this->debt_with_supplier_balance = 0;

        }

    }

    public function updatedCustomerDebtId($detail_id){

        if($this->active_customer_debts->firstWhere('id',$detail_id) != null){

            $this->customer_debt_balance = number_format($this->active_customer_debts->firstWhere('id',$detail_id)->amount,2);

        }else{

            $this->customer_debt_balance = 0;

        }

    }

    public function Store(){

        $rules = [

            'description' => 'required|min:10|max:255',
            'action' => 'not_in:elegir',
            'relation' => 'exclude_if:action,elegir|not_in:elegir',
            'income_amount' => 'exclude_unless:action,ingreso|required|gt:0',
            'discharge_amount' => "exclude_unless:action,egreso|required|gt:0|lte:$this->my_total",
            'AccountId' => 'exclude_unless:relation,cuentas bancarias|not_in:elegir',
            'bank_account_balance' => 'exclude_unless:relation,cuentas bancarias|exclude_if:action,egreso|gte:income_amount',
            'SupplierId' => 'exclude_unless:relation,deudas con proveedores|not_in:elegir',
            'DebtWithSupplierId' => 'exclude_unless:relation,deudas con proveedores|exclude_if:SupplierId,elegir|not_in:elegir',
            'debt_with_supplier_balance' => 'exclude_unless:relation,deudas con proveedores|exclude_if:DebtWithSupplierId,elegir|gte:discharge_amount',
            'CustomerId' => 'exclude_unless:relation,deudas de clientes|not_in:elegir',
            'CustomerDebtId' => 'exclude_unless:relation,deudas de clientes|exclude_if:CustomerId,elegir|not_in:elegir',
            'customer_debt_balance' => 'exclude_unless:relation,deudas de clientes|exclude_if:CustomerDebtId,elegir|gte:income_amount',
            'PaycheckCustomerId' => 'exclude_unless:relation,cheques de pago|not_in:elegir',
            'PaycheckId' => 'exclude_unless:relation,cheques de pago|exclude_if:PaycheckCustomerId,elegir|not_in:elegir',
            'paycheck_balance' => 'exclude_unless:relation,cheques de pago|exclude_if:PaycheckId,elegir|gte:income_amount',
        ];

        $messages = [

            'description.required' => 'Campo requerido',
            'description.min' => 'Minimo 10 caracteres',
            'description.max' => 'Maximo 255 caracteres',
            'action.not_in' => 'Seleccione una opcion',
            'relation.not_in' => 'Seleccione una opcion',
            'income_amount.required' => 'Campo requerido',
            'income_amount.gt' => 'El monto debe ser mayor 0',
            'discharge_amount.required' => 'Campo requerido',
            'discharge_amount.gt' => 'El monto debe ser mayor 0',
            'discharge_amount.lte' => 'Saldo de caja insuficiente',
            'AccountId.not_in' => 'Seleccione una opcion',
            'bank_account_balance.gte' => 'Saldo insuficiente',
            'SupplierId.not_in' => 'Seleccione una opcion',
            'DebtWithSupplierId.not_in' => 'Seleccione una opcion',
            'debt_with_supplier_balance.gte' => 'Saldo de deuda sobrepasado',
            'CustomerId.not_in' => 'Seleccione una opcion',
            'CustomerDebtId.not_in' => 'Seleccione una opcion',
            'customer_debt_balance.gte' => 'Saldo de deuda sobrepasado',
            'PaycheckCustomerId.not_in' => 'Seleccione una opcion',
            'PaycheckId.not_in' => 'Seleccione una opcion',
            'paycheck_balance.gte' => 'Saldo de deuda sobrepasado',
        ];
        
        $this->validate($rules, $messages);

        //$debt = $this->debts_with_suppliers->find($this->DebtWithSupplierId);
        //$income = $debt->income;
        //dd($income);

        DB::beginTransaction();

        try {

            $now = Carbon::parse(Carbon::now())->format('d-m-Y');

            switch ($this->action){

                case 'ingreso':

                    switch ($this->relation){

                        case 'caja general':

                            CashTransaction::create([

                                'action' => $this->action,
                                'description' => 
                                    $this->description . ' ' .
                                    'en fecha' . ' ' .
                                    $now,
                                'amount' => $this->income_amount,
                                'status_id' => 1,
                                'cashable_id' => CashTransaction::all()->max('id') + 1,
                                'cashable_type' => 'App\Models\CashTransaction'

                            ]);

                        break;

                        case 'cheques de pago':

                            $paycheck = $this->active_paychecks->find($this->PaycheckId);

                            $transaction = $paycheck->CashTransactions()->create([

                                'action' => $this->action,
                                'description' => 
                                    $this->description . ' ' .
                                    'n°' . ' ' .
                                    $paycheck->number . ' ' .
                                    'de cliente' . ' ' .
                                    $paycheck->customer->name . ' ' .
                                    'en fecha' . ' ' .
                                    $now . ' ' .
                                    'por venta' . ' ' .
                                    $paycheck->sale->file_number,
                                'amount' => $this->income_amount,
                                'status_id' => 1
                            ]);

                            $detail = $paycheck->details()->create([

                                'action' => 'egreso',
                                'relation_file_number' => $transaction->file_number,
                                'description' => $transaction->description,
                                'amount' => $transaction->amount,
                                'previus_balance' => $paycheck->amount,
                                'actual_balance' => $paycheck->amount - $transaction->amount,
                                'status_id' => 1
                            ]);

                            $transaction->Update([
    
                                'detail_id' => $detail->id
    
                            ]);

                            if(($paycheck->amount - $transaction->amount) == 0){

                                $sale = $paycheck->sale;

                                $paycheck->Update([
    
                                    'amount' => $paycheck->amount - $transaction->amount,
                                    'status_id' => 2
        
                                ]);

                                $sale->Update([
    
                                    'status_id' => 3
        
                                ]);

                            }else{

                                $paycheck->Update([
    
                                    'amount' => $paycheck->amount - $transaction->amount
        
                                ]);

                            }

                        break;

                        case 'deudas de clientes':

                            $debt = $this->active_customer_debts->find($this->CustomerDebtId);

                            $transaction = $debt->CashTransactions()->create([

                                'action' => $this->action,
                                'description' => 
                                    $this->description . ' ' .
                                    $debt->customer->name . ' ' .
                                    'en fecha' . ' ' .
                                    $now . ' ' .
                                    'por venta' . ' ' .
                                    $debt->sale->file_number,
                                'amount' => $this->income_amount,
                                'status_id' => 1
                            ]);

                            $detail = $debt->details()->create([

                                'action' => 'egreso',
                                'relation_file_number' => $transaction->file_number,
                                'description' => $transaction->description,
                                'amount' => $transaction->amount,
                                'previus_balance' => $debt->amount,
                                'actual_balance' => $debt->amount - $transaction->amount,
                                'status_id' => 1
                            ]);

                            $transaction->Update([
    
                                'detail_id' => $detail->id
    
                            ]);

                            if(($debt->amount - $transaction->amount) == 0){

                                $sale = $debt->sale;

                                $debt->Update([
    
                                    'amount' => $debt->amount - $transaction->amount,
                                    'status_id' => 2
        
                                ]);

                                $sale->Update([
    
                                    'status_id' => 3
        
                                ]);

                            }else{

                                $debt->Update([
    
                                    'amount' => $debt->amount - $transaction->amount
        
                                ]);

                            }

                        break;

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
                                'amount' => $this->income_amount,
                                'status_id' => 1
                            ]);

                            $detail = $account->details()->create([
                                'action' => 'egreso',
                                'relation_file_number' => $transaction->file_number,
                                'description' => $transaction->description,
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

                        case 'caja general':

                            CashTransaction::create([

                                'action' => $this->action,
                                'description' => 
                                    $this->description . ' ' .
                                    'en fecha' . ' ' .
                                    $now,
                                'amount' => $this->discharge_amount,
                                'status_id' => 1,
                                'cashable_id' => CashTransaction::all()->max('id') + 1,
                                'cashable_type' => 'App\Models\CashTransaction'

                            ]);

                        break;

                        case 'deudas con proveedores':

                            $debt = $this->active_debts_with_suppliers->find($this->DebtWithSupplierId);

                            $transaction = $debt->CashTransactions()->create([

                                'action' => $this->action,
                                'description' => 
                                    $this->description . ' ' .
                                    $debt->supplier->name . ' ' .
                                    'en fecha' . ' ' .
                                    $now . ' ' .
                                    'por ingreso' . ' ' .
                                    $debt->income->file_number,
                                'amount' => $this->discharge_amount,
                                'status_id' => 1
                            ]);

                            $detail = $debt->details()->create([

                                'action' => 'egreso',
                                'relation_file_number' => $transaction->file_number,
                                'description' => $transaction->description,
                                'amount' => $transaction->amount,
                                'previus_balance' => $debt->amount,
                                'actual_balance' => $debt->amount - $transaction->amount,
                                'status_id' => 1
                            ]);

                            $transaction->Update([
    
                                'detail_id' => $detail->id
    
                            ]);

                            if(($debt->amount - $transaction->amount) == 0){

                                $income = $debt->income;

                                $debt->Update([
    
                                    'amount' => $debt->amount - $transaction->amount,
                                    'status_id' => 2
        
                                ]);

                                $income->Update([
    
                                    'status_id' => 3
        
                                ]);

                            }else{

                                $debt->Update([
    
                                    'amount' => $debt->amount - $transaction->amount
        
                                ]);

                            }

                        break;

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
                                'amount' => $this->discharge_amount,
                                'status_id' => 1
                            ]);

                            $detail = $account->details()->create([
                                'action' => 'ingreso',
                                'relation_file_number' => $transaction->file_number,
                                'description' => $transaction->description,
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

                        case 'App\Models\Paycheck':

                            $detail = $this->details->find($transaction->detail_id);

                            $paycheck = $this->paychecks->find($detail->detailable_id);

                            if($paycheck->amount != $detail->actual_balance){

                                $this->emit('item-error','Se han realizado transacciones adicionales con la deuda. Anule esas transacciones primero');
                                return;

                            }else{

                                if($paycheck->amount == 0){

                                    $sale = $paycheck->sale;
                                    
                                    $paycheck->update([

                                        'amount' => $paycheck->amount + $transaction->amount,
                                        'status_id' => 1

                                    ]);

                                    $sale->update([

                                        'status_id' => 4

                                    ]);

                                }else{

                                    $paycheck->update([

                                        'amount' => $paycheck->amount + $transaction->amount

                                    ]);

                                }

                                $detail->update([

                                    'status_id' => 2

                                ]);

                            }

                        break;

                        case 'App\Models\CustomerDebt':

                            $detail = $this->details->find($transaction->detail_id);

                            $debt = $this->customer_debts->find($detail->detailable_id);

                            if($debt->amount != $detail->actual_balance){

                                $this->emit('item-error','Se han realizado transacciones adicionales con la deuda. Anule esas transacciones primero');
                                return;

                            }else{

                                if($debt->amount == 0){

                                    $sale = $debt->sale;
                                    
                                    $debt->update([

                                        'amount' => $debt->amount + $transaction->amount,
                                        'status_id' => 1

                                    ]);

                                    $sale->update([

                                        'status_id' => 4

                                    ]);

                                }else{

                                    $debt->update([

                                        'amount' => $debt->amount + $transaction->amount

                                    ]);

                                }

                                $detail->update([

                                    'status_id' => 2

                                ]);

                            }

                        break;
    
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

                        case 'App\Models\DebtsWithSupplier':

                            $detail = $this->details->find($transaction->detail_id);

                            $debt = $this->debts_with_suppliers->find($detail->detailable_id);

                            if($debt->amount != $detail->actual_balance){

                                $this->emit('item-error','Se han realizado transacciones adicionales con la deuda. Anule esas transacciones primero');
                                return;

                            }else{

                                if($debt->amount == 0){

                                    $income = $debt->income;
                                    
                                    $debt->update([

                                        'amount' => $debt->amount + $transaction->amount,
                                        'status_id' => 1

                                    ]);

                                    $income->update([

                                        'status_id' => 4

                                    ]);

                                }else{

                                    $debt->update([

                                        'amount' => $debt->amount + $transaction->amount

                                    ]);

                                }

                                $detail->update([

                                    'status_id' => 2

                                ]);

                            }

                        break;
    
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
