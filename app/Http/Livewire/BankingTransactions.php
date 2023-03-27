<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\BankAccount;
use App\Models\Detail;
use App\Models\Cover;
use App\Models\Bank;
use App\Models\Company;
use Carbon\Carbon;

class BankingTransactions extends Component
{

    public $componentName, $transactions, $reportRange, $account_id, $dateFrom, $dateTo,$search_2;

    public function mount()
    {

        $this->componentName = 'transacciones bancarias';
        $this->transactions = [];
        $this->reportRange = 0;
        $this->account_id = 0;
        $this->search_2 = 0;
        //$this->dateFrom = Carbon::parse(Carbon::today())->format('Y-m-d') . ' 00:00:00';
        //$this->dateTo = Carbon::parse(Carbon::today())->format('Y-m-d') . ' 23:59:59';
        //$this->cov = Cover::firstWhere('description',$this->componentName);
        //$this->cov_det = $this->cov->details->where('cover_id',$this->cov->id)->whereBetween('created_at',[$this->from, $this->to])->first();
    }

    public function render()
    {
        $this->ReportsByDate();

        return view('livewire.banking_transaction.banking-transactions', [

            'accounts' => BankAccount::where('status_id', 1)->with('company', 'bank')->get()
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function ReportsByDate()
    {

        if ($this->reportRange == 0) {

            $from = Carbon::parse(Carbon::today())->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse(Carbon::today())->format('Y-m-d') . ' 23:59:59';
        } else {

            $from = Carbon::parse($this->dateFrom)->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse($this->dateTo)->format('Y-m-d') . ' 23:59:59';
        }

        if ($this->reportRange == 1 && ($this->dateFrom == '' || $this->dateTo == '')) {

            $this->emit('report-error', 'Seleccione fecha de inicio y fecha de fin');
            return;
        }

        switch ($this->search_2){

            case 0:

                if($this->account_id == 0){

                    $this->transactions = Detail::where('status_id',1)
                    ->where('detailable_type','App\Models\BankAccount')
                    ->whereBetween('created_at', [$from, $to])
                    ->orderBy('action', 'asc')
                    ->get();
        
                }else{
                    
                    $this->transactions = Detail::where('status_id',1)
                    ->where('detailable_type','App\Models\BankAccount')
                    ->where('detailable_id',$this->account_id)
                    ->whereBetween('created_at', [$from, $to])
                    ->orderBy('action', 'asc')
                    ->get();
                }

            break;

            case 1:

                if($this->account_id == 0){

                    $this->transactions = Detail::where('status_id',2)
                    ->where('detailable_type','App\Models\BankAccount')
                    ->whereBetween('created_at', [$from, $to])
                    ->orderBy('action', 'asc')
                    ->get();
        
                }else{
                    
                    $this->transactions = Detail::where('status_id',2)
                    ->where('detailable_type','App\Models\BankAccount')
                    ->where('detailable_id',$this->account_id)
                    ->whereBetween('created_at', [$from, $to])
                    ->orderBy('action', 'asc')
                    ->get();
                }

            break;

        }
        
    }

    protected $listeners = [

        'destroy' => 'Destroy'
    ];

    /*public function Destroy(Detail $det){

        $account = BankAccount::firstWhere('id',$det->detailable_id);
        $company = Company::firstWhere('id',$account->company_id)->description;
        $bank = Bank::firstWhere('id',$account->bank_id)->description;
        $cov = Cover::firstWhere('description',$bank . ' ' . $account->type . ' ' . $account->currency . ' ' . $company);
        $cov_det = $cov->details->where('cover_id',$cov->id)->whereBetween('created_at',[$this->from, $this->to])->first();

        if($det->actual_balance > $det->previus_balance){

            if(($det->actual_balance - $det->amount) == ($account->amount - $det->amount)){

                $account->update([
                
                    'amount' => $account->amount - $det->amount

                ]);

                $cov->update([

                    'balance' => $cov->balance - $det->amount

                ]);

                $cov_det->update([

                    'ingress' => $cov_det->ingress - $det->amount,
                    'actual_balance' => $cov_det->actual_balance - $det->amount

                ]);

                $det->delete();
                $this->emit('report-error', 'Movimiento Anulado.');

            }else{

                $this->emit('report-error', 'El saldo no coincide. Anule los movimientos mas recientes.');
                return;
            }

        }else{

            if(($det->actual_balance + $det->amount) == ($account->amount + $det->amount)){
                
                $account->update([
            
                    'amount' => $account->amount + $det->amount
    
                ]);
    
                $cov->update([
        
                    'balance' => $cov->balance + $det->amount
    
                ]);
    
                $cov_det->update([
    
                    'egress' => $cov_det->egress - $det->amount,
                    'actual_balance' => $cov_det->actual_balance + $det->amount
    
                ]);

                $det->delete();
                $this->emit('report-error', 'Movimiento Anulado.');

            }else{

                $this->emit('report-error', 'El saldo no coincide. Anule los movimientos mas recientes.');
                return;
            }
        }

        $this->mount();
        $this->render();
    }*/
}
