<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = ['name','alias','entity_code','status_id'];


    //relacion muchos a uno con statuses
    public function status(){

        return $this->belongsTo(status::class);
    }

    //relacion uno a muchos con bank_accounts
    public function bank_accounts(){

        return $this->hasMany(BankAccount::class);
    }

    //relacion uno a muchos con paychecks
    public function paychecks(){

        return $this->hasMany(Paycheck::class);
    }

}
