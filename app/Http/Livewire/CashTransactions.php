<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\CashTransaction;
use App\Models\BalanceSheetAccount;
use App\Models\Detail;
use App\Models\Customer;
use App\Models\CustomerDebt;
use App\Models\BankAccount;
use App\Models\Supplier;
use App\Models\DebtsWithSupplier;
use App\Models\Paycheck;
use App\Models\Tax;
use App\Models\MiscellaneousReceivable;
use App\Models\MiscellaneousPayable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CashTransactions extends Component
{

    public $pageTitle,$componentName,$search,$search_2,$total_income,$total_discharge,$my_total,$dateFrom,$dateTo,$reportRange,$transactions,$transactions_2,$selected_id;
    public $action,$description,$income_amount,$discharge_amount,$TypeId,$types,$Temp1,$TempArr1,$Temp2,$TempArr2,$TempBal;
    public $customer_debts,$CustomerId,$customers_with_debts,$CustomerDebtId,$customer_debt_detail,$customer_debt_balance;
    public $customer_paychecks,$PaycheckCustomerId,$customers_with_paychecks,$PaycheckId,$paycheck_detail,$paycheck_description,$paycheck_balance;
    public $bank_accounts,$BankAccountId,$bank_account_balance;
    public $miscellaneus_payables,$MPRELID,$MPREL,$IMPREF,$MPREFID,$DMPREF,$MPDESCID,$MPDESC,$MPBAL;
    public $miscellaneus_receivables,$MRRELID,$MRREL,$DMRREF,$MRREFID,$IMRREF,$MRDESCID,$MRDESC,$MRBAL;
    public $taxes,$TaxId,$active_taxes,$tax_description,$tax_balance;
    public $debts_with_suppliers,$SupplierId,$suppliers_with_debts,$DebtWithSupplierId,$debt_with_supplier_detail,$debt_with_supplier_balance;
    public $balance_sheet_accounts,$details;

    public function paginationView(){

        return 'vendor.livewire.bootstrap';
    }

    public function mount(){

        //$this->Temp1 = 'elegir';    //1er id temporal
        //$this->TempArr1 = [];   //1er array temporal
        //$this->Temp2 = 'elegir';    //2do id temporal
        //$this->TempArr2 = [];   //2do array temporal
        //$this->TempBal = 0;     //balance temporal
        $this->pageTitle = 'listado';
        $this->componentName = 'transacciones en efectivo';
        $this->search = '';
        $this->search_2 = 0;
        $this->total_income = 0;
        $this->total_discharge = 0;
        $this->my_total = 0;
        $this->reportRange = 0;
        $this->transactions = [];
        $this->transactions_2 = CashTransaction::where('status_id',1)->get();
        $this->selected_id = 0;
        $this->action = 'elegir';
        $this->description = '';
        $this->income_amount = '';
        $this->discharge_amount = '';
        $this->TypeId = 'elegir';
        $this->types = [];
        $this->balance_sheet_accounts = BalanceSheetAccount::where('status_id',1)->get();
        $this->customer_debts = CustomerDebt::with(['customer','sale'])
            ->orderBy(Customer::select('name')->whereColumn('customers.id','customer_debts.customer_id'))
            ->get();
        //$this->active_customer_debts = CustomerDebt::where('status_id',1)->with(['customer','sale'])->get();
        /*$this->customers_with_debts = Customer::whereHas('debts',function ($query) {
            $query->where('status_id',1);
            })
            ->with(['debts.sale'])
            ->get();*/
        $this->CustomerId = 'elegir';
        $this->customers_with_debts = [];
        $this->CustomerDebtId = 'elegir';
        $this->customer_debt_detail = [];
        $this->customer_debt_balance = 0;
        $this->customer_paychecks = Paycheck::with(['customer','sale'])
            ->orderBy(Customer::select('name')->whereColumn('customers.id','paychecks.customer_id'))
            ->get();
        $this->PaycheckCustomerId = 'elegir';
        $this->customers_with_paychecks = [];
        $this->PaycheckId = 'elegir';
        $this->paycheck_detail = [];
        $this->paycheck_description = '';
        $this->paycheck_balance = 0;
        $this->bank_accounts = BankAccount::where('status_id',1)->with(['bank','company'])->get();
        $this->BankAccountId = 'elegir';
        $this->bank_account_balance = 0;
        $this->miscellaneus_payables = MiscellaneousPayable::orderBy('reference')->get();
        $this->MPRELID = 'elegir';
        $this->MPREL = [];
        $this->IMPREF = '';
        $this->MPREFID = 'elegir';
        $this->DMPREF = [];
        $this->MPDESCID = 'elegir';
        $this->MPDESC = [];
        $this->MPBAL = 0;
        $this->miscellaneus_receivables = MiscellaneousReceivable::orderBy('reference')->get();
        $this->MRRELID = 'elegir';
        $this->MRREL = [];
        $this->DMRREF = '';
        $this->MRREFID = 'elegir';
        $this->IMRREF = [];
        $this->MRDESCID = 'elegir';
        $this->MRDESC = [];
        $this->MRBAL = 0;
        $this->taxes = Tax::with('taxable')->get();
        $this->TaxId = 'elegir';
        $this->active_taxes = [];
        $this->tax_description = '';
        $this->tax_balance = 0;
        $this->debts_with_suppliers = DebtsWithSupplier::with(['supplier','income'])
            ->orderBy(Supplier::select('name')->whereColumn('suppliers.id','debts_with_suppliers.supplier_id'))    
            ->get();
        $this->SupplierId = 'elegir';
        $this->suppliers_with_debts = [];
        $this->DebtWithSupplierId = 'elegir';
        $this->debt_with_supplier_detail = [];
        $this->details = Detail::where('status_id',1)->get();
        $this->resetValidation();
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
                    ->whereBetween('created_at', [$fecha1, $fecha2])
                    ->orderBy('file_number','asc')
                    ->get();
                }

            break;

            case 1:

                if (strlen($this->search) > 0){

                    $this->transactions = CashTransaction::where('status_id',2)
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
                    ->whereBetween('created_at', [$fecha1, $fecha2])
                    ->orderBy('file_number','asc')
                    ->get();
                }

            break;

        }

        $this->my_total = $this->total_income - $this->total_discharge;

    }

    public function updatedaction(){

        $this->TypeId = 'elegir';
        $this->income_amount = '';
        $this->discharge_amount = '';

        switch ($this->action){

            case 'elegir':

                $this->types = [];

            break;

            case 'ingreso':

                $this->types = ['caja','clientes','cheques','bancario','documentos','otros por pagar','otros por cobrar'];

            break;

            case 'egreso':

                $this->types = ['caja','bancario','impuestos','inmuebles','intangibles','documentos','proveedores','otros por pagar','otros por cobrar'];

            break;

        }

    }

    public function updatedTypeId(){

        //$this->Temp1 = 'elegir';

        switch ($this->TypeId){

            /*case 'elegir':

                $this->TempArr1 = [];

            break;*/

            case 'clientes':

                $this->CustomerId = 'elegir';
                $this->customers_with_debts = $this->customer_debts->where('status_id',1)->unique('customer_id');

            break;

            case 'cheques':

                $this->PaycheckCustomerId = 'elegir';
                $this->customers_with_paychecks = $this->customer_paychecks->where('status_id',1)->unique('customer_id');

            break;

            case 'bancario':

                $this->BankAccountId = 'elegir';
                //$this->TempArr1 = $this->bank_accounts;

            break;

            case 'otros por pagar':

                $this->MPRELID = 'elegir';
                $this->MPREL = ['empleados','externos','otros'];

            break;

            case 'otros por cobrar':

                $this->MRRELID = 'elegir';
                $this->MRREL = ['empleados','externos','otros'];

            break;

            case 'impuestos':

                $this->TaxId = 'elegir';
                $this->active_taxes = $this->taxes->where('status_id',1);

            break;

            case 'proveedores':

                $this->SupplierId = 'elegir';
                $this->suppliers_with_debts = $this->debts_with_suppliers->where('status_id',1)->unique('supplier_id');

            break;

        }

    }

    public function updatedCustomerId($customer_id){

        $this->CustomerDebtId = 'elegir';

        $this->customer_debt_detail = $this->customer_debts->where('status_id',1)->where('customer_id',$customer_id);

        /*$customer = $this->customers_with_debts->find($customer_id);
        
        if($customer != null){

            $this->customer_debt_detail = $customer->debts->where('status_id',1);

        }else{

            $this->customer_debt_detail = [];

        }*/
    }

    public function updatedCustomerDebtId($id){

        /*if($id != 'elegir'){

            $this->customer_debt_balance = number_format($this->customer_debt_detail->firstWhere('id',$id)->amount,2);

        }else{

            $this->customer_debt_balance = 0;

        }*/

        if($this->customer_debts->firstWhere('id',$id) != null){

            $this->customer_debt_balance = number_format($this->customer_debts->firstWhere('id',$id)->amount,2);

        }else{

            $this->customer_debt_balance = 0;

        }

    }

    public function updatedPaycheckCustomerId($customer_id){

        $this->PaycheckId = 'elegir';

        $this->paycheck_detail = $this->customer_paychecks->where('status_id',1)->where('customer_id',$customer_id);
    }

    public function updatedPaycheckId($id){

        if($this->customer_paychecks->firstWhere('id',$id) != null){

            $this->paycheck_description = $this->customer_paychecks->firstWhere('id',$id)->description;
            $this->paycheck_balance = number_format($this->customer_paychecks->firstWhere('id',$id)->amount,2);

        }else{
            
            $this->paycheck_description = '';
            $this->paycheck_balance = 0;

        }

    }

    public function updatedBankAccountId($id){

        if($this->bank_accounts->firstWhere('id',$id) != null){

            $this->bank_account_balance = number_format($this->bank_accounts->firstWhere('id',$id)->balance,2);

        }else{

            $this->bank_account_balance = 0;

        }

    }

    public function updatedMPRELID($relation){

        $this->MPREFID = 'elegir';
        $this->DMPREF = $this->miscellaneus_payables->where('status_id',1)->where('relation',$relation)->unique('reference');

    }

    public function updatedMPREFID($reference){

        $this->MPDESCID = 'elegir';
        $this->MPDESC = $this->miscellaneus_payables->where('status_id',1)->where('reference',$reference);

    }

    public function updatedMPDESCID($id){

        if($this->miscellaneus_payables->firstWhere('id',$id) != null){

            $this->MPBAL = number_format($this->miscellaneus_payables->firstWhere('id',$id)->amount,2);

        }else{

            $this->MPBAL = 0;

        }

    }

    public function updatedMRRELID($relation){

        $this->MRREFID = 'elegir';
        $this->IMRREF = $this->miscellaneus_receivables->where('status_id',1)->where('relation',$relation)->unique('reference');

    }

    public function updatedMRREFID($reference){

        $this->MRDESCID = 'elegir';
        $this->MRDESC = $this->miscellaneus_receivables->where('status_id',1)->where('reference',$reference);

    }

    public function updatedMRDESCID($id){

        if($this->miscellaneus_receivables->firstWhere('id',$id) != null){

            $this->MRBAL = number_format($this->miscellaneus_receivables->firstWhere('id',$id)->amount,2);

        }else{

            $this->MRBAL = 0;

        }

    }

    public function updatedTaxId($id)
    {
        if($this->taxes->firstWhere('id',$id) != null){

            $this->tax_description = $this->taxes->firstWhere('id',$id)->description;
            $this->tax_balance = number_format($this->taxes->firstWhere('id',$id)->amount,2);

        }else{
            
            $this->tax_description = '';
            $this->tax_balance = 0;

        }
    }

    public function updatedSupplierId($supplier_id)
    {
        $this->DebtWithSupplierId = 'elegir';

        $this->debt_with_supplier_detail = $this->debts_with_suppliers->where('status_id',1)->where('supplier_id',$supplier_id);

    }

    public function updatedDebtWithSupplierId($id){

        if($this->debts_with_suppliers->firstWhere('id',$id) != null){

            $this->debt_with_supplier_balance = number_format($this->debts_with_suppliers->firstWhere('id',$id)->amount,2);

        }else{

            $this->debt_with_supplier_balance = 0;

        }

    }

    /*public function updatedTemp1($id){

        $this->Temp2 = 'elegir';

        switch ($this->TypeId){

            case 'elegir':

                $this->TempArr2 = [];

            break;

            case 'clientes':

                $this->TempArr2 = $this->customer_debts->where('status_id',2)->where('customer_id',$id);

            break;

            case 'cheques':

                $this->TempArr2 = $this->customer_paychecks->where('status_id',2)->where('customer_id',$id);

            break;

        }

    }

    public function updatedTemp2($id){

        switch ($this->TypeId){

            case 'elegir':

                $this->TempBal = 0;

            break;

            case 'clientes':

                $this->TempBal = number_format($this->customer_debts->firstWhere('id',$id)->amount,2);

            break;

        }

    }*/

    public function Store(){

        //$debt = $this->debts_with_suppliers->find($this->DebtWithSupplierId);
        //dd($debt);

        $rules = [

            'description' => 'required|min:10|max:255',
            'action' => 'not_in:elegir',
            'TypeId' => 'exclude_if:action,elegir|not_in:elegir',
            'income_amount' => 'exclude_unless:action,ingreso|required|gt:0',
            'discharge_amount' => "exclude_unless:action,egreso|required|gt:0|lte:$this->my_total",
            //'Temp1' => 'exclude_unless:action,ingreso|exclude_if:TypeId,elegir|exclude_if:TypeId,caja|not_in:elegir',
            'CustomerId' => 'exclude_unless:TypeId,clientes|not_in:elegir',
            'CustomerDebtId' => 'exclude_unless:TypeId,clientes|exclude_if:CustomerId,elegir|not_in:elegir',
            'customer_debt_balance' => 'exclude_unless:TypeId,clientes|exclude_if:CustomerId,elegir|exclude_if:CustomerDebtId,elegir|gte:income_amount',
            'PaycheckCustomerId' => 'exclude_unless:TypeId,cheques|not_in:elegir',
            'PaycheckId' => 'exclude_unless:TypeId,cheques|exclude_if:PaycheckCustomerId,elegir|not_in:elegir',
            'paycheck_balance' => 'exclude_unless:TypeId,cheques|exclude_if:PaycheckCustomerId,elegir|exclude_if:PaycheckId,elegir|gte:income_amount',
            'BankAccountId' => 'exclude_unless:TypeId,bancario|not_in:elegir',
            'bank_account_balance' => 'exclude_unless:TypeId,bancario|exclude_if:BankAccountId,elegir|exclude_if:action,egreso|gte:income_amount',
            'MPRELID' => 'exclude_unless:TypeId,otros por pagar|not_in:elegir',
            'IMPREF' => 'exclude_unless:TypeId,otros por pagar|exclude_if:MPRELID,elegir|exclude_if:action,egreso|required|min:3|max:45',
            'MPREFID' => 'exclude_unless:TypeId,otros por pagar|exclude_if:MPRELID,elegir|exclude_if:action,ingreso|not_in:elegir',
            'MPDESCID' => 'exclude_unless:TypeId,otros por pagar|exclude_if:MPRELID,elegir|exclude_if:MPREFID,elegir|exclude_if:action,ingreso|not_in:elegir',
            'MPBAL' => 'exclude_unless:TypeId,otros por pagar|exclude_if:MPRELID,elegir|exclude_if:MPREFID,elegir|exclude_if:MPDESCID,elegir|exclude_if:action,ingreso|gte:discharge_amount',
            'MRRELID' => 'exclude_unless:TypeId,otros por cobrar|not_in:elegir',
            'DMRREF' => 'exclude_unless:TypeId,otros por cobrar|exclude_if:MRRELID,elegir|exclude_if:action,ingreso|required|min:3|max:45',
            'MRREFID' => 'exclude_unless:TypeId,otros por cobrar|exclude_if:MRRELID,elegir|exclude_if:action,egreso|not_in:elegir',
            'MRDESCID' => 'exclude_unless:TypeId,otros por cobrar|exclude_if:MRRELID,elegir|exclude_if:MRREFID,elegir|exclude_if:action,egreso|not_in:elegir',
            'MRBAL' => 'exclude_unless:TypeId,otros por cobrar|exclude_if:MRRELID,elegir|exclude_if:MRREFID,elegir|exclude_if:MRDESCID,elegir|exclude_if:action,egreso|gte:income_amount',
            'TaxId' => 'exclude_unless:TypeId,impuestos|not_in:elegir',
            'tax_balance' => 'exclude_unless:TypeId,impuestos|exclude_if:TaxId,elegir|gte:discharge_amount',
            'SupplierId' => 'exclude_unless:TypeId,proveedores|not_in:elegir',
            'DebtWithSupplierId' => 'exclude_unless:TypeId,proveedores|exclude_if:SupplierId,elegir|not_in:elegir',
            'debt_with_supplier_balance' => 'exclude_unless:TypeId,proveedores|exclude_if:SupplierId,elegir|exclude_if:DebtWithSupplierId,elegir|gte:discharge_amount',
        ];

        $messages = [

            'description.required' => 'Campo requerido',
            'description.min' => 'Minimo 10 caracteres',
            'description.max' => 'Maximo 255 caracteres',
            'action.not_in' => 'Seleccione una opcion',
            'TypeId.not_in' => 'Seleccione una opcion',
            'income_amount.required' => 'Campo requerido',
            'income_amount.gt' => 'El monto debe ser mayor a 0',
            'discharge_amount.required' => 'Campo requerido',
            'discharge_amount.gt' => 'El monto debe ser mayor a 0',
            'discharge_amount.lte' => 'Saldo de caja insuficiente',
            //'Temp1.not_in' => 'Seleccione una opcion',
            'CustomerId.not_in' => 'Seleccione una opcion',
            'CustomerDebtId.not_in' => 'Seleccione una opcion',
            'customer_debt_balance.gte' => 'Saldo sobrepasado',
            'PaycheckCustomerId.not_in' => 'Seleccione una opcion',
            'PaycheckId.not_in' => 'Seleccione una opcion',
            'paycheck_balance.gte' => 'Saldo sobrepasado',
            'BankAccountId.not_in' => 'Seleccione una opcion',
            'bank_account_balance.gte' => 'Saldo sobrepasado',
            'MPRELID.not_in' => 'Seleccione una opcion',
            'IMPREF.required' => 'Campo requerido',
            'IMPREF.min' => 'Minimo 3 caracteres',
            'IMPREF.max' => 'Maximo 45 caracteres',
            'MPREFID.not_in' => 'Seleccione una opcion',
            'MPDESCID.not_in' => 'Seleccione una opcion',
            'MPBAL.gte' => 'Saldo sobrepasado',
            'MRRELID.not_in' => 'Seleccione una opcion',
            'DMRREF.required' => 'Campo requerido',
            'DMRREF.min' => 'Minimo 3 caracteres',
            'DMRREF.max' => 'Maximo 45 caracteres',
            'MRREFID.not_in' => 'Seleccione una opcion',
            'MRDESCID.not_in' => 'Seleccione una opcion',
            'MRBAL.gte' => 'Saldo sobrepasado',
            'TaxId.not_in' => 'Seleccione una opcion',
            'tax_balance.gte' => 'Saldo sobrepasado',
            'SupplierId.not_in' => 'Seleccione una opcion',
            'DebtWithSupplierId.not_in' => 'Seleccione una opcion',
            'debt_with_supplier_balance.gte' => 'Saldo sobrepasado',
        ];
        
        $this->validate($rules, $messages);

        DB::beginTransaction();

        try {

            $now = Carbon::parse(Carbon::now())->format('d-m-Y');
            $cash_transaction_account = $this->balance_sheet_accounts->find(1);

            if($this->balance_sheet_accounts->firstWhere('alias',$this->TypeId) != null){

                $account = $this->balance_sheet_accounts->firstWhere('alias',$this->TypeId);

            }else{

                $bank_account = $this->bank_accounts->find($this->BankAccountId);
                $account = $this->balance_sheet_accounts->firstWhere('name',$bank_account->number);

            }

            switch ($this->action){

                case 'ingreso':

                    $transaction = CashTransaction::create([

                        'action' => $this->action,
                        'type' => $this->TypeId,
                        'description' => 
                            'ingreso a caja en fecha' . ' ' .
                            $now . ' ' .
                            'por' . ' ' .
                            $this->description,
                        'amount' => $this->income_amount,
                        'status_id' => 1,
                        'balance_sheet_account_id' => $account->id
        
                    ]);

                    switch ($transaction->type){

                        case 'clientes':

                            //$debt = $this->active_customer_debts->find($this->CustomerDebtId);
                            $debt = $this->customer_debts->find($this->CustomerDebtId);
        
                            $detail = $debt->details()->create([

                                'action' => 'egreso',
                                'relation_file_number' => $transaction->file_number,
                                'description' => 
                                    $transaction->description . ' ' .
                                    $debt->customer->name . ' ' .
                                    'por venta con recibo' . ' ' .
                                    $debt->sale->file_number,
                                'amount' => $transaction->amount,
                                'previus_balance' => $debt->amount,
                                'actual_balance' => $debt->amount - $transaction->amount,
                                'status_id' => 1
                            ]);

                            $transaction->Update([
                                
                                'description' => $detail->description,
                                'relation' => $detail->id
    
                            ]);

                            $account->Update([
                                
                                'balance' => $account->balance - $transaction->amount
    
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

                        case 'cheques':

                            $paycheck = $this->customer_paychecks->find($this->PaycheckId);

                            $detail = $paycheck->details()->create([

                                'action' => 'egreso',
                                'relation_file_number' => $transaction->file_number,
                                'description' => 
                                $transaction->description . ' ' .
                                'n°' . ' ' .
                                $paycheck->number . ' ' .
                                'de cliente' . ' ' .
                                $paycheck->customer->name . ' ' .
                                'por venta con recibo' . ' ' .
                                $paycheck->sale->file_number,
                                'amount' => $transaction->amount,
                                'previus_balance' => $paycheck->amount,
                                'actual_balance' => $paycheck->amount - $transaction->amount,
                                'status_id' => 1
                            ]);

                            $transaction->Update([
                                
                                'description' =>  $detail->description,
                                'relation' => $detail->id
    
                            ]);

                            $account->Update([
                                
                                'balance' => $account->balance - $transaction->amount
    
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

                        case 'bancario':

                            $detail = $bank_account->details()->create([
                                'action' => 'egreso',
                                'relation_file_number' => $transaction->file_number,
                                'description' =>
                                $transaction->description . ' ' .
                                $bank_account->bank->alias . '-' .
                                $bank_account->company->alias . '-' .
                                $bank_account->number,
                                'amount' => $transaction->amount,
                                'previus_balance' => $bank_account->balance,
                                'actual_balance' => $bank_account->balance - $transaction->amount,
                                'status_id' => 1
                            ]);

                            $transaction->Update([
                                
                                'description' => $detail->description,
                                'relation' => $detail->id
    
                            ]);

                            $bank_account->update([

                                'balance' => $bank_account->balance - $transaction->amount

                            ]);

                            $account->Update([
                                
                                'balance' => $account->balance - $transaction->amount
    
                            ]);

                        break;

                        case 'documentos':

                            $account->update([

                                'balance' => $account->balance + $transaction->amount

                            ]);

                        break;

                        case 'otros por pagar':

                            $payable = MiscellaneousPayable::create([

                                'relation' => $this->MPRELID,
                                'reference' => $this->IMPREF,
                                'description' => 
                                $transaction->description . ' ' .
                                'con' . ' ' .
                                $this->IMPREF,
                                'amount' => $transaction->amount,
                                'status_id' => 1

                            ]);

                            $account->Update([
                                
                                'balance' => $account->balance + $transaction->amount
    
                            ]);

                            $transaction->Update([
                                
                                'description' => $payable->description,
                                'relation' => $payable->id

                            ]);

                        break;

                        case 'otros por cobrar':

                            $debt = $this->miscellaneus_receivables->find($this->MRDESCID);
        
                            $detail = $debt->details()->create([

                                'action' => 'egreso',
                                'relation_file_number' => $transaction->file_number,
                                'description' => 
                                    $transaction->description . ' ' .
                                    'de' . ' ' .
                                    $debt->reference,
                                'amount' => $transaction->amount,
                                'previus_balance' => $debt->amount,
                                'actual_balance' => $debt->amount - $transaction->amount,
                                'status_id' => 1
                            ]);

                            $transaction->Update([
                                
                                'description' => $detail->description,
                                'relation' => $detail->id
    
                            ]);

                            $account->Update([
                                
                                'balance' => $account->balance - $transaction->amount
    
                            ]);

                            if(($debt->amount - $transaction->amount) == 0){

                                $debt->Update([
    
                                    'amount' => $debt->amount - $transaction->amount,
                                    'status_id' => 2
        
                                ]);


                            }else{

                                $debt->Update([
    
                                    'amount' => $debt->amount - $transaction->amount
        
                                ]);

                            }

                        break;

                    }

                    $cash_transaction_account->update([

                        'balance' => $cash_transaction_account->balance + $transaction->amount

                    ]);

                break;

                case 'egreso':

                    $transaction = CashTransaction::create([

                        'action' => $this->action,
                        'type' => $this->TypeId,
                        'description' => 
                            'egreso de caja en fecha' . ' ' .
                            $now . ' ' .
                            'por' . ' ' .
                            $this->description,
                        'amount' => $this->discharge_amount,
                        'status_id' => 1,
                        'balance_sheet_account_id' => $account->id
        
                    ]);

                    switch ($transaction->type){

                        case 'bancario':

                            $detail = $bank_account->details()->create([
                                'action' => 'ingreso',
                                'relation_file_number' => $transaction->file_number,
                                'description' => 
                                $transaction->description . ' ' .
                                $bank_account->bank->alias . '-' .
                                $bank_account->company->alias . '-' .
                                $bank_account->number,
                                'amount' => $transaction->amount,
                                'previus_balance' => $bank_account->balance,
                                'actual_balance' => $bank_account->balance + $transaction->amount,
                                'status_id' => 1
                            ]);

                            $transaction->Update([
                                
                                'description' => $detail->description,
                                'relation' => $detail->id
    
                            ]);

                            $bank_account->update([

                                'balance' => $bank_account->balance + $transaction->amount

                            ]);

                            $account->update([

                                'balance' => $account->balance + $transaction->amount

                            ]);

                        break;

                        case 'impuestos':

                            $tax = $this->taxes->find($this->TaxId);

                            $detail = $tax->details()->create([

                                'action' => 'egreso',
                                'relation_file_number' => $transaction->file_number,
                                'description' => $transaction->description,
                                'amount' => $transaction->amount,
                                'previus_balance' => $tax->amount,
                                'actual_balance' => $tax->amount - $transaction->amount,
                                'status_id' => 1
                            ]);

                            $transaction->Update([
    
                                'relation' => $detail->id
    
                            ]);

                            if(($tax->amount - $transaction->amount) == 0){

                                $tax->Update([
    
                                    'amount' => $tax->amount - $transaction->amount,
                                    'status_id' => 2
        
                                ]);

                            }else{

                                $tax->Update([
    
                                    'amount' => $tax->amount - $transaction->amount
        
                                ]);

                            }

                            $account->update([

                                'balance' => $account->balance - $transaction->amount
        
                            ]);

                        break;

                        case 'inmuebles':

                            $account->update([

                                'balance' => $account->balance + $transaction->amount

                            ]);

                        break;

                        case 'intangibles':

                            $account->update([

                                'balance' => $account->balance + $transaction->amount

                            ]);

                        break;

                        case 'documentos':

                            $account->update([

                                'balance' => $account->balance - $transaction->amount

                            ]);

                        break;

                        case 'proveedores':

                            $debt = $this->debts_with_suppliers->find($this->DebtWithSupplierId);

                            $detail = $debt->details()->create([

                                'action' => 'egreso',
                                'relation_file_number' => $transaction->file_number,
                                'description' => 
                                    $transaction->description . ' ' .
                                    $debt->supplier->name . ' ' .
                                    'por compra de mercaderia con recibo n°' . ' ' .
                                    $debt->income->file_number,
                                'amount' => $transaction->amount,
                                'previus_balance' => $debt->amount,
                                'actual_balance' => $debt->amount - $transaction->amount,
                                'status_id' => 1
                            ]);

                            $transaction->Update([
                                
                                'description' => $detail->description,
                                'relation' => $detail->id
    
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

                            $account->update([

                                'balance' => $account->balance - $transaction->amount
        
                            ]);

                        break;

                        case 'otros por pagar':

                            $debt = $this->miscellaneus_payables->find($this->MPDESCID);
        
                            $detail = $debt->details()->create([

                                'action' => 'egreso',
                                'relation_file_number' => $transaction->file_number,
                                'description' => 
                                    $transaction->description . ' ' .
                                    'con' . ' ' .
                                    $debt->reference,
                                'amount' => $transaction->amount,
                                'previus_balance' => $debt->amount,
                                'actual_balance' => $debt->amount - $transaction->amount,
                                'status_id' => 1
                            ]);

                            $transaction->Update([
                                
                                'description' => $detail->description,
                                'relation' => $detail->id
    
                            ]);

                            $account->Update([
                                
                                'balance' => $account->balance - $transaction->amount
    
                            ]);

                            if(($debt->amount - $transaction->amount) == 0){

                                $debt->Update([
    
                                    'amount' => $debt->amount - $transaction->amount,
                                    'status_id' => 2
        
                                ]);


                            }else{

                                $debt->Update([
    
                                    'amount' => $debt->amount - $transaction->amount
        
                                ]);

                            }

                        break;

                        case 'otros por cobrar':

                            $debt = MiscellaneousReceivable::create([

                                'relation' => $this->MRRELID,
                                'reference' => $this->DMRREF,
                                'description' => 
                                    $transaction->description . ' ' .
                                    $this->DMRREF,
                                'amount' => $transaction->amount,
                                'status_id' => 1

                            ]);

                            $account->Update([
                                
                                'balance' => $account->balance + $transaction->amount
    
                            ]);

                            $transaction->Update([
                                
                                'description' => $debt->description,
                                'relation' => $debt->id
    
                            ]);

                        break;

                    }

                    $cash_transaction_account->update([

                        'balance' => $cash_transaction_account->balance - $transaction->amount

                    ]);

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
        $cash_transaction_account = $this->balance_sheet_accounts->find(1);
        $account = $this->balance_sheet_accounts->find($transaction->balance_sheet_account_id);
        //$detail = $this->details->find($transaction->relation);
        //$debt = $this->miscellaneus_payables->find($detail->detailable_id);
        //dd($account);

        DB::beginTransaction();

        try {
            
            switch ($transaction->action){

                case 'ingreso':

                    switch ($transaction->type){

                        case 'clientes':

                            $detail = $this->details->find($transaction->relation);
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

                                $account->update([

                                    'balance' => $account->balance + $transaction->amount

                                ]);

                            }

                        break;

                        case 'cheques':
        
                            $detail = $this->details->find($transaction->relation);
                            $paycheck = $this->customer_paychecks->find($detail->detailable_id);

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

                                $account->update([

                                    'balance' => $account->balance + $transaction->amount

                                ]);

                            }

                        break;

                        case 'bancario':
    
                            $bank_account = $this->bank_accounts->firstWhere('number',$account->name);
                            $detail = $this->details->find($transaction->relation);

                            if($bank_account->balance != ($detail->previus_balance - $transaction->amount)){

                                $this->emit('item-error','Se han realizado transacciones adicionales con la cuenta de destino. Anule esas transacciones primero');
                                return;

                            }else{

                                $bank_account->update([

                                    'balance' => $bank_account->balance + $transaction->amount

                                ]);

                                $detail->update([

                                    'status_id' => 2

                                ]);

                                $account->update([

                                    'balance' => $account->balance + $transaction->amount

                                ]);

                            }
    
                        break;

                        case 'documentos':

                            $account->update([

                                'balance' => $account->balance - $transaction->amount

                            ]);

                        break;

                        case 'otros por pagar':

                            $debt = $this->miscellaneus_payables->find($transaction->relation);

                            if($debt->amount != $transaction->amount){

                                $this->emit('item-error','Se han realizado transacciones adicionales con la deuda. Anule esas transacciones primero');
                                return;

                            }else{

                                $debt->update([

                                    'status_id' => 2

                                ]);

                                $account->update([

                                    'balance' => $account->balance - $transaction->amount

                                ]);
                                
                            }

                        break;

                        case 'otros por cobrar':

                            $detail = $this->details->find($transaction->relation);
                            $debt = $this->miscellaneus_receivables->find($detail->detailable_id);

                            if($debt->amount != $detail->actual_balance){

                                $this->emit('item-error','Se han realizado transacciones adicionales con la deuda. Anule esas transacciones primero');
                                return;

                            }else{

                                if($debt->amount == 0){
                                    
                                    $debt->update([

                                        'amount' => $debt->amount + $transaction->amount,
                                        'status_id' => 1

                                    ]);


                                }else{

                                    $debt->update([

                                        'amount' => $debt->amount + $transaction->amount

                                    ]);

                                }

                                $detail->update([

                                    'status_id' => 2

                                ]);

                                $account->update([

                                    'balance' => $account->balance + $transaction->amount

                                ]);

                            }

                        break;

                    }

                    $cash_transaction_account->update([

                        'balance' => $cash_transaction_account->balance - $transaction->amount

                    ]);
    
                break;
    
                case 'egreso':
    
                    switch ($transaction->type){

                        case 'bancario':
    
                            $bank_account = $this->bank_accounts->firstWhere('number',$account->name);
                            $detail = $this->details->find($transaction->relation);

                            if($bank_account->balance != ($detail->previus_balance + $transaction->amount)){

                                $this->emit('item-error','Se han realizado transacciones adicionales con la cuenta de destino. Anule esas transacciones primero');
                                return;

                            }else{

                                $bank_account->update([

                                    'balance' => $bank_account->balance - $transaction->amount

                                ]);

                                $account->update([

                                    'balance' => $account->balance - $transaction->amount

                                ]);

                                $detail->update([

                                    'status_id' => 2

                                ]);

                            }
    
                        break;

                        case 'impuestos':

                            $detail = $this->details->find($transaction->relation);

                            $tax = $this->taxes->find($detail->detailable_id);

                            if($tax->amount != $detail->actual_balance){

                                $this->emit('item-error','Se han realizado transacciones adicionales con la deuda. Anule esas transacciones primero');
                                return;

                            }else{

                                if($tax->amount == 0){
                                    
                                    $tax->update([

                                        'amount' => $tax->amount + $transaction->amount,
                                        'status_id' => 1

                                    ]);

                                }else{

                                    $tax->update([

                                        'amount' => $tax->amount + $transaction->amount

                                    ]);

                                }

                                $detail->update([

                                    'status_id' => 2

                                ]);

                                $account->update([

                                    'balance' => $account->balance + $transaction->amount
            
                                ]);

                            }

                        break;

                        case 'inmuebles':

                            $account->update([

                                'balance' => $account->balance - $transaction->amount

                            ]);

                        break;

                        case 'intangibles':

                            $account->update([

                                'balance' => $account->balance - $transaction->amount

                            ]);

                        break;

                        case 'documentos':

                            $account->update([

                                'balance' => $account->balance + $transaction->amount

                            ]);

                        break;

                        case 'proveedores':

                            $detail = $this->details->find($transaction->relation);

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

                                $account->update([

                                    'balance' => $account->balance + $transaction->amount
            
                                ]);

                            }

                        break;
                        
                        case 'otros por cobrar':

                            $debt = $this->miscellaneus_receivables->find($transaction->relation);

                            if($debt->amount != $transaction->amount){

                                $this->emit('item-error','Se han realizado transacciones adicionales con la deuda. Anule esas transacciones primero');
                                return;

                            }else{

                                $debt->update([

                                    'status_id' => 2

                                ]);

                                $account->update([

                                    'balance' => $account->balance - $transaction->amount

                                ]);
                                
                            }

                        break;

                        case 'otros por pagar':

                            $detail = $this->details->find($transaction->relation);
                            $debt = $this->miscellaneus_payables->find($detail->detailable_id);

                            if($debt->amount != $detail->actual_balance){

                                $this->emit('item-error','Se han realizado transacciones adicionales con la deuda. Anule esas transacciones primero');
                                return;

                            }else{

                                if($debt->amount == 0){
                                    
                                    $debt->update([

                                        'amount' => $debt->amount + $transaction->amount,
                                        'status_id' => 1

                                    ]);


                                }else{

                                    $debt->update([

                                        'amount' => $debt->amount + $transaction->amount

                                    ]);

                                }

                                $detail->update([

                                    'status_id' => 2

                                ]);

                                $account->update([

                                    'balance' => $account->balance + $transaction->amount

                                ]);

                            }

                        break;
    
                    }

                    $cash_transaction_account->update([

                        'balance' => $cash_transaction_account->balance + $transaction->amount

                    ]);
    
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
