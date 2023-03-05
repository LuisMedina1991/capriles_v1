<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;


    protected $fillable = ['name','address','phone'];

    
    public function values(){
        
        return $this->belongsToMany(Value::class)
        ->withPivot(['id','stock','alerts'])
        ->withTimestamps()
        ->using(OfficeValue::class);
    }

    public function activeValues(){
        
        return $this->belongsToMany(Value::class)
        ->where('status_id',1)
        ->withPivot(['id','stock','alerts'])
        ->using(OfficeValue::class);
    }
    
}
