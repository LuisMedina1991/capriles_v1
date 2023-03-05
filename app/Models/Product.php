<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    //variable para indicar que columnas se van a llenar y que columnas se pueden omitir al llenar de forma masiva
    protected $fillable = ['number', 'code', 'comment', 'status_id', 'brand_id', 'presentation_subcategory_id'];
    //protected $guarded = [];

    /*protected $attributes = [
        'brand' => 's/m',
        'ring' => 's/a',
        'threshing' => 's/t',
        'tarp' => 's/l',
        'comment' => 'no'
    ];*/

    //relacion muchos a uno con statuses
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    //relacion muchos a uno con brands
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    //relacion muchos a uno con presentation_subcategory
    public function container()
    {
        return $this->belongsTo(PresentationSubcategory::class,'presentation_subcategory_id');
    }

    //relacion muchos a uno con presentation_subcategory con filtro
    public function activeContainer()
    {
        return $this->belongsTo(PresentationSubcategory::class,'presentation_subcategory_id')->where('status_id',1);
    }

    //relacion uno a muchos con values
    public function values()
    {
        return $this->hasMany(Value::class);
    }

    //relacion uno a muchos con values con filtro
    public function activeValues()
    {
        return $this->hasMany(Value::class)->where('status_id',1);
    }

    //relacion uno a muchos con office_value a traves de values
    public function stocks(){

        return $this->hasManyThrough(OfficeValue::class,Value::class);
    }

    //relacion uno a muchos con office_value a traves de values con filtro
    public function activeStocks(){

        return $this->hasManyThrough(OfficeValue::class,Value::class)->where('status_id',1);
    }

    //relacion uno a uno polimorfica
    public function image()
    {

        //return $this->morphOne('App\Models\Image','imageable');
        return $this->morphOne(Image::class, 'imageable');
    }

    public static function boot()
    {

        parent::boot();

        static::creating(function ($model) {

            $model->number = Product::where('presentation_subcategory_id', $model->presentation_subcategory_id)->max('number') + 1;
            $model->code = $model->container->prefix . '-' . str_pad($model->number, 4, 0, STR_PAD_LEFT);
        });
    }

    /*//nombre del accesor es imagen
    public function getImagenAttribute(){   //metodo accesor para mostrar imagen por defecto en caso de no registrarle ninguna
        
        if($this->image == null)    //validar si la columna image no tiene nada registrado en la base de datos
            return '../noimg.jpg'; //retornar imagen por defecto

        if(file_exists('storage/products/' . $this->image)) //validar si archivo existe fisicarmente en el almacenamiento interno
            return $this->image;    //retornar imagen registrada en la base de datos
        else
            return '../noimg.jpg'; //caso contrario retornar imagen por defecto
    }*/
}
