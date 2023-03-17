<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'number',
        'file_number',
        'income_type',
        'payment_type',
        'previus_stock',
        'quantity',
        'total',
        'status_id',
        'user_id',
        'supplier_id',
        'customer_id',
        'office_value_id'
    ];


    public function tax()
    {
        return $this->morphOne(Tax::class, 'taxable');
    }

    public function debt()
    {
        return $this->hasOne(DebtsWithSupplier::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
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

            $model->number = Income::all()->max('number') + 1;
            $model->file_number = 'ing' . '-' . str_pad($model->number, 2, 0, STR_PAD_LEFT);
        });
    }
}
