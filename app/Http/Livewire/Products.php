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
use Illuminate\Support\Facades\DB;

class Products extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search, $search_2, $selected_id, $pageTitle, $componentName, $comment, $value, $image;
    public $brandId, $statusId, $containerId, $brand_name, $status_name;
    public $allBrands, $allStatuses, $allContainers, $allValues, $allOffices, $allProducts, $allAccounts, $allBanks,$allCompanies;
    public $allContainers_2, $allSuppliers, $allStatuses_2, $allCustomers;
    public $my_total, $stock_details, $payment_type, $tax, $aux_1, $aux_2,$modal_id,$modal_id_2;
    public $product_id, $office_id_1, $office_id_2, $value_id, $cant_1, $cant_2, $accountId, $bankId,$companyId;
    public $supplierId, $customerId, $name,$alias, $phone, $fax, $email, $nit, $city, $country, $number,$address,$type,$currency,$balance,$entity_code;
    private $pagination = 30;
    public $productValues = [];

    public function paginationView()
    {

        return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {

        $this->resetValidation();
        $this->resetPage();
        $this->pageTitle = 'listado';
        $this->componentName = 'productos';
        $this->selected_id = 0;
        $this->search = '';
        $this->search_2 = 0;
        $this->comment = '';
        $this->image = null;
        $this->value = 'Elegir';
        $this->statusId = 'Elegir';
        $this->brandId = 'Elegir';
        $this->containerId = 'Elegir';
        $this->supplierId = 'Elegir';
        $this->customerId = 'Elegir';
        $this->accountId = 'Elegir';
        $this->bankId = 'Elegir';
        $this->companyId = 'Elegir';
        $this->payment_type = 'Elegir';
        $this->tax = 'Elegir';
        $this->type = 'Elegir';
        $this->currency = 'Elegir';
        $this->status_name = '';
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
        $this->allBrands = Brand::select('id', 'name')->get();
        $this->allContainers = PresentationSubcategory::where('status_id', 1)->with('presentation', 'subcategory.category')->get();
        $this->allContainers_2 = PresentationSubcategory::with('presentation', 'subcategory.category')->get();
        $this->allProducts = Product::all();
        $this->allAccounts = BankAccount::where('status_id', 1)->with(['company', 'bank'])->get();
        $this->allBanks = Bank::select('id', 'alias')->get();
        $this->allCompanies = Company::select('id', 'alias')->get();
        $this->allSuppliers = Supplier::select('id', 'name')->get();
        $this->allCustomers = Customer::select('id', 'name')->get();
        $this->productValues = [
            ['id' => '', 'cost' => '', 'price' => '', 'is_saved' => false]
        ];
    }

    public function render()
    {

        switch ($this->search_2) {

            case 0:

                if (strlen($this->search) > 0) {

                    $data = Product::with([
                        'activeValues.offices',
                        'activeStocks',
                        'brand',
                        'container.subcategory.category',
                        'container.presentation'
                    ])
                        ->where('status_id', 1)
                        ->whereHas('container', function ($query) {
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
                        })
                        ->orderBy('code', 'asc')
                        ->paginate($this->pagination);
                } else {

                    $data = Product::where('status_id', 1)
                        ->with([
                            'activeValues.offices',
                            'activeStocks',
                            'brand',
                            'container.subcategory.category',
                            'container.presentation'
                        ])
                        ->orderBy('code', 'asc')
                        ->paginate($this->pagination);
                }

                break;

            case 1:

                if (strlen($this->search) > 2) {

                    $data = Product::with([
                        'activeValues.offices',
                        'activeStocks',
                        'brand',
                        'container.subcategory.category',
                        'container.presentation'
                    ])
                        ->where('status_id', 2)
                        ->whereHas('container', function ($query) {
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
                        })
                        ->orderBy('code', 'asc')
                        ->paginate($this->pagination);
                } else {

                    $data = Product::where('status_id', 2)
                        ->with([
                            'activeValues.offices',
                            'activeStocks',
                            'brand',
                            'container.subcategory.category',
                            'container.presentation'
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

    public function updatedsearch()
    {
        $this->value = 'Elegir';
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
        $this->office_id_1 = $stock->office_id;
        $this->value_id = $stock->value_id;
        $this->statusId = 'Elegir';
        $this->customerId = 'Elegir';
        $this->payment_type = 'Elegir';
        $this->tax = 'Elegir';
        $this->accountId = 'Elegir';
        $this->bankId = 'Elegir';
        $this->cant_1 = $stock->stock;
        $this->cant_2 = '';
        $this->aux_1 = number_format($stock->value->cost, 2);
        $this->aux_2 = '';
        $this->number = '';
        $this->modal_id_2 = 1;
        $this->emit('show-sale-modal', 'Abrir Modal');
    }

    public function CloseSaleModal()
    {

        $this->resetValidation($this->cant_2 = null);
        $this->modal_id_2 = 0;
        $this->Stock_Detail($this->product_id);
    }

    public function Sale()
    {

        $rules = [

            'customerId' => 'not_in:Elegir',
            'payment_type' => 'not_in:Elegir',
            'statusId' => 'not_in:Elegir',
            'tax' => 'not_in:Elegir',
            'cant_2' => "required|lte:$this->cant_1|integer|gt:0",
            'aux_2' => 'required|numeric',
            'accountId' => 'exclude_unless:payment_type,deposito|not_in:Elegir',
            'bankId' => 'exclude_unless:payment_type,cheque|not_in:Elegir',
            'number' => 'exclude_unless:payment_type,cheque|required|digits_between:6,15',
        ];

        $messages = [

            'customerId.not_in' => 'Seleccione una opcion',
            'payment_type.not_in' => 'Seleccione una opcion',
            'statusId.not_in' => 'Seleccione una opcion',
            'tax.not_in' => 'Seleccione una opcion',
            'cant_2.required' => 'Campo requerido',
            'cant_2.lte' => 'La cantidad es mayor al stock',
            'cant_2.integer' => 'Solo numeros enteros',
            'cant_2.gt' => 'Solo numeros mayores a 0',
            'aux_2.required' => 'Campo Requerido',
            'aux_2.numeric' => 'Solo Numeros',
            'accountId.not_in' => 'Seleccione una opcion',
            'bankId.not_in' => 'Seleccione una opcion',
            'number.required' => 'Campo requerido',
            'number.digits_between' => 'Solo numeros enteros positivos, de 6 a 15 digitos',
        ];

        $this->validate($rules, $messages);

        DB::beginTransaction();

        try {

            $value = Value::find($this->value_id);
            $now = Carbon::parse(Carbon::now())->format('d-m-Y');

            if ($this->tax == 1) {

                $sale = Sale::create([

                    'quantity' => $this->cant_2,
                    'sale_price' => $this->aux_2,
                    'total_cost' => $value->cost * $this->cant_2,
                    'total_price' => ($this->aux_2 * $this->cant_2) + (($value->cost * $this->cant_2) * 0.13),
                    'utility' => ($this->aux_2 * $this->cant_2) - ($value->cost * $this->cant_2),
                    'payment_type' => $this->payment_type,
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
                        number_format($sale->sale_price, 2) . ' ' .
                        'por unidad' . ' ' .
                        'en fecha' . ' ' .
                        $now,
                    'amount' => ($value->cost * $sale->quantity) * 0.13,
                    'status_id' => 1
                ]);
            } else {

                $sale = Sale::create([

                    'quantity' => $this->cant_2,
                    'sale_price' => $this->aux_2,
                    'total_cost' => $value->cost * $this->cant_2,
                    'total_price' => $this->aux_2 * $this->cant_2,
                    'utility' => ($this->aux_2 * $this->cant_2) - ($value->cost * $this->cant_2),
                    'payment_type' => $this->payment_type,
                    'status_id' => $this->statusId,
                    'user_id' => Auth()->user()->id,
                    'customer_id' => $this->customerId,
                    'office_value_id' => $this->selected_id

                ]);
            }


            if ($sale) {

                switch ($this->payment_type) {

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
                                'status_id' => $sale->status_id,
                                'sale_id' => $sale->id,
                                'bank_id' => $this->bankId,
                                'customer_id' => $sale->customer_id
    
                            ]);

                        }

                    break;

                }

                $value->offices()->updateExistingPivot($this->office_id_1, [

                    'stock' => $this->cant_1 - $this->cant_2

                ]);
            }

            DB::commit();
            $this->emit('item-saled', 'Venta Exitosa');
            $this->value = 'Elegir';
            $this->modal_id_2 = 0;
            $this->Stock_Detail($this->product_id);
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }
    }

    public function ShowIncomeModal(OfficeValue $stock)
    {

        $this->selected_id = $stock->id;
        $this->product_id = $stock->value->product_id;
        $this->office_id_1 = $stock->office_id;
        $this->value_id = $stock->value_id;
        $this->statusId = 'Elegir';
        $this->supplierId = 'Elegir';
        $this->payment_type = 'Elegir';
        $this->tax = 'Elegir';
        $this->accountId = 'Elegir';
        $this->cant_1 = $stock->stock;
        $this->cant_2 = '';
        $this->emit('show-income-modal', 'Abrir Modal');
    }

    public function CloseIncomeModal()
    {

        $this->resetValidation($this->cant_2 = null);
        $this->Stock_Detail($this->product_id);
    }

    public function Income()
    {

        $rules = [

            'supplierId' => 'not_in:Elegir',
            'payment_type' => 'not_in:Elegir',
            'statusId' => 'not_in:Elegir',
            'tax' => 'not_in:Elegir',
            'cant_2' => "required|integer|gt:0",
        ];

        $messages = [

            'supplierId.not_in' => 'Seleccione una opcion',
            'payment_type.not_in' => 'Seleccione una opcion',
            'statusId.not_in' => 'Seleccione una opcion',
            'tax.not_in' => 'Seleccione una opcion',
            'cant_2.required' => 'Campo requerido',
            'cant_2.integer' => 'Solo numeros enteros',
            'cant_2.gt' => 'Solo numeros mayores a 0',
        ];

        $this->validate($rules, $messages);

        DB::beginTransaction();

        try {

            $value = Value::find($this->value_id);
            $now = Carbon::parse(Carbon::now())->format('d-m-Y');


            $income = Income::create([

                'income_type' => 'compra',
                'payment_type' => $this->payment_type,
                'previus_stock' => $this->cant_1,
                'quantity' => $this->cant_2,
                'total' => $value->cost * $this->cant_2,
                'status_id' => $this->statusId,
                'user_id' => Auth()->user()->id,
                'supplier_id' => $this->supplierId,
                'office_value_id' => $this->selected_id

            ]);


            if ($income) {

                if ($this->tax == 1) {

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
                            'en fecha' . ' ' .
                            $now,
                        'amount' => ($value->cost * $income->quantity) * 0.13,
                        'status_id' => 1
                    ]);
                }

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

                $value->offices()->updateExistingPivot($this->office_id_1, [

                    'stock' => $this->cant_1 + $this->cant_2

                ]);
            }

            DB::commit();
            $this->emit('item-entered', 'Ingreso Exitoso');
            $this->value = 'Elegir';
            $this->Stock_Detail($this->product_id);
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

    public function Store()
    {

        $rules = [

            'containerId' => 'not_in:Elegir',
            'brandId' => 'not_in:Elegir',
            'comment' => 'max:100',
            'productValues.*.cost' => 'required|numeric',
            'productValues.*.price' => 'required|numeric',
        ];

        $messages = [

            'containerId.not_in' => 'Seleccione una opcion',
            'brandId.not_in' => 'Seleccione una opcion',
            'comment.max' => 'Maximo 100 caracteres',
            'productValues.*.cost.required' => 'Campo requerido',
            'productValues.*.cost.numeric' => 'Solo numeros',
            'productValues.*.price.required' => 'Campo requerido',
            'productValues.*.price.numeric' => 'Solo numeros',
        ];

        $this->validate($rules, $messages);

        DB::beginTransaction();

        try {

            $product = Product::create([

                'status_id' => 1,
                'presentation_subcategory_id' => $this->containerId,
                'brand_id' => $this->brandId,
                'comment' => $this->comment
            ]);


            if ($product) {

                /*if($this->image){   //validar si se selecciono una imagen para el registro
                    //metodo de php uniqid() para asignar id automatico y unico
                    $customFileName = uniqid() . '_.' . $this->image->extension();  //guardar en variable el id concatenado de _.extension del archivo seleccionado
                    //metodo storeAs solicita 2 parametros (directorio para almacenar archivo, nombre del archivo)
                    $this->image->storeAs('public/products', $customFileName);  //almacenar informacion
                    $product->image = $customFileName;  //actualizar la columna image del registro anteriormente guardado en variable
                    $product->save();   //volvemos a guardar el registro con la informacion actualizada
                    }*/

                foreach ($this->productValues as $value_1) {

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
                }
            }

            DB::commit();
            $this->emit('item-added', 'Registrado correctamente');
            $this->mount();

            /*} catch (Exception) {
                    
                DB::rollback();
                $this->emit('record-error', 'Error. Cancelando registro');
            }*/
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

    public function Edit(Product $product)
    {
        $this->productValues = [];
        $this->selected_id = $product->id;
        $this->statusId = $product->status_id;
        $this->containerId = $product->presentation_subcategory_id;
        $this->brandId = $product->brand_id;
        $this->comment = $product->comment;
        $this->aux_1 = $this->allValues->where('product_id', $product->id);

        foreach ($this->aux_1 as $value) {

            $this->productValues[] = [

                'id' => $value->id,
                'cost' => number_format($value->cost, 2),
                'price' => number_format($value->price, 2),
                'is_saved' => true
            ];
        }

        $this->emit('show-modal', 'Abrir Modal');
    }

    public function Update()
    {
        $rules = [

            'statusId' => 'not_in:Elegir',
            'containerId' => 'not_in:Elegir',
            'brandId' => 'not_in:Elegir',
            'comment' => 'max:100',
            'productValues.*.cost' => 'required|numeric',
            'productValues.*.price' => 'required|numeric',
        ];

        $messages = [

            'statusId.not_in' => 'Seleccione una opcion',
            'containerId.not_in' => 'Seleccione una opcion',
            'brandId.not_in' => 'Seleccione una opcion',
            'comment.max' => 'Maximo 100 caracteres',
            'productValues.*.cost.required' => 'Campo requerido',
            'productValues.*.cost.numeric' => 'Solo numeros',
            'productValues.*.price.required' => 'Campo requerido',
            'productValues.*.price.numeric' => 'Solo numeros',
        ];

        $this->validate($rules, $messages);

        $product = $this->allProducts->find($this->selected_id);
        $this->aux_2 = collect($this->productValues);

        DB::beginTransaction();

        try {

            $container = $this->allContainers_2->find($this->containerId);

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
            }

            DB::commit();
            $this->emit('item-updated', 'Registro Actualizado');
            $this->mount();
        } catch (\Throwable $th) {

            DB::rollback();
            throw $th;
        }

        /*if ($this->image) {
            
            $customFileName = uniqid() . '_.' . $this->image->extension();
            $this->image->storeAs('public/products', $customFileName);
            $imageTemp = $product->image;
            $product->image = $customFileName;
            $product->save();

            if ($imageTemp != null) {
                
                if (file_exists('storage/products/' . $imageTemp)) {
                    
                    unlink('storage/products/' . $imageTemp);
                }
            }
        }*/
    }

    protected $listeners = [

        'destroy' => 'Destroy'
    ];

    public function Destroy(Product $product)
    {
        /*$imageTemp = $product->image;

        if ($imageTemp != null) {

            if (file_exists('storage/products/' . $imageTemp)) {
                
                unlink('storage/products/' . $imageTemp);
            }
        }*/

        //$status = $this->allStatuses->firstWhere('id', 2);
        //dd($status);
        $product->Update([

            'status_id' => 2
        ]);

        $this->emit('item-deleted', 'Registro eliminado');
        $this->mount();
    }

    public function resetUI()
    {
        $this->mount();
    }
}
