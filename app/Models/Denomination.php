<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denomination extends Model
{
    use HasFactory;

    //variable para indicar que columnas se van a llenar y que columnas se pueden omitir al llenar de forma masiva
    protected $fillable = ['type','value'];

    //relacion uno a uno polimorfica
    public function image(){

        //return $this->morphOne('App\Models\Image','imageable');
        return $this->morphOne(Image::class,'imageable');
    }

    //nombre del accesor es imagen
    /*public function getImagenAttribute(){   //metodo accesor para mostrar imagen por defecto en caso de no registrarle ninguna
        
        if($this->image == null)    //validar si la columna image no tiene nada registrado en la base de datos
            return '../noimg.jpg'; //retornar imagen por defecto

        if(file_exists('storage/denominations/' . $this->image->url)) //validar si archivo existe fisicarmente en el almacenamiento interno
            return $this->image->url;    //retornar imagen registrada en la base de datos
        else
            return '../noimg.jpg'; //caso contrario retornar imagen por defecto
    }*/
}
