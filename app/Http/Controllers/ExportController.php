<?php

namespace App\Http\Controllers;

use App\Exports\SalesExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;  //paquete facade para reporte pdf
use Carbon\Carbon;  //paquete para calendario personalizado
use App\Models\Sale;
use App\Models\Income;
use App\Models\Transfer;
use App\Models\User;
use App\Models\Tax;
use App\Models\Supplier;
use App\Models\DebtsWithSupplier;
use App\Models\Customer;
use App\Models\CustomerDebt;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;    //paquete facade para reporte excel

class ExportController extends Controller
{

    public function TaxesReport($total,$search_2,$search = ''){

        switch ($search_2) {

            case 0:

                if(strlen($search) > 0){

                    $taxes = Tax::with(['status','taxable'])
                    ->where('status_id',1)
                    ->where('amount','>',0)
                    ->whereHas('taxable', function ($query) use ($search) {
                        $query->where('file_number', 'like', '%' . $search . '%');
                        $query->orWhereHas('tax', function ($query) use ($search){
                            $query->where('description', 'like', '%' . $search . '%');
                        });
                    })
                    ->orderBy('id','asc')
                    ->get();
        
                }else{
        
                    $taxes = Tax::with(['status','taxable'])
                    ->where('status_id',1)
                    ->where('amount','>',0)
                    ->orderBy('id','asc')
                    ->get();
        
                }

            break;

            case 1:

                if(strlen($search) > 0){

                    $taxes = Tax::with(['status','taxable'])
                    ->where('status_id',2)
                    ->where('amount','>',0)
                    ->whereHas('taxable', function ($query) use ($search) {
                        $query->where('file_number', 'like', '%' . $search . '%');
                        $query->orWhereHas('tax', function ($query) use ($search){
                            $query->where('description', 'like', '%' . $search . '%');
                        });
                    })
                    ->orderBy('id','asc')
                    ->get();
        
                }else{
        
                    $taxes = Tax::with(['status','taxable'])
                    ->where('status_id',2)
                    ->where('amount','>',0)
                    ->orderBy('id','asc')
                    ->get();
        
                }

            break;

        }

        $pdf = PDF::loadView('pdf.taxes_report', compact('total','search_2','search','taxes'));
        return $pdf->stream('reporte.pdf');
    }

    public function SuppliersReport($total,$search_2,$search = ''){

        switch ($search_2) {

            case 0:

                if(strlen($search) > 0){

                    $debts = DebtsWithSupplier::with(['status','income','supplier'])
                    ->where('status_id',1)
                    ->where('amount','>',0)
                    ->whereHas('income', function ($query) use ($search) {
                        $query->where('file_number', 'like', '%' . $search . '%');
                        $query->orWhereHas('supplier', function ($query) use ($search){
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                        $query->orWhereHas('debt', function ($query) use ($search){
                            $query->where('description', 'like', '%' . $search . '%');
                        });
                    })
                    ->orderBy(Supplier::select('name')->whereColumn('suppliers.id','debts_with_suppliers.supplier_id'))
                    ->get();
        
                }else{
        
                    $debts = DebtsWithSupplier::with(['status','income','supplier'])
                    ->where('status_id',1)
                    ->where('amount','>',0)
                    ->orderBy(Supplier::select('name')->whereColumn('suppliers.id','debts_with_suppliers.supplier_id'))
                    ->get();
        
                }

            break;

            case 1:

                if(strlen($search) > 0){

                    $debts = DebtsWithSupplier::with(['status','income','supplier'])
                    ->where('status_id',2)
                    ->where('amount','>',0)
                    ->whereHas('income', function ($query) use ($search) {
                        $query->where('file_number', 'like', '%' . $search . '%');
                        $query->orWhereHas('supplier', function ($query) use ($search){
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                        $query->orWhereHas('debt', function ($query) use ($search){
                            $query->where('description', 'like', '%' . $search . '%');
                        });
                    })
                    ->orderBy(Supplier::select('name')->whereColumn('suppliers.id','debts_with_suppliers.supplier_id'))
                    ->get();
        
                }else{
        
                    $debts = DebtsWithSupplier::with(['status','income','supplier'])
                    ->where('status_id',2)
                    ->where('amount','>',0)
                    ->orderBy(Supplier::select('name')->whereColumn('suppliers.id','debts_with_suppliers.supplier_id'))
                    ->get();
        
                }

            break;

        }

        $pdf = PDF::loadView('pdf.suppliers_report', compact('total','search_2','search','debts'));
        return $pdf->stream('reporte.pdf');
    }

    public function CustomersReport($total,$search_2,$search = ''){

        switch ($search_2) {

            case 0:

                if(strlen($search) > 0){

                    $debts = CustomerDebt::with(['status','sale','customer'])
                    ->where('status_id',1)
                    ->where('amount','>',0)
                    ->whereHas('sale', function ($query) use ($search) {
                        $query->where('file_number', 'like', '%' . $search . '%');
                        $query->orWhereHas('customer', function ($query) use ($search){
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                        $query->orWhereHas('debt', function ($query) use ($search){
                            $query->where('description', 'like', '%' . $search . '%');
                        });
                    })
                    ->orderBy(Customer::select('name')->whereColumn('customers.id','customer_debts.customer_id'))
                    ->get();
        
                }else{
        
                    $debts = CustomerDebt::with(['status','sale','customer'])
                    ->where('status_id',1)
                    ->where('amount','>',0)
                    ->orderBy(Customer::select('name')->whereColumn('customers.id','customer_debts.customer_id'))
                    ->get();
        
                }

            break;

            case 1:

                if(strlen($search) > 0){

                    $debts = CustomerDebt::with(['status','sale','customer'])
                    ->where('status_id',2)
                    ->where('amount','>',0)
                    ->whereHas('sale', function ($query) use ($search) {
                        $query->where('file_number', 'like', '%' . $search . '%');
                        $query->orWhereHas('customer', function ($query) use ($search){
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                        $query->orWhereHas('debt', function ($query) use ($search){
                            $query->where('description', 'like', '%' . $search . '%');
                        });
                    })
                    ->orderBy(Customer::select('name')->whereColumn('customers.id','customer_debts.customer_id'))
                    ->get();
        
                }else{
        
                    $debts = CustomerDebt::with(['status','sale','customer'])
                    ->where('status_id',2)
                    ->where('amount','>',0)
                    ->orderBy(Customer::select('name')->whereColumn('customers.id','customer_debts.customer_id'))
                    ->get();
        
                }

            break;

        }

        $pdf = PDF::loadView('pdf.customers_report', compact('total','search_2','search','debts'));
        return $pdf->stream('reporte.pdf');
    }

    public function StockReport($my_total,$search_2,$search = ''){

        switch ($search_2) {

            case 0:

                if (strlen($search) > 0){

                    $stocks = Product::with([
                        'activeValues.offices', 
                        'activeStocks',
                        'brand',
                        'container.subcategory.category',
                        'container.presentation'
                        ])
                    ->where('status_id', 1)
                    ->whereHas('container', function ($query) use ($search){
                        $query->whereHas('subcategory', function ($query) use ($search){
                            $query->whereHas('category', function ($query) use ($search){
                                $query->where('name', 'like', '%' . $search . '%');
                            });
                        });
                        $query->orWhereHas('subcategory', function($query) use ($search){
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                        $query->orWhereHas('presentation', function($query) use ($search){
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                        $query->orWhereHas('products', function($query) use ($search){
                            $query->where('code', 'like', '%' . $search . '%');
                        });
                    })
                    ->orderBy('code', 'asc')
                    ->get();
        
                }else{
        
                    $stocks = Product::where('status_id', 1)
                    ->with([
                        'activeValues.offices', 
                        'activeStocks',
                        'brand',
                        'container.subcategory.category',
                        'container.presentation'
                        ])
                    ->orderBy('code', 'asc')
                    ->get();
        
                }

            break;

            case 1:

                if (strlen($search) > 0){

                    $stocks = Product::with([
                        'activeValues.offices', 
                        'activeStocks',
                        'brand',
                        'container.subcategory.category',
                        'container.presentation'
                        ])
                    ->where('status_id', 2)
                    ->whereHas('container', function ($query) use ($search){
                        $query->whereHas('subcategory', function ($query) use ($search){
                            $query->whereHas('category', function ($query) use ($search){
                                $query->where('name', 'like', '%' . $search . '%');
                            });
                        });
                        $query->orWhereHas('subcategory', function($query) use ($search){
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                        $query->orWhereHas('presentation', function($query) use ($search){
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                        $query->orWhereHas('products', function($query) use ($search){
                            $query->where('code', 'like', '%' . $search . '%');
                        });
                    })
                    ->orderBy('code', 'asc')
                    ->get();
        
                }else{
        
                    $stocks = Product::where('status_id', 2)
                    ->with([
                        'activeValues.offices', 
                        'activeStocks',
                        'brand',
                        'container.subcategory.category',
                        'container.presentation'
                        ])
                    ->orderBy('code', 'asc')
                    ->get();
        
                }

            break;
        }

        $pdf = PDF::loadView('pdf.stock_report', compact('my_total','search_2','search','stocks'));

        return $pdf->stream('reporte.pdf');
    }
    
    public function WarehouseReports($userId,$reportRange,$reportType,$reportStatus,$dateFrom = null,$dateTo = null,$search = ''){

        if($reportRange == 0){

            $from = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 23:59:59';

        }else{

            $from = Carbon::parse($dateFrom)->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse($dateTo)->format('Y-m-d') . ' 23:59:59';

        }

        if($reportStatus == 0){

            if (strlen($search) > 0){

                if($userId == 0){

                    $incomes = Income::with([
                        'status',
                        'tax',
                        'user',
                        'supplier',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id','!=',5)
                    ->whereBetween('incomes.created_at', [$from, $to])
                    ->whereHas('stock', function ($query) use ($search){
                        $query->whereHas('value', function ($query) use ($search){
                            $query->whereHas('product', function ($query) use ($search){
                                $query->where('code', 'like', '%' . $search . '%');
                            });
                        });
                    })
                    ->orderBy('file_number','asc')
                    ->get();
        
                    $transfers = Transfer::with([
                        'status',
                        'user',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id','!=',5)
                    ->whereHas('stock', function ($query) use ($search){
                        $query->whereHas('value', function ($query) use ($search){
                            $query->whereHas('product', function ($query) use ($search){
                                $query->where('code', 'like', '%' . $search . '%');
                            });
                        });
                    })
                    ->whereBetween('transfers.created_at', [$from, $to])
                    ->orderBy('file_number','asc')
                    ->get();
        
                    $sales = Sale::with([
                        'status',
                        'tax',
                        'user',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id','!=',5)
                    ->whereHas('stock', function ($query) use ($search){
                        $query->whereHas('value', function ($query) use ($search){
                            $query->whereHas('product', function ($query) use ($search){
                                $query->where('code', 'like', '%' . $search . '%');
                            });
                        });
                    })
                    ->whereBetween('sales.created_at', [$from, $to])
                    ->orderBy('file_number','asc')
                    ->get();
        
                }else{
        
                    $incomes = Income::with([
                        'status',
                        'tax',
                        'user',
                        'supplier',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id','!=',5)
                    ->where('user_id',$userId)
                    ->whereBetween('incomes.created_at', [$from, $to])
                    ->whereHas('stock', function ($query) use ($search){
                        $query->whereHas('value', function ($query) use ($search){
                            $query->whereHas('product', function ($query) use ($search){
                                $query->where('code', 'like', '%' . $search . '%');
                            });
                        });
                    })
                    ->orderBy('file_number','asc')
                    ->get();
        
                    $transfers = Transfer::with([
                        'status',
                        'user',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id','!=',5)
                    ->where('user_id',$userId)
                    ->whereBetween('transfers.created_at', [$from, $to])
                    ->whereHas('stock', function ($query) use ($search){
                        $query->whereHas('value', function ($query) use ($search){
                            $query->whereHas('product', function ($query) use ($search){
                                $query->where('code', 'like', '%' . $search . '%');
                            });
                        });
                    })
                    ->orderBy('file_number','asc')
                    ->get();
        
                    $sales = Sale::with([
                        'status',
                        'tax',
                        'user',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id','!=',5)
                    ->where('user_id',$userId)
                    ->whereBetween('sales.created_at', [$from, $to])
                    ->whereHas('stock', function ($query) use ($search){
                        $query->whereHas('value', function ($query) use ($search){
                            $query->whereHas('product', function ($query) use ($search){
                                $query->where('code', 'like', '%' . $search . '%');
                            });
                        });
                    })
                    ->orderBy('file_number','asc')
                    ->get();

                }

            }else{

                if($userId == 0){

                    $incomes = Income::with([
                        'status',
                        'tax',
                        'user',
                        'supplier',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id','!=',5)
                    ->whereBetween('incomes.created_at', [$from, $to])
                    ->orderBy('file_number','asc')
                    ->get();
        
                    $transfers = Transfer::with([
                        'status',
                        'user',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id','!=',5)
                    ->whereBetween('transfers.created_at', [$from, $to])
                    ->orderBy('file_number','asc')
                    ->get();
        
                    $sales = Sale::with([
                        'status',
                        'tax',
                        'user',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id','!=',5)
                    ->whereBetween('sales.created_at', [$from, $to])
                    ->orderBy('file_number','asc')
                    ->get();
        
                }else{
        
                    $incomes = Income::with([
                        'status',
                        'tax',
                        'user',
                        'supplier',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id','!=',5)
                    ->where('user_id',$userId)
                    ->whereBetween('incomes.created_at', [$from, $to])
                    ->orderBy('file_number','asc')
                    ->get();
        
                    $transfers = Transfer::with([
                        'status',
                        'user',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id','!=',5)
                    ->where('user_id',$userId)
                    ->whereBetween('transfers.created_at', [$from, $to])
                    ->orderBy('file_number','asc')
                    ->get();
        
                    $sales = Sale::with([
                        'status',
                        'tax',
                        'user',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id','!=',5)
                    ->where('user_id',$userId)
                    ->whereBetween('sales.created_at', [$from, $to])
                    ->orderBy('file_number','asc')
                    ->get();

                }

            }

        }else{

            if (strlen($search) > 0){

                if($userId == 0){

                    $incomes = Income::with([
                        'status',
                        'tax',
                        'user',
                        'supplier',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id',5)
                    ->whereBetween('incomes.created_at', [$from, $to])
                    ->whereHas('stock', function ($query) use ($search){
                        $query->whereHas('value', function ($query) use ($search){
                            $query->whereHas('product', function ($query) use ($search){
                                $query->where('code', 'like', '%' . $search . '%');
                            });
                        });
                    })
                    ->orderBy('file_number','asc')
                    ->get();
        
                    $transfers = Transfer::with([
                        'status',
                        'user',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id',5)
                    ->whereHas('stock', function ($query) use ($search){
                        $query->whereHas('value', function ($query) use ($search){
                            $query->whereHas('product', function ($query) use ($search){
                                $query->where('code', 'like', '%' . $search . '%');
                            });
                        });
                    })
                    ->whereBetween('transfers.created_at', [$from, $to])
                    ->orderBy('file_number','asc')
                    ->get();
        
                    $sales = Sale::with([
                        'status',
                        'tax',
                        'user',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id',5)
                    ->whereHas('stock', function ($query) use ($search){
                        $query->whereHas('value', function ($query) use ($search){
                            $query->whereHas('product', function ($query) use ($search){
                                $query->where('code', 'like', '%' . $search . '%');
                            });
                        });
                    })
                    ->whereBetween('sales.created_at', [$from, $to])
                    ->orderBy('file_number','asc')
                    ->get();
        
                }else{
        
                    $incomes = Income::with([
                        'status',
                        'tax',
                        'user',
                        'supplier',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id',5)
                    ->where('user_id',$userId)
                    ->whereBetween('incomes.created_at', [$from, $to])
                    ->whereHas('stock', function ($query) use ($search){
                        $query->whereHas('value', function ($query) use ($search){
                            $query->whereHas('product', function ($query) use ($search){
                                $query->where('code', 'like', '%' . $search . '%');
                            });
                        });
                    })
                    ->orderBy('file_number','asc')
                    ->get();
        
                    $transfers = Transfer::with([
                        'status',
                        'user',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id',5)
                    ->where('user_id',$userId)
                    ->whereBetween('transfers.created_at', [$from, $to])
                    ->whereHas('stock', function ($query) use ($search){
                        $query->whereHas('value', function ($query) use ($search){
                            $query->whereHas('product', function ($query) use ($search){
                                $query->where('code', 'like', '%' . $search . '%');
                            });
                        });
                    })
                    ->orderBy('file_number','asc')
                    ->get();
        
                    $sales = Sale::with([
                        'status',
                        'tax',
                        'user',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id',5)
                    ->where('user_id',$userId)
                    ->whereBetween('sales.created_at', [$from, $to])
                    ->whereHas('stock', function ($query) use ($search){
                        $query->whereHas('value', function ($query) use ($search){
                            $query->whereHas('product', function ($query) use ($search){
                                $query->where('code', 'like', '%' . $search . '%');
                            });
                        });
                    })
                    ->orderBy('file_number','asc')
                    ->get();

                }

            }else{

                if($userId == 0){

                    $incomes = Income::with([
                        'status',
                        'tax',
                        'user',
                        'supplier',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id',5)
                    ->whereBetween('incomes.created_at', [$from, $to])
                    ->orderBy('file_number','asc')
                    ->get();
        
                    $transfers = Transfer::with([
                        'status',
                        'user',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id',5)
                    ->whereBetween('transfers.created_at', [$from, $to])
                    ->orderBy('file_number','asc')
                    ->get();
        
                    $sales = Sale::with([
                        'status',
                        'tax',
                        'user',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id',5)
                    ->whereBetween('sales.created_at', [$from, $to])
                    ->orderBy('file_number','asc')
                    ->get();
        
                }else{
        
                    $incomes = Income::with([
                        'status',
                        'tax',
                        'user',
                        'supplier',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id',5)
                    ->where('user_id',$userId)
                    ->whereBetween('incomes.created_at', [$from, $to])
                    ->orderBy('file_number','asc')
                    ->get();
        
                    $transfers = Transfer::with([
                        'status',
                        'user',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id',5)
                    ->where('user_id',$userId)
                    ->whereBetween('transfers.created_at', [$from, $to])
                    ->orderBy('file_number','asc')
                    ->get();
        
                    $sales = Sale::with([
                        'status',
                        'tax',
                        'user',
                        'customer',
                        'stock.value.product.brand',
                        'stock.office',
                    ])
                    ->where('status_id',5)
                    ->where('user_id',$userId)
                    ->whereBetween('sales.created_at', [$from, $to])
                    ->orderBy('file_number','asc')
                    ->get();

                }

            }

        }

        $user = $userId == 0 ? 'Todos' : User::find($userId)->name;
                    
        $pdf = PDF::loadView('pdf.warehouse_report', compact('incomes','transfers','sales','user','reportRange','reportType','dateFrom','dateTo','reportStatus','search'));

        //return $pdf->download('reporte.pdf');   //descargar pdf
        return $pdf->stream('reporte.pdf'); //visualizar pdf

        /*if($userId != 0 && $reportType == 0){

            $income = Income::join('users as u','u.id','incomes.user_id')
            ->join('states as st','st.id','incomes.state_id')
            ->join('products as p','p.id','incomes.product_id')
            ->select('incomes.*','u.name as user','p.code as code','p.cost as cost','p.price as price','p.brand as brand')
            ->whereBetween('incomes.created_at', [$from, $to])
            ->where('incomes.state_id',8)
            ->where('incomes.user_id',$userId)
            ->get();

            $transfer = Transfer::join('users as u','u.id','transfers.user_id')
            ->join('states as st','st.id','transfers.state_id')
            ->join('products as p','p.id','transfers.product_id')
            ->select('transfers.*','u.name as user','p.code as code','p.cost as cost','p.price as price','p.brand as brand')
            ->whereBetween('transfers.created_at', [$from, $to])
            ->where('transfers.state_id',8)
            ->where('transfers.user_id',$userId)
            ->get();

            $sale = Sale::join('users as u','u.id','sales.user_id')
            ->join('states as st','st.id','sales.state_id')
            ->join('products as p','p.id','sales.product_id')
            ->select('sales.*','u.name as user','p.code as code','p.cost as cost','p.price as price','p.brand as brand')
            ->whereBetween('sales.created_at', [$from, $to])
            ->where('sales.state_id',8)
            ->where('sales.user_id',$userId)
            ->get();

            //validar si el usuario esta seleccionando/deja por defecto el usuario (todos) y guardar en variable
            $user = $userId == 0 ? 'Todos' : User::find($userId)->name;
            //metodo loadView de paquete DomPDF recibe entre parametros (vista, informacion a renderizar)
            $pdf = PDF::loadView('pdf.reporte', compact('income','transfer','sale','user','reportRange','reportType','dateFrom','dateTo'));   //cargar vista y guardar en variable

            //metodo stream de paquete DomPDF
            return $pdf->stream('reporte.pdf'); //visualizar pdf
            //metodo download de paquete DomPDF
            //return $pdf->download('reporte.pdf');   //descargar pdf
        }

        if($userId == 0 && $reportType == 1){

            $income = Income::join('users as u','u.id','incomes.user_id')
            ->join('states as st','st.id','incomes.state_id')
            ->join('products as p','p.id','incomes.product_id')
            ->select('incomes.*','u.name as user','p.code as code','p.cost as cost','p.price as price','p.brand as brand')
            ->whereBetween('incomes.created_at', [$from, $to])
            ->where('incomes.state_id',8)
            ->get();

            //validar si el usuario esta seleccionando/deja por defecto el usuario (todos) y guardar en variable
            $user = $userId == 0 ? 'Todos' : User::find($userId)->name;
            //metodo loadView de paquete DomPDF recibe entre parametros (vista, informacion a renderizar)
            $pdf = PDF::loadView('pdf.reporte', compact('income','user','reportRange','reportType','dateFrom','dateTo'));   //cargar vista y guardar en variable

            //metodo stream de paquete DomPDF
            return $pdf->stream('reporte.pdf'); //visualizar pdf
            //metodo download de paquete DomPDF
            //return $pdf->download('reporte.pdf');   //descargar pdf
        }

        if($userId != 0 && $reportType == 1){

            $income = Income::join('users as u','u.id','incomes.user_id')
            ->join('states as st','st.id','incomes.state_id')
            ->join('products as p','p.id','incomes.product_id')
            ->select('incomes.*','u.name as user','p.code as code','p.cost as cost','p.price as price','p.brand as brand')
            ->whereBetween('incomes.created_at', [$from, $to])
            ->where('incomes.state_id',8)
            ->where('incomes.user_id',$userId)
            ->get();

            //validar si el usuario esta seleccionando/deja por defecto el usuario (todos) y guardar en variable
            $user = $userId == 0 ? 'Todos' : User::find($userId)->name;
            //metodo loadView de paquete DomPDF recibe entre parametros (vista, informacion a renderizar)
            $pdf = PDF::loadView('pdf.reporte', compact('income','user','reportRange','reportType','dateFrom','dateTo'));   //cargar vista y guardar en variable

            //metodo stream de paquete DomPDF
            return $pdf->stream('reporte.pdf'); //visualizar pdf
            //metodo download de paquete DomPDF
            //return $pdf->download('reporte.pdf');   //descargar pdf
        }

        if($userId == 0 && $reportType == 2){

            $transfer = Transfer::join('users as u','u.id','transfers.user_id')
            ->join('states as st','st.id','transfers.state_id')
            ->join('products as p','p.id','transfers.product_id')
            ->select('transfers.*','u.name as user','p.code as code','p.cost as cost','p.price as price','p.brand as brand')
            ->whereBetween('transfers.created_at', [$from, $to])
            ->where('transfers.state_id',8)
            ->get();

            //validar si el usuario esta seleccionando/deja por defecto el usuario (todos) y guardar en variable
            $user = $userId == 0 ? 'Todos' : User::find($userId)->name;
            //metodo loadView de paquete DomPDF recibe entre parametros (vista, informacion a renderizar)
            $pdf = PDF::loadView('pdf.reporte', compact('transfer','user','reportRange','reportType','dateFrom','dateTo'));   //cargar vista y guardar en variable

            //metodo stream de paquete DomPDF
            return $pdf->stream('reporte.pdf'); //visualizar pdf
            //metodo download de paquete DomPDF
            //return $pdf->download('reporte.pdf');   //descargar pdf
        }

        if($userId != 0 && $reportType == 2){

            $transfer = Transfer::join('users as u','u.id','transfers.user_id')
            ->join('states as st','st.id','transfers.state_id')
            ->join('products as p','p.id','transfers.product_id')
            ->select('transfers.*','u.name as user','p.code as code','p.cost as cost','p.price as price','p.brand as brand')
            ->whereBetween('transfers.created_at', [$from, $to])
            ->where('transfers.state_id',8)
            ->where('transfers.user_id',$userId)
            ->get();

            //validar si el usuario esta seleccionando/deja por defecto el usuario (todos) y guardar en variable
            $user = $userId == 0 ? 'Todos' : User::find($userId)->name;
            //metodo loadView de paquete DomPDF recibe entre parametros (vista, informacion a renderizar)
            $pdf = PDF::loadView('pdf.reporte', compact('transfer','user','reportRange','reportType','dateFrom','dateTo'));   //cargar vista y guardar en variable

            //metodo stream de paquete DomPDF
            return $pdf->stream('reporte.pdf'); //visualizar pdf
            //metodo download de paquete DomPDF
            //return $pdf->download('reporte.pdf');   //descargar pdf
        }

        if($userId == 0 && $reportType == 3){

            $sale = Sale::join('users as u','u.id','sales.user_id')
            ->join('states as st','st.id','sales.state_id')
            ->join('products as p','p.id','sales.product_id')
            ->select('sales.*','u.name as user','p.code as code','p.cost as cost','p.price as price','p.brand as brand')
            ->whereBetween('sales.created_at', [$from, $to])
            ->where('sales.state_id',8)
            ->get();

            //validar si el usuario esta seleccionando/deja por defecto el usuario (todos) y guardar en variable
            $user = $userId == 0 ? 'Todos' : User::find($userId)->name;
            //metodo loadView de paquete DomPDF recibe entre parametros (vista, informacion a renderizar)
            $pdf = PDF::loadView('pdf.reporte', compact('sale','user','reportRange','reportType','dateFrom','dateTo'));   //cargar vista y guardar en variable

            //metodo stream de paquete DomPDF
            return $pdf->stream('reporte.pdf'); //visualizar pdf
            //metodo download de paquete DomPDF
            //return $pdf->download('reporte.pdf');   //descargar pdf
        }

        if($userId != 0 && $reportType == 3){

            $sale = Sale::join('users as u','u.id','sales.user_id')
            ->join('states as st','st.id','sales.state_id')
            ->join('products as p','p.id','sales.product_id')
            ->select('sales.*','u.name as user','p.code as code','p.cost as cost','p.price as price','p.brand as brand')
            ->whereBetween('sales.created_at', [$from, $to])
            ->where('sales.state_id',8)
            ->where('sales.user_id',$userId)
            ->get();

            //validar si el usuario esta seleccionando/deja por defecto el usuario (todos) y guardar en variable
            $user = $userId == 0 ? 'Todos' : User::find($userId)->name;
            //metodo loadView de paquete DomPDF recibe entre parametros (vista, informacion a renderizar)
            $pdf = PDF::loadView('pdf.reporte', compact('sale','user','reportRange','reportType','dateFrom','dateTo'));   //cargar vista y guardar en variable

            //metodo stream de paquete DomPDF
            return $pdf->stream('reporte.pdf'); //visualizar pdf
            //metodo download de paquete DomPDF
            //return $pdf->download('reporte.pdf');   //descargar pdf
        }*/

    }

    public function reporteExcel($userId,$reportRange,$reportType,$dateFrom = null,$dateTo = null){

        $reportName = 'Reporte_' . uniqid() . '.xlsx';
        return Excel::download(new SalesExport($userId,$reportRange,$reportType,$dateFrom,$dateTo),$reportName );
    }
}
