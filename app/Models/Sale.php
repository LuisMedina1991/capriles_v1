<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    
    protected $fillable = ['number','file_number','quantity','sale_price','total','utility','status_id','user_id','costumer_id','office_value_id'];


    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function costumer()
    {
        return $this->belongsTo(Costumer::class);
    }

    public function stock()
    {
        return $this->belongsTo(OfficeValue::class,'office_value_id');
    }


    public static function boot()
    {

        parent::boot();

        static::creating(function ($model) {

            $model->number = Sale::all()->max('number') + 1;
            $model->file_number = 'ven' . '-' . str_pad($model->number, 2, 0, STR_PAD_LEFT);
        });
    }

}
