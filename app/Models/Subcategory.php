<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = ['name','status_id','category_id'];
    

    //relacion muchos a uno con statuses
    public function status(){

        return $this->belongsTo(Status::class);
    }

    //relacion muchos a uno con categories
    public function category(){

        return $this->belongsTo(Category::class);
    }

    //relacion muchos a muchos con presentations
    public function presentations(){

        return $this->belongsToMany(Presentation::class)
        ->withPivot(['id','status_id'])
        ->withTimestamps()
        ->using(PresentationSubcategory::class);
    }

}
