<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function detailable(){

        return $this->morphTo();
    }

    public function statuses(){

        return $this->belongsTo(Status::class);
    }

}
