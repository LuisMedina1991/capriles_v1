<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Income;
use App\Models\Product;
use Carbon\Carbon;  //paquete para formatos de fechas

class Incomes extends Component
{
    //propiedades publicas dentro del backend para accesar desde el frontend
    public $fromDate, $toDate, $userid, $total, $items, $incomes, $details;

    public function mount(){    //metodo que se ejecuta en cuanto se monta este componente
        //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->fromDate = null;
        $this->toDate = null;
        $this->userid = 0;
        $this->total = 0;
        $this->incomes = [];
        $this->details = [];
    }

    public function render()
    {   
        $this->Consultar();   //llamado a metodo
        //retorna la vista con la informacion almacenada en variable
        //listado de usuarios por nombre
        return view('livewire.incomes.income', [
            'users' => User::orderBy('name', 'asc')->get()
        ])
        ->extends('layouts.theme.app')  //indicamos que la vista que estamos retornando extiende de esta plantilla
        ->section('content');   //contenido del componente que se renderiza en esta seccion
    }

    public function Consultar(){    //metodo para boton consultar

        $fi = Carbon::parse($this->fromDate)->format('Y-m-d') . ' 00:00:00';    //dando formato a la fecha de inicio y guardando en variable
        $ff = Carbon::parse($this->toDate)->format('Y-m-d') . ' 23:59:59';    //dando formato a la fecha de fin y guardando en variable

        /*
        $this->incomes = Income::whereBetween('created_at', [$fi, $ff]) //consulta para obtener las ventas entre fechas y guardar en variable
        ->where('status', 'recibido')   //filtrado para seleccionar solo las ventas pagadas
        ->where('user_id', $this->userid)   //filtrado para seleccionar solo las ventas realizadas por el usuario seleccionado
        ->get();    //obtener datos*/

        $this->incomes = Income::join('users as u', 'u.id', 'incomes.user_id')  //union de tabla ventas con tabla usuarios
        ->join('income_details as i_d','i_d.income_id','incomes.id')
        ->join('products as p','p.id','i_d.product_id')
        ->select('incomes.*','p.code as product','u.name as user')   //obtener todas las columnas de la tabla ventas y la columna name de la tabla users
        ->whereBetween('incomes.created_at', [$fi, $ff])    //filtrado de las ventas entre fechas
        ->where('incomes.status', 'recibido')
        ->where('incomes.user_id', $this->userid)   //filtrado de las ventas del usuario seleccionado
        ->get();    //obtener registros

        $this->total = $this->incomes ? $this->incomes->sum('total') : 0;   //guardando en variable la sumatoria de la columna total en caso que sales tenga informacion
        $this->items = $this->incomes ? $this->incomes->sum('items') : 0;   //guardando en variable la sumatoria de la columna items en caso que sales tenga informacion
    }

    public function viewDetails(Income $income){    //metodo para modal de detalle de ventas

        $fi = Carbon::parse($this->fromDate)->format('Y-m-d') . ' 00:00:00';    //dando formato a la fecha de inicio y guardando en variable
        $ff = Carbon::parse($this->toDate)->format('Y-m-d') . ' 23:59:59';    //dando formato a la fecha de fin y guardando en variable

        $this->details = Income::join('income_details as i','i.income_id','incomes.id')   //union entre sales Y sale_details
        ->join('products as p','p.id','i.product_id')     //union entre products Y sale_details
        ->join('subcategories as s','s.id','p.subcategory_id')
        ->select('i.income_id','i.quantity','i.cost','p.measurement as product','s.name as type')     //seleccion de columnas
        ->whereBetween('incomes.created_at', [$fi, $ff])      //filtrado entre fechas
        ->where('incomes.status', 'recibido')   //filtrado para seleccionar solo las ventas pagadas
        ->where('incomes.user_id', $this->userid)   //filtrado para seleccionar solo las ventas realizadas por el usuario seleccionado
        ->where('incomes.id', $income->id)  //filtrado para seleccionar la venta seleccionada
        ->get();    //obtener datos

        $this->emit('show-modal', 'Mostrando modal');   //evento a ser escuchado desde el frontend
    }

    public function Remove(Income $income){
        
        $income->Update([ //actualizacion del registro de la venta
            'status' => 'anulado'
        ]);
        
        $data = $income->join('income_details as i_d', 'i_d.income_id', 'incomes.id')   //union entre sales y sale_details
        ->join('products as p','p.id','i_d.product_id') //union entre sale_details y products
        ->join('office_product as o_p','o_p.product_id','p.id') //union entre products y office_product
        ->join('offices as o','o.id','o_p.office_id')   //union entre office_product y offices
        ->select('i_d.quantity as quantity','p.id as product','o_p.office_id as office','o_p.stock as stock')   //seleccion de campos
        ->where('incomes.id',$income->id)   //filtrado por id de venta
        ->where('o.name',$income->office) //filtrado por nombre de sucursal registrada en la venta
        ->first();

        $products = Product::find($data->product);  //obtencion del producto y guardado en variable
        
        $products->offices()->updateExistingPivot($data->office,[   //actualizacion de registro en tabla pivote
            'stock' => $data->stock - $data->quantity
        ]);

        $this->emit('income-deleted', 'Ingreso Anulado');   //evento a ser escuchado desde el frontend
        $this->Consultar(); //llamado al metodo para refrescar la vista
    }

    protected $listeners = [    //eventos provenientes del frontend a ser escuchados
        'remove' => 'Remove'  //al escuchar el evento destroy se hace llamado al metodo Destroy
    ];

    public function Print(){    //metodo para la impresion


    }
}
