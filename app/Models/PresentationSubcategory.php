<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PresentationSubcategory extends Pivot
{
    use HasFactory;


    //relacion muchos a uno con statuses
    public function status(){

        return $this->belongsTo(Status::class);
    }

    //relacion muchos a uno con subcategories
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class,'subcategory_id');
    }

    //relacion muchos a uno con presentations
    public function presentation()
    {
        return $this->belongsTo(Presentation::class,'presentation_id');
    }

    /*public function products(){

        return $this->hasMany(Product::class,'presentation_subcategory_id','id');
    }

    public function activeProducts(){

        return $this->hasMany(Product::class,'presentation_subcategory_id','id')->where('status_id',1);
    }*/

}
