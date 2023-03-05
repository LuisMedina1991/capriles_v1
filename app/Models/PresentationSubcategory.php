<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PresentationSubcategory extends Pivot
{
    use HasFactory;


    public function products(){

        return $this->hasMany(Product::class,'presentation_subcategory_id','id');
    }

    public function activeProducts(){

        return $this->hasMany(Product::class,'presentation_subcategory_id','id')->where('status_id',1);
    }

    public function status(){

        return $this->belongsTo(Status::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class,'subcategory_id');
    }

    public function presentation()
    {
        return $this->belongsTo(Presentation::class,'presentation_id');
    }

}
