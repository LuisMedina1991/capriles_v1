<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    
    protected $fillable = ['number','type','total','quantity','file_number','status_id','user_id','provider_id','costumer_id','office_value_id'];


    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
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

            $model->number = Income::all()->max('number') + 1;
            $model->file_number = 'ing' . '-' . str_pad($model->number, 2, 0, STR_PAD_LEFT);
        });
    }
}
