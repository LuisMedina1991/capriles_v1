<?php

namespace App\Http\Livewire;

use App\Models\Denomination;
use App\Models\Product;
use App\Models\Office;
use App\Models\Sale;
use App\Models\SaleDetail;
use Darryldecode\Cart\Facades\CartFacade as Cart;   //paquete de carrito de compras renombrado como Cart
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;

class Pos extends Component
{
    public $total,$itemsQuantity,$efectivo,$change;  //propiedades publicas dentro del backend para accesar desde el frontend

    public function mount(){    //metodo que se ejecuta en cuanto se monta este componente

        $this->efectivo = 0;    //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->change = 0;  //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->total = Cart::getTotal();    //obtener el total del carrito
        $this->itemsQuantity = Cart::getTotalQuantity();    //obtenemos la cantidad de productos agregados al carrito
    }

    public function render()
    {
        return view('livewire.pos.pos',[    //retorna la vista con la informacion almacenada en variable
            'denominations' => Denomination::orderBy('value', 'desc')->get(),   //obtenemos las denominaciones
            'cart' => Cart::getContent()->sortBy('name')    //obtener contenido del carrito
        ])
        ->extends('app')  //indicamos que la vista que estamos retornando extiende de esta plantilla
        ->section('content');   //contenido del componente que se renderiza en esta seccion
    }

    public function ACash($value){  //metodo para acumular el valor al clickear las botonos de denominaciones
        //sumatoria de lo que tiene en $efectivo + lo que se vaya clickeando
        //si se clickea exacto(0) la caja $efectivo muestra el valor de $total
        $this->efectivo += ($value == 0 ? $this->total : $value);   //caso contrario muestra el acumulativo de $value
        $this->change = ($this->efectivo - $this->total);   //obtenemos el cambio
    }

    protected $listeners = [    //eventos provenientes del frontend a ser escuchados
        'scan-code' => 'ScanCode',  //evento proveniente de plantilla search
        'removeItem' => 'removeItem',
        'clearCart' => 'clearCart',
        'saveSale' => 'saveSale'
    ];

    //metodo recibe 3 parametros (codigo de producto,sucursal,cantidad por defecto)
    public function ScanCode($code,$sale_price,$office,$cant = 1){  //metodo para recibir el codigo de barras que el usuario esta escaneando

        //consulta para obtener el producto cuyo codigo coincida con el codigo ingresado en la caja de busqueda
        //$data = Product::find($code)->offices()->first()->pivot->id;
        $data = Product::where('code', $code)->first(); //obtener el primer producto que contenga el codigo pasado por parametro
        //dd($data->offices()->firstWhere('name',$office)->pivot->stock);

            if($data == null || empty($code) || $data->offices()->firstWhere('name',$office) == null){  //validar si el producto no fue encontrado o la caja de busqueda esta vacia
                $this->emit('scan-notfound', 'El producto no esta registrado'); //evento a ser escuchado desde el frontend
            }else{
                //metodo InCart verifica si un producto ya existe en el carrito
                if($this->InCart($data->offices()->first()->pivot->id, $office)){   //validar si el producto ya existe en el carrito
                    $this->increaseQty($data->offices()->first()->pivot->id);  //de ser asi se incrementa la cantidad del producto en el carrito
                    return; //detener el flujo del proceso
                }

                if($data->offices()->firstWhere('name',$office)->pivot->stock < 1){   //validar si las existencias del producto son suficientes
                    $this->emit('no-stock','Stock insuficiente');   //evento a ser escuchado desde el frontend
                    return; //detener el flujo del proceso
                }

                //metodo add agrega o actualiza item en el carrito de compras
                //aqui obtenemos el id de office_product a travez de la relacion entre offices y products
                Cart::add($data->offices()->firstWhere('name',$office)->pivot->id, $office, $sale_price, $cant, array($data->image, $data->measurement,$data->id,$data->brand));
                //Cart::add($data->id,$data->code . ' ' . $data->offices()->first()->name . ' ' . $data->offices()->first()->pivot->stock,$data->price,$cant,$data->image);   //Agregar producto al carrito con metodo add pasandole los parametros
                $this->total = Cart::getTotal();    //actualizar el total con el metodo getTotal del carrito de compras
                $this->itemsQuantity = Cart::getTotalQuantity();    //actualizar la cantidad de item con el metodo getTotalQuantity del carrito de compras
                $this->emit('scan-ok', 'Producto agregado al carrito'); //evento a ser escuchado desde el frontend
            }
            
    }

    public function InCart($id, $office){     //metodo para verificar si un producto ya existe en el carrito
        //metodo get obtiene un item por su id, de no ser encontrado devuelve null
        $exist = Cart::get($id); //obtener item del carrito y lo guardamos en variable

        //validar si el id del producto en el carrito es el mismo id de office_product
        if($exist && $exist->name == $office)
            return true;
        else
            return false;
    }

    //metodo recibe 2 parametros (id del producto, cantidad por defecto)
    public function increaseQty($cart_id, $cant = 1){ //metodo para incrementar la cantidad de la existencia del producto en el carrito
        $title = '';    //variable para manejar los mensajes que vamos a retornar en la vista
        $exist = Cart::get($cart_id);    //obtener el producto del carrito y guardar en variable
        $data = Office::where('name',$exist->name)->first();    //obtener la primera sucursal que contenga el nombre pasado por parametro
        //dd($data->products()->get()->firstWhere('pivot.id',$exist->id));
        
        if($exist)  //validar si el id de office_product existe en el carrito

            $title = 'Cantidad Actualizada';

        else

            $title = 'Producto Agregado';
        
        
        if($exist){     //validar si el id de office_product existe en el carrito

            //validar si el stock del producto es suficiente obteniendolo de office_product a travez de la relacion entre offices y products
            if($data->products()->get()->firstWhere('pivot.id',$exist->id)->pivot->stock < ($cant + $exist->quantity)){
                $this->emit('no-stock', 'Stock insuficiente');  //evento a ser escuchado desde el frontend
                return; //detener el flujo del proceso
            }
        }
        //metodo update actualiza el producto en el carrito
        //solo requiere el id del producto en el carrito y un array con los datos a actualizar
        Cart::update($exist->id,array('quantity'=> $cant));   //Agregar producto al carrito con metodo add pasandole los parametros
        $this->total = Cart::getTotal();    //actualizar el total con el metodo getTotal del carrito de compras
        $this->itemsQuantity = Cart::getTotalQuantity();    //actualizar la cantidad de item con el metodo getTotalQuantity del carrito de compras
        $this->emit('scan-ok', $title); //evento a ser escuchado desde el frontend
        
    }

    public function decreaseQty($cart_id){ //metodo para incrementar la cantidad de la existencia del producto en el carrito

        //consulta para obtener el producto cuyo id coincida con el id del producto en el carrito
        $exist = Cart::get($cart_id);    //obtener el producto del carrito y guardar en variable
        $data = Office::where('name',$exist->name)->first();    //obtener la primera sucursal que contenga el nombre pasado por parametro
        //$exist = Cart::get($cart_id); //obtener item del carrito y lo guardamos en variable
        Cart::remove($cart_id);   //metodo remove del carrito que recibe el id del producto y lo elimina
        $newQty = ($exist->quantity) - 1;    //restamos en 1 la cantidad y guardamos en variable
        $image = $data->products()->get()->firstWhere('pivot.id',$exist->id)->image;
        $measurement = $data->products()->get()->firstWhere('pivot.id',$exist->id)->measurement;
        $product_id = $data->products()->get()->firstWhere('pivot.id',$exist->id)->id;
        $brand = $data->products()->get()->firstWhere('pivot.id',$exist->id)->brand;
        //dd($data->products()->get()->firstWhere('pivot.id',$exist->id)->id);

        if($newQty > 0){ //validar si la nueva cantidad es mayor a 0

            //metodo add agrega o actualiza item en el carrito de compras
            //aqui obtenemos el id de office_product a travez de la relacion entre offices y products
            Cart::add($data->products()->get()->firstWhere('pivot.id',$exist->id)->pivot->id, $exist->name, $exist->price, $newQty, array($image,$measurement,$product_id,$brand));
            $this->total = Cart::getTotal();    //actualizar el total con el metodo getTotal del carrito de compras
            $this->itemsQuantity = Cart::getTotalQuantity();    //actualizar la cantidad de item con el metodo getTotalQuantity del carrito de compras
            $this->emit('scan-ok', 'Cantidad actualizada'); //evento a ser escuchado desde el frontend

        }else{

            $this->removeItem($cart_id);    //se hace llamado al metodo para eliminar producto del carrito
            $this->emit('scan-ok', 'Producto eliminado del carrito');   //evento a ser escuchado desde el frontend
        }
    }

    //metodo recibe 2 parametros (id del producto, cantidad por defecto) que borra y reemplaza la informacion
    public function updateQty($cart_id, $cant = 1){   //metodo para actualizar la cantidad de la existencia del producto en el carrito

        $title = '';    //variable para manejar los mensajes que vamos a retornar en la vista
        $exist = Cart::get($cart_id); //obtener item del carrito y lo guardamos en variable
        $data = Office::where('name',$exist->name)->first();    //obtener la primera sucursal que contenga el nombre pasado por parametro
        $image = $data->products()->get()->firstWhere('pivot.id',$exist->id)->image;
        $measurement = $data->products()->get()->firstWhere('pivot.id',$exist->id)->measurement;
        $product_id = $data->products()->get()->firstWhere('pivot.id',$exist->id)->id;
        $brand = $data->products()->get()->firstWhere('pivot.id',$exist->id)->brand;

        if($exist)  //validar si se obtuvo un valor distinto de null para el id buscado
            $title = 'Cantidad Actualizada';
        else
            $title = 'Producto Agregado';

        if($exist){ //validar si se obtuvo un valor distinto de null para el id buscado

            if($data->products()->get()->firstWhere('pivot.id',$exist->id)->pivot->stock < $cant){    //validar si las existencias del producto son suficientes

                $this->emit('no-stock', 'Stock insuficiente');  //evento a ser escuchado desde el frontend
                return; //detener el flujo del proceso
            }
        }

        $this->removeItem($cart_id);  //eliminar producto del carrito

        if($cant > 0){  //validar la cantidad

            //metodo add agrega o actualiza item en el carrito de compras
            //aqui obtenemos el id de office_product a travez de la relacion entre offices y products
            Cart::add($data->products()->get()->firstWhere('pivot.id',$exist->id)->pivot->id, $exist->name, $exist->price, $cant, array($image,$measurement,$product_id,$brand));
            //Cart::update($exist->id,array('quantity'=> $cant));
            $this->total = Cart::getTotal();    //actualizar el total con el metodo getTotal del carrito de compras
            $this->itemsQuantity = Cart::getTotalQuantity();    //actualizar la cantidad de item con el metodo getTotalQuantity del carrito de compras
            $this->emit('scan-ok', $title); //evento a ser escuchado desde el frontend
        }
    }

    public function removeItem($cart_id){ //metodo para eliminar item del carrito

        Cart::remove($cart_id);   //metodo remove del carrito que recibe el id del producto y lo elimina
        $this->total = Cart::getTotal();    //actualizar el total con el metodo getTotal del carrito de compras
        $this->itemsQuantity = Cart::getTotalQuantity();    //actualizar la cantidad de item con el metodo getTotalQuantity del carrito de compras
        $this->emit('scan-ok', 'Producto eliminado del carrito');   //evento a ser escuchado desde el frontend
    }

    public function clearCart(){    //metodo para limpiar el carrito y reinicializar propiedades publicas

        Cart::clear();  //metodo clear de carrito para eliminar items del carrito
        $this->efectivo = 0;
        $this->change = 0;
        $this->total = Cart::getTotal();    //actualizar el total con el metodo getTotal del carrito de compras
        $this->itemsQuantity = Cart::getTotalQuantity();    //actualizar la cantidad de item con el metodo getTotalQuantity del carrito de compras
        $this->emit('scan-ok', 'Carrito vacio');    //evento a ser escuchado desde el frontend
    }

    public function saveSale(){ //metodo para guardar venta

        if($this->total <= 0){  //validar que el total sea mayor a 0
            $this->emit('sale-error', 'AGREGA PRODUCTOS AL CARRITO');  //evento a ser escuchado desde el frontend
            return; //detener el flujo del proceso
        }

        if($this->efectivo <= 0){   //validar que el efectivo sea mayor a 0
            $this->emit('sale-error', 'INGRESE EL EFECTIVO');   //evento a ser escuchado desde el frontend
            return; //detener el flujo del proceso
        }

        if($this->total > $this->efectivo){ //validar que el efectivo sea suficiente para la compra
            $this->emit('sale-error', 'EL EFECTIVO ES INSUFICIENTE PARA LA COMPRA');    //evento a ser escuchado desde el frontend
            return; //detener el flujo del proceso
        }

        //helper DB de laravel que contiene el metodo beginTransaction
        //metodo beginTransaction es necesario debido a que se afectaran diferentes tablas a la vez
        DB::beginTransaction();     //iniciar transaccion
        
        try {
            $items = Cart::getContent();    //guardar items del carrito en variable
            //dd($items);
            foreach($items as $item){   //recorrido de items del carrito
            
                $sale = Sale::create([      //guardar venta y almacenar en variable
                    'total' => $item->price * $item->quantity,
                    'items' => $item->quantity,
                    'cash' => $this->efectivo,
                    'change' => $this->change,
                    'office' => $item->name,
                    'user_id' => Auth()->user()->id //obtener id de usuario logueado en el sistema
                ]);
                
                if($sale){  //validar si se guardo venta

                    //$items = Cart::getContent();    //guardar items del carrito en variable

                    //foreach($items as $item){   //recorrido de items del carrito

                        SaleDetail::create([   //guardar detalle de venta
                            'price' => $sale->total / $sale->items,
                            'quantity' => $sale->items,
                            'utility' => $sale->total - (Product::find($item->attributes[2])->cost * $sale->items),
                            'office' => $sale->office,
                            'product_id' => $item->attributes[2],
                            'sale_id' => $sale->id
                        ]);
                        //actualizacion de stock
                        $data = Product::find($item->attributes[2]);    //obtener el id del producto y guardarlo en variable
                        //dd($data->offices()->get()->firstWhere('pivot.id',$item->id)->pivot->stock);
                        $data->offices()->updateExistingPivot($data->offices()->get()->firstWhere('pivot.id',$item->id)->pivot->office_id,[  //actualizar el registro con metodo update de eloquent
                            'stock' => $data->offices()->get()->firstWhere('pivot.id',$item->id)->pivot->stock - $item->quantity,
                        ]);
                    //}
                }
            }
            DB::commit();   //confirmar la transaccion en la base de datos

            Cart::clear();  //metodo clear de carrito para eliminar items del carrito
            $this->efectivo = 0;
            $this->change = 0;
            $this->total = Cart::getTotal();    //actualizar el total con el metodo getTotal del carrito de compras
            $this->itemsQuantity = Cart::getTotalQuantity();    //actualizar la cantidad de item con el metodo getTotalQuantity del carrito de compras
            $this->emit('scan-ok', 'Venta registrada con exito');   //evento a ser escuchado desde el frontend
            //$this->emit('print-ticket', $sale->id); //evento a ser escuchado desde el frontend

        } catch (Exception $e) {    //capturar error en variable 
            DB::rollback();     //deshacer todas las operaciones en caso de error
            $this->emit('sale-error', $e->getMessage());    //evento a ser escuchado desde el frontend
        }
    }

    public function printTicket($sale){ //metodo para imprimir tickets de venta

        return Redirect::to("print://$sale->id");   
    }
}
