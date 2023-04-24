<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['number','file_number','action','type','relation','description','amount','status_id','balance_sheet_account_id'];


    public function status(){

        return $this->belongsTo(Status::class);
    }

    public function balance_sheet_account(){

        return $this->belongsTo(BalanceSheetAccount::class);
    }


    public static function boot()
    {

        parent::boot();

        static::creating(function ($model) {

            $model->number = CashTransaction::all()->max('number') + 1;
            $model->file_number = 'cash' . '-' . str_pad($model->number, 2, 0, STR_PAD_LEFT);
        });
    }
}
