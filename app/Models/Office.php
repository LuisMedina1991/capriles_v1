<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;


    protected $fillable = ['name','alias','phone','address','status_id'];

    
    //relacion muchos a uno con statuses
    public function status(){

        return $this->belongsTo(Status::class);
    }

    //relacion muchos a muchos con values
    public function values(){
        
        return $this->belongsToMany(Value::class)
        ->withPivot(['id','stock','alerts'])
        ->withTimestamps()
        ->using(OfficeValue::class);
    }

    //relacion muchos a muchos con values con estado activo
    public function activeValues(){
        
        return $this->belongsToMany(Value::class)
        ->where('status_id',1)
        ->withPivot(['id','stock','alerts'])
        ->using(OfficeValue::class);
    }
    
}
