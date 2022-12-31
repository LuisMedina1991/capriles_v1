<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    //variable para indicar que columnas se van a llenar y que columnas se pueden omitir al llenar de forma masiva
    protected $fillable = ['number','code','brand','ring','threshing','tarp','comment','category_id','subcategory_id','status_id','prefix_id'];
    //protected $guarded = [];

    /*protected $attributes = [
        'brand' => 's/m',
        'ring' => 's/a',
        'threshing' => 's/t',
        'tarp' => 's/l',
        'comment' => 'no'
    ];*/

    //relacion muchos a uno con categories
    public function category(){

        //return $this->belongsToMany('App\Models\Category');
        return $this->belongsTo(Category::class);
    }

    //relacion muchos a uno con subcategories
    public function subcategory(){

        return $this->belongsTo(Subcategory::class);
    }

    //relacion muchos a uno con statuses
    public function status(){

        return $this->belongsTo(Status::class);
    }

    //relacion muchos a uno con prefixes
    public function prefix(){

        return $this->belongsTo(Prefix::class);
    }

    //relacion uno a muchos con values
    public function values(){

        return $this->hasMany(Value::class);
    }

    //relacion muchos a muchos con offices
    public function offices(){
        return $this->belongsToMany(Office::class)->withPivot(['id','stock','alerts'])->withTimestamps();
    }

    //relacion uno a uno polimorfica
    public function image(){

        //return $this->morphOne('App\Models\Image','imageable');
        return $this->morphOne(Image::class,'imageable');
    }

    public static function boot(){

        parent::boot();

        static::creating(function($model){

            $model->number = Product::where('prefix_id',$model->prefix_id)->max('number') + 1;
            $model->code = $model->prefix->name . '-' . str_pad($model->number,3,0,STR_PAD_LEFT);
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
