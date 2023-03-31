<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function taxable(){

        return $this->morphTo();
    }

    public function status(){

        return $this->belongsTo(Status::class);
    }

    public function details(){

        return $this->morphMany(Detail::class,'detailable');
    }

    public function CashTransactions(){

        return $this->morphMany(CashTransaction::class,'cashable','cashable_type','cashable_id','id');
    }
}
