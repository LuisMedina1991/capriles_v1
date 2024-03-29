<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function taxable(){

        return $this->morphTo();
    }

    public function status(){

        return $this->belongsTo(Status::class);
    }

    public function details(){

        return $this->morphMany(Detail::class,'detailable');
    }

}
