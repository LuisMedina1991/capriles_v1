<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    use HasFactory;

    
    protected $fillable = ['name'];

    
    public function subcategories(){

        return $this->belongsToMany(Subcategory::class)
        ->withPivot(['id','prefix','additional_info','status_id'])
        //->withTimestamps()
        ->using(PresentationSubcategory::class);
    }

    public function activeSubcategories(){

        return $this->belongsToMany(Subcategory::class)
        ->withPivot(['id','prefix','additional_info','status_id'])
        ->wherePivot('status_id',1)
        ->using(PresentationSubcategory::class);
    }

}
