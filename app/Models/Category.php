<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    //variable para indicar que columnas se van a llenar y que columnas se pueden omitir al llenar de forma masiva
    protected $fillable = ['name','status_id'];


    //relacion muchos a uno con statuses
    public function status(){

        return $this->belongsTo(Status::class);
    }

    //relacion uno a muchos con subcategories
    public function subcategories(){

        return $this->hasMany(Subcategory::class);
    }
    
}
