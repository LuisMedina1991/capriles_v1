<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    use HasFactory;

    
    protected $fillable = ['name','status_id'];

    
    //relacion muchos a uno con statuses
    public function status(){

        return $this->belongsTo(Status::class);
    }

    //relacion muchos a muchos con subcategories
    public function subcategories(){

        return $this->belongsToMany(Subcategory::class)
        ->withPivot(['id','status_id'])
        ->withTimestamps()
        ->using(PresentationSubcategory::class);
    }

}
