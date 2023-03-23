<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'number',
        'file_number',
        'quantity',
        'sale_price',
        'total_cost',
        'total_price',
        'utility',
        'payment_type',
        'status_id',
        'user_id',
        'customer_id',
        'office_value_id'
    ];


    public function tax()
    {
        return $this->morphOne(Tax::class, 'taxable');
    }

    public function debt()
    {
        return $this->hasOne(CustomerDebt::class);
    }

    public function paycheck()
    {
        return $this->hasOne(Paycheck::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
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
