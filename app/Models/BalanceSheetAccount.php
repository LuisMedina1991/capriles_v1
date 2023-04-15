<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceSheetAccount extends Model
{
    use HasFactory;

    protected $fillable = ['name','alias','balance','subtype_id','status_id'];


    public function subtype(){

        return $this->belongsTo(Subtype::class);
    }

    public function status(){

        return $this->belongsTo(Status::class);
    }

    public function cash_transactions(){

        return $this->hasMany(CashTransaction::class);
    }
}
