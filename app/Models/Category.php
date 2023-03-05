<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    //variable para indicar que columnas se van a llenar y que columnas se pueden omitir al llenar de forma masiva
    protected $fillable = ['name'];

    //relacion uno a muchos con subcategories
    public function subcategories(){
        return $this->hasMany(Subcategory::class);
    }
    
}
