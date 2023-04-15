<?php

use App\Http\Livewire\Asignar;
use App\Http\Livewire\Cashout;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Livewire\Categories;
use App\Http\Livewire\Coins;
use App\Http\Livewire\Incomes;
use App\Http\Livewire\Movement;
use App\Http\Livewire\Offices;
use App\Http\Livewire\Permisos;
use App\Http\Livewire\Pos;
use App\Http\Livewire\Products;
use App\Http\Livewire\Reports;
use App\Http\Livewire\Roles;
use App\Http\Livewire\Stocks;
use App\Http\Livewire\Subcategories;
use App\Http\Livewire\Users;
use App\Http\Controllers\ExportController;
use App\Http\Livewire\BalanceSheetAccounts;
use App\Http\Livewire\BankAccounts;
use App\Http\Livewire\BankingTransactions;
use App\Http\Livewire\Banks;
use App\Http\Livewire\Brands;
use App\Http\Livewire\CashTransactions;
use App\Http\Livewire\Companies;
use App\Http\Livewire\Containers;
use App\Http\Livewire\CustomerDebts;
use App\Http\Livewire\Customers;
use App\Http\Livewire\DebtsWithSuppliers;
use App\Http\Livewire\Paychecks;
use App\Http\Livewire\Presentations;
use App\Http\Livewire\Statuses;
use App\Http\Livewire\Suppliers;
use App\Http\Livewire\Taxes;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes([
    //'register' => false,
    //'reset' => false,
]);

Route::middleware(['auth'])->group(function(){   //proteger grupo de rutas con el sistema de autenticacion

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    //Route::get('home', Reports::class)->name('home');
    Route::get('categories', Categories::class);
    Route::get('subcategories', Subcategories::class);
    Route::get('offices', Offices::class);
    Route::get('coins', Coins::class);
    Route::get('statuses', Statuses::class);
    Route::get('products', Products::class);
    Route::get('brands',Brands::class);
    Route::get('presentations',Presentations::class);
    Route::get('containers',Containers::class);
    Route::get('reports', Reports::class);
    Route::get('suppliers', Suppliers::class);
    Route::get('debts_with_suppliers', DebtsWithSuppliers::class);
    Route::get('customers', Customers::class);
    Route::get('customers_debts', CustomerDebts::class);
    Route::get('taxes', Taxes::class);
    Route::get('banks', Banks::class);
    Route::get('companies', Companies::class);
    Route::get('bank_accounts', BankAccounts::class);
    Route::get('paychecks', Paychecks::class);
    Route::get('banking_transactions', BankingTransactions::class);
    Route::get('cash_transactions', CashTransactions::class);
    Route::get('balance_sheet_accounts', BalanceSheetAccounts::class);
    /*Route::get('products', Products::class)->middleware('permission:Product_Index');    //ruta para componente que por defecto ejecuta el metodo render del controlador
    Route::get('stocks', Stocks::class)->middleware('permission:Stock_Index');    //ruta para componente que por defecto ejecuta el metodo render del controlador
    Route::get('pos', Pos::class)->middleware('permission:Ventas_Index');    //ruta para componente que por defecto ejecuta el metodo render del controlador
    Route::get('incomes', Incomes::class)->middleware('permission:Ingresos_Index');    //ruta para componente que por defecto ejecuta el metodo render del controlador
    Route::get('cashout', Cashout::class)->middleware('permission:Egresos_Index');    //ruta para componente que por defecto ejecuta el metodo render del controlador
    Route::get('transfers', Movement::class)->middleware('permission:Traspasos_Index');    //ruta para componente que por defecto ejecuta el metodo render del controlador
    Route::get('reports', Reports::class)->middleware('permission:Reportes_Index');    //ruta para componente que por defecto ejecuta el metodo render del controlador

    Route::group(['middleware' => ['role:admin']],function(){   //proteger grupo de rutas con el rol de usuario

        Route::get('categories', Categories::class);    //ruta para componente que por defecto ejecuta el metodo render del controlador
        Route::get('subcategories', Subcategories::class);    //ruta para componente que por defecto ejecuta el metodo render del controlador
        Route::get('offices', Offices::class);    //ruta para componente que por defecto ejecuta el metodo render del controlador
        Route::get('roles', Roles::class);    //ruta para componente que por defecto ejecuta el metodo render del controlador
        Route::get('permisos', Permisos::class);    //ruta para componente que por defecto ejecuta el metodo render del controlador
        Route::get('asignar', Asignar::class);    //ruta para componente que por defecto ejecuta el metodo render del controlador
        Route::get('users', Users::class);    //ruta para componente que por defecto ejecuta el metodo render del controlador
        Route::get('coins', Coins::class);    //ruta para componente que por defecto ejecuta el metodo render del controlador

    });*/
    
    //REPORTES PDF
    Route::get('warehouse_reports/pdf/{user}/{range}/{type}/{status}/{f1}/{f2}/{search}', [ExportController::class, 'WarehouseReports']);
    Route::get('warehouse_reports/pdf/{user}/{range}/{type}/{status}/{f1}/{f2}', [ExportController::class, 'WarehouseReports']);
    Route::get('warehouse_reports/pdf/{user}/{range}/{type}/{status}/{search}', [ExportController::class, 'WarehouseReports']);
    Route::get('warehouse_reports/pdf/{user}/{range}/{type}/{status}', [ExportController::class, 'WarehouseReports']);
    Route::get('stock_report/pdf/{total}/{seach_2}', [ExportController::class, 'StockReport']);
    Route::get('stock_report/pdf/{total}/{seach_2}/{search}', [ExportController::class, 'StockReport']);
    Route::get('suppliers_report/pdf/{total}/{search_2}', [ExportController::class, 'SuppliersReport']);
    Route::get('suppliers_report/pdf/{total}/{search_2}/{search}', [ExportController::class, 'SuppliersReport']);
    Route::get('customers_report/pdf/{total}/{search_2}', [ExportController::class, 'CustomersReport']);
    Route::get('customers_report/pdf/{total}/{search_2}/{search}', [ExportController::class, 'CustomersReport']);
    Route::get('taxes_report/pdf/{total}/{search_2}', [ExportController::class, 'TaxesReport']);
    Route::get('taxes_report/pdf/{total}/{search_2}/{search}', [ExportController::class, 'TaxesReport']);
    Route::get('paychecks_report/pdf/{total}/{search_2}', [ExportController::class, 'PaychecksReport']);
    Route::get('paychecks_report/pdf/{total}/{search_2}/{search}', [ExportController::class, 'PaychecksReport']);
    Route::get('bank_accounts_report/pdf/{search_2}', [ExportController::class, 'BankAccountsReport']);
    Route::get('bank_accounts_report/pdf/{search_2}/{search}', [ExportController::class, 'BankAccountsReport']);
    Route::get('banking_transactions_report/pdf/{account}/{range}/{search_2}', [ExportController::class, 'BankingTransactionsReport']);
    Route::get('banking_transactions_report/pdf/{account}/{range}/{search_2}/{f1}/{f2}', [ExportController::class, 'BankingTransactionsReport']);
    Route::get('cash_transactions_report/pdf/{total}/{range}/{search_2}', [ExportController::class, 'CashTransactionsReport']);
    Route::get('cash_transactions_report/pdf/{total}/{range}/{search_2}/{search}', [ExportController::class, 'CashTransactionsReport']);
    Route::get('cash_transactions_report/pdf/{total}/{range}/{search_2}/{f1}/{f2}', [ExportController::class, 'CashTransactionsReport']);
    Route::get('cash_transactions_report/pdf/{total}/{range}/{search_2}/{f1}/{f2}/{search}', [ExportController::class, 'CashTransactionsReport']);
    //REPORTES EXCEL
    Route::get('report/excel/{user}/{range}/{type}/{f1}/{f2}', [ExportController::class, 'reporteExcel']);
    Route::get('report/excel/{user}/{range}/{type}', [ExportController::class, 'reporteExcel']);

});
