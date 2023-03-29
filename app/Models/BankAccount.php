<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = ['number','type','currency','balance','status_id','bank_id','company_id'];


    public function status(){

        return $this->belongsTo(Status::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function details(){

        return $this->morphMany(Detail::class,'detailable');
    }

    public function CashTransactions(){

        return $this->morphMany(CashTransaction::class,'cashable','cashable_type','cashable_id','id');
    }
}
