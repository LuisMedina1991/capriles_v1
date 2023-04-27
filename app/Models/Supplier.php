<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['name','alias','phone','email','city','country'];
    

    public function incomes(){

        return $this->hasMany(Income::class);
    }

    public function debts(){

        return $this->hasMany(DebtsWithSupplier::class);
    }
}
