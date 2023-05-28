<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    //variable para indicar que columnas se van a llenar y que columnas se pueden omitir al llenar de forma masiva
    protected $fillable = ['name','type'];


    //relacion uno a muchos con usuarios
    public function users(){

        return $this->hasMany(User::class);
    }

    //relacion uno a muchos con suppliers
    public function suppliers(){

        return $this->hasMany(Supplier::class);
    }

    //relacion uno a muchos con customers
    public function customers(){

        return $this->hasMany(Customer::class);
    }

    //relacion uno a muchos con companies
    public function companies(){

        return $this->hasMany(Company::class);
    }

    //relacion uno a muchos con banks
    public function banks(){

        return $this->hasMany(Bank::class);
    }

    //relacion uno a muchos con offices
    public function offices(){

        return $this->hasMany(Office::class);
    }

    //relacion uno a muchos con categories
    public function categories(){

        return $this->hasMany(Category::class);
    }

    //relacion uno a muchos con presentations
    public function presentations(){

        return $this->hasMany(Presentation::class);
    }

    //relacion uno a muchos con subcategories
    public function subcategories(){

        return $this->hasMany(Subcategory::class);
    }

    //relacion uno a muchos con presentation_subcategory
    public function containers(){

        return $this->hasMany(PresentationSubcategory::class);
    }

    //relacion uno a muchos con products
    public function products(){

        return $this->hasMany(Product::class);
    }

    public function miscellaneous_receivables(){

        return $this->hasMany(MiscellaneousReceivable::class);
    }

    public function miscellaneous_payables(){

        return $this->hasMany(MiscellaneousPayable::class);
    }

    public function balance_sheet_accounts(){

        return $this->hasMany(BalanceSheetAccount::class);
    }

    public function cash_transactions(){

        return $this->hasMany(CashTransaction::class);
    }

    public function accounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    public function checks(){

        return $this->hasMany(Paycheck::class);
    }

    public function details(){

        return $this->hasMany(Detail::class);
    }

    public function customer_debts(){

        return $this->hasMany(CustomerDebt::class);
    }

    public function supplier_debts(){

        return $this->hasMany(DebtsWithSupplier::class);
    }

    public function taxes(){

        return $this->hasMany(Tax::class);
    }

    public function incomes(){

        return $this->hasMany(Income::class);
    }

    public function transfers(){

        return $this->hasMany(Transfer::class);
    }

    public function sales(){

        return $this->hasMany(Sale::class);
    }

    //relacion uno a muchos con values
    public function values(){

        return $this->hasMany(Value::class);
    }
}
