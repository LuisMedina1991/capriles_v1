<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Income;
use App\Models\User;
use App\Models\Sale;
use App\Models\Transfer;
use App\Models\Detail;
use App\Models\Office;
use App\Models\OfficeValue;
use App\Models\Status;
use App\Models\BankAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Reports extends Component
{

    public $componentName, $data, $details, $sumDetails, $countDetails, $reportRange, $userId, $search;
    public $dateFrom, $dateTo, $saleId, $reportType, $incomes, $sales, $transfers, $status_ok, $status_no, $stocks, $offices, $reportStatus;
    public $desde, $hasta;

    public function mount()
    {

        $this->componentName = 'REPORTES DE INGRESOS | EGRESOS | TRASPASOS';
        $this->data = [];
        $this->details = [];
        $this->sumDetails = 0;
        $this->countDetails = 0;
        $this->reportRange = 0;
        $this->reportType = 0;
        $this->reportStatus = 0;
        $this->userId = 0;
        $this->saleId = 0;
        $this->incomes = [];
        $this->transfers = [];
        $this->sales = [];
        $this->stocks = OfficeValue::all();
        $this->offices = Office::all();
        $this->status_ok = Status::firstWhere('name', 'realizado');
        $this->status_no = Status::firstWhere('name', 'anulado');
        $this->desde = Carbon::parse(Carbon::today())->format('Y-m-d') . ' 00:00:00';
        $this->hasta = Carbon::parse(Carbon::today())->format('Y-m-d') . ' 23:59:59';
    }

    public function render()
    {
        $this->ReportsByDate();

        return view('livewire.report.reports', [
            'users' => User::orderBy('name', 'asc')->get()
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


        if ($this->reportStatus == 0) {

            if (strlen($this->search) > 0) {

                if ($this->userId == 0) {

                    $this->incomes = Income::with([
                        'status',
                        'tax',
                        'user',
                        'supplier',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', '!=', $this->status_no->id)
                        ->whereBetween('incomes.created_at', [$from, $to])
                        ->whereHas('stock', function ($query) {
                            $query->whereHas('value', function ($query) {
                                $query->whereHas('product', function ($query) {
                                    $query->where('code', 'like', '%' . $this->search . '%');
                                });
                            });
                        })
                        ->orderBy('file_number', 'asc')
                        ->get();

                    $this->transfers = Transfer::with([
                        'status',
                        'user',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', '!=', $this->status_no->id)
                        ->whereBetween('transfers.created_at', [$from, $to])
                        ->whereHas('stock', function ($query) {
                            $query->whereHas('value', function ($query) {
                                $query->whereHas('product', function ($query) {
                                    $query->where('code', 'like', '%' . $this->search . '%');
                                });
                            });
                        })
                        ->orderBy('file_number', 'asc')
                        ->get();

                    $this->sales = Sale::with([
                        'status',
                        'tax',
                        'user',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', '!=', $this->status_no->id)
                        ->whereBetween('sales.created_at', [$from, $to])
                        ->whereHas('stock', function ($query) {
                            $query->whereHas('value', function ($query) {
                                $query->whereHas('product', function ($query) {
                                    $query->where('code', 'like', '%' . $this->search . '%');
                                });
                            });
                        })
                        ->orderBy('file_number', 'asc')
                        ->get();
                } else {

                    $this->incomes = Income::with([
                        'status',
                        'tax',
                        'user',
                        'supplier',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', '!=', $this->status_no->id)
                        ->where('user_id', $this->userId)
                        ->whereBetween('incomes.created_at', [$from, $to])
                        ->whereHas('stock', function ($query) {
                            $query->whereHas('value', function ($query) {
                                $query->whereHas('product', function ($query) {
                                    $query->where('code', 'like', '%' . $this->search . '%');
                                });
                            });
                        })
                        ->orderBy('file_number', 'asc')
                        ->get();

                    $this->transfers = Transfer::with([
                        'status',
                        'user',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', '!=', $this->status_no->id)
                        ->where('user_id', $this->userId)
                        ->whereBetween('transfers.created_at', [$from, $to])
                        ->whereHas('stock', function ($query) {
                            $query->whereHas('value', function ($query) {
                                $query->whereHas('product', function ($query) {
                                    $query->where('code', 'like', '%' . $this->search . '%');
                                });
                            });
                        })
                        ->orderBy('file_number', 'asc')
                        ->get();

                    $this->sales = Sale::with([
                        'status',
                        'tax',
                        'user',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', '!=', $this->status_no->id)
                        ->where('user_id', $this->userId)
                        ->whereBetween('sales.created_at', [$from, $to])
                        ->whereHas('stock', function ($query) {
                            $query->whereHas('value', function ($query) {
                                $query->whereHas('product', function ($query) {
                                    $query->where('code', 'like', '%' . $this->search . '%');
                                });
                            });
                        })
                        ->orderBy('file_number', 'asc')
                        ->get();
                }
            } else {

                if ($this->userId == 0) {

                    $this->incomes = Income::with([
                        'status',
                        'tax',
                        'user',
                        'supplier',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', '!=', $this->status_no->id)
                        ->whereBetween('incomes.created_at', [$from, $to])
                        ->orderBy('file_number', 'asc')
                        ->get();

                    $this->transfers = Transfer::with([
                        'status',
                        'user',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', '!=', $this->status_no->id)
                        ->whereBetween('transfers.created_at', [$from, $to])
                        ->orderBy('file_number', 'asc')
                        ->get();

                    $this->sales = Sale::with([
                        'status',
                        'tax',
                        'user',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', '!=', $this->status_no->id)
                        ->whereBetween('sales.created_at', [$from, $to])
                        ->orderBy('file_number', 'asc')
                        ->get();
                } else {

                    $this->incomes = Income::with([
                        'status',
                        'tax',
                        'user',
                        'supplier',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', '!=', $this->status_no->id)
                        ->where('user_id', $this->userId)
                        ->whereBetween('incomes.created_at', [$from, $to])
                        ->orderBy('file_number', 'asc')
                        ->get();

                    $this->transfers = Transfer::with([
                        'status',
                        'user',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', '!=', $this->status_no->id)
                        ->where('user_id', $this->userId)
                        ->whereBetween('transfers.created_at', [$from, $to])
                        ->orderBy('file_number', 'asc')
                        ->get();

                    $this->sales = Sale::with([
                        'status',
                        'tax',
                        'user',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', '!=', $this->status_no->id)
                        ->where('user_id', $this->userId)
                        ->whereBetween('sales.created_at', [$from, $to])
                        ->orderBy('file_number', 'asc')
                        ->get();
                }
            }
        } else {

            if (strlen($this->search) > 0) {

                if ($this->userId == 0) {

                    $this->incomes = Income::with([
                        'status',
                        'tax',
                        'user',
                        'supplier',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', $this->status_no->id)
                        ->whereBetween('incomes.created_at', [$from, $to])
                        ->whereHas('stock', function ($query) {
                            $query->whereHas('value', function ($query) {
                                $query->whereHas('product', function ($query) {
                                    $query->where('code', 'like', '%' . $this->search . '%');
                                });
                            });
                        })
                        ->orderBy('file_number', 'asc')
                        ->get();

                    $this->transfers = Transfer::with([
                        'status',
                        'user',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', $this->status_no->id)
                        ->whereBetween('transfers.created_at', [$from, $to])
                        ->whereHas('stock', function ($query) {
                            $query->whereHas('value', function ($query) {
                                $query->whereHas('product', function ($query) {
                                    $query->where('code', 'like', '%' . $this->search . '%');
                                });
                            });
                        })
                        ->orderBy('file_number', 'asc')
                        ->get();

                    $this->sales = Sale::with([
                        'status',
                        'tax',
                        'user',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', $this->status_no->id)
                        ->whereBetween('sales.created_at', [$from, $to])
                        ->whereHas('stock', function ($query) {
                            $query->whereHas('value', function ($query) {
                                $query->whereHas('product', function ($query) {
                                    $query->where('code', 'like', '%' . $this->search . '%');
                                });
                            });
                        })
                        ->orderBy('file_number', 'asc')
                        ->get();
                } else {

                    $this->incomes = Income::with([
                        'status',
                        'tax',
                        'user',
                        'supplier',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', $this->status_no->id)
                        ->where('user_id', $this->userId)
                        ->whereBetween('incomes.created_at', [$from, $to])
                        ->whereHas('stock', function ($query) {
                            $query->whereHas('value', function ($query) {
                                $query->whereHas('product', function ($query) {
                                    $query->where('code', 'like', '%' . $this->search . '%');
                                });
                            });
                        })
                        ->orderBy('file_number', 'asc')
                        ->get();

                    $this->transfers = Transfer::with([
                        'status',
                        'user',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', $this->status_no->id)
                        ->where('user_id', $this->userId)
                        ->whereBetween('transfers.created_at', [$from, $to])
                        ->whereHas('stock', function ($query) {
                            $query->whereHas('value', function ($query) {
                                $query->whereHas('product', function ($query) {
                                    $query->where('code', 'like', '%' . $this->search . '%');
                                });
                            });
                        })
                        ->orderBy('file_number', 'asc')
                        ->get();

                    $this->sales = Sale::with([
                        'status',
                        'tax',
                        'user',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', $this->status_no->id)
                        ->where('user_id', $this->userId)
                        ->whereBetween('sales.created_at', [$from, $to])
                        ->whereHas('stock', function ($query) {
                            $query->whereHas('value', function ($query) {
                                $query->whereHas('product', function ($query) {
                                    $query->where('code', 'like', '%' . $this->search . '%');
                                });
                            });
                        })
                        ->orderBy('file_number', 'asc')
                        ->get();
                }
            } else {

                if ($this->userId == 0) {

                    $this->incomes = Income::with([
                        'status',
                        'tax',
                        'user',
                        'supplier',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', $this->status_no->id)
                        ->whereBetween('incomes.created_at', [$from, $to])
                        ->orderBy('file_number', 'asc')
                        ->get();

                    $this->transfers = Transfer::with([
                        'status',
                        'user',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', $this->status_no->id)
                        ->whereBetween('transfers.created_at', [$from, $to])
                        ->orderBy('file_number', 'asc')
                        ->get();

                    $this->sales = Sale::with([
                        'status',
                        'tax',
                        'user',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', $this->status_no->id)
                        ->whereBetween('sales.created_at', [$from, $to])
                        ->orderBy('file_number', 'asc')
                        ->get();
                } else {

                    $this->incomes = Income::with([
                        'status',
                        'tax',
                        'user',
                        'supplier',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', $this->status_no->id)
                        ->where('user_id', $this->userId)
                        ->whereBetween('incomes.created_at', [$from, $to])
                        ->orderBy('file_number', 'asc')
                        ->get();

                    $this->transfers = Transfer::with([
                        'status',
                        'user',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', $this->status_no->id)
                        ->where('user_id', $this->userId)
                        ->whereBetween('transfers.created_at', [$from, $to])
                        ->orderBy('file_number', 'asc')
                        ->get();

                    $this->sales = Sale::with([
                        'status',
                        'tax',
                        'user',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                        ->where('status_id', $this->status_no->id)
                        ->where('user_id', $this->userId)
                        ->whereBetween('sales.created_at', [$from, $to])
                        ->orderBy('file_number', 'asc')
                        ->get();
                }
            }
        }
    }

    protected $listeners = [
        'remove_income' => 'Remove_Income',
        'remove_transfer' => 'Remove_Transfer',
        'remove_sale' => 'Remove_Sale',
    ];

    public function Remove_Income(Income $income)
    {

        $pivot = $this->stocks->find($income->office_value_id);

        if ($income->previus_stock != ($pivot->stock - $income->quantity)) {

            $this->emit('report-error', 'Se han realizado movimientos con el stock ingresado. Anule esos movimientos primero');
            return;
        } else {

            $value = $pivot->value;
            $office = $pivot->office;

            DB::beginTransaction();

            try {

                switch ($income->payment_type){

                    case 'efectivo':

                        if ($income->debt) {

                            $debt = $income->debt;
        
                            $debt->update([
        
                                'status_id' => 2
        
                            ]);
                        }

                    break;

                    case 'deposito':

                        $detail = Detail::firstWhere('relation_file_number',$income->file_number);
                        $account = BankAccount::find($detail->detailable_id);

                        if($account->balance != $detail->actual_balance){

                            $this->emit('report-error', 'Se han realizado transacciones con la cuenta de destino. Anule esas transacciones primero');
                            return;

                        }else{

                            $account->update([

                                'balance' => $account->balance + $detail->amount

                            ]);

                            $detail->update([

                                'status_id' => 2

                            ]);

                        }

                    break;

                }

                if ($income->tax) {

                    $tax = $income->tax;

                    $tax->update([

                        'status_id' => 2

                    ]);
                }

                $value->offices()->updateExistingPivot($office->id, [

                    'stock' => $pivot->stock - $income->quantity
                ]);

                $income->Update([
                    'status_id' => $this->status_no->id
                ]);

                DB::commit();
                $this->emit('movement-deleted', 'Ingreso Anulado');
                $this->render();
            } catch (\Throwable $th) {

                DB::rollback();
                throw $th;
            }
        }
    }

    public function Remove_Transfer(Transfer $transfer)
    {
        $from_pivot = $this->stocks->find($transfer->office_value_id);
        $value = $from_pivot->value;
        $from_office_id = $this->offices->firstWhere('name', $transfer->from_office)->id;
        $to_office_id = $this->offices->firstWhere('name', $transfer->to_office)->id;
        $from_stock = $from_pivot->stock;
        $to_stock = $value->offices()->firstWhere('office_id', $to_office_id)->pivot->stock;

        if ($transfer->previus_stock != ($to_stock - $transfer->quantity)) {

            $this->emit('report-error', 'Se han realizado movimientos con el stock transferido. Anule esos movimientos primero');
            return;
        } else {

            DB::beginTransaction();

            try {

                $value->offices()->updateExistingPivot($from_office_id, [

                    'stock' => $from_stock + $transfer->quantity
                ]);

                $value->offices()->updateExistingPivot($to_office_id, [

                    'stock' => $to_stock - $transfer->quantity
                ]);

                $transfer->Update([

                    'status_id' => $this->status_no->id
                ]);

                DB::commit();
                $this->emit('movement-deleted', 'Traspaso Anulado');
                $this->render();
            } catch (\Throwable $th) {

                DB::rollback();
                throw $th;
            }
        }
    }

    public function Remove_Sale(Sale $sale)
    {

        $pivot = $this->stocks->find($sale->office_value_id);
        $value = $pivot->value;
        $office = $pivot->office;

        DB::beginTransaction();

        try {

            switch ($sale->payment_type){

                case 'efectivo':

                    if ($sale->debt) {

                        $debt = $sale->debt;
        
                        $debt->update([
        
                            'status_id' => 2
        
                        ]);
                    }

                break;

                case 'deposito':

                    $detail = Detail::firstWhere('relation_file_number',$sale->file_number);
                    $account = BankAccount::find($detail->detailable_id);

                    if($account->balance != $detail->actual_balance){

                        $this->emit('report-error', 'Se han realizado transacciones con la cuenta de destino. Anule esas transacciones primero');
                        return;

                    }else{

                        $account->update([

                            'balance' => $account->balance - $detail->amount

                        ]);

                        $detail->update([

                            'status_id' => 2

                        ]);

                    }

                break;

                case 'cheque':

                    if ($sale->paycheck) {

                        $paycheck = $sale->paycheck;
                        
                        $paycheck->update([
            
                            'status_id' => 2
            
                        ]);
                    }

                break;

            }

            if ($sale->tax) {

                $tax = $sale->tax;

                $tax->update([

                    'status_id' => 2

                ]);
            }


            $value->offices()->updateExistingPivot($office->id, [

                'stock' => $pivot->stock + $sale->quantity
            ]);

            Income::create([

                'income_type' => 'devolucion',
                'payment_type' => 'efectivo',
                'previus_stock' => $pivot->stock,
                'quantity' => $sale->quantity,
                'total' => $value->cost * $sale->quantity,
                'status_id' => $this->status_ok->id,
                'user_id' => Auth()->user()->id,
                'customer_id' => $sale->customer_id,
                'office_value_id' => $pivot->id

            ]);

            $sale->Update([

                'status_id' => $this->status_no->id
            ]);

            DB::commit();
            $this->emit('movement-deleted', 'Venta Anulada');
            $this->render();
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }
}
