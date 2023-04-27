<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name','alias','phone','email','city','country'];


    public function incomes(){

        return $this->hasMany(Income::class);
    }

    public function sales(){

        return $this->hasMany(Sale::class);
    }

    public function debts(){

        return $this->hasMany(CustomerDebt::class);
    }

    public function checks(){

        return $this->hasMany(Paycheck::class);
    }
}
