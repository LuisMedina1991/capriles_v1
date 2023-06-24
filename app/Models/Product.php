<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    //variable para indicar que columnas se van a llenar y que columnas se pueden omitir al llenar de forma masiva
    protected $fillable = ['number','code','barcode_image','comment','status_id','brand_id','presentation_subcategory_id'];


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

    //relacion uno a uno polimorfica con images
    public function image()
    {
        //return $this->morphOne('App\Models\Image','imageable');   //otra forma para hacer lo mismo
        return $this->morphOne(Image::class, 'imageable');
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

    /*public static function boot(){

        parent::boot();

        static::creating(function ($model) {

            $model->number = Product::where('presentation_subcategory_id', $model->presentation_subcategory_id)->where('status_id',1)->max('number') + 1;
            $model->code = $model->container->prefix . '-' . str_pad($model->number, 4, 0, STR_PAD_LEFT);
        });
    }*/

    //Accesor para manipular el campo "barcode_image"
    public function getBarcodeImageAttribute($barcode_image){
        
        if ($barcode_image == null) {

            return null;

        }

        if ( file_exists('storage/products/barcodes/' . $barcode_image) ) {

            return 'storage/products/barcodes/' . $barcode_image;

        } else {

            return null;

        }

    }
    
}
