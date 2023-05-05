<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name','alias','phone','email','city','country','status_id'];


    //relacion muchos a uno con statuses
    public function status(){

        return $this->belongsTo(Status::class);
    }

    //relacion uno a muchos con incomes
    public function incomes(){

        return $this->hasMany(Income::class);
    }

    //relacion uno a muchos con sales
    public function sales(){

        return $this->hasMany(Sale::class);
    }

    //relacion uno a muchos con customer_debts
    public function debts(){

        return $this->hasMany(CustomerDebt::class);
    }

    //relacion uno a muchos con paychecks
    public function paychecks(){

        return $this->hasMany(Paycheck::class);
    }
}
