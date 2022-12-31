<?php

namespace App\Http\Livewire;

use App\Models\Office;
use Livewire\Component;
use App\Models\User;
use App\Models\Transfer;
use App\Models\Product;
use Carbon\Carbon;  //paquete para formatos de fechas

class Movement extends Component
{
    //propiedades publicas dentro del backend para accesar desde el frontend
    public $fromDate, $toDate, $userid, $total, $items, $transfers, $details;

    public function mount(){    //metodo que se ejecuta en cuanto se monta este componente
        //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->fromDate = null;
        $this->toDate = null;
        $this->userid = 0;
        $this->total = 0;
        $this->transfers = [];
        $this->details = [];
    }

    public function render()
    {   
        $this->Consultar();   //llamado a metodo
        //retorna la vista con la informacion almacenada en variable
        //listado de usuarios por nombre
        return view('livewire.movements.movement', [
            'users' => User::orderBy('name', 'asc')->get()
        ])
        ->extends('layouts.theme.app')  //indicamos que la vista que estamos retornando extiende de esta plantilla
        ->section('content');   //contenido del componente que se renderiza en esta seccion
    }

    public function Consultar(){    //metodo para boton consultar

        $fi = Carbon::parse($this->fromDate)->format('Y-m-d') . ' 00:00:00';    //dando formato a la fecha de inicio y guardando en variable
        $ff = Carbon::parse($this->toDate)->format('Y-m-d') . ' 23:59:59';    //dando formato a la fecha de fin y guardando en variable

        $this->transfers = Transfer::join('users as u', 'u.id', 'transfers.user_id')  //union de tabla ventas con tabla usuarios
        ->join('transfer_details as t_d','t_d.transfer_id','transfers.id')
        ->join('products as p','p.id','t_d.product_id')
        ->select('transfers.*','p.code as product','u.name as user')   //obtener todas las columnas de la tabla ventas y la columna name de la tabla users
        ->whereBetween('transfers.created_at', [$fi, $ff])    //filtrado de las ventas entre fechas
        ->where('transfers.status', 'recibido')
        ->where('transfers.user_id', $this->userid)   //filtrado de las ventas del usuario seleccionado
        ->get();    //obtener registros

        $this->total = $this->transfers ? $this->transfers->sum('total') : 0;   //guardando en variable la sumatoria de la columna total en caso que sales tenga informacion
        $this->items = $this->transfers ? $this->transfers->sum('items') : 0;   //guardando en variable la sumatoria de la columna items en caso que sales tenga informacion
    }

    public function viewDetails(Transfer $transfer){    //metodo para modal de detalle de ventas

        $fi = Carbon::parse($this->fromDate)->format('Y-m-d') . ' 00:00:00';    //dando formato a la fecha de inicio y guardando en variable
        $ff = Carbon::parse($this->toDate)->format('Y-m-d') . ' 23:59:59';    //dando formato a la fecha de fin y guardando en variable

        $this->details = Transfer::join('transfer_details as t', 't.transfer_id', 'transfers.id')   //union entre sales Y sale_details
        ->join('products as p', 'p.id', 't.product_id')     //union entre products Y sale_details
        ->join('subcategories as s','s.id','p.subcategory_id')
        ->select('t.transfer_id', 't.quantity', 't.cost', 'p.measurement as product','s.name as type')     //seleccion de columnas
        ->whereBetween('transfers.created_at', [$fi, $ff])      //filtrado entre fechas
        ->where('transfers.status', 'recibido')   //filtrado para seleccionar solo las ventas pagadas
        ->where('transfers.user_id', $this->userid)   //filtrado para seleccionar solo las ventas realizadas por el usuario seleccionado
        ->where('transfers.id', $transfer->id)  //filtrado para seleccionar la venta seleccionada
        ->get();    //obtener datos

        $this->emit('show-modal', 'Mostrando modal');   //evento a ser escuchado desde el frontend
    }

    public function Remove(Transfer $transfer){
        
        $transfer->Update([ //actualizacion del registro de la venta
            'status' => 'anulado'
        ]);

        $cant_prod = $transfer->join('transfer_details as t_d','t_d.transfer_id','transfers.id')   //union entre sales y sale_details
        ->select('t_d.quantity as cant','t_d.product_id as product')    //seleccion de la cantidad del traspaso y el id del producto
        ->firstWhere('t_d.transfer_id',$transfer->id);

        $product = Product::find($cant_prod->product);  //obtencion del producto con el id antes obtenido

        $from = Office::firstWhere('name',$transfer->from_office)->id;  //obtencion del id de la sucursal de origen

        $to = Office::firstWhere('name',$transfer->to_office)->id;  //obtencion del id de la sucursal de destino

        $from_stock = $product->offices()->firstWhere('office_id',$from)->pivot->stock; //obtencion del stock de la sucursal de origen

        $to_stock = $product->offices()->firstWhere('office_id',$to)->pivot->stock; //obtencion del stock de la sucursal de destino
        
        $product->offices()->updateExistingPivot($from,[   //actualizacion de registro en tabla pivote
            'stock' => $from_stock + $cant_prod->cant
        ]);

        $product->offices()->updateExistingPivot($to,[   //actualizacion de registro en tabla pivote
            'stock' => $to_stock - $cant_prod->cant
        ]);

        $this->emit('transfer-deleted', 'Traspaso Anulado');   //evento a ser escuchado desde el frontend
        $this->Consultar(); //llamado al metodo para refrescar la vista
    }

    protected $listeners = [    //eventos provenientes del frontend a ser escuchados
        'remove' => 'Remove'  //al escuchar el evento destroy se hace llamado al metodo Destroy
    ];

    public function Print(){    //metodo para la impresion


    }
}
