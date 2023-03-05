<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = ['name','phone','fax','email','nit','city','country'];
    

    public function incomes(){

        return $this->hasMany(Income::class);
    }
}
