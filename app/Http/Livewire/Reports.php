<?php

namespace App\Http\Livewire;

use App\Models\Income;
use App\Models\IncomeDetail;
use Livewire\Component;
use App\Models\User;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Transfer;
use App\Models\TransferDetail;
use Carbon\Carbon;  //paquete para calendario personalizado

class Reports extends Component
{   
    //propiedades publicas dentro del backend para accesar desde el frontend
    public $componentName,$data,$details,$sumDetails,$countDetails,$reportRange,$userId,$dateFrom,$dateTo,$saleId,$reportType;

    public function mount(){    //metodo que se ejecuta en cuanto se monta este componente

        //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->componentName = 'REPORTES DE INGRESOS | EGRESOS | TRASPASOS';
        $this->data = [];
        $this->details = [];
        $this->sumDetails = 0;
        $this->countDetails = 0;
        $this->reportRange = 0;
        $this->reportType = 0;
        $this->userId = 0;
        $this->saleId = 0;
    }

    public function render()
    {
        $this->ReportsByDate();   //llamado a metodo

        //retorna la vista con la informacion almacenada en variable
        //listado de usuarios
        return view('livewire.report.reports', [
            'users' => User::orderBy('name', 'asc')->get()
        ])
        ->extends('layouts.theme.app')  //indicamos que la vista que estamos retornando extiende de esta plantilla
        ->section('content');   //contenido del componente que se renderiza en esta seccion
    }

    public function ReportsByDate(){  //metodo para retornar reporte ventas por fecha

        if($this->reportRange == 0){     //validar si el usuario esta seleccionando/deja por defecto el tipo de reporte (ventas del dia)

            $from = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 00:00:00';    //dar formato personalizado a fecha de inicio y guardar en variable
            $to = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 23:59:59';  //dar formato personalizado a fecha de fin y guardar en variable

        }else{

            $from = Carbon::parse($this->dateFrom)->format('Y-m-d') . ' 00:00:00';  //dar formato personalizado a fecha de inicio y guardar en variable
            $to = Carbon::parse($this->dateTo)->format('Y-m-d') . ' 23:59:59';  //dar formato personalizado a fecha de fin y guardar en variable

        }

        if($this->reportRange == 1 && ($this->dateFrom == '' || $this->dateTo == '')){   //validar si se selecciona ventas por fecha sin seleccionar alguna de las fechas

            $this->emit('report-error', 'Seleccione fecha de inicio y fecha de fin');   //evento a ser escuchado desde el frontend
            return; //detener el flujo del proceso
        }

        if($this->userId == 0 && $this->reportType == 0){     //validar si no se esta seleccionando un usuario (opcion todos)

            $this->data = Sale::join('users as u', 'u.id', 'sales.user_id')  //union de tabla ventas con tabla usuarios
            ->join('sale_details as s_d','s_d.sale_id','sales.id')
            ->join('products as p','p.id','s_d.product_id')
            ->select('sales.*','p.code as product','u.name as user','s_d.utility as utility')   //obtener todas las columnas de la tabla ventas y la columna name de la tabla users
            ->whereBetween('sales.created_at', [$from, $to])    //filtrado de las ventas entre fechas
            ->where('sales.status','pagado')
            ->get();    //obtener registros
        }

        if($this->userId != 0 && $this->reportType == 0){

            $this->data = Sale::join('users as u', 'u.id', 'sales.user_id')  //union de tabla ventas con tabla usuarios
            ->join('sale_details as s_d','s_d.sale_id','sales.id')
            ->join('products as p','p.id','s_d.product_id')
            ->select('sales.*','p.code as product','u.name as user','s_d.utility as utility')   //obtener todas las columnas de la tabla ventas y la columna name de la tabla users
            ->whereBetween('sales.created_at', [$from, $to])    //filtrado de las ventas entre fechas
            ->where('sales.status','pagado')
            ->where('user_id', $this->userId)   //filtrado de las ventas del usuario seleccionado
            ->get();    //obtener registros
        }

        if($this->userId == 0 && $this->reportType == 1){

            $this->data = Income::join('users as u', 'u.id', 'incomes.user_id')  //union de tabla ventas con tabla usuarios
            ->join('income_details as i_d','i_d.income_id','incomes.id')
            ->join('products as p','p.id','i_d.product_id')
            ->select('incomes.*','p.code as product','u.name as user')   //obtener todas las columnas de la tabla ventas y la columna name de la tabla users
            ->whereBetween('incomes.created_at', [$from, $to])    //filtrado de las ventas entre fechas
            ->where('incomes.status','recibido')
            ->get();    //obtener registros
        }

        if($this->userId != 0 && $this->reportType == 1){

            $this->data = Income::join('users as u', 'u.id', 'incomes.user_id')  //union de tabla ventas con tabla usuarios
            ->join('income_details as i_d','i_d.income_id','incomes.id')
            ->join('products as p','p.id','i_d.product_id')
            ->select('incomes.*','p.code as product','u.name as user')   //obtener todas las columnas de la tabla ventas y la columna name de la tabla users
            ->whereBetween('incomes.created_at', [$from, $to])    //filtrado de las ventas entre fechas
            ->where('incomes.status','recibido')
            ->where('user_id', $this->userId)   //filtrado de las ventas del usuario seleccionado
            ->get();    //obtener registros
        }

        if($this->userId == 0 && $this->reportType == 2){

            $this->data = Transfer::join('users as u', 'u.id', 'transfers.user_id')  //union de tabla ventas con tabla usuarios
            ->join('transfer_details as t_d','t_d.transfer_id','transfers.id')
            ->join('products as p','p.id','t_d.product_id')
            ->select('transfers.*','p.code as product','u.name as user')   //obtener todas las columnas de la tabla ventas y la columna name de la tabla users
            ->whereBetween('transfers.created_at', [$from, $to])    //filtrado de las ventas entre fechas
            ->where('transfers.status','recibido')
            ->get();    //obtener registros
        }

        if($this->userId != 0 && $this->reportType == 2){

            $this->data = Transfer::join('users as u', 'u.id', 'transfers.user_id')  //union de tabla ventas con tabla usuarios
            ->join('transfer_details as t_d','t_d.transfer_id','transfers.id')
            ->join('products as p','p.id','t_d.product_id')
            ->select('transfers.*','p.code as product','u.name as user')   //obtener todas las columnas de la tabla ventas y la columna name de la tabla users
            ->whereBetween('transfers.created_at', [$from, $to])    //filtrado de las ventas entre fechas
            ->where('transfers.status','recibido')
            ->where('user_id', $this->userId)   //filtrado de las ventas del usuario seleccionado
            ->get();    //obtener registros
        }

    }

    public function getDetails($saleId){    //metodo para obtener los detalles de venta que recibe el id de la venta como parametro

        if($this->userId == 0 && $this->reportType == 0){

            $this->details= SaleDetail::join('products as p', 'p.id', 'sale_details.product_id')   //union de tabla sale_details con tabla products
            ->join('sales as s','s.id','sale_details.sale_id')
            ->join('users as u','u.id','s.user_id')
            ->select('p.brand as brand','p.measurement as measurement','sale_details.price as price','sale_details.quantity') //seleccion de columnas
            ->where('sale_details.sale_id', $saleId)    //filtrado por venta seleccionada
            ->get();    //obtener registros

            //funcion anonima o closure que obtendra la sumatoria del precio * cantidad de cada fila recorrida
            $suma = $this->details->sum(function($item){    //variable $item hara la iteracion en cada una de las filas de details
                return $item->price * $item->quantity;  //operacion precio * cantidad
            });

            $this->sumDetails = $suma;  //asignar a variable publica la sumatoria de los importes obtenidos anteriormente
            $this->countDetails = $this->details->sum('quantity');  //asignar a variable publica la sumatoria de la cantidad de items de la venta
            $this->saleId = $saleId;    //asignar a variable publica el id de la venta

            $this->emit('show-modal', 'Mostrar Modal'); //evento a ser escuchado desde el frontend
        
        }

        if($this->userId != 0 && $this->reportType == 0){

            $this->details= SaleDetail::join('products as p', 'p.id', 'sale_details.product_id')   //union de tabla sale_details con tabla products
            ->join('sales as s','s.id','sale_details.sale_id')
            ->join('users as u','u.id','s.user_id')
            ->select('p.brand as brand','p.measurement as measurement','sale_details.price as price','sale_details.quantity') //seleccion de columnas
            ->where('sale_details.sale_id', $saleId)    //filtrado por venta seleccionada
            ->where('s.user_id',$this->userId)
            ->get();    //obtener registros

            //funcion anonima o closure que obtendra la sumatoria del precio * cantidad de cada fila recorrida
            $suma = $this->details->sum(function($item){    //variable $item hara la iteracion en cada una de las filas de details
                return $item->price * $item->quantity;  //operacion precio * cantidad
            });

            $this->sumDetails = $suma;  //asignar a variable publica la sumatoria de los importes obtenidos anteriormente
            $this->countDetails = $this->details->sum('quantity');  //asignar a variable publica la sumatoria de la cantidad de items de la venta
            $this->saleId = $saleId;    //asignar a variable publica el id de la venta

            $this->emit('show-modal', 'Mostrar Modal'); //evento a ser escuchado desde el frontend
        }

        if($this->userId == 0 && $this->reportType == 1){

            $this->details= IncomeDetail::join('products as p', 'p.id', 'income_details.product_id')   //union de tabla sale_details con tabla products
            ->join('incomes as i','i.id','income_details.income_id')
            ->join('users as u','u.id','i.user_id')
            ->select('p.brand as brand','p.measurement as measurement','income_details.cost as price','income_details.quantity') //seleccion de columnas
            ->where('income_details.income_id', $saleId)    //filtrado por venta seleccionada
            ->get();    //obtener registros

            //funcion anonima o closure que obtendra la sumatoria del precio * cantidad de cada fila recorrida
            $suma = $this->details->sum(function($item){    //variable $item hara la iteracion en cada una de las filas de details
                return $item->price * $item->quantity;  //operacion precio * cantidad
            });

            $this->sumDetails = $suma;  //asignar a variable publica la sumatoria de los importes obtenidos anteriormente
            $this->countDetails = $this->details->sum('quantity');  //asignar a variable publica la sumatoria de la cantidad de items de la venta
            $this->saleId = $saleId;    //asignar a variable publica el id de la venta

            $this->emit('show-modal', 'Mostrar Modal'); //evento a ser escuchado desde el frontend
        }

        if($this->userId != 0 && $this->reportType == 1){

            $this->details= IncomeDetail::join('products as p', 'p.id', 'income_details.product_id')   //union de tabla sale_details con tabla products
            ->join('incomes as i','i.id','income_details.income_id')
            ->join('users as u','u.id','i.user_id')
            ->select('p.brand as brand','p.measurement as measurement','income_details.cost as price','income_details.quantity') //seleccion de columnas
            ->where('income_details.income_id', $saleId)    //filtrado por venta seleccionada
            ->where('i.user_id',$this->userId)
            ->get();    //obtener registros

            //funcion anonima o closure que obtendra la sumatoria del precio * cantidad de cada fila recorrida
            $suma = $this->details->sum(function($item){    //variable $item hara la iteracion en cada una de las filas de details
                return $item->price * $item->quantity;  //operacion precio * cantidad
            });

            $this->sumDetails = $suma;  //asignar a variable publica la sumatoria de los importes obtenidos anteriormente
            $this->countDetails = $this->details->sum('quantity');  //asignar a variable publica la sumatoria de la cantidad de items de la venta
            $this->saleId = $saleId;    //asignar a variable publica el id de la venta

            $this->emit('show-modal', 'Mostrar Modal'); //evento a ser escuchado desde el frontend
        }

        if($this->userId == 0 && $this->reportType == 2){

            $this->details= TransferDetail::join('products as p', 'p.id', 'transfer_details.product_id')   //union de tabla sale_details con tabla products
            ->join('transfers as t','t.id','transfer_details.transfer_id')
            ->join('users as u','u.id','t.user_id')
            ->select('p.brand as brand','p.measurement as measurement','transfer_details.cost as price','transfer_details.quantity') //seleccion de columnas
            ->where('transfer_details.transfer_id', $saleId)    //filtrado por venta seleccionada
            ->get();    //obtener registros

            //funcion anonima o closure que obtendra la sumatoria del precio * cantidad de cada fila recorrida
            $suma = $this->details->sum(function($item){    //variable $item hara la iteracion en cada una de las filas de details
                return $item->price * $item->quantity;  //operacion precio * cantidad
            });

            $this->sumDetails = $suma;  //asignar a variable publica la sumatoria de los importes obtenidos anteriormente
            $this->countDetails = $this->details->sum('quantity');  //asignar a variable publica la sumatoria de la cantidad de items de la venta
            $this->saleId = $saleId;    //asignar a variable publica el id de la venta

            $this->emit('show-modal', 'Mostrar Modal'); //evento a ser escuchado desde el frontend
        }

        if($this->userId != 0 && $this->reportType == 2){

            $this->details= TransferDetail::join('products as p', 'p.id', 'transfer_details.product_id')   //union de tabla sale_details con tabla products
            ->join('transfers as t','t.id','transfer_details.transfer_id')
            ->join('users as u','u.id','t.user_id')
            ->select('p.brand as brand','p.measurement as measurement','transfer_details.cost as price','transfer_details.quantity') //seleccion de columnas
            ->where('transfer_details.transfer_id', $saleId)    //filtrado por venta seleccionada
            ->where('t.user_id',$this->userId)
            ->get();    //obtener registros

            //funcion anonima o closure que obtendra la sumatoria del precio * cantidad de cada fila recorrida
            $suma = $this->details->sum(function($item){    //variable $item hara la iteracion en cada una de las filas de details
                return $item->price * $item->quantity;  //operacion precio * cantidad
            });

            $this->sumDetails = $suma;  //asignar a variable publica la sumatoria de los importes obtenidos anteriormente
            $this->countDetails = $this->details->sum('quantity');  //asignar a variable publica la sumatoria de la cantidad de items de la venta
            $this->saleId = $saleId;    //asignar a variable publica el id de la venta

            $this->emit('show-modal', 'Mostrar Modal'); //evento a ser escuchado desde el frontend
        }
    }
}
