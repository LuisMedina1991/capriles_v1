<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    //variable para indicar que columnas se van a llenar y que columnas se pueden omitir al llenar de forma masiva
    protected $fillable = ['stock','alerts','office_id','product_id'];

    //relacion uno a muchos con products
    public function product(){

        return $this->belongsTo(Product::class);
    }

    //relacion uno a muchos con offices
    public function office(){

        return $this->belongsTo(Office::class);
    }
}
