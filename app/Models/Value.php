<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    use HasFactory;

    
    protected $fillable = ['cost','price','product_id','status_id'];


    //relacion muchos a uno con products
    public function product(){

        return $this->belongsTo(Product::class);
    }

    //relacion muchos a uno con statuses
    public function status(){

        return $this->belongsTo(Status::class);
    }

    /*//relacion muchos a muchos con offices
    public function offices()
    {
        return $this->belongsToMany(Office::class)
        ->withPivot(['id', 'stock', 'alerts'])
        ->withTimestamps()
        ->using(OfficeValue::class);
    }*/

    /*los siguientes son accesors y mutators para manipular campos en la tabla*/
    /*los campos cost y price se deben almacenar como enteros en la db pero deben ser decimales al manipularlos*/
    /*por lo que al obtenerlos su valor se divide entre 100 y al almacenarlos su valor se multiplica por 100*/

    //accesor para el atributo cost
    public function getCostAttribute($value)
    {
        return $value / 100;
    }

    //mutator para el atributo cost
    public function setCostAttribute($value)
    {
        $this->attributes['cost'] = $value * 100;
    }

    //accesor para el atributo price
    public function getPriceAttribute($value)
    {
        return $value / 100;
    }

    //mutator para el atributo price
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value * 100;
    }

}
