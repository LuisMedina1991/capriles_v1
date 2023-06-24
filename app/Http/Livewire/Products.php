<?php

namespace App\Http\Livewire;

use App\Models\Brand;
use App\Models\PresentationSubcategory;
use App\Models\Status;
use Livewire\Component;
use App\Models\Product;
use App\Models\Value;
use App\Models\Office;
use App\Models\OfficeValue;
use App\Models\Income;
use App\Models\Transfer;
use App\Models\Supplier;
use App\Models\DebtsWithSupplier;
use App\Models\Customer;
use App\Models\CustomerDebt;
use App\Models\Sale;
use App\Models\BankAccount;
use App\Models\Bank;
use App\Models\Paycheck;
use App\Models\Company;
use Carbon\Carbon;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Exception;
use Illuminate\Support\Facades\DB;
use Picqer;

class Products extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $pageTitle,$componentName,$search,$search_2,$selected_id;
    public $comment,$value,$image,$CodeOptions,$GenerateBarcode,$code,$random_variable;
    public $brandId,$statusId,$containerId,$brand_name,$office_name;
    public $allBrands,$allStatuses,$allContainers,$allValues,$allOffices,$allProducts,$allAccounts,$allBanks,$allCompanies;
    public $allContainers_2,$allSuppliers,$allStatuses_2,$allCustomers;
    public $my_total,$stock_details,$PaymentType,$tax_option,$tax,$aux_1,$aux_2,$aux_3,$modal_id,$modal_id_2;
    public $product_id,$office_id_1,$office_id_2,$value_id,$cant_1,$cant_2,$quantity,$accountId,$bankId,$companyId;
    public $supplierId,$customerId,$name,$alias,$phone,$fax,$email,$nit,$city,$country,$number,$address,$type,$currency,$balance,$entity_code;
    public $product_code,$cost,$price,$total_income,$total_sale,$stock,$total_income_tax,$total_sale_tax;
    private $pagination = 30;
    public $productValues = [];

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {
        $this->pageTitle = 'listado';
        $this->componentName = 'productos';
        $this->search = '';
        $this->search_2 = 0;
        $this->selected_id = 0;
        $this->image = null;
        $this->CodeOptions = 'elegir';
        $this->GenerateBarcode = 'elegir';
        $this->code = '';
        $this->random_variable = rand();
        $this->containerId = 'elegir';
        $this->allContainers = PresentationSubcategory::where('status_id', 1)->with('presentation', 'subcategory.category')->orderBy('prefix')->get();
        $this->brandId = 'elegir';
        $this->allBrands = Brand::select('id', 'name')->orderBy('name')->get();
        $this->comment = null;
        $this->allProducts = Product::all();
        /*$this->value = 'Elegir';
        $this->statusId = 'Elegir';
        $this->supplierId = 'Elegir';
        $this->customerId = 'Elegir';
        $this->accountId = 'Elegir';
        $this->bankId = 'Elegir';
        $this->companyId = 'Elegir';
        $this->PaymentType = 'Elegir';
        $this->tax_option = 'Elegir';
        $this->tax = 0;
        $this->type = 'Elegir';
        $this->currency = 'Elegir';
        $this->office_name = '';
        $this->brand_name = '';
        $this->name = '';
        $this->alias = '';
        $this->email = '';
        $this->phone = '';
        $this->fax = '';
        $this->nit = '';
        $this->city = '';
        $this->country = '';
        $this->number = '';
        $this->address = '';
        $this->balance = '';
        $this->entity_code = '';
        $this->product_code = '';
        $this->cost = '';
        $this->price = 0;
        $this->total_income = 0;
        $this->total_sale = 0;
        $this->total_income_tax = 0;
        $this->total_sale_tax = 0;
        $this->quantity = 0;
        $this->stock = 0;
        $this->aux_1 = '';
        $this->aux_2 = '';
        $this->my_total = 0;
        $this->modal_id = 0;
        $this->modal_id_2 = 0;
        $this->stock_details = [];
        $this->allValues = Value::where('status_id', 1)->get();
        $this->allOffices = Office::select('id', 'name')->get();
        $this->allStatuses = Status::select('id', 'name', 'type')->where('type', 'registro')->get();
        $this->allStatuses_2 = Status::select('id', 'name', 'type')->where('type', 'transaccion')->get();
        $this->allContainers_2 = PresentationSubcategory::with('presentation', 'subcategory.category')->get();
        $this->allAccounts = BankAccount::where('status_id', 1)->with(['company', 'bank'])->get();
        $this->allBanks = Bank::select('id', 'alias')->get();
        $this->allCompanies = Company::select('id', 'alias')->get();
        $this->allSuppliers = Supplier::select('id', 'name')->get();
        $this->allCustomers = Customer::select('id', 'name')->get();
        $this->productValues = [
            ['id' => '', 'cost' => '', 'price' => '', 'is_saved' => false]
        ];*/
        $this->resetValidation();
        $this->resetPage();
    }

    public function render()
    {

        switch ($this->search_2) {

            case 0:

                if (strlen($this->search) > 0) {

                    $data = Product::with([
                        'brand',
                        'image',
                        'container.subcategory.category',
                        'container.presentation'
                        /*'activeValues.offices',
                        'activeStocks',*/
                    ])
                        ->where('status_id', 1)
                        ->where(function ($q1) {
                            $q1->where('code', 'like', '%' . $this->search . '%');
                            $q1->orWhere(function ($q2) {
                                $q2->where('comment', 'like', '%' . $this->search . '%');
                                $q2->orWhereHas('brand', function ($q3) {
                                    $q3->where('name', 'like', '%' . $this->search . '%');
                                });
                                $q2->orWhereHas('container', function ($q3) {
                                    $q3->where('additional_info', 'like', '%' . $this->search . '%');
                                });
                            });
                        })
                        /*->whereHas('container', function ($query) {
                            $query->whereHas('subcategory', function ($query) {
                                $query->whereHas('category', function ($query) {
                                    $query->where('name', 'like', '%' . $this->search . '%');
                                });
                            });
                            $query->orWhereHas('subcategory', function ($query) {
                                $query->where('name', 'like', '%' . $this->search . '%');
                            });
                            $query->orWhereHas('presentation', function ($query) {
                                $query->where('name', 'like', '%' . $this->search . '%');
                            });
                            $query->orWhereHas('products', function ($query) {
                                $query->where('code', 'like', '%' . $this->search . '%');
                            });
                        })*/
                        ->orderBy('code', 'asc')
                        ->paginate($this->pagination);
                } else {

                    $data = Product::where('status_id', 1)
                        ->with([
                            'brand',
                            'image',
                            'container.subcategory.category',
                            'container.presentation'
                            /*'activeValues.offices',
                            'activeStocks',*/
                        ])
                        ->orderBy('code', 'asc')
                        ->paginate($this->pagination);
                }

                break;

            case 1:

                if (strlen($this->search) > 2) {

                    $data = Product::with([
                        'brand',
                        'image',
                        'container.subcategory.category',
                        'container.presentation'
                        /*'activeValues.offices',
                        'activeStocks',*/
                    ])
                        ->where('status_id', 2)
                        ->where(function ($q1) {
                            $q1->where('code', 'like', '%' . $this->search . '%');
                            $q1->orWhere(function ($q2) {
                                $q2->where('comment', 'like', '%' . $this->search . '%');
                                $q2->orWhereHas('brand', function ($q3) {
                                    $q3->where('name', 'like', '%' . $this->search . '%');
                                });
                                $q2->orWhereHas('container', function ($q3) {
                                    $q3->where('additional_info', 'like', '%' . $this->search . '%');
                                });
                            });
                        })
                        /*->whereHas('container', function ($query) {
                            $query->whereHas('subcategory', function ($query) {
                                $query->whereHas('category', function ($query) {
                                    $query->where('name', 'like', '%' . $this->search . '%');
                                });
                            });
                            $query->orWhereHas('subcategory', function ($query) {
                                $query->where('name', 'like', '%' . $this->search . '%');
                            });
                            $query->orWhereHas('presentation', function ($query) {
                                $query->where('name', 'like', '%' . $this->search . '%');
                            });
                            $query->orWhereHas('products', function ($query) {
                                $query->where('code', 'like', '%' . $this->search . '%');
                            });
                        })*/
                        ->orderBy('code', 'asc')
                        ->paginate($this->pagination);
                } else {

                    $data = Product::where('status_id', 2)
                        ->with([
                            'brand',
                            'image',
                            'container.subcategory.category',
                            'container.presentation'
                            /*'activeValues.offices',
                            'activeStocks',*/
                        ])
                        ->orderBy('code', 'asc')
                        ->paginate($this->pagination);
                }

                break;
        }

        /*foreach($data as $product){

            foreach($product->activeValues as $value){

                foreach($value->offices as $office){
    
                    $this->my_total += $value->cost * $office->pivot->stock;
                }
            }
        }*/


        return view('livewire.product.products', [
            'products' => $data,
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    //metodo para limpiar el campo del codigo o seleccionarlo al cambiar el campo de opciones de codigo
    public function updatedCodeOptions(){

        $this->code = '';

        if($this->CodeOptions == 1){

            $this->emit('code-focus');

        }

    }

    public function updatedsearch()
    {
        $this->value = 'elegir';
    }

    public function updatedPaymentType()
    {
        $this->statusId = 'elegir';
    }

    /*public function updatedquantity()
    {

        if($this->quantity != '' && $this->quantity > 0){

            $this->total_income = number_format($this->cost * $this->quantity,2);

            if($this->price != '' && $this->price > 0){

                $this->total_sale = number_format($this->price * $this->quantity,2);

            }else{

                $this->total_sale = 0;
            }
            
            if($this->tax != '' && $this->tax > 0){

                $this->total_income_tax = number_format($this->total_income * $this->tax,2);
                $this->total_sale_tax = number_format($this->total_sale * $this->tax,2);

            }else{

                $this->total_income_tax = 0;
                $this->total_sale_tax = 0;
            }

        }else{

            $this->total_income = 0;
            $this->total_income_tax = 0;
            $this->total_sale = 0;
            $this->total_sale_tax = 0;
        }

    }

    public function updatedtax()
    {
        if($this->quantity == '' || $this->quantity <= 0 || $this->tax == '' || $this->tax <= 0){

            $this->total_income_tax = 0;
            $this->total_sale_tax = 0;

        }else{

            $this->total_income_tax = number_format($this->total_income * $this->tax,2);
            $this->total_sale_tax = number_format($this->total_sale * $this->tax,2);
        }

    }

    public function updatedprice()
    {
        if($this->price == '' || $this->price <= 0 || $this->quantity == '' || $this->quantity <= 0){

            $this->total_sale = 0;

        }else{

            $this->total_sale = number_format($this->price * $this->quantity,2);

            if($this->tax == '' || $this->tax <= 0){

                $this->total_sale_tax = 0;
    
            }else{
    
                $this->total_sale_tax = number_format($this->total_sale * $this->tax,2);
            }
        }

    }*/

    public function updatedaccountId()
    {
        if($this->accountId != 'Elegir'){
            
            $this->balance = number_format($this->allAccounts->find($this->accountId)->balance,2);

        }else{

            $this->balance = 0;
        }

    }

    public function addValue()
    {

        foreach ($this->productValues as $key => $productValue) {

            $this->resetErrorBag(['productValues.' . $key . '.cost', 'productValues.' . $key . '.price']);

            if (!$productValue['is_saved']) {

                $this->addError('productValues.' . $key . '.cost', 'Debe terminar de editar');
                $this->addError('productValues.' . $key . '.price', 'Debe terminar de editar');
                return;
            }

            if ($key == 2) {

                $this->addError('productValues.' . $key . '.cost', 'Maximo 3 costos');
                $this->addError('productValues.' . $key . '.price', 'Maximo 3 precios');
                return;
            }
        }

        $this->productValues[] = ['id' => '', 'cost' => '', 'price' => '', 'is_saved' => false];
    }

    public function saveValue($index)
    {

        $this->resetErrorBag(['productValues.' . $index . '.cost', 'productValues.' . $index . '.price']);
        $this->productValues[$index]['is_saved'] = true;
    }

    public function editValue($index)
    {

        foreach ($this->productValues as $key => $productValue) {

            $this->resetErrorBag(['productValues.' . $key . '.cost', 'productValues.' . $key . '.price']);

            if (!$productValue['is_saved']) {

                $this->addError('productValues.' . $key . '.cost', 'Debe terminar de editar');
                $this->addError('productValues.' . $key . '.price', 'Debe terminar de editar');
                return;
            }
        }

        $this->productValues[$index]['is_saved'] = false;
    }

    public function removeValue($index)
    {

        $this->resetErrorBag(['productValues.' . $index . '.cost', 'productValues.' . $index . '.price']);

        if ($index > 0) {

            unset($this->productValues[$index]);
            $this->productValues = array_values($this->productValues);
        } else {

            $this->addError('productValues.' . $index . '.cost', 'Minimo 1 costo');
            $this->addError('productValues.' . $index . '.price', 'Minimo 1 precio');
            return;
        }
    }

    public function Stock_Detail($product_id)
    {

        $product = Product::find($product_id);
        $this->stock_details = $product->activeStocks;
        $this->emit('show-stock-detail', 'mostrando modal');
    }

    public function ShowAccountModal($modal)
    {
        $this->number = '';
        $this->type = 'Elegir';
        $this->currency = 'Elegir';
        $this->balance = '';
        $this->bankId = 'Elegir';
        $this->companyId = 'Elegir';
        $this->modal_id = 1;
        
        if ($modal < 1) {

            $this->emit('show-account-modal-1', 'Abrir Modal');
        } else {

            $this->emit('show-account-modal-2', 'Abrir Modal');
        }
    }

    public function CloseAccountModal($modal){

        $this->resetValidation($this->number = null);
        $this->resetValidation($this->balance = null);
        $this->modal_id = 0;
        //$this->ShowSaleModal(OfficeValue::find($this->selected_id));
        if ($modal < 1) {

            $this->emit('show-income-modal', 'Mostrando modal');
        } else {

            $this->emit('show-sale-modal', 'Mostrando modal');
        }
    }

    public function StoreAccount($modal){

        $rules = [

            'companyId' => 'not_in:Elegir',
            'bankId' => 'not_in:Elegir',
            'type' => 'not_in:Elegir',
            'currency' => 'not_in:Elegir',
            'number' => 'required|digits_between:11,14|unique:bank_accounts',
            'balance' => 'required|numeric|gte:0',
        ];

        $messages = [

            'companyId.not_in' => 'Seleccione una opcion',
            'bankId.not_in' => 'Seleccione una opcion',
            'type.not_in' => 'Seleccione una opcion',
            'currency.not_in' => 'Seleccione una opcion',
            'number.required' => 'Campo requerido',
            'number.digits_between' => 'Solo numeros enteros positivos, de 11 a 14 digitos',
            'number.unique' => 'Ya existe',
            'balance.required' => 'Campo requerido',
            'balance.numeric' => 'Solo numeros',
            'balance.gte' => 'Solo numeros positivos',
        ];

        $this->validate($rules, $messages);

        $account = BankAccount::create([

            'number' => $this->number,
            'type' => $this->type,
            'currency' => $this->currency,
            'balance' => $this->balance,
            'company_id' => $this->companyId,
            'bank_id' => $this->bankId,
            'status_id' => 1

        ]);

        if ($account) {

            $this->number = '';
            $this->type = 'Elegir';
            $this->currency = 'Elegir';
            $this->balance = '';
            $this->companyId = 'Elegir';
            $this->bankId = 'Elegir';
            $this->allAccounts = BankAccount::where('status_id', 1)->with(['company', 'bank'])->get();
            $this->accountId = $account->id;
            $this->modal_id = 0;
            $this->emit('account-added', 'Registrado correctamente');

            if ($modal < 1) {

                $this->emit('show-income-modal', 'Mostrando modal');
            } else {
    
                $this->emit('show-sale-modal', 'Mostrando modal');
            }

        } else {

            $this->emit('record-error', 'Error al Registrar');
            return;
        }

    }

    public function ShowBankModal($modal)
    {
        $this->name = '';
        $this->alias = '';
        $this->entity_code = '';

        if ($modal < 1) {

            $this->emit('show-bank-modal-1', 'Abrir Modal');
        } else {

            $this->emit('show-bank-modal-2', 'Abrir Modal');
        }

    }

    public function CloseBankModal($modal){

        $this->resetValidation($this->name = null);
        $this->resetValidation($this->alias = null);
        $this->resetValidation($this->entity_code = null);
        
        if ($modal < 1) {

            $this->emit('show-sale-modal', 'Mostrando modal');
        } else {

            $this->emit('show-account-modal', 'Mostrando modal');
        }
    }

    public function StoreBank($modal){

        $rules = [

            'name' => 'required|min:3|max:100|unique:banks',
            'alias' => 'required|min:3|max:15|unique:banks',
            'entity_code' => 'required|digits_between:4,6|unique:banks',
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 100 caracteres',
            'name.unique' => 'Ya existe',
            'alias.required' => 'Campo requerido',
            'alias.min' => 'Minimo 3 caracteres',
            'alias.max' => 'Maximo 15 caracteres',
            'alias.unique' => 'Ya existe',
            'entity_code.required' => 'Campo requerido',
            'entity_code.unique' => 'Ya existe',
            'entity_code.digits_between' => 'Solo numeros enteros positivos, de 4 a 6 digitos',
        ];

        $this->validate($rules, $messages);

        $bank = Bank::create([
            
            'name' => $this->name,
            'alias' => $this->alias,
            'entity_code' => $this->entity_code,
            'status_id' => 1
        ]);

        if ($bank) {

            $this->name = '';
            $this->alias = '';
            $this->entity_code = '';
            $this->allBanks = Bank::select('id','alias')->get();
            $this->bankId = $bank->id;
            $this->emit('bank-added', 'Registrado correctamente');

            if ($modal < 1) {

                $this->emit('show-account-modal', 'Mostrando modal');
            } else {
    
                $this->emit('show-sale-modal', 'Mostrando modal');
            }


        } else {

            $this->emit('item-error', 'Error al Registrar');
            return;
        }

    }

    public function ShowCompanyModal()
    {
        $this->name = '';
        $this->alias = '';
        $this->phone = '';
        $this->fax = '';
        $this->email = '';
        $this->nit = '';
        $this->address = '';
        $this->emit('show-company-modal', 'Abrir Modal');
    }

    public function CloseCompanyModal(){

        $this->resetValidation($this->name = null);
        $this->resetValidation($this->alias = null);
        $this->resetValidation($this->phone = null);
        $this->resetValidation($this->fax = null);
        $this->resetValidation($this->email = null);
        $this->resetValidation($this->nit = null);
        $this->resetValidation($this->address = null);
        $this->emit('show-account-modal', 'Mostrando Modal');
    }

    public function StoreCompany(){

        $rules = [

            'name' => 'required|min:3|max:100',
            'alias' => 'required|min:3|max:15|unique:companies',
            'phone' => 'max:12',
            'fax' => 'max:12',
            'email' => 'max:100',
            'nit' => 'required|digits_between:12,13|unique:companies',
            'address' => 'max:100',
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 100 caracteres',
            'alias.required' => 'Campo requerido',
            'alias.min' => 'Minimo 3 caracteres',
            'alias.max' => 'Maximo 15 caracteres',
            'alias.unique' => 'Ya existe',
            'phone.max' => 'Maximo 12 caracteres',
            'fax.max' => 'Maximo 12 caracteres',
            'email.max' => 'Maximo 100 caracteres',
            'nit.required' => 'Campo requerido',
            'nit.unique' => 'Ya existe',
            'nit.digits_between' => 'Solo numeros enteros positivos, de 12 a 13 digitos',
            'address.max' => 'Maximo 100 caracteres',
        ];

        $this->validate($rules, $messages);

        $company = Company::create([
            
            'name' => $this->name,
            'alias' => $this->alias,
            'phone' => $this->phone,
            'fax' => $this->fax,
            'email' => $this->email,
            'nit' => $this->nit,
            'address' => $this->address,
            'status_id' => 1
        ]);

        if ($company) {

            $this->name = '';
            $this->alias = '';
            $this->phone = '';
            $this->fax = '';
            $this->email = '';
            $this->nit = '';
            $this->address = '';
            $this->allCompanies = Company::select('id','alias')->get();
            $this->companyId = $company->id;
            $this->emit('company-added', 'Registrado correctamente');
        } else {

            $this->emit('item-error', 'Error al Registrar');
            return;
        }

    }

    public function ShowSaleModal(OfficeValue $stock)
    {

        $this->selected_id = $stock->id;
        $this->product_id = $stock->value->product_id;
        $this->product_code = $stock->value->product->code;
        $this->office_id_1 = $stock->office_id;
        $this->office_name = $stock->office->name;
        $this->value_id = $stock->value_id;
        $this->statusId = 'Elegir';
        $this->customerId = 'Elegir';
        $this->PaymentType = 'Elegir';
        $this->tax_option = 'Elegir';
        $this->accountId = 'Elegir';
        $this->bankId = 'Elegir';
        $this->stock = $stock->stock;
        $this->quantity = 0;
        $this->balance = 0;
        $this->total_sale_tax = 0;
        $this->total_sale = 0;
        $this->cost = number_format($stock->value->cost, 2);
        $this->price = 0;
        $this->tax = 0;
        $this->number = '';
        $this->modal_id_2 = 1;
        $this->emit('show-sale-modal', 'Abrir Modal');
    }

    public function CloseSaleModal()
    {

        $this->resetValidation($this->quantity = null);
        $this->modal_id_2 = 0;
        $this->Stock_Detail($this->product_id);
    }

    public function Sale()
    {

        $rules = [

            'PaymentType' => 'not_in:Elegir',
            'statusId' => 'not_in:Elegir',
            'quantity' => "required|lte:$this->stock|integer|gt:0",
            'customerId' => 'not_in:Elegir',
            'accountId' => 'exclude_unless:PaymentType,deposito|not_in:Elegir',
            'number' => 'exclude_unless:PaymentType,cheque|required|digits_between:6,15',
            'bankId' => 'exclude_unless:PaymentType,cheque|not_in:Elegir',
            'tax' => 'exclude_unless:tax_option,1|required|numeric|gt:0',
            'tax_option' => 'not_in:Elegir',
            'price' => 'required|numeric|gt:0',
        ];

        $messages = [

            'PaymentType.not_in' => 'Seleccione una opcion',
            'statusId.not_in' => 'Seleccione una opcion',
            'quantity.required' => 'Campo requerido',
            'quantity.lte' => 'La cantidad es mayor al stock',
            'quantity.integer' => 'Solo numeros enteros',
            'quantity.gt' => 'Solo numeros mayores a 0',
            'customerId.not_in' => 'Seleccione una opcion',
            'accountId.not_in' => 'Seleccione una opcion',
            'number.required' => 'Campo requerido',
            'number.digits_between' => 'Solo numeros enteros positivos, de 6 a 15 digitos',
            'bankId.not_in' => 'Seleccione una opcion',
            'tax.required' => 'Campo requerido',
            'tax.numeric' => 'Solo numeros',
            'tax.gt' => 'Solo numeros mayores a 0',
            'tax_option.not_in' => 'Seleccione una opcion',
            'price.required' => 'Campo Requerido',
            'price.numeric' => 'Solo Numeros',
            'price.gt' => 'Solo numeros mayores a 0',
        ];

        $this->validate($rules, $messages);

        DB::beginTransaction();

        try {

            $value = Value::find($this->value_id);
            $now = Carbon::parse(Carbon::now())->format('d-m-Y');

            if ($this->tax_option == 1) {

                $sale = Sale::create([

                    'quantity' => $this->quantity,
                    'sale_price' => ($this->price * $this->tax) + $this->price,
                    'total_cost' => $value->cost * $this->quantity,
                    'total_price' => ($this->price * $this->quantity) + (($this->price * $this->quantity) * $this->tax),
                    'utility' => (($this->price * $this->quantity) + (($this->price * $this->quantity) * $this->tax)) - ($value->cost * $this->quantity),
                    'payment_type' => $this->PaymentType,
                    'status_id' => $this->statusId,
                    'user_id' => Auth()->user()->id,
                    'customer_id' => $this->customerId,
                    'office_value_id' => $this->selected_id
    
                ]);

                $sale->tax()->create([
                    'description' =>
                    $sale->quantity . ' ' .
                        'unidades' . ' ' .
                        'de' . ' ' .
                        $value->product->code . ' ' .
                        'a' . ' ' .
                        '$' .
                        number_format($this->price, 2) . ' ' .
                        'por unidad' . ' ' .
                        'con impuesto del' . ' ' .
                        $this->tax * 100 .
                        '%' . ' ' .
                        'en fecha' . ' ' .
                        $now,
                    'amount' => ($this->price * $this->quantity) * $this->tax,
                    'status_id' => 1
                ]);

            }else{

                $sale = Sale::create([

                    'quantity' => $this->quantity,
                    'sale_price' => $this->price,
                    'total_cost' => $value->cost * $this->quantity,
                    'total_price' => $this->price * $this->quantity,
                    'utility' => ($this->price * $this->quantity) - ($value->cost * $this->quantity),
                    'payment_type' => $this->PaymentType,
                    'status_id' => $this->statusId,
                    'user_id' => Auth()->user()->id,
                    'customer_id' => $this->customerId,
                    'office_value_id' => $this->selected_id
    
                ]);

            }


            if ($sale) {

                switch ($sale->payment_type) {

                    case 'efectivo':

                        if ($sale->status_id == 4) {

                            CustomerDebt::create([
        
                                'description' =>
                                $sale->quantity . ' ' .
                                    'unidades' . ' ' .
                                    'de' . ' ' .
                                    $value->product->code . ' ' .
                                    'a' . ' ' .
                                    '$' .
                                    number_format($sale->sale_price, 2) . ' ' .
                                    'por unidad' . ' ' .
                                    'en fecha' . ' ' .
                                    $now,
                                'amount' => $sale->total_price,
                                'status_id' => 1,
                                'sale_id' => $sale->id,
                                'customer_id' => $sale->customer_id
        
                            ]);
                        }

                    break;

                    case 'deposito':

                        $account = $this->allAccounts->find($this->accountId);

                        $account->details()->create([
                            'action' => 'ingreso',
                            'relation_file_number' => $sale->file_number,
                            'description' =>
                                'venta de producto' . ' ' .
                                'en fecha' . ' ' .
                                $now,
                            'amount' => $sale->total_price,
                            'previus_balance' => $account->balance,
                            'actual_balance' => $account->balance + $sale->total_price,
                            'status_id' => 1
                        ]);

                        $account->Update([

                            'balance' => $account->balance + $sale->total_price

                        ]);

                    break;

                    case 'cheque':

                        if ($sale->status_id == 4) {

                            Paycheck::create([

                                'description' =>
                                $sale->quantity . ' ' .
                                    'unidades' . ' ' .
                                    'de' . ' ' .
                                    $value->product->code . ' ' .
                                    'a' . ' ' .
                                    '$' .
                                    number_format($sale->sale_price, 2) . ' ' .
                                    'por unidad' . ' ' .
                                    'en fecha' . ' ' .
                                    $now,
                                'number' => $this->number,
                                'amount' => $sale->total_price,
                                'status_id' => 1,
                                'sale_id' => $sale->id,
                                'bank_id' => $this->bankId,
                                'customer_id' => $sale->customer_id
    
                            ]);

                        }

                    break;

                }

                $value->offices()->updateExistingPivot($this->office_id_1, [

                    'stock' => $this->stock - $this->quantity

                ]);


                DB::commit();
                $this->emit('item-saled', 'Venta Exitosa');
                $this->value = 'Elegir';
                $this->modal_id_2 = 0;
                $this->Stock_Detail($this->product_id);

            }

        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function ShowIncomeModal(OfficeValue $stock)
    {

        $this->selected_id = $stock->id;
        $this->product_id = $stock->value->product_id;
        $this->product_code = $stock->value->product->code;
        $this->office_id_1 = $stock->office_id;
        $this->office_name = $stock->office->name;
        $this->value_id = $stock->value_id;
        $this->cost = number_format($stock->value->cost,2);
        $this->stock = $stock->stock;
        $this->quantity = 0;
        $this->statusId = 'Elegir';
        $this->supplierId = 'Elegir';
        $this->PaymentType = 'Elegir';
        $this->tax_option = 'Elegir';
        $this->tax = 0;
        $this->total_income = 0;
        $this->total_income_tax = 0;
        $this->balance = 0;
        $this->accountId = 'Elegir';
        $this->emit('show-income-modal', 'Abrir Modal');
    }

    public function CloseIncomeModal()
    {

        $this->resetValidation($this->quantity = null);
        $this->Stock_Detail($this->product_id);
    }

    public function Income()
    {

        $rules = [

            'supplierId' => 'not_in:Elegir',
            'PaymentType' => 'not_in:Elegir',
            'statusId' => 'not_in:Elegir',
            'tax_option' => 'not_in:Elegir',
            'quantity' => "required|integer|gt:0",
            'accountId' => 'exclude_unless:PaymentType,deposito|not_in:Elegir',
            'tax' => 'exclude_unless:tax_option,1|required|numeric|gt:0',
            'balance' => "exclude_unless:PaymentType,deposito|gte:$this->total_income",
        ];

        $messages = [

            'supplierId.not_in' => 'Seleccione una opcion',
            'PaymentType.not_in' => 'Seleccione una opcion',
            'statusId.not_in' => 'Seleccione una opcion',
            'tax_option.not_in' => 'Seleccione una opcion',
            'quantity.required' => 'Campo requerido',
            'quantity.integer' => 'Solo numeros enteros',
            'quantity.gt' => 'Solo numeros mayores a 0',
            'accountId.not_in' => 'Seleccione una opcion',
            'tax.required' => 'Campo requerido',
            'tax.numeric' => 'Solo numeros',
            'tax.gt' => 'Solo numeros mayores a 0',
            'balance.gte' => 'Saldo insuficiente',
        ];

        $this->validate($rules, $messages);

        DB::beginTransaction();

        try {

            $value = Value::find($this->value_id);
            $now = Carbon::parse(Carbon::now())->format('d-m-Y');

            if ($this->tax_option == 1) {

                $income = Income::create([

                    'income_type' => 'compra',
                    'payment_type' => $this->PaymentType,
                    'previus_stock' => $this->stock,
                    'quantity' => $this->quantity,
                    'total' => (($value->cost * $this->quantity) + (($value->cost * $this->quantity) * $this->tax)),
                    'status_id' => $this->statusId,
                    'user_id' => Auth()->user()->id,
                    'supplier_id' => $this->supplierId,
                    'office_value_id' => $this->selected_id
    
                ]);

                $income->tax()->create([
                    'description' =>
                    $income->quantity . ' ' .
                        'unidades' . ' ' .
                        'de' . ' ' .
                        $value->product->code . ' ' .
                        'a' . ' ' .
                        '$' .
                        number_format($value->cost, 2) . ' ' .
                        'por unidad' . ' ' .
                        'con impuesto del' . ' ' .
                        $this->tax * 100 .
                        '%' . ' ' .
                        'en fecha' . ' ' .
                        $now,
                    'amount' => ($value->cost * $income->quantity) * $this->tax,
                    'status_id' => 1
                ]);

            }else{

                $income = Income::create([

                    'income_type' => 'compra',
                    'payment_type' => $this->PaymentType,
                    'previus_stock' => $this->stock,
                    'quantity' => $this->quantity,
                    'total' => $value->cost * $this->quantity,
                    'status_id' => $this->statusId,
                    'user_id' => Auth()->user()->id,
                    'supplier_id' => $this->supplierId,
                    'office_value_id' => $this->selected_id
    
                ]);

            }


            if ($income) {

                switch($income->payment_type){

                    case 'efectivo':

                        if ($income->status_id == 4) {

                            DebtsWithSupplier::create([
        
                                'description' =>
                                $income->quantity . ' ' .
                                    'unidades' . ' ' .
                                    'de' . ' ' .
                                    $value->product->code . ' ' .
                                    'a' . ' ' .
                                    '$' .
                                    number_format($value->cost, 2) . ' ' .
                                    'por unidad' . ' ' .
                                    'en fecha' . ' ' .
                                    $now,
                                'amount' => $income->total,
                                'status_id' => 1,
                                'income_id' => $income->id,
                                'supplier_id' => $income->supplier_id
        
                            ]);
                        }

                    break;

                    case 'deposito':

                        $account = $this->allAccounts->find($this->accountId);

                        $account->details()->create([
                            'action' => 'egreso',
                            'relation_file_number' => $income->file_number,
                            'description' =>
                                'compra de producto' . ' ' .
                                'en fecha' . ' ' .
                                $now,
                            'amount' => $income->total,
                            'previus_balance' => $account->balance,
                            'actual_balance' => $account->balance - $income->total,
                            'status_id' => 1
                        ]);

                        $account->Update([

                            'balance' => $account->balance - $income->total

                        ]);

                    break;

                }

                $value->offices()->updateExistingPivot($this->office_id_1, [

                    'stock' => $this->stock + $this->quantity

                ]);

                DB::commit();
                $this->emit('item-entered', 'Ingreso Exitoso');
                $this->value = 'Elegir';
                $this->Stock_Detail($this->product_id);
                
            }

            
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function ShowTransferModal(OfficeValue $stock)
    {

        $this->selected_id = $stock->id;
        $this->product_id = $stock->value->product_id;
        $this->office_id_1 = $stock->office_id;
        $this->office_id_2 = 'Elegir';
        $this->value_id = $stock->value_id;
        $this->cant_1 = $stock->stock;
        $this->cant_2 = $stock->stock;
        $this->emit('show-transfer-modal', 'Abrir Modal');
    }

    public function CloseTransferModal()
    {

        $this->resetValidation($this->cant_2 = null);
        $this->Stock_Detail($this->product_id);
    }

    public function Transfer()
    {

        $rules = [

            'office_id_2' => "not_in:Elegir,$this->office_id_1",
            'cant_2' => "required|lte:$this->cant_1|integer|gt:0",
        ];

        $messages = [

            'office_id_2.not_in' => 'Elija una sucursal de destino diferente',
            'cant_2.required' => 'Campo requerido',
            'cant_2.lte' => 'La cantidad es mayor al stock',
            'cant_2.integer' => 'Solo numeros enteros',
            'cant_2.gt' => 'Solo numeros mayores a 0',
        ];

        $this->validate($rules, $messages);

        DB::beginTransaction();

        try {

            $value = Value::find($this->value_id);
            $from_office = $this->allOffices->find($this->office_id_1);
            $to_office = $this->allOffices->find($this->office_id_2);
            //$pivot = $value->offices()->firstWhere('office_id',$this->office_id_2);
            //$pivot = $value->offices()->get()->firstWhere('pivot.office_id',$this->office_id_2);
            $pivot = $value->offices->firstWhere('pivot.office_id', $this->office_id_2);

            $transfer = Transfer::create([

                'previus_stock' => $pivot->pivot->stock,
                'quantity' => $this->cant_2,
                'from_office' => $from_office->name,
                'to_office' => $to_office->name,
                'status_id' => 3,
                'user_id' => Auth()->user()->id,
                'office_value_id' => $this->selected_id

            ]);

            if ($transfer) {

                $value->offices()->updateExistingPivot($this->office_id_1, [

                    'stock' => $this->cant_1 - $this->cant_2

                ]);

                $value->offices()->updateExistingPivot($this->office_id_2, [

                    'stock' => $pivot->pivot->stock + $this->cant_2

                ]);
            }

            DB::commit();
            $this->emit('item-transfered', 'Traspaso Exitoso');
            $this->Stock_Detail($this->product_id);
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function ShowCustomerModal()
    {
        $this->emit('show-customer-modal', 'Mostrando Modal');
    }

    public function CloseCustomerModal()
    {
        $this->name = '';
        $this->resetValidation($this->name = null);
        $this->email = '';
        $this->resetValidation($this->email = null);
        $this->phone = '';
        $this->resetValidation($this->phone = null);
        $this->fax = '';
        $this->resetValidation($this->fax = null);
        $this->nit = '';
        $this->resetValidation($this->nit = null);
        $this->city = '';
        $this->resetValidation($this->city = null);
        $this->country = '';
        $this->resetValidation($this->country = null);
        $this->emit('show-sale-modal', 'Mostrando Modal');
    }

    public function StoreCustomer()
    {
        $rules = [

            'name' => 'required|min:3|max:100',
            'email' => 'max:100',
            'phone' => 'max:12',
            'fax' => 'max:12',
            'nit' => 'max:12',
            'city' => 'max:45',
            'country' => 'max:45',
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 100 caracteres',
            'email.max' => 'Maximo 100 caracteres',
            'phone.max' => 'Maximo 12 caracteres',
            'fax.max' => 'Maximo 12 caracteres',
            'nit.max' => 'Maximo 12 caracteres',
            //'nit.unique' => 'Otro cliente ocupa este NIT',
            'city.max' => 'Maximo 45 caracteres',
            'country.max' => 'Maximo 45 caracteres',
        ];

        $this->validate($rules, $messages);

        $customer = Customer::create([

            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'fax' => $this->fax,
            'nit' => $this->nit,
            'city' => $this->city,
            'country' => $this->country
        ]);

        if ($customer) {

            $this->name = '';
            $this->email = '';
            $this->phone = '';
            $this->fax = '';
            $this->nit = '';
            $this->city = '';
            $this->country = '';
            $this->allCustomers = Customer::select('id', 'name')->get();
            $this->customerId = $customer->id;
            $this->emit('customer-added', 'Registrado correctamente');
        } else {

            $this->emit('record-error', 'Error al Registrar');
            return;
        }
    }

    public function ShowSupplierModal()
    {
        $this->emit('show-supplier-modal', 'Mostrando Modal');
    }

    public function CloseSupplierModal()
    {
        $this->name = '';
        $this->resetValidation($this->name = null);
        $this->email = '';
        $this->resetValidation($this->email = null);
        $this->phone = '';
        $this->resetValidation($this->phone = null);
        $this->fax = '';
        $this->resetValidation($this->fax = null);
        $this->nit = '';
        $this->resetValidation($this->nit = null);
        $this->city = '';
        $this->resetValidation($this->city = null);
        $this->country = '';
        $this->resetValidation($this->country = null);
        $this->emit('show-income-modal', 'Mostrando Modal');
    }

    public function StoreSupplier()
    {
        $rules = [

            'name' => 'required|min:3|max:100',
            'email' => 'max:100',
            'phone' => 'max:12',
            'fax' => 'max:12',
            'nit' => 'max:12',
            'city' => 'max:45',
            'country' => 'max:45',
        ];

        $messages = [

            'name.required' => 'Campo requerido',
            'name.min' => 'Minimo 3 caracteres',
            'name.max' => 'Maximo 100 caracteres',
            'email.max' => 'Maximo 100 caracteres',
            'phone.max' => 'Maximo 12 caracteres',
            'fax.max' => 'Maximo 12 caracteres',
            'nit.max' => 'Maximo 12 caracteres',
            //'nit.unique' => 'Otro proveedor ocupa este NIT',
            'city.max' => 'Maximo 45 caracteres',
            'country.max' => 'Maximo 45 caracteres',
        ];

        $this->validate($rules, $messages);

        $supplier = Supplier::create([

            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'fax' => $this->fax,
            'nit' => $this->nit,
            'city' => $this->city,
            'country' => $this->country
        ]);

        if ($supplier) {

            $this->name = '';
            $this->email = '';
            $this->phone = '';
            $this->fax = '';
            $this->nit = '';
            $this->city = '';
            $this->country = '';
            $this->allSuppliers = Supplier::select('id', 'name')->get();
            $this->supplierId = $supplier->id;
            $this->emit('supplier-added', 'Registrado correctamente');
        } else {

            $this->emit('record-error', 'Error al Registrar');
            return;
        }
    }

    public function ShowBrandModal()
    {
        $this->emit('show-modal-2', 'Mostrando Modal');
    }

    public function CloseBrandModal()
    {
        $this->brand_name = '';
        $this->resetValidation($this->brand_name = null);
        $this->emit('show-modal', 'Mostrando Modal');
    }

    public function StoreBrand()
    {

        $rules = [

            'brand_name' => 'required|unique:brands,name|min:3|max:100'
        ];

        $messages = [

            'brand_name.required' => 'Campo requerido',
            'brand_name.unique' => 'Ya existe',
            'brand_name.min' => 'Minimo 3 caracteres',
            'brand_name.max' => 'Maximo 100 caracteres',
        ];

        $this->validate($rules, $messages);

        $brand = Brand::create([

            'name' => $this->brand_name
        ]);

        if ($brand) {

            $this->brand_name = '';
            $this->allBrands = Brand::select('id', 'name')->get();
            $this->brandId = $brand->id;
            $this->emit('item-added-2', 'Registrado correctamente');
        } else {

            $this->emit('record-error', 'Error al Registrar');
            return;
        }
    }

    public function Store()
    {
        $rules = [

            'containerId' => 'not_in:elegir',
            'brandId' => 'not_in:elegir',
            'comment' => 'max:45',
            'image' => 'exclude_if:image,null|mimes:jpg,png',
            'GenerateBarcode' => 'not_in:elegir',
            'CodeOptions' => 'not_in:elegir',
            'code' => 'required_if:CodeOptions,1|max:45|unique:products',
            //'productValues.*.cost' => 'required|numeric',
            //'productValues.*.price' => 'required|numeric',
        ];

        $messages = [

            'containerId.not_in' => 'Seleccione una opcion',
            'brandId.not_in' => 'Seleccione una opcion',
            'comment.max' => 'Maximo 45 caracteres',
            'image.mimes' => 'Solo formatos jpg o png',
            'GenerateBarcode.not_in' => 'Seleccione una opcion',
            'CodeOptions.not_in' => 'Seleccione una opcion',
            'code.required_if' => 'Campo requerido',
            'code.max' => 'Maximo 45 caracteres',
            'code.unique' => 'Ya existe',
            //'productValues.*.cost.required' => 'Campo requerido',
            //'productValues.*.cost.numeric' => 'Solo numeros',
            //'productValues.*.price.required' => 'Campo requerido',
            //'productValues.*.price.numeric' => 'Solo numeros',
        ];

        $this->validate($rules, $messages);

        DB::beginTransaction();

        try {

            if ($this->CodeOptions == 0) {    //validamos si la opcion de codigo "generar" esta seleccionada

                //obtenemos todos los productos que tengan un numero asignado ordenados de forma asc. Lo que significa que su codigo fue generado automaticamente
                //en este caso se asignara al producto un codigo con un prefijo seguido de una secuencia numeral que se ira incrementando
                $products = $this->allProducts->whereNotNull('number')->sortBy('number');
                $new_number = $products->max('number') + 1;     //generamos un nuevo numero para usar en un nuevo codigo de producto e iniciar/continuar con la secuencia
                $prefix = 'prod';   //asignamos un prefijo para usar en un nuevo codigo de producto
                $new_code = $prefix . '-' . str_pad($new_number, 4, 0, STR_PAD_LEFT);    //generamos un nuevo codigo de producto que se usara dependiendo de la situacion

                if (count($products) > 0) {   //validamos si existen productos con codigo generado

                    //lo siguiente nos servira para verificar si la secuencia es continua/correcta hasta el ultimo producto con codigo generado

                    $i = 1; //variable para verificar la secuencia,la secuencia deberia empezar en 1 por lo que se le asigna ese valor a la variable

                    foreach ($products as $p) {   //bucle para recorrer todos los productos con codigo generado

                        if ($p->number == $i) {   //si el numero secuencial del producto coincide con el valor de la variable

                            $i++;   //se incrementa el valor de la variable en 1

                        }

                    }

                    //si la secuencia es continua/correcta (1,2,3,4...) $i = $new_number (el numero mas alto entre los productos con codigo generado + 1)
                    //caso contrario significa que falta un numero en la secuencia (1,3,5,6...) $i = 2 (1er numero faltante en la secuencia en este caso)

                    $disabled_products = $products->where('status_id',2); //obtenemos todos los productos bloqueados
                    $missing_code = $prefix . '-' . str_pad($i, 4, 0, STR_PAD_LEFT);    //generamos un nuevo codigo usando el numero faltante en la secuencia

                    if (count($disabled_products) > 0) {  //validamos si existen productos bloqueados

                        $disabled_products_min_number = $disabled_products->min('number');  //obtenemos el numero mas bajo entre los productos bloqueados
                        $disabled_first_product = $disabled_products->firstWhere('number',$disabled_products_min_number);   //obtenemos el producto bloqueado con el numero mas bajo
                        $disabled_first_product_number = $disabled_first_product->number;   //guardamos el numero del producto bloqueado con el numero mas bajo
                        $disabled_first_product_code = $disabled_first_product->code;   //guardamos el codigo del producto bloqueado con el numero mas bajo

                        if ($new_number == $i) {   //validamos si la secuencia es correcta

                            //actualizamos el numero y codigo del producto bloqueado con el numero mas bajo con los nuevos generados
                            //esto para asignarle su numero y codigo al nuevo producto a crear y la secuencia continue ordenada entre los productos activos

                            $disabled_first_product->Update([
                
                                'number' => $new_number,
                                'code' => $new_code
            
                            ]);
            
                            $product = Product::create([
            
                                'number' => $disabled_first_product_number,
                                'code' => $disabled_first_product_code,
                                'comment' => $this->comment,
                                'status_id' => 1,
                                'brand_id' => $this->brandId,
                                'presentation_subcategory_id' => $this->containerId
            
                            ]);

                        } else {  //si la secuencia no es correcta

                            if ($disabled_products_min_number < $i) { //validamos si el numero mas bajo entre los productos bloqueados es menor al numero faltante en la secuencia

                                //actualizamos el producto bloqueado con el numero mas bajo con el numero y codigo faltantes en la secuencia
                                //esto para asignarle su numero y codigo al nuevo producto a crear y la secuencia continue ordenada entre los productos activos

                                $disabled_first_product->Update([
                    
                                    'number' => $i,
                                    'code' => $missing_code
                
                                ]);
                
                                $product = Product::create([
                
                                    'number' => $disabled_first_product_number,
                                    'code' => $disabled_first_product_code,
                                    'comment' => $this->comment,
                                    'status_id' => 1,
                                    'brand_id' => $this->brandId,
                                    'presentation_subcategory_id' => $this->containerId
                
                                ]);

                            } else {  //caso contrario se creara el producto usando el numero y codigo faltantes en la secuencia

                                $product = Product::create([
                        
                                    'number' => $i,
                                    'code' => $missing_code,
                                    'comment' => $this->comment,
                                    'status_id' => 1,
                                    'brand_id' => $this->brandId,
                                    'presentation_subcategory_id' => $this->containerId
            
                                ]);

                            }

                        }

                    } else {  //si no existen productos bloqueados

                        if ($new_number == $i) {   //validamos si la secuencia es correcta

                            $product = Product::create([
                                
                                'number' => $new_number,    //el valor en el campo number nos servira para identificar los productos con codigo generado ademas de su lugar en la secuencia
                                'code' => $new_code,    //el producto se creara usando el codigo generado
                                'comment' => $this->comment,
                                'status_id' => 1,
                                'brand_id' => $this->brandId,
                                'presentation_subcategory_id' => $this->containerId
        
                            ]);

                        } else {  //caso contrario se creara el producto usando el numero y codigo faltantes en la secuencia

                            $product = Product::create([
                        
                                'number' => $i,
                                'code' => $missing_code,
                                'comment' => $this->comment,
                                'status_id' => 1,
                                'brand_id' => $this->brandId,
                                'presentation_subcategory_id' => $this->containerId
        
                            ]);
                            
                        }

                    }
        
                } else {  //caso contrario creara el primero para iniciar la secuencia
        
                    $product = Product::create([
                        
                        'number' => $new_number,    //el valor en el campo number nos servira para identificar los productos con codigo generado ademas de su lugar en la secuencia
                        'code' => $new_code,    //el producto se creara usando el codigo generado
                        'comment' => $this->comment,
                        'status_id' => 1,
                        'brand_id' => $this->brandId,
                        'presentation_subcategory_id' => $this->containerId
        
                    ]);
        
                }

            } else {  //caso contrario significa que la opcion de codigo "escanear" fue seleccionada

                //creamos un nuevo producto dejando el campo number en null esto nos servira para identificar los productos con codigo escaneado
                $product = Product::create([
                    
                    'code' => $this->code,  //el producto se creara usando el codigo escaneado
                    'comment' => $this->comment,
                    'status_id' => 1,
                    'brand_id' => $this->brandId,
                    'presentation_subcategory_id' => $this->containerId
    
                ]);

            }

            if ($product) {     //validamos si el producto se registro

                if ($this->image) {     //validamos si se selecciono alguna imagen

                    $customFileName = uniqid() . '.' . $this->image->extension();  //generamos un id unico concatenado de la extension de la imagen para nombrar la imagen
                    $this->image->storeAs('public/products', $customFileName);  //almacenamos fisicamente la imagen indicando la ruta y el nombre que tendra
                    $product->image()->create(['url' => $customFileName]);  //creamos registro en la tabla polimorfica images y en el campo url se guarda el nombre de la imagen
                }

                if ($this->GenerateBarcode == 1) {    //validamos si la opcion de codigo de barras fue activada

                    $barcode_image_name = uniqid() . '.jpg';    //generamos un id unico concatenado de extension jpg para nombrar la imagen del codigo de barras
                    //asignamos el nombre de la imagen del codigo de barras al campo barcode_image del producto
                    $product->barcode_image = $barcode_image_name;  //esto nos servira como url para acceder a la imagen
                    $generator = new Picqer\Barcode\BarcodeGeneratorJPG();  //instanciamos un nuevo generador codigo de barras para imagenes
                    //almacenamos fisicamente la imagen indicando la ruta y el nombre que tendra
                    //seguido del generador de codigo de barras que recibe 2 parametros (caracteres a codificar,tipo de codificacion)
                    file_put_contents(('storage/products/barcodes/' . $barcode_image_name), $generator->getBarcode($product->code, $generator::TYPE_CODE_128,3,50));
                    $product->save();   //actualizamos el registro

                }

                /*foreach ($this->productValues as $value_1) {

                    $value_2 = Value::create([

                        'cost' => $value_1['cost'],
                        'price' => $value_1['price'],
                        'product_id' => $product->id,
                        'status_id' => 1
                    ]);

                    $stocks = [];

                    foreach ($this->allOffices as $office) {

                        $stocks[$office->id] = ['alerts' => 1, 'stock' => 0];
                    }

                    $value_2->offices()->attach($stocks);
                }*/
            }

            DB::commit();
            $this->emit('record-added', 'Registrado correctamente');
            $this->mount();

            /*} catch (Exception $e) {
                    
                DB::rollback();
                $this->emit('record-error', 'Error al registrar');

            }*/
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function Edit(Product $product)
    {
        $this->selected_id = $product->id;
        $this->containerId = $product->presentation_subcategory_id;
        $this->brandId = $product->brand_id;
        $this->comment = $product->comment;

        if($product->barcode_image != null){

            $this->GenerateBarcode = 1;

        }else{

            $this->GenerateBarcode = 0;

        }

        $this->aux_1 = $this->GenerateBarcode;

        if($product->number != null){

            $this->CodeOptions = 0;

        }else{

            $this->CodeOptions = 1;

        }

        $this->aux_2 = $this->CodeOptions;
        $this->code = $product->code;
        $this->aux_3 = $product->code;

        //$this->statusId = $product->status_id;
        //$this->productValues = [];
        //$this->aux_1 = $this->allValues->where('product_id', $product->id);

        /*foreach ($this->aux_1 as $value) {

            $this->productValues[] = [

                'id' => $value->id,
                'cost' => number_format($value->cost, 2),
                'price' => number_format($value->price, 2),
                'is_saved' => true
            ];
        }*/

        $this->emit('show-modal', 'Abrir Modal');
    }

    public function Update()
    {   
        $rules = [

            'containerId' => 'not_in:elegir',
            'brandId' => 'not_in:elegir',
            'comment' => 'max:45',
            'image' => 'exclude_if:image,null|mimes:jpg,png',
            'GenerateBarcode' => 'not_in:elegir',
            'CodeOptions' => 'not_in:elegir',
            'code' => "required_if:CodeOptions,1|max:45|unique:products,code,{$this->selected_id}",
        ];

        $messages = [

            'containerId.not_in' => 'Seleccione una opcion',
            'brandId.not_in' => 'Seleccione una opcion',
            'comment.max' => 'Maximo 45 caracteres',
            'image.mimes' => 'Solo formatos jpg o png',
            'GenerateBarcode.not_in' => 'Seleccione una opcion',
            'CodeOptions.not_in' => 'Seleccione una opcion',
            'code.required_if' => 'Campo requerido',
            'code.max' => 'Maximo 45 caracteres',
            'code.unique' => 'Ya existe',
        ];

        $this->validate($rules, $messages);

        //$this->aux_2 = collect($this->productValues);

        DB::beginTransaction();

        try {

            $product = $this->allProducts->find($this->selected_id);    //obtenemos el producto que se esta editando

            //validamos que la opcion "codigo de barras", la opcion "opciones de codigo" y el codigo del producto no fueron modificados
            //esto significa que se actualizaran los campos irrelevantes (contenedor,marca y comentarios) que no requieren de validaciones extras
            if ( ($this->GenerateBarcode == $this->aux_1) && ($this->CodeOptions == $this->aux_2) && ($this->code == $this->aux_3) ) {

                $product->Update([

                    'presentation_subcategory_id' => $this->containerId,
                    'brand_id' => $this->brandId,
                    'comment' => $this->comment
                    
                ]);

            } else {    //caso contrario tenemos certeza de que algun campo importante fue modificado por lo que vamos a verificar multiples factores antes de actualizar el registro

                $previus_barcode_image = $product->barcode_image;   //guardamos la url de la imagen del codigo de barras del producto
                $new_barcode_image = uniqid() . '.jpg';     //generamos un nuevo nombre para asignar a alguna nueva imagen de codigo de barras
                $generator = new Picqer\Barcode\BarcodeGeneratorJPG();  //isntanciamos un nuevo generador de imagenes de codigo de barras

                //en este caso podemos omitir validaciones relacionadas al codigo del producto en si ya que fue generado
                //por lo que el usuario no podra manipular el codigo actual del producto a menos que modifique la opcion "opciones de codigo"
                if ($product->number != null) { //validamos si el producto tiene un codigo generado

                    if ($product->barcode_image != null) {  //validamos si el producto tiene una imagen de codigo de barras
                        
                        //validamos si ambas opciones "codigo de barras" y "opciones de codigo" fueron modificadas
                        //en este caso se actualizara el producto con las opciones opuestas a lo que tenia originalmente
                        if ( ($this->GenerateBarcode != $this->aux_1) && ($this->CodeOptions != $this->aux_2) ) {
                            
                            //el campo "number" sera actualizado a null para identificar que el codigo del producto es ahora escaneado
                            //el campo "code" sera actualizado con el codigo escaneado
                            //el campo "barcode_image" sera actualizado a null para identificar que el producto ya no cuenta con imagen de codigo de barras
                            $product->Update([
                                    
                                'number' => null,
                                'code' => $this->code,
                                'barcode_image' => null,
                                'comment' => $this->comment,
                                'brand_id' => $this->brandId,
                                'presentation_subcategory_id' => $this->containerId
                                
                            ]);
    
                        } else {    //caso contrario nos indica que solo una de ambas opciones fue modificada
    
                            if ( ($this->GenerateBarcode != $this->aux_1) ) {   //validamos si la opcion "codigo de barras" fue modificada
                                
                                //el campo "barcode_image" sera actualizado a null para identificar que el producto ya no cuenta con imagen de codigo de barras
                                $product->Update([
                                    
                                    'barcode_image' => null,
                                    'comment' => $this->comment,
                                    'brand_id' => $this->brandId,
                                    'presentation_subcategory_id' => $this->containerId
                                    
                                ]);
    
                            } else {    //caso contrario tenemos la certeza de que la opcion "opciones de codigo" fue modificada
                                
                                //el campo "number" sera actualizado a null para identificar que el codigo del producto es ahora escaneado
                                //el campo "code" sera actualizado con el codigo escaneado
                                //el campo "barcode_image" sera actualizado con el nombre de la nueva imagen de codigo de barras
                                //ya que al cambiar el codigo del producto su imagen de codigo de barras tambien debe cambiar
                                $product->Update([
                                    
                                    'number' => null,
                                    'code' => $this->code,
                                    'barcode_image' => $new_barcode_image,
                                    'comment' => $this->comment,
                                    'brand_id' => $this->brandId,
                                    'presentation_subcategory_id' => $this->containerId
                                    
                                ]);
                                
                                //almacenamos fisicamente la imagen indicando la ruta y el nombre que tendra
                                //seguido del generador de codigo de barras que recibe 2 parametros (caracteres a codificar,tipo de codificacion)
                                file_put_contents(('storage/products/barcodes/' . $new_barcode_image), $generator->getBarcode($this->code, $generator::TYPE_CODE_128,3,50));
    
                            }
    
                        }
                        
                        //dado que la imagen de codigo de barras del producto ha sido modificada independientemente de la condicional
                        //validamos si la imagen de codigo de barras que tenia el producto antes de su modificacion existe fisicamente
                        if (file_exists($previus_barcode_image)) {
    
                            unlink($previus_barcode_image); //eliminamos la imagen definitivamente
    
                        }
    
                    } else {    //si el producto tiene no una imagen de codigo de barras

                        //validamos si la opcion "opciones de codigo" es la unica modificada
                        if ( ($this->GenerateBarcode == $this->aux_1) && ($this->CodeOptions != $this->aux_2) ) {

                            //el campo "number" sera actualizado a null para identificar que el codigo del producto es ahora escaneado
                            //el campo "code" sera actualizado con el codigo escaneado
                            $product->Update([
                                        
                                'number' => null,
                                'code' => $this->code,
                                'comment' => $this->comment,
                                'brand_id' => $this->brandId,
                                'presentation_subcategory_id' => $this->containerId
                                
                            ]);

                        } else {    //caso contrario continuamos con las validaciones

                            //en este caso se creara una imagen de codigo de barras al producto independientemente de la condicional
                            //aunque los caracteres a codificar seran distintos dependiendo de la validacion
                            //por lo que vamos a guardar los caracteres a codificar en la variable $new_code

                            //validamos si ambas opciones "codigo de barras" y "opciones de codigo" fueron modificadas
                            if ( ($this->GenerateBarcode != $this->aux_1) && ($this->CodeOptions != $this->aux_2) ) {

                                $new_code = $this->code;    //en este caso el codigo escaneado sera usado para la imagen de codigo de barras

                                //el campo "number" sera actualizado a null para identificar que el codigo del producto es ahora escaneado
                                //el campo "code" sera actualizado con el codigo escaneado
                                //el campo "barcode_image" sera actualizado con el nombre de la nueva imagen de codigo de barras
                                //ya que al cambiar el codigo del producto su imagen de codigo de barras tambien debe cambiar
                                $product->Update([
                                        
                                    'number' => null,
                                    'code' => $this->code,
                                    'barcode_image' => $new_barcode_image,
                                    'comment' => $this->comment,
                                    'brand_id' => $this->brandId,
                                    'presentation_subcategory_id' => $this->containerId
                                    
                                ]);

                            } else {    //caso contrario tenemos la certeza de que la opcion "codigo de barras" fue la unica modifica

                                $new_code = $product->code; //en este caso el mismo codigo del producto sera usado para la imagen de codigo de barras

                                //el campo "barcode_image" sera actualizado con el nombre de la nueva imagen de codigo de barras
                                $product->Update([
                                    
                                    'barcode_image' => $new_barcode_image,
                                    'comment' => $this->comment,
                                    'brand_id' => $this->brandId,
                                    'presentation_subcategory_id' => $this->containerId
                                    
                                ]);

                            }

                            //almacenamos fisicamente la imagen indicando la ruta y el nombre que tendra
                            //seguido del generador de codigo de barras que recibe 2 parametros (caracteres a codificar,tipo de codificacion)
                            file_put_contents(('storage/products/barcodes/' . $new_barcode_image), $generator->getBarcode($new_code, $generator::TYPE_CODE_128,3,50));

                        }

                    }
    
                } else {    //caso contrario significa que el producto tiene un codigo escaneado

                    //obtenemos todos los productos que tengan un numero asignado lo que significa que su codigo fue generado automaticamente
                    $products = $this->allProducts->whereNotNull('number');
                    $new_number = $products->max('number') + 1; //generamos un nuevo numero para usar en un nuevo codigo de producto e iniciar/continuar con la secuencia
                    $prefix = 'prod';   //asignamos un prefijo para usar en un nuevo codigo de producto
                    $new_code = $prefix . '-' . str_pad($new_number, 4, 0, STR_PAD_LEFT);   //generamos un nuevo codigo de producto que se usara dependiendo de la situacion

                    if ($product->barcode_image != null) {  //validamos si el producto tiene una imagen de codigo de barras

                        //validamos si las opciones "codigo de barras" y "opciones de codigo" no han sido modificadas pero el codigo del producto si
                        if ( ($this->GenerateBarcode == $this->aux_1) && ($this->CodeOptions == $this->aux_2) && ($this->code != $this->aux_3) ) {

                            //en este caso se entiende que se ha escaneado un nuevo codigo para el producto
                            //el campo "code" sera actualizado con el nuevo codigo escaneado
                            //el campo "barcode_image" sera actualizado con el nombre de la nueva imagen de codigo de barras
                            //ya que al cambiar el codigo del producto su imagen de codigo de barras tambien debe cambiar
                            $product->Update([
                                
                                'code' => $this->code,
                                'barcode_image' => $new_barcode_image,
                                'comment' => $this->comment,
                                'brand_id' => $this->brandId,
                                'presentation_subcategory_id' => $this->containerId
                                
                            ]);

                            //almacenamos fisicamente la imagen indicando la ruta y el nombre que tendra
                            //seguido del generador de codigo de barras que recibe 2 parametros (caracteres a codificar,tipo de codificacion)
                            file_put_contents(('storage/products/barcodes/' . $new_barcode_image), $generator->getBarcode($this->code, $generator::TYPE_CODE_128,3,50));

                        } else {    //caso contrario continuamos con las validaciones

                            //validamos si ambas opciones "codigo de barras" y "opciones de codigo" fueron modificadas
                            //en este caso se actualizara el producto con las opciones opuestas a lo que tenia originalmente
                            if ( ($this->GenerateBarcode != $this->aux_1) && ($this->CodeOptions != $this->aux_2) ) {

                                //se actualizara el campo "number" del producto con el nuevo numero generado
                                //se actualizara el campo "code" del producto con el nuevo codigo generado
                                //se actualizara el campo "barcode_image" del producto a null lo que identificara que no tiene una imagen de codigo de barras
                                $product->Update([
                                    
                                    'number' => $new_number,
                                    'code' => $new_code,
                                    'barcode_image' => null,
                                    'comment' => $this->comment,
                                    'brand_id' => $this->brandId,
                                    'presentation_subcategory_id' => $this->containerId
                                    
                                ]);

                            } else {    //caso contrario entendemos que solo una de ambas opciones ha sido modificada

                                if ( ($this->CodeOptions != $this->aux_2) ) {   //validamos si solo la opcion "opciones de codigo" ha sido modificada

                                    //se actualizara el campo "number" del producto con el nuevo numero generado
                                    //se actualizara el campo "code" del producto con el nuevo codigo generado
                                    //se actualizara el campo "barcode_image" del producto con el nombre de la nueva imagen de codigo de barras
                                    $product->Update([
                                    
                                        'number' => $new_number,
                                        'code' => $new_code,
                                        'barcode_image' => $new_barcode_image,
                                        'comment' => $this->comment,
                                        'brand_id' => $this->brandId,
                                        'presentation_subcategory_id' => $this->containerId
                                        
                                    ]);

                                    //almacenamos fisicamente la imagen indicando la ruta y el nombre que tendra
                                    //seguido del generador de codigo de barras que recibe 2 parametros (caracteres a codificar,tipo de codificacion)
                                    file_put_contents(('storage/products/barcodes/' . $new_barcode_image), $generator->getBarcode($new_code, $generator::TYPE_CODE_128,3,50));

                                } else {    //caso contrario entendemos que la opcion "codigo de barras" ha sido modificada

                                    if ($this->code == $this->aux_3) {  //validamos si el codigo escaneado del producto no ha sido modificado

                                        //se actualizara el campo "barcode_image" del producto a null lo que identificara que no tiene una imagen de codigo de barras
                                        $product->Update([
                                            
                                            'barcode_image' => null,
                                            'comment' => $this->comment,
                                            'brand_id' => $this->brandId,
                                            'presentation_subcategory_id' => $this->containerId
                                            
                                        ]);

                                    } else {    //caso contrario entendemos que se ha escaneado un nuevo codigo para el producto

                                        //se actualizara el campo "code" del producto con el nuevo codigo escaneado
                                        //se actualizara el campo "barcode_image" del producto a null lo que identificara que no tiene una imagen de codigo de barras
                                        $product->Update([
                                            
                                            'code' => $this->code,
                                            'barcode_image' => null,
                                            'comment' => $this->comment,
                                            'brand_id' => $this->brandId,
                                            'presentation_subcategory_id' => $this->containerId
                                            
                                        ]);

                                    }

                                }

                            }

                        }

                        //dado que la imagen de codigo de barras del producto ha sido modificada independientemente de la condicional
                        //validamos si la imagen de codigo de barras que tenia el producto antes de su modificacion existe fisicamente
                        if (file_exists($previus_barcode_image)) {
    
                            unlink($previus_barcode_image); //eliminamos la imagen definitivamente
    
                        }

                    } else {    //si el producto no tiene una imagen de codigo de barras

                        //validamos si las opciones "codigo de barras" y "opciones de codigo" no han sido modificadas pero el codigo del producto si
                        if ( ($this->GenerateBarcode == $this->aux_1) && ($this->CodeOptions == $this->aux_2) && ($this->code != $this->aux_3) ) {

                            //en este caso se entiende que se ha escaneado un nuevo codigo para el producto
                            //el campo "code" sera actualizado con el nuevo codigo escaneado
                            $product->Update([
                                
                                'code' => $this->code,
                                'comment' => $this->comment,
                                'brand_id' => $this->brandId,
                                'presentation_subcategory_id' => $this->containerId
                                
                            ]);

                        } else {    //caso contrario continuamos con las validaciones

                            //validamos si la opcion "codigo de barras" no ha sido modificada pero la opcion "opciones de codigo" si
                            if ( ($this->GenerateBarcode == $this->aux_1) && ($this->CodeOptions != $this->aux_2) ) {

                                //se actualizara el campo "number" del producto con el nuevo numero generado
                                //se actualizara el campo "code" del producto con el nuevo codigo generado
                                $product->Update([
                                    
                                    'number' => $new_number,
                                    'code' => $new_code,
                                    'comment' => $this->comment,
                                    'brand_id' => $this->brandId,
                                    'presentation_subcategory_id' => $this->containerId
                                    
                                ]);

                            } else {    //caso contrario continuamos con las validaciones

                                //en este caso se creara una imagen de codigo de barras al producto independientemente de la condicional
                                //aunque los caracteres a codificar seran distintos dependiendo de la validacion
                                //por lo que vamos a guardar los caracteres a codificar en la variable $updated_code

                                //validamos si ambas opciones "codigo de barras" y "opciones de codigo" han sido modificadas
                                if ( ($this->GenerateBarcode != $this->aux_1) && ($this->CodeOptions != $this->aux_2) ) {

                                    $updated_code = $new_code;  //en este caso el nuevo codigo generado sera usado en la imagen de codigo de barras 

                                    //se actualiza el campo "number" del producto con el nuevo numero generado
                                    //se actualiza el campo "code" del producto con el nuevo codigo generado
                                    //se actualiza el campo "barcode_image" del producto con el nombre de la nueva imagen de codigo de barras
                                    $product->Update([
                                    
                                        'number' => $new_number,
                                        'code' => $new_code,
                                        'barcode_image' => $new_barcode_image,
                                        'comment' => $this->comment,
                                        'brand_id' => $this->brandId,
                                        'presentation_subcategory_id' => $this->containerId
                                        
                                    ]);

                                } else {    //caso contrario entendemos que solo la opcion "codigo de barras" ha sido modificada

                                    if ($this->code == $this->aux_3) {  //validamos si el codigo escaneado del producto no ha sido modificado

                                        $updated_code = $product->code; //en este caso el codigo del producto sera usado en la imagen de codigo de barras 

                                        //se actualiza el campo "barcode_image" del producto con el nombre de la nueva imagen de codigo de barras
                                        $product->Update([
                                            
                                            'barcode_image' => $new_barcode_image,
                                            'comment' => $this->comment,
                                            'brand_id' => $this->brandId,
                                            'presentation_subcategory_id' => $this->containerId
                                            
                                        ]);

                                    } else {    //caso contrario entendemos que se ha escaneado un nuevo codigo para el producto

                                        $updated_code = $this->code;    //en este caso el nuevo codigo escaneado sera usado en la imagen de codigo de barras 

                                        //se actualiza el campo "code" del producto con el nuevo codigo escaneado
                                        //se actualiza el campo "barcode_image" del producto con el nombre de la nueva imagen de codigo de barras
                                        $product->Update([
                                            
                                            'code' => $this->code,
                                            'barcode_image' => $new_barcode_image,
                                            'comment' => $this->comment,
                                            'brand_id' => $this->brandId,
                                            'presentation_subcategory_id' => $this->containerId
                                            
                                        ]);

                                    }

                                }

                                //almacenamos fisicamente la imagen indicando la ruta y el nombre que tendra
                                //seguido del generador de codigo de barras que recibe 2 parametros (caracteres a codificar,tipo de codificacion)
                                file_put_contents(('storage/products/barcodes/' . $new_barcode_image), $generator->getBarcode($updated_code, $generator::TYPE_CODE_128,3,50));

                            }

                        }

                    }

                }

            }

            if ($this->image) { //validamos si se selecciono alguna imagen

                $customFileName = uniqid() . '.' . $this->image->extension();   //generamos un id unico concatenado de la extension de la imagen para nombrarla
    
                if ($product->image != null) {  //validamos si existe un registro para este producto en la tabla polimorfica images
    
                    $imageTemp = $product->image->url;  //guardamos en variable el nombre de la imagen del producto antes de actualizarlo
                    $product->image()->update(['url' => $customFileName]);  //actualizamos registro en la tabla polimorfica images con el nombre de la nueva imagen
    
                    if ($imageTemp != null) {   //validamos si la variable capturo el nombre de la antigua imagen del producto
    
                        if (file_exists('storage/products/' . $imageTemp)) {    //validamos si la imagen existe fisicamente
    
                            unlink('storage/products/' . $imageTemp);   //eliminamos la imagen definitivamente
                        }
                    }
    
                } else {    //si no existe un registro para este producto en la tabla polimorfica images
    
                    $product->image()->create(['url' => $customFileName]);  //creamos registro en la tabla polimorfica images y en el campo url se guarda el nombre de la imagen
                }

                $this->image->storeAs('public/products', $customFileName);  //almacenamos fisicamente la imagen indicando la ruta y el nombre que tendra
            }

            /*$container = $this->allContainers_2->find($this->containerId);

            if ($product->presentation_subcategory_id == $this->containerId) {

                $product->Update([

                    'status_id' => $this->statusId,
                    'brand_id' => $this->brandId,
                    'comment' => $this->comment
                ]);

            } else {

                $products = $this->allProducts->where('presentation_subcategory_id', $product->presentation_subcategory_id);
                $previus_max_number = $products->max('number');
                $previus_number = $product->number;
                $previus_code = $product->code;
                $number = $this->allProducts->where('presentation_subcategory_id', $this->containerId)->max('number') + 1;
                $code = $container->prefix . '-' . str_pad($number, 4, 0, STR_PAD_LEFT);

                $product->Update([

                    'number' => $number,
                    'code' => $code,
                    'status_id' => $this->statusId,
                    'presentation_subcategory_id' => $this->containerId,
                    'brand_id' => $this->brandId,
                    'comment' => $this->comment
                ]);


                if (count($products) > 1) {

                    if ($previus_number != $previus_max_number) {

                        $last_product = $products->firstWhere('number', $previus_max_number);

                        $last_product->Update([

                            'number' => $previus_number,
                            'code' => $previus_code

                        ]);
                    }
                }
            }

            if ($this->statusId == 1 && $container->status_id == 2) {

                $container->Update([

                    'status_id' => 1
                ]);
            }

            foreach ($this->aux_2 as $actual) {

                if ($actual['id']) {

                    $value = $this->aux_1->find($actual['id']);

                    $value->Update([

                        'cost' => $actual['cost'],
                        'price' => $actual['price']

                    ]);
                } else {

                    $value = Value::create([

                        'cost' => $actual['cost'],
                        'price' => $actual['price'],
                        'product_id' => $product->id,
                        'status_id' => 1
                    ]);

                    $stocks = [];

                    foreach ($this->allOffices as $office) {

                        $stocks[$office->id] = ['alerts' => 1, 'stock' => 0];
                    }

                    $value->offices()->attach($stocks);
                }
            }

            foreach ($this->aux_1 as $previus) {

                if ($this->aux_2->doesntContain('id', $previus->id)) {

                    $previus->Update([

                        'status_id' => 2

                    ]);
                }
            }*/

            DB::commit();
            $this->emit('record-updated', 'Registro Actualizado');
            $this->mount();

        /*} catch (Exception $e) {
                    
            DB::rollback();
            $this->emit('record-error', 'Error al registrar');

        }*/

        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }

    }

    protected $listeners = [

        'destroy' => 'Destroy'
    ];

    public function Destroy(Product $product)
    {   
        
        DB::beginTransaction();

        try {

            /*if($product->number != null){

                $products = $this->allProducts->where('presentation_subcategory_id',$product->presentation_subcategory_id)->whereNotNull('number');

                if (count($products) > 1) {

                    $products_max_number = $products->max('number');
                    $last_product = $products->firstWhere('number', $products_max_number);

                    if ($product->id != $last_product->id) {

                        $previus_number = $product->number;
                        $previus_code = $product->code;
                        $last_product_number = $last_product->number;
                        $last_product_code = $last_product->code;
                        $new_number = $products->max('number') + 1;
                        $new_code = $product->container->prefix . '-' . str_pad($new_number, 4, 0, STR_PAD_LEFT);

                        if($last_product->status_id != 1){

                            $active_products = $products->where('status_id',1);
                            $active_products_max_number = $active_products->max('number');
                            $last_active_product = $active_products->firstWhere('number',$active_products_max_number);
                            $last_active_product_number = $last_active_product->number;
                            $last_active_product_code = $last_active_product->code;

                            if($product->id == $last_active_product->id){

                                $product->Update([

                                    'number' => $new_number,
                                    'code' => $new_code
        
                                ]);
        
                                $last_product->Update([
        
                                    'number' => $previus_number,
                                    'code' => $previus_code
        
                                ]);
        
                                $product->Update([
        
                                    'number' => $last_product_number,
                                    'code' => $last_product_code,
                                    'status_id' => 2
        
                                ]);

                            }else{

                                $product->Update([

                                'number' => $new_number,
                                'code' => $new_code

                                ]);

                                $last_active_product->Update([

                                    'number' => $previus_number,
                                    'code' => $previus_code

                                ]);

                                $last_product->Update([

                                    'number' => $last_active_product_number,
                                    'code' => $last_active_product_code

                                ]);

                                $product->Update([

                                    'number' => $last_product_number,
                                    'code' => $last_product_code,
                                    'status_id' => 2

                                ]);

                            }

                        }else{

                            $product->Update([

                                'number' => $new_number,
                                'code' => $new_code

                            ]);

                            $last_product->Update([

                                'number' => $previus_number,
                                'code' => $previus_code

                            ]);

                            $product->Update([

                                'number' => $last_product_number,
                                'code' => $last_product_code,
                                'status_id' => 2

                            ]);

                        }

                    }else{

                        $product->Update([

                            'status_id' => 2
        
                        ]);

                    }

                }else{

                    $product->Update([

                        'status_id' => 2

                    ]);

                }

            }else{

                $product->Update([

                    'status_id' => 2

                ]);

            }*/

            $product->Update([

                'status_id' => 2

            ]);

            if($product->barcode_image != null){

                $barcode_image_url = $product->barcode_image;
                $product->barcode_image = null;

                if (file_exists($barcode_image_url)) {

                    unlink($barcode_image_url);
                }

                $product->save();

            }

            if ($product->image != null) {

                $imageTemp = $product->image->url;
                $product->image()->delete();

                if (file_exists('storage/products/' . $imageTemp)) {

                    unlink('storage/products/' . $imageTemp);
                }

            }

            DB::commit();
            $this->emit('record-deleted', 'Registro eliminado');
            $this->mount();

        } catch (Exception $e) {
                    
            DB::rollback();
            $this->emit('record-error', 'Error al registrar');
        }

    }

    public function resetUI()
    {
        $this->mount();
    }
}
