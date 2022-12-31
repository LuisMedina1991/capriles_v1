<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Sale;
use App\Models\Product;
use Carbon\Carbon;  //paquete para formatos de fechas

class Cashout extends Component
{   
    //propiedades publicas dentro del backend para accesar desde el frontend
    public $fromDate, $toDate, $userid, $total, $items, $sales, $details;

    public function mount(){    //metodo que se ejecuta en cuanto se monta este componente
        //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->fromDate = null;
        $this->toDate = null;
        $this->userid = 0;
        $this->total = 0;
        $this->sales = [];
        $this->details = [];
    }

    public function render()
    {   
        $this->Consultar();   //llamado a metodo
        //retorna la vista con la informacion almacenada en variable
        //listado de usuarios por nombre
        return view('livewire.cashouts.cashout', [
            'users' => User::orderBy('name', 'asc')->get()
        ])
        ->extends('layouts.theme.app')  //indicamos que la vista que estamos retornando extiende de esta plantilla
        ->section('content');   //contenido del componente que se renderiza en esta seccion
    }

    public function Consultar(){    //metodo para boton consultar

        $fi = Carbon::parse($this->fromDate)->format('Y-m-d') . ' 00:00:00';    //dando formato a la fecha de inicio y guardando en variable
        $ff = Carbon::parse($this->toDate)->format('Y-m-d') . ' 23:59:59';    //dando formato a la fecha de fin y guardando en variable

        $this->sales = Sale::join('users as u', 'u.id', 'sales.user_id')  //union de tabla ventas con tabla usuarios
        ->join('sale_details as s_d','s_d.sale_id','sales.id')
        ->join('products as p','p.id','s_d.product_id')
        ->select('sales.*','p.code as product','u.name as user')   //obtener todas las columnas de la tabla ventas y la columna name de la tabla users
        ->whereBetween('sales.created_at', [$fi, $ff])    //filtrado de las ventas entre fechas
        ->where('sales.status', 'pagado')
        ->where('sales.user_id', $this->userid)   //filtrado de las ventas del usuario seleccionado
        ->get();    //obtener registros

        $this->total = $this->sales ? $this->sales->sum('total') : 0;   //guardando en variable la sumatoria de la columna total en caso que sales tenga informacion
        $this->items = $this->sales ? $this->sales->sum('items') : 0;   //guardando en variable la sumatoria de la columna items en caso que sales tenga informacion
    }

    public function viewDetails(Sale $sale){    //metodo para modal de detalle de ventas

        $fi = Carbon::parse($this->fromDate)->format('Y-m-d') . ' 00:00:00';    //dando formato a la fecha de inicio y guardando en variable
        $ff = Carbon::parse($this->toDate)->format('Y-m-d') . ' 23:59:59';    //dando formato a la fecha de fin y guardando en variable

        $this->details = Sale::join('sale_details as d','d.sale_id','sales.id')   //union entre sales Y sale_details
        ->join('products as p','p.id','d.product_id')     //union entre products Y sale_details
        ->join('subcategories as s','s.id','p.subcategory_id')
        ->select('d.sale_id','d.quantity','d.price','d.utility','p.measurement as product','s.name as type')     //seleccion de columnas
        ->whereBetween('sales.created_at', [$fi, $ff])      //filtrado entre fechas
        ->where('sales.status', 'pagado')   //filtrado para seleccionar solo las ventas pagadas
        ->where('sales.user_id', $this->userid)   //filtrado para seleccionar solo las ventas realizadas por el usuario seleccionado
        ->where('sales.id', $sale->id)  //filtrado para seleccionar la venta seleccionada
        ->get();    //obtener datos

        $this->emit('show-modal', 'Mostrando modal');   //evento a ser escuchado desde el frontend
    }

    public function Remove(Sale $sale){
        
        $sale->Update([ //actualizacion del registro de la venta
            'status' => 'anulado'
        ]);
        
        $data = $sale->join('sale_details as s_d', 's_d.sale_id', 'sales.id')   //union entre sales y sale_details
        ->join('products as p','p.id','s_d.product_id') //union entre sale_details y products
        ->join('office_product as o_p','o_p.product_id','p.id') //union entre products y office_product
        ->join('offices as o','o.id','o_p.office_id')   //union entre office_product y offices
        ->select('s_d.quantity as quantity','p.id as product','o_p.office_id as office','o_p.stock as stock')   //seleccion de campos
        ->where('sales.id',$sale->id)   //filtrado por id de venta
        ->where('o.name',$sale->office) //filtrado por nombre de sucursal registrada en la venta
        ->first();

        $products = Product::find($data->product);  //obtencion del producto y guardado en variable
        
        $products->offices()->updateExistingPivot($data->office,[   //actualizacion de registro en tabla pivote
            'stock' => $data->stock + $data->quantity
        ]);

        $this->emit('sale-deleted', 'Venta Anulada');   //evento a ser escuchado desde el frontend
        $this->Consultar(); //llamado al metodo para refrescar la vista
    }

    protected $listeners = [    //eventos provenientes del frontend a ser escuchados
        'remove' => 'Remove'  //al escuchar el evento destroy se hace llamado al metodo Destroy
    ];

    public function Print(){    //metodo para la impresion


    }
}
