<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    use HasFactory;

    
    protected $fillable = ['cost','price','product_id','status_id'];

    
    public function product(){

        return $this->belongsTo(Product::class);
    }

    
    public function status(){

        return $this->belongsTo(Status::class);
    }

    
    public function offices()
    {
        return $this->belongsToMany(Office::class)
        ->withPivot(['id', 'stock', 'alerts'])
        ->withTimestamps()
        ->using(OfficeValue::class);
    }

}
