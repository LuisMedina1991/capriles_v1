<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    //variable para indicar que columnas se van a llenar y que columnas se pueden omitir al llenar de forma masiva
    protected $fillable = ['name','type'];

    
    public function users(){

        return $this->hasMany(User::class);
    }

    public function banks(){

        return $this->hasMany(Bank::class);
    }

    public function companies(){

        return $this->hasMany(Company::class);
    }

    public function accounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    public function checks(){

        return $this->hasMany(Paycheck::class);
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

    //relacion uno a muchos con products
    public function products(){

        return $this->hasMany(Product::class);
    }

    //relacion uno a muchos con presentation_subcategory
    public function containers(){

        return $this->hasMany(PresentationSubcategory::class);
    }

    //relacion uno a muchos con values
    public function values(){

        return $this->hasMany(Value::class);
    }
}
