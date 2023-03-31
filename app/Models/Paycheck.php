<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paycheck extends Model
{
    use HasFactory;

    protected $fillable = ['description','number','amount','status_id','sale_id','bank_id','customer_id'];
    

    public function status(){

        return $this->belongsTo(Status::class);
    }

    public function sale(){

        return $this->belongsTo(Sale::class);
    }

    public function customer(){

        return $this->belongsTo(Customer::class);
    }

    public function bank(){

        return $this->belongsTo(Bank::class);
    }

    public function details(){

        return $this->morphMany(Detail::class,'detailable');
    }

    public function CashTransactions(){

        return $this->morphMany(CashTransaction::class,'cashable','cashable_type','cashable_id','id');
    }
}
