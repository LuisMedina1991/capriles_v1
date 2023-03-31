<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtsWithSupplier extends Model
{
    use HasFactory;

    protected $fillable = ['description','amount','status_id','income_id','supplier_id'];


    public function income()
    {
        return $this->belongsTo(Income::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function status()
    {
        return $this->belongsTo(status::class);
    }

    public function details(){

        return $this->morphMany(Detail::class,'detailable');
    }

    public function CashTransactions(){

        return $this->morphMany(CashTransaction::class,'cashable','cashable_type','cashable_id','id');
    }
}
