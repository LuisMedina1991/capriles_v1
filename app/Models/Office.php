<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    //VARIABLE PARA INDICAR QUE COLUMNAS SE VAN A LLENAR Y QUE COLUMNAS SE PUEDEN OMITIR AL LLENAR DE FORMA MASIVA
    protected $fillable = ['name','address','phone'];

    //relacion muchos a muchos con products
    public function products(){
        return $this->belongsToMany(Product::class)->withPivot(['id','stock','alerts'])->withTimestamps();
    }
    
}
