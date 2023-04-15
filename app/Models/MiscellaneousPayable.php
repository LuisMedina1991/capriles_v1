<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiscellaneousPayable extends Model
{
    use HasFactory;

    protected $fillable = ['relation','reference','description','amount','status_id'];


    public function status(){

        return $this->belongsTo(Status::class);
    }

    public function details(){

        return $this->morphMany(Detail::class,'detailable');
    }
}
