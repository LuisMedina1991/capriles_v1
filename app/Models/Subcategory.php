<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = ['name','category_id'];
    

    public function category(){

        return $this->belongsTo(Category::class);
    }


    public function presentations(){

        return $this->belongsToMany(Presentation::class)
        ->withPivot(['id','prefix','additional_info','status_id'])
        //->withTimestamps()
        ->using(PresentationSubcategory::class);
    }

    public function activePresentations(){

        return $this->belongsToMany(Presentation::class)
        ->withPivot(['id','prefix','additional_info','status_id'])
        ->wherePivot('status_id',1)
        ->using(PresentationSubcategory::class);
    }

    public function products(){

        return $this->hasManyThrough(Product::class,PresentationSubcategory::class,'subcategory_id','presentation_subcategory_id');
    }

    public function activeProducts(){

        return $this->hasManyThrough(Product::class,PresentationSubcategory::class,'subcategory_id','presentation_subcategory_id')
        ->where('products.status_id',1);
    }

}
