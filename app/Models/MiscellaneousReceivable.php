<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiscellaneousReceivable extends Model
{
    use HasFactory;

    protected $fillable = ['relation','reference','description','amount','status_id'];


    public function status(){

        return $this->belongsTo(Status::class);
    }
}
