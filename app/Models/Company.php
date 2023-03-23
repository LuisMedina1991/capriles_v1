<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name','alias','phone','fax','email','nit','address','status_id'];


    public function status()
    {
        return $this->belongsTo(status::class);
    }

    public function accounts()
    {
        return $this->hasMany(BankAccount::class);
    }

}
