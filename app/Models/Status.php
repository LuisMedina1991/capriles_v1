<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    //variable para indicar que columnas se van a llenar y que columnas se pueden omitir al llenar de forma masiva
    protected $fillable = ['name','type'];

    //relacion uno a muchos con users
    public function users(){

        return $this->hasMany(User::class);
    }

    public function incomes(){

        return $this->hasMany(Income::class);
    }

    public function transfers(){

        return $this->hasMany(Transfer::class);
    }

    public function sales(){

        return $this->hasMany(Sale::class);
    }

    //relacion uno a muchos con products
    public function products(){

        return $this->hasMany(Product::class);
    }

    //relacion uno a muchos con presentation_subcategory
    public function containers(){

        return $this->hasMany(PresentationSubcategory::class);
    }

    //relacion uno a muchos con values
    public function values(){

        return $this->hasMany(Value::class);
    }
}
