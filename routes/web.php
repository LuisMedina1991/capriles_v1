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
use App\Http\Livewire\Statuses;

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
    'reset' => false,
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
    Route::get('report/pdf/{user}/{range}/{type}/{f1}/{f2}', [ExportController::class, 'reportPDF']);
    Route::get('report/pdf/{user}/{range}/{type}', [ExportController::class, 'reportPDF']);
    //REPORTES EXCEL
    Route::get('report/excel/{user}/{range}/{type}/{f1}/{f2}', [ExportController::class, 'reporteExcel']);
    Route::get('report/excel/{user}/{range}/{type}', [ExportController::class, 'reporteExcel']);

});
