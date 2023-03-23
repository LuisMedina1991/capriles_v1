<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = ['name','alias','entity_code','status_id'];


    public function status()
    {
        return $this->belongsTo(status::class);
    }

    public function accounts()
    {
        
        return $this->hasMany(BankAccount::class);
    }

    public function checks(){

        return $this->hasMany(Paycheck::class);
    }

}
