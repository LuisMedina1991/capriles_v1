<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['name','alias','phone','email','city','country','status_id'];
    

    //relacion muchos a uno con statuses
    public function status(){

        return $this->belongsTo(Status::class);
    }

    //relacion uno a muchos con incomes
    public function incomes(){

        return $this->hasMany(Income::class);
    }

    //relacion uno a muchos con debts_with_suppliers
    public function debts(){

        return $this->hasMany(DebtsWithSupplier::class);
    }
}
