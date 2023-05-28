<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    
    //relacion uno a muchos con products
    public function products(){
        
        return $this->hasMany(Product::class);
    }
}
