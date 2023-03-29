<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashTransaction extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function cashable(){

        return $this->morphTo('cashable','cashable_type','cashable_id','id');
    }

    public function status(){

        return $this->belongsTo(Status::class);
    }

    public function detail(){

        return $this->belongsTo(Detail::class);
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
