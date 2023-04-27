<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name','alias','phone','email','nit','status_id'];


    public function status()
    {
        return $this->belongsTo(status::class);
    }

    public function bank_accounts()
    {
        return $this->hasMany(BankAccount::class);
    }

}
